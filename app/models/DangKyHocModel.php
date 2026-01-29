<?php
/**
 * DangKyHocModel - Quản lý đăng ký học
 */
require_once __DIR__ . '/../core/Model.php';

class DangKyHocModel extends Model {
    protected $table_name = "DANG_KY_HOC";
    protected $primaryKey = "MaDangKy";

    public $MaDangKy;
    public $MaSinhVien;
    public $MaLopHocPhan;
    public $NgayDangKy;
    public $DiemTongKet;
    public $DiemChu;
    public $DiemSo;
    public $KetQua;

    public function __construct($db) {
        parent::__construct($db);
    }

    /**
     * Lấy tất cả đăng ký học với chi tiết
     */
    public function readAllWithDetails() {
        $query = "SELECT dk.*, sv.HoTen as TenSinhVien, lhp.MaLopHocPhan, mh.TenMonHoc, hk.TenHocKy
                  FROM {$this->table_name} dk
                  LEFT JOIN SINH_VIEN sv ON dk.MaSinhVien = sv.MaSinhVien
                  LEFT JOIN LOP_HOC_PHAN lhp ON dk.MaLopHocPhan = lhp.MaLopHocPhan
                  LEFT JOIN MON_HOC mh ON lhp.MaMonHoc = mh.MaMonHoc
                  LEFT JOIN HOC_KY hk ON lhp.MaHocKy = hk.MaHocKy
                  ORDER BY dk.NgayDangKy DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Lấy đăng ký học theo lớp học phần
     */
    public function getByLopHocPhan($maLopHocPhan) {
        $query = "SELECT dk.*, sv.HoTen as TenSinhVien
                  FROM {$this->table_name} dk
                  JOIN SINH_VIEN sv ON dk.MaSinhVien = sv.MaSinhVien
                  WHERE dk.MaLopHocPhan = :maLop";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maLop', $maLopHocPhan);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Kiểm tra sinh viên đã đăng ký lớp học phần chưa
     */
    public function isRegistered($maSinhVien, $maLopHocPhan) {
        $query = "SELECT COUNT(*) as count FROM {$this->table_name} 
                  WHERE MaSinhVien = :maSV AND MaLopHocPhan = :maLHP";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maSV', $maSinhVien);
            $stmt->bindParam(':maLHP', $maLopHocPhan);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Tạo mới đăng ký học
     */
    public function create() {
        // Kiểm tra đã đăng ký chưa
        if ($this->isRegistered($this->MaSinhVien, $this->MaLopHocPhan)) {
            return "Sinh viên đã đăng ký lớp học phần này rồi!";
        }

        $query = "INSERT INTO {$this->table_name} (MaSinhVien, MaLopHocPhan, NgayDangKy) 
                  VALUES (:MaSinhVien, :MaLopHocPhan, NOW())";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
            $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return $this->handlePdoException($e);
        }
    }

    /**
     * Cập nhật điểm
     */
    public function updateDiem() {
        $query = "UPDATE {$this->table_name} 
                  SET DiemTongKet=:DiemTongKet, DiemChu=:DiemChu, DiemSo=:DiemSo, KetQua=:KetQua 
                  WHERE MaDangKy=:MaDangKy";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":MaDangKy", $this->MaDangKy);
            $stmt->bindParam(":DiemTongKet", $this->DiemTongKet);
            $stmt->bindParam(":DiemChu", $this->DiemChu);
            $stmt->bindParam(":DiemSo", $this->DiemSo);
            $stmt->bindParam(":KetQua", $this->KetQua);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return $this->handlePdoException($e);
        }
    }

    /**
     * Xóa đăng ký học
     */
    public function delete() {
        $query = "DELETE FROM {$this->table_name} WHERE MaDangKy = :MaDangKy";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":MaDangKy", $this->MaDangKy);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return $this->handlePdoException($e);
        }
    }
}