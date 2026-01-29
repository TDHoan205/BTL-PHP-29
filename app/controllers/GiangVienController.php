<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/GiangVien.php';
require_once __DIR__ . '/../models/LopHocPhan.php';
require_once __DIR__ . '/../models/DangKyHoc.php';
require_once __DIR__ . '/../models/SinhVien.php';
require_once __DIR__ . '/../models/ChiTietDiem.php';
require_once __DIR__ . '/../models/CauTrucDiem.php';
require_once __DIR__ . '/../models/LoaiDiem.php';
require_once __DIR__ . '/../models/MonHoc.php';

class GiangVienController extends Controller {

    private $db;
    private $conn;
    private $giangVienModel;
    private $lopHocPhanModel;
    private $dangKyHocModel;
    private $sinhVienModel;
    private $chiTietDiemModel;
    private $cauTrucDiemModel;
    private $loaiDiemModel;
    private $monHocModel;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();

        $this->giangVienModel   = new GiangVien($this->conn);
        $this->lopHocPhanModel  = new LopHocPhan($this->conn);
        $this->dangKyHocModel   = new DangKyHoc($this->conn);
        $this->sinhVienModel    = new SinhVien($this->conn);
        $this->chiTietDiemModel = new ChiTietDiem($this->conn);
        $this->cauTrucDiemModel = new CauTrucDiem($this->conn);
        $this->loaiDiemModel    = new LoaiDiem($this->conn);
        $this->monHocModel      = new MonHoc($this->conn);
    }

    /**
     * Dashboard giảng viên: xem lớp & môn được dạy
     * URL: GiangVien/dashboard
     */
    public function dashboard() {
        // TODO: Lấy mã giảng viên từ session sau khi đăng nhập
        $maGiangVien = isset($_SESSION['MaGiangVien']) ? $_SESSION['MaGiangVien'] : 'GV001';

        // 1. Thông tin giảng viên
        $giangVien = $this->giangVienModel->getById($maGiangVien);
        if (!$giangVien) {
            $giangVien = ['MaGiangVien' => $maGiangVien, 'HoTen' => 'Giảng viên'];
        }

        // 2. Danh sách học kỳ (demo đơn giản, sau có thể lấy từ bảng HocKy)
        $hocKyList = [
            ['value' => '2025_1', 'label' => 'Học kỳ 1 - Năm 2025-2026'],
            ['value' => '2024_2', 'label' => 'Học kỳ 2 - Năm 2024-2025'],
        ];

        // 3. Danh sách lớp học phần giảng viên dạy (tạm thời dùng method readAll và lọc nếu cần)
        // Bạn có thể tạo method riêng trong model LopHocPhan: getByGiangVien($maGiangVien, $maHocKy)
        $stmtLhp = $this->lopHocPhanModel->readAll();
        $lopHocPhanList = $stmtLhp ? $stmtLhp->fetchAll(PDO::FETCH_ASSOC) : [];

        // 4. Tạm thời chưa load danh sách sinh viên (sẽ dùng AJAX hoặc tham số MaLopHocPhan sau)
        $sinhVienLopHocPhan = [];

        $data = [
            'giangVien' => $giangVien,
            'hocKyList' => $hocKyList,
            'lopHocPhanList' => $lopHocPhanList,
            'sinhVienLopHocPhan' => $sinhVienLopHocPhan,
        ];

        $this->view('giangvien/dashboard', $data);
    }

    // Các hàm CRUD cũ vẫn giữ lại để dùng ở nơi khác

    // Lấy danh sách tất cả giảng viên
    public function getAllGiangVien() {
        return $this->giangVienModel->readAll();
    }

    // Lấy thông tin một giảng viên theo mã giảng viên
    public function getGiangVienById($maGiangVien) {
        return $this->giangVienModel->getById($maGiangVien);
    }

    // Thêm một giảng viên mới
    public function addGiangVien($data) {
        $this->giangVienModel->MaGiangVien = $data['MaGiangVien'];
        $this->giangVienModel->HoTen = $data['HoTen'];
        $this->giangVienModel->NgaySinh = $data['NgaySinh'];
        $this->giangVienModel->GioiTinh = $data['GioiTinh'];
        $this->giangVienModel->Email = $data['Email'];
        $this->giangVienModel->SoDienThoai = $data['SoDienThoai'];
        $this->giangVienModel->HocVi = $data['HocVi'];
        $this->giangVienModel->MaKhoa = $data['MaKhoa'];
        $this->giangVienModel->TrangThai = $data['TrangThai'];
        return $this->giangVienModel->create();
    }

    // Cập nhật thông tin giảng viên
    public function updateGiangVien($maGiangVien, $data) {
        $this->giangVienModel->MaGiangVien = $maGiangVien;
        $this->giangVienModel->HoTen = $data['HoTen'];
        $this->giangVienModel->NgaySinh = $data['NgaySinh'];
        $this->giangVienModel->GioiTinh = $data['GioiTinh'];
        $this->giangVienModel->Email = $data['Email'];
        $this->giangVienModel->SoDienThoai = $data['SoDienThoai'];
        $this->giangVienModel->HocVi = $data['HocVi'];
        $this->giangVienModel->MaKhoa = $data['MaKhoa'];
        $this->giangVienModel->TrangThai = $data['TrangThai'];
        return $this->giangVienModel->update();
    }

    // Xóa một giảng viên
    public function deleteGiangVien($maGiangVien) {
        $this->giangVienModel->MaGiangVien = $maGiangVien;
        return $this->giangVienModel->delete();
    }

    // Tìm kiếm giảng viên
    public function searchGiangVien($criteria) {
        return $this->giangVienModel->search($criteria);
    }

    /**
     * Trang nhập điểm cho giảng viên
     * URL: GiangVien/nhapDiem?maLopHocPhan=...
     */
    public function nhapDiem() {
        $maGiangVien = isset($_SESSION['MaGiangVien']) ? $_SESSION['MaGiangVien'] : 'GV001';
        
        // Lấy thông tin giảng viên
        $giangVien = $this->giangVienModel->getById($maGiangVien);
        if (!$giangVien) {
            $giangVien = ['MaGiangVien' => $maGiangVien, 'HoTen' => 'Giảng viên'];
        }

        // Lấy danh sách lớp học phần của giảng viên
        $stmtLhp = $this->lopHocPhanModel->readAll();
        $lopHocPhanList = $stmtLhp ? $stmtLhp->fetchAll(PDO::FETCH_ASSOC) : [];

        // Nếu có MaLopHocPhan trong GET, lấy thông tin lớp đó
        $lopHocPhanSelected = null;
        $cauTrucDiem = [];
        $maLopHocPhan = $_GET['maLopHocPhan'] ?? '';

        if ($maLopHocPhan) {
            // Lấy thông tin lớp học phần
            $lopHocPhanSelected = $this->lopHocPhanModel->getById($maLopHocPhan);
            
            if ($lopHocPhanSelected) {
                // Lấy cấu trúc điểm của môn học
                $maMonHoc = $lopHocPhanSelected['MaMonHoc'];
                $query = "SELECT ctd.*, ld.TenLoaiDiem 
                          FROM CAU_TRUC_DIEM ctd 
                          JOIN LOAI_DIEM ld ON ctd.MaLoaiDiem = ld.MaLoaiDiem 
                          WHERE ctd.MaMonHoc = :MaMonHoc 
                          ORDER BY ctd.HeSo DESC";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':MaMonHoc', $maMonHoc);
                $stmt->execute();
                $cauTrucDiem = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        $data = [
            'giangVien' => $giangVien,
            'lopHocPhanList' => $lopHocPhanList,
            'lopHocPhanSelected' => $lopHocPhanSelected,
            'cauTrucDiem' => $cauTrucDiem,
        ];

        $this->view('giangvien/nhapdiem', $data);
    }

    /**
     * API: Lấy danh sách sinh viên và điểm của lớp học phần
     * URL: GiangVien/getSinhVienDiem?maLopHocPhan=...
     */
    public function getSinhVienDiem() {
        header('Content-Type: application/json');
        
        $maLopHocPhan = $_GET['maLopHocPhan'] ?? '';
        if (!$maLopHocPhan) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã lớp học phần']);
            return;
        }

        try {
            // Lấy danh sách sinh viên đã đăng ký lớp học phần này
            $query = "SELECT dk.MaDangKy, dk.MaSinhVien, dk.DiemTongKet, dk.DiemChu, dk.DiemSo,
                             sv.HoTen, sv.MaLop
                      FROM DANG_KY_HOC dk
                      JOIN SINH_VIEN sv ON dk.MaSinhVien = sv.MaSinhVien
                      WHERE dk.MaLopHocPhan = :MaLopHocPhan
                      ORDER BY sv.MaSinhVien";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':MaLopHocPhan', $maLopHocPhan);
            $stmt->execute();
            $sinhVienList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Lấy điểm chi tiết cho mỗi sinh viên
            foreach ($sinhVienList as &$sv) {
                $maDangKy = $sv['MaDangKy'];
                $queryDiem = "SELECT ctd.MaLoaiDiem, ctd.SoDiem 
                              FROM CHI_TIET_DIEM ctd 
                              WHERE ctd.MaDangKy = :MaDangKy";
                $stmtDiem = $this->conn->prepare($queryDiem);
                $stmtDiem->bindParam(':MaDangKy', $maDangKy);
                $stmtDiem->execute();
                $diemList = $stmtDiem->fetchAll(PDO::FETCH_ASSOC);
                
                $sv['diem'] = [];
                foreach ($diemList as $d) {
                    $sv['diem'][$d['MaLoaiDiem']] = ['SoDiem' => floatval($d['SoDiem'])];
                }
            }

            echo json_encode(['success' => true, 'sinhVien' => $sinhVienList]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * API: Lưu/cập nhật điểm cho sinh viên
     * POST JSON: {maDangKy: ..., diem: {MaLoaiDiem: SoDiem, ...}}
     */
    public function saveDiem() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $maDangKy = $input['maDangKy'] ?? null;
        $diemData = $input['diem'] ?? [];

        if (!$maDangKy || empty($diemData)) {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
            return;
        }

        try {
            $this->conn->beginTransaction();

            // Lấy thông tin đăng ký để biết MaMonHoc
            $dangKy = $this->dangKyHocModel->getById($maDangKy);
            if (!$dangKy) {
                throw new Exception('Không tìm thấy đăng ký học');
            }

            // Lấy lớp học phần để biết MaMonHoc
            $lopHocPhan = $this->lopHocPhanModel->getById($dangKy['MaLopHocPhan']);
            if (!$lopHocPhan) {
                throw new Exception('Không tìm thấy lớp học phần');
            }

            $maMonHoc = $lopHocPhan['MaMonHoc'];
            $maGiangVien = isset($_SESSION['MaGiangVien']) ? $_SESSION['MaGiangVien'] : 'GV001';

            // Lấy cấu trúc điểm để tính điểm tổng
            $query = "SELECT * FROM CAU_TRUC_DIEM WHERE MaMonHoc = :MaMonHoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':MaMonHoc', $maMonHoc);
            $stmt->execute();
            $cauTrucDiem = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Lưu/cập nhật từng loại điểm
            foreach ($diemData as $maLoaiDiem => $soDiem) {
                // Kiểm tra điểm đã tồn tại chưa
                $queryCheck = "SELECT MaChiTiet FROM CHI_TIET_DIEM 
                              WHERE MaDangKy = :MaDangKy AND MaLoaiDiem = :MaLoaiDiem";
                $stmtCheck = $this->conn->prepare($queryCheck);
                $stmtCheck->bindParam(':MaDangKy', $maDangKy);
                $stmtCheck->bindParam(':MaLoaiDiem', $maLoaiDiem);
                $stmtCheck->execute();
                $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // Cập nhật
                    $queryUpdate = "UPDATE CHI_TIET_DIEM 
                                   SET SoDiem = :SoDiem, NgayNhap = NOW(), NguoiNhap = :NguoiNhap 
                                   WHERE MaChiTiet = :MaChiTiet";
                    $stmtUpdate = $this->conn->prepare($queryUpdate);
                    $stmtUpdate->bindParam(':SoDiem', $soDiem);
                    $stmtUpdate->bindParam(':NguoiNhap', $maGiangVien);
                    $stmtUpdate->bindParam(':MaChiTiet', $existing['MaChiTiet']);
                    $stmtUpdate->execute();
                } else {
                    // Tạo mới
                    $queryInsert = "INSERT INTO CHI_TIET_DIEM (MaDangKy, MaLoaiDiem, SoDiem, NgayNhap, NguoiNhap) 
                                   VALUES (:MaDangKy, :MaLoaiDiem, :SoDiem, NOW(), :NguoiNhap)";
                    $stmtInsert = $this->conn->prepare($queryInsert);
                    $stmtInsert->bindParam(':MaDangKy', $maDangKy);
                    $stmtInsert->bindParam(':MaLoaiDiem', $maLoaiDiem);
                    $stmtInsert->bindParam(':SoDiem', $soDiem);
                    $stmtInsert->bindParam(':NguoiNhap', $maGiangVien);
                    $stmtInsert->execute();
                }
            }

            // Tính điểm tổng
            $diemTong = 0;
            foreach ($cauTrucDiem as $ct) {
                $maLoaiDiem = $ct['MaLoaiDiem'];
                if (isset($diemData[$maLoaiDiem])) {
                    $diemTong += floatval($diemData[$maLoaiDiem]) * floatval($ct['HeSo']);
                }
            }

            // Chuyển điểm chữ
            $diemChu = $this->chuyenDiemChu($diemTong);
            $diemSo = $diemTong;

            // Cập nhật điểm tổng vào DANG_KY_HOC
            $queryUpdateDiem = "UPDATE DANG_KY_HOC 
                               SET DiemTongKet = :DiemTongKet, DiemChu = :DiemChu, DiemSo = :DiemSo,
                                   KetQua = :KetQua
                               WHERE MaDangKy = :MaDangKy";
            $stmtUpdateDiem = $this->conn->prepare($queryUpdateDiem);
            $ketQua = ($diemTong >= 4) ? 1 : 0;
            $stmtUpdateDiem->bindParam(':DiemTongKet', $diemTong);
            $stmtUpdateDiem->bindParam(':DiemChu', $diemChu);
            $stmtUpdateDiem->bindParam(':DiemSo', $diemSo);
            $stmtUpdateDiem->bindParam(':KetQua', $ketQua);
            $stmtUpdateDiem->bindParam(':MaDangKy', $maDangKy);
            $stmtUpdateDiem->execute();

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Lưu điểm thành công', 'diemTong' => $diemTong, 'diemChu' => $diemChu]);
        } catch (Exception $e) {
            $this->conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Trang tra cứu điểm
     * URL: GiangVien/traCuuDiem?maLopHocPhan=...
     */
    public function traCuuDiem() {
        $maGiangVien = isset($_SESSION['MaGiangVien']) ? $_SESSION['MaGiangVien'] : 'GV001';
        
        $giangVien = $this->giangVienModel->getById($maGiangVien);
        if (!$giangVien) {
            $giangVien = ['MaGiangVien' => $maGiangVien, 'HoTen' => 'Giảng viên'];
        }

        // Lấy danh sách lớp học phần
        $stmtLhp = $this->lopHocPhanModel->readAll();
        $lopHocPhanList = $stmtLhp ? $stmtLhp->fetchAll(PDO::FETCH_ASSOC) : [];

        $lopHocPhanSelected = null;
        $sinhVienDiem = [];
        $thongKe = ['tong' => 0, 'dau' => 0, 'rot' => 0, 'chuaCoDiem' => 0];
        $loaiDiemList = [];
        $maLopHocPhan = $_GET['maLopHocPhan'] ?? '';

        if ($maLopHocPhan) {
            $lopHocPhanSelected = $this->lopHocPhanModel->getById($maLopHocPhan);
            
            if ($lopHocPhanSelected) {
                // Lấy danh sách loại điểm từ cấu trúc điểm của môn học
                $maMonHoc = $lopHocPhanSelected['MaMonHoc'];
                $queryLoaiDiem = "SELECT DISTINCT ld.MaLoaiDiem, ld.TenLoaiDiem
                                  FROM CAU_TRUC_DIEM ctd
                                  JOIN LOAI_DIEM ld ON ctd.MaLoaiDiem = ld.MaLoaiDiem
                                  WHERE ctd.MaMonHoc = :MaMonHoc
                                  ORDER BY ctd.HeSo DESC";
                $stmtLoaiDiem = $this->conn->prepare($queryLoaiDiem);
                $stmtLoaiDiem->bindParam(':MaMonHoc', $maMonHoc);
                $stmtLoaiDiem->execute();
                $loaiDiemList = $stmtLoaiDiem->fetchAll(PDO::FETCH_ASSOC);

                // Lấy danh sách sinh viên và điểm
                $query = "SELECT dk.MaDangKy, dk.MaSinhVien, dk.DiemTongKet, dk.DiemChu, dk.DiemSo, dk.KetQua,
                                 sv.HoTen, sv.MaLop, sv.Email, sv.SoDienThoai
                          FROM DANG_KY_HOC dk
                          JOIN SINH_VIEN sv ON dk.MaSinhVien = sv.MaSinhVien
                          WHERE dk.MaLopHocPhan = :MaLopHocPhan
                          ORDER BY sv.MaSinhVien";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':MaLopHocPhan', $maLopHocPhan);
                $stmt->execute();
                $sinhVienList = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Lấy điểm chi tiết và thống kê
                foreach ($sinhVienList as &$sv) {
                    $maDangKy = $sv['MaDangKy'];
                    $queryDiem = "SELECT ctd.MaLoaiDiem, ctd.SoDiem, ld.TenLoaiDiem
                                  FROM CHI_TIET_DIEM ctd
                                  JOIN LOAI_DIEM ld ON ctd.MaLoaiDiem = ld.MaLoaiDiem
                                  WHERE ctd.MaDangKy = :MaDangKy";
                    $stmtDiem = $this->conn->prepare($queryDiem);
                    $stmtDiem->bindParam(':MaDangKy', $maDangKy);
                    $stmtDiem->execute();
                    $diemList = $stmtDiem->fetchAll(PDO::FETCH_ASSOC);
                    
                    $sv['diem'] = [];
                    foreach ($diemList as $d) {
                        $sv['diem'][$d['TenLoaiDiem']] = ['SoDiem' => floatval($d['SoDiem'])];
                    }

                    // Thống kê
                    $thongKe['tong']++;
                    if ($sv['DiemTongKet'] === null) {
                        $thongKe['chuaCoDiem']++;
                    } elseif ($sv['DiemTongKet'] >= 4) {
                        $thongKe['dau']++;
                    } else {
                        $thongKe['rot']++;
                    }
                }

                $sinhVienDiem = $sinhVienList;
            }
        }

        $data = [
            'giangVien' => $giangVien,
            'lopHocPhanList' => $lopHocPhanList,
            'lopHocPhanSelected' => $lopHocPhanSelected,
            'sinhVienDiem' => $sinhVienDiem,
            'thongKe' => $thongKe,
            'loaiDiemList' => $loaiDiemList,
        ];

        $this->view('giangvien/tracuudiem', $data);
    }

    /**
     * Trang gửi thông báo
     * URL: GiangVien/guiThongBao?maLopHocPhan=...
     */
    public function guiThongBao() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // API gửi thông báo
            header('Content-Type: application/json');
            $input = json_decode(file_get_contents('php://input'), true);
            
            $maLopHocPhan = $input['maLopHocPhan'] ?? '';
            $tieuDe = $input['tieuDe'] ?? '';
            $noiDung = $input['noiDung'] ?? '';
            $sinhVienList = $input['sinhVien'] ?? [];

            if (!$maLopHocPhan || !$tieuDe || !$noiDung || empty($sinhVienList)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
                return;
            }

            try {
                // TODO: Lưu thông báo vào database hoặc gửi email
                // Tạm thời chỉ trả về thành công
                // Bạn có thể tạo bảng THONG_BAO để lưu lịch sử
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã gửi thông báo đến ' . count($sinhVienList) . ' sinh viên thành công!'
                ]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
            }
            return;
        }

        // Hiển thị view
        $maGiangVien = isset($_SESSION['MaGiangVien']) ? $_SESSION['MaGiangVien'] : 'GV001';
        
        $giangVien = $this->giangVienModel->getById($maGiangVien);
        if (!$giangVien) {
            $giangVien = ['MaGiangVien' => $maGiangVien, 'HoTen' => 'Giảng viên'];
        }

        $stmtLhp = $this->lopHocPhanModel->readAll();
        $lopHocPhanList = $stmtLhp ? $stmtLhp->fetchAll(PDO::FETCH_ASSOC) : [];

        $lopHocPhanSelected = null;
        $sinhVienList = [];
        $maLopHocPhan = $_GET['maLopHocPhan'] ?? '';

        if ($maLopHocPhan) {
            $lopHocPhanSelected = $this->lopHocPhanModel->getById($maLopHocPhan);
            
            if ($lopHocPhanSelected) {
                $query = "SELECT sv.MaSinhVien, sv.HoTen, sv.Email, sv.SoDienThoai, sv.MaLop,
                                 dk.DiemTongKet, dk.KetQua
                          FROM DANG_KY_HOC dk
                          JOIN SINH_VIEN sv ON dk.MaSinhVien = sv.MaSinhVien
                          WHERE dk.MaLopHocPhan = :MaLopHocPhan
                          ORDER BY sv.MaSinhVien";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':MaLopHocPhan', $maLopHocPhan);
                $stmt->execute();
                $sinhVienList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        $data = [
            'giangVien' => $giangVien,
            'lopHocPhanList' => $lopHocPhanList,
            'lopHocPhanSelected' => $lopHocPhanSelected,
            'sinhVienList' => $sinhVienList,
        ];

        $this->view('giangvien/guithongbao', $data);
    }

    /**
     * Xuất Excel bảng điểm
     * URL: GiangVien/xuatExcel?maLopHocPhan=...
     */
    public function xuatExcel() {
        $maLopHocPhan = $_GET['maLopHocPhan'] ?? '';
        
        if (!$maLopHocPhan) {
            header('Location: ' . (defined('URLROOT') ? URLROOT : '') . '/GiangVien/traCuuDiem');
            exit;
        }

        $lopHocPhan = $this->lopHocPhanModel->getById($maLopHocPhan);
        if (!$lopHocPhan) {
            header('Location: ' . (defined('URLROOT') ? URLROOT : '') . '/GiangVien/traCuuDiem');
            exit;
        }

        // Lấy thông tin môn học
        $monHoc = $this->monHocModel->getById($lopHocPhan['MaMonHoc']);

        // Lấy danh sách sinh viên và điểm
        $query = "SELECT dk.MaDangKy, dk.MaSinhVien, dk.DiemTongKet, dk.DiemChu, dk.DiemSo, dk.KetQua,
                         sv.HoTen, sv.MaLop
                  FROM DANG_KY_HOC dk
                  JOIN SINH_VIEN sv ON dk.MaSinhVien = sv.MaSinhVien
                  WHERE dk.MaLopHocPhan = :MaLopHocPhan
                  ORDER BY sv.MaSinhVien";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':MaLopHocPhan', $maLopHocPhan);
        $stmt->execute();
        $sinhVienList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy các loại điểm
        $loaiDiemList = $this->loaiDiemModel->readAll();

        // Xuất Excel (CSV format đơn giản)
        $filename = 'BangDiem_' . $maLopHocPhan . '_' . date('YmdHis') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        
        // BOM UTF-8 để Excel hiển thị tiếng Việt đúng
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header
        fputcsv($output, ['BẢNG ĐIỂM LỚP HỌC PHẦN']);
        fputcsv($output, ['Lớp học phần:', $maLopHocPhan]);
        fputcsv($output, ['Môn học:', $monHoc['TenMonHoc'] ?? $lopHocPhan['MaMonHoc']]);
        fputcsv($output, []);

        // Tiêu đề cột
        $headers = ['STT', 'Mã SV', 'Họ tên', 'Lớp hành chính'];
        foreach ($loaiDiemList as $ld) {
            $headers[] = $ld['TenLoaiDiem'];
        }
        $headers[] = 'Điểm tổng';
        $headers[] = 'Điểm chữ';
        $headers[] = 'Kết quả';
        fputcsv($output, $headers);

        // Dữ liệu
        foreach ($sinhVienList as $idx => $sv) {
            $row = [
                $idx + 1,
                $sv['MaSinhVien'],
                $sv['HoTen'],
                $sv['MaLop'] ?? ''
            ];

            // Lấy điểm chi tiết
            $queryDiem = "SELECT ctd.MaLoaiDiem, ctd.SoDiem
                          FROM CHI_TIET_DIEM ctd
                          WHERE ctd.MaDangKy = :MaDangKy";
            $stmtDiem = $this->conn->prepare($queryDiem);
            $stmtDiem->bindParam(':MaDangKy', $sv['MaDangKy']);
            $stmtDiem->execute();
            $diemList = $stmtDiem->fetchAll(PDO::FETCH_ASSOC);
            $diemMap = [];
            foreach ($diemList as $d) {
                $diemMap[$d['MaLoaiDiem']] = $d['SoDiem'];
            }

            foreach ($loaiDiemList as $ld) {
                $row[] = $diemMap[$ld['MaLoaiDiem']] ?? '';
            }

            $row[] = $sv['DiemTongKet'] ? number_format($sv['DiemTongKet'], 2) : '';
            $row[] = $sv['DiemChu'] ?? '';
            $row[] = ($sv['DiemTongKet'] === null) ? 'Chưa có điểm' : (($sv['DiemTongKet'] >= 4) ? 'Đậu' : 'Rớt');

            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    /**
     * Chuyển điểm số sang điểm chữ
     */
    private function chuyenDiemChu($diem) {
        if ($diem >= 9) return 'A+';
        if ($diem >= 8.5) return 'A';
        if ($diem >= 8) return 'B+';
        if ($diem >= 7) return 'B';
        if ($diem >= 6.5) return 'C+';
        if ($diem >= 6) return 'C';
        if ($diem >= 5) return 'D+';
        if ($diem >= 4) return 'D';
        return 'F';
    }
}

?>