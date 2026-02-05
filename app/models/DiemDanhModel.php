<?php
/**
 * DiemDanhModel - Quản lý điểm danh
 * 1 tín chỉ = 5 ca = 15 tiết. Điểm chuyên cần = % tham gia buổi học * 10
 */
require_once __DIR__ . '/../core/Model.php';

class DiemDanhModel extends Model {
    protected $table_name = "DIEM_DANH";
    protected $primaryKey = "ID";

    public $ID;
    public $MaDangKy;
    public $MaLopHocPhan;
    public $BuoiThu;
    public $NgayDiemDanh;
    public $CoMat;
    public $GhiChu;
    public $NguoiDiemDanh;

    /**
     * Lấy bảng điểm danh theo lớp học phần (Stt, Mã SV, Tên SV, Mã HP, % tham gia, Điểm CC)
     */
    public function getBangDiemDanhByLop($maLopHocPhan) {
        if (empty($maLopHocPhan)) return [];
        try {
            $query = "SELECT dk.MaDangKy, sv.MaSinhVien, sv.HoTen, lhp.MaLopHocPhan, lhp.MaMonHoc, mh.TenMonHoc, mh.SoTinChi,
                      (SELECT COUNT(*) FROM {$this->table_name} dd WHERE dd.MaDangKy = dk.MaDangKy AND dd.CoMat = 1) as SoBuoiCoMat,
                      (SELECT COUNT(*) FROM {$this->table_name} dd WHERE dd.MaDangKy = dk.MaDangKy) as SoBuoiDaDiemDanh
                      FROM DANG_KY_HOC dk
                      JOIN SINH_VIEN sv ON dk.MaSinhVien = sv.MaSinhVien
                      JOIN LOP_HOC_PHAN lhp ON dk.MaLopHocPhan = lhp.MaLopHocPhan
                      LEFT JOIN MON_HOC mh ON lhp.MaMonHoc = mh.MaMonHoc
                      WHERE dk.MaLopHocPhan = :maLop
                      ORDER BY sv.MaSinhVien";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maLop', $maLopHocPhan);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            foreach ($rows as $r) {
                $soTinChi = (int)($r['SoTinChi'] ?? 1);
                $tongBuoi = $soTinChi * 5;
                $soCoMat = (int)($r['SoBuoiCoMat'] ?? 0);
                $soDaDD = (int)($r['SoBuoiDaDiemDanh'] ?? 0);
                $phanTram = $tongBuoi > 0 ? round($soCoMat / $tongBuoi * 100, 1) : 0;
                $diemCC = $tongBuoi > 0 ? round($soCoMat / $tongBuoi * 10, 2) : null;
                $result[] = array_merge($r, [
                    'TongBuoi' => $tongBuoi,
                    'PhanTramThamGia' => $phanTram,
                    'DiemChuyenCan' => $diemCC,
                ]);
            }
            return $result;
        } catch (PDOException $e) {
            error_log("DiemDanhModel::getBangDiemDanhByLop: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy chi tiết điểm danh theo MaDangKy (các buổi)
     */
    public function getByMaDangKy($maDangKy) {
        if (empty($maDangKy)) return [];
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE MaDangKy = :maDangKy ORDER BY BuoiThu";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDangKy', $maDangKy);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Cập nhật hoặc tạo điểm danh cho một buổi
     */
    public function upsertBuoi($maDangKy, $maLopHocPhan, $buoiThu, $coMat, $ngayDiemDanh = null, $nguoiDD = null) {
        $ngay = $ngayDiemDanh ?: date('Y-m-d');
        try {
            $query = "INSERT INTO {$this->table_name} (MaDangKy, MaLopHocPhan, BuoiThu, NgayDiemDanh, CoMat, NguoiDiemDanh)
                      VALUES (:maDangKy, :maLop, :buoiThu, :ngay, :coMat, :nguoi)
                      ON DUPLICATE KEY UPDATE CoMat = :coMat2, NgayDiemDanh = :ngay2, NguoiDiemDanh = :nguoi2";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':maDangKy', $maDangKy);
            $stmt->bindValue(':maLop', $maLopHocPhan);
            $stmt->bindValue(':buoiThu', (int)$buoiThu);
            $stmt->bindValue(':ngay', $ngay);
            $stmt->bindValue(':coMat', $coMat ? 1 : 0);
            $stmt->bindValue(':nguoi', $nguoiDD);
            $stmt->bindValue(':coMat2', $coMat ? 1 : 0);
            $stmt->bindValue(':ngay2', $ngay);
            $stmt->bindValue(':nguoi2', $nguoiDD);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("DiemDanhModel::upsertBuoi: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lưu điểm danh hàng loạt (buổi hiện tại)
     */
    public function saveDiemDanhBuoi($maLopHocPhan, $buoiThu, $danhSachCoMat, $nguoiDD = null) {
        require_once __DIR__ . '/DangKyHocModel.php';
        $dangKyModel = new DangKyHocModel($this->conn);
        $dangKys = $dangKyModel->getByLopHocPhan($maLopHocPhan);
        $ngay = date('Y-m-d');
        foreach ($dangKys as $dk) {
            $maDK = $dk['MaDangKy'] ?? 0;
            $coMat = isset($danhSachCoMat[$maDK]) ? (bool)$danhSachCoMat[$maDK] : false;
            $this->upsertBuoi($maDK, $maLopHocPhan, $buoiThu, $coMat, $ngay, $nguoiDD);
        }
        return true;
    }
}
