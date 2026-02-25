<?php
/**
 * SinhVienController - Quản lý Sinh viên (admin) + Cổng Sinh viên (portal)
 */
require_once __DIR__ . '/../core/Controller.php';

class SinhVienController extends Controller {
    private $svModel;
    private $lopModel;

    public function __construct() {
        parent::__construct();
        $this->svModel = $this->model('SinhVienModel');
        $this->lopModel = $this->model('LopHanhChinhModel');
    }

    /**
     * Lấy thông tin SV đăng nhập (cổng SV). Redirect nếu chưa đăng nhập.
     */
    private function getSinhVienPortalData() {
        $baseUrl = defined('URLROOT') ? rtrim(URLROOT, '/') : '';
        if (empty($_SESSION['logged_in']) || empty($_SESSION['user_id'])) {
            header('Location: ' . $baseUrl . '/Auth/index');
            exit;
        }
        $userModel = $this->model('UserModel');
        $user = $userModel->getById($_SESSION['user_id']);
        if (!$user) {
            session_destroy();
            header('Location: ' . $baseUrl . '/Auth/index');
            exit;
        }
        $tenDangNhap = trim($user['TenDangNhap'] ?? '');
        $sinhVien = $this->svModel->getByIdWithLop($tenDangNhap);
        if (!$sinhVien) {
            $all = $this->svModel->readAllWithLop();
            foreach ($all as $s) {
                if (strcasecmp($s['MaSinhVien'] ?? '', $tenDangNhap) === 0) {
                    $sinhVien = $s;
                    break;
                }
            }
        }
        if (!$sinhVien) {
            $sinhVien = ['MaSinhVien' => $tenDangNhap, 'HoTen' => $user['HoTen'] ?? 'Sinh viên', 'MaLop' => '', 'TenLop' => ''];
        }
        return $sinhVien;
    }

    /**
     * Dashboard cổng sinh viên
     */
    public function dashboard() {
        $sinhVien = $this->getSinhVienPortalData();
        $maSV = $sinhVien['MaSinhVien'] ?? '';
        $dkhModel = $this->model('DangKyHocModel');
        $diemModel = $this->model('ChiTietDiemModel');
        $dangKys = $dkhModel->getByMaSinhVienWithDetails($maSV);
        $tongMon = count($dangKys);
        $tongTinChi = 0;
        $tinChiDat = 0;
        $tongDiem = 0;
        $tongHeSo = 0;
        foreach ($dangKys as $dk) {
            $tc = (int)($dk['SoTinChi'] ?? 0);
            $diem = isset($dk['DiemTongKet']) && $dk['DiemTongKet'] !== null
                ? (float)$dk['DiemTongKet'] : null;
            if ($diem === null) {
                $diems = $diemModel->getByDangKy($dk['MaDangKy'] ?? 0);
                $diem = $this->tinhDiemTrungBinh($diems);
            }
            $tongTinChi += $tc;
            if ($diem !== null) {
                $tongDiem += $diem * $tc;
                $tongHeSo += $tc;
                if ($diem >= 4.0) $tinChiDat += $tc;
            }
        }
        $tbToanKhoa = $tongHeSo > 0 ? round($tongDiem / $tongHeSo, 2) : null;
        require_once __DIR__ . '/../views/sinhvien/dashboard.php';
    }

    /**
     * Alias cho dashboard - dùng để match URL route /SinhVien/dashboardsinhvien
     */
    public function dashboardsinhvien() {
        $this->dashboard();
    }

    /**
     * Xem thông tin cá nhân
     */
    public function thongTinCaNhan() {
        $sinhVien = $this->getSinhVienPortalData();
        require_once __DIR__ . '/../views/sinhvien/thongtincanhan.php';
    }

    /**
     * Xem điểm: theo môn, theo học kỳ, TB học kỳ / toàn khóa
     */
    public function xemDiem() {
        $sinhVien = $this->getSinhVienPortalData();
        $maSV = $sinhVien['MaSinhVien'] ?? '';
        $hocKyModel = $this->model('HocKyModel');
        $dkhModel = $this->model('DangKyHocModel');
        $diemModel = $this->model('ChiTietDiemModel');
        $hocKys = $hocKyModel->readAll();
        $hocKyList = [];
        foreach ($hocKys as $hk) {
            $hocKyList[] = ['value' => $hk['MaHocKy'] ?? '', 'label' => $hk['TenHocKy'] ?? ''];
        }
        $maHK = $_GET['maHocKy'] ?? null;
        $dangKys = $dkhModel->getByMaSinhVienWithDetails($maSV);

        // Chi tiết điểm theo MaDangKy + Cấu trúc điểm (HeSo) theo môn
        $cauTrucModel = $this->model('CauTrucDiemModel');
        $chiTietByDangKy = [];
        $cauTrucByMonHoc = [];
        $dangKyList = [];
        foreach ($dangKys as $dk) {
            $chiTiet = $diemModel->getByDangKy($dk['MaDangKy'] ?? 0);
            $chiTietByDangKy[$dk['MaDangKy']] = $chiTiet;

            $maMH = $dk['MaMonHoc'] ?? '';
            if ($maMH && !isset($cauTrucByMonHoc[$maMH])) {
                $cauTrucByMonHoc[$maMH] = $cauTrucModel->getByMaMonHoc($maMH);
            }

            $diem = isset($dk['DiemTongKet']) && $dk['DiemTongKet'] !== null
                ? (float)$dk['DiemTongKet'] : null;
            if ($diem === null) {
                $diem = $this->tinhDiemTrungBinh($chiTiet);
            }
            $diemChu = $this->diemSoSangChu($diem);

            $dangKyList[] = array_merge($dk, [
                'DiemTongKet' => $diem,
                'DiemChu' => $diemChu,
            ]);
        }

        if ($maHK) {
            $dangKyList = array_filter($dangKyList, function ($r) use ($maHK) {
                return ($r['MaHocKy'] ?? '') === $maHK;
            });
        }

        $tbHK = null;
        $tongTC = 0;
        $tongDiemTC = 0;
        foreach ($dangKyList as $r) {
            if (($r['DiemTongKet'] ?? null) !== null) {
                $tc = (int)($r['SoTinChi'] ?? 0);
                $tongTC += $tc;
                $tongDiemTC += $r['DiemTongKet'] * $tc;
            }
        }
        if ($tongTC > 0) $tbHK = round($tongDiemTC / $tongTC, 2);

        $tongDiemAll = 0;
        $tongTCAll = 0;
        foreach ($dkhModel->getByMaSinhVienWithDetails($maSV) as $dk) {
            $ct = $diemModel->getByDangKy($dk['MaDangKy'] ?? 0);
            $d = isset($dk['DiemTongKet']) && $dk['DiemTongKet'] !== null ? (float)$dk['DiemTongKet'] : $this->tinhDiemTrungBinh($ct);
            if ($d !== null) {
                $tc = (int)($dk['SoTinChi'] ?? 0);
                $tongTCAll += $tc;
                $tongDiemAll += $d * $tc;
            }
        }
        $tbToanKhoa = $tongTCAll > 0 ? round($tongDiemAll / $tongTCAll, 2) : null;

        // Thông tin tổng quát: TBC thang 4, xếp loại, tín chỉ tích lũy
        $tongDiem4 = 0;
        $tongTC4 = 0;
        $tongTinChiTichLuy = 0;
        foreach ($dkhModel->getByMaSinhVienWithDetails($maSV) as $dk) {
            $ct = $diemModel->getByDangKy($dk['MaDangKy'] ?? 0);
            $d = isset($dk['DiemTongKet']) && $dk['DiemTongKet'] !== null ? (float)$dk['DiemTongKet'] : $this->tinhDiemTrungBinh($ct);
            if ($d !== null && $d >= 4.0) {
                $tc = (int)($dk['SoTinChi'] ?? 0);
                $tongTinChiTichLuy += $tc;
                $d4 = $this->diem10Sang4($d);
                $tongDiem4 += $d4 * $tc;
                $tongTC4 += $tc;
            }
        }
        $tbcTichLuyThang4 = $tongTC4 > 0 ? round($tongDiem4 / $tongTC4, 2) : null;
        $tbcHocTapThang4 = $tbcTichLuyThang4;
        $xepHangHocLuc = $this->xepLoaiThang4($tbcTichLuyThang4);
        $xepLoaiThang4 = $xepHangHocLuc;
        $tbcHocTapThang10 = $tbToanKhoa;
        $xepLoaiThang10 = $this->xepLoaiThang10($tbToanKhoa);

        require_once __DIR__ . '/../views/sinhvien/xemdiem.php';
    }

    /**
     * Thống kê cá nhân: tổng tín chỉ đạt, cảnh báo học lực
     */
    public function thongKe() {
        $sinhVien = $this->getSinhVienPortalData();
        $maSV = $sinhVien['MaSinhVien'] ?? '';
        $dkhModel = $this->model('DangKyHocModel');
        $diemModel = $this->model('ChiTietDiemModel');
        $dangKys = $dkhModel->getByMaSinhVienWithDetails($maSV);
        $tongTinChi = 0;
        $tinChiDat = 0;
        $tongDiemTC = 0;
        $monRot = 0;
        foreach ($dangKys as $dk) {
            $tc = (int)($dk['SoTinChi'] ?? 0);
            $diem = isset($dk['DiemTongKet']) && $dk['DiemTongKet'] !== null
                ? (float)$dk['DiemTongKet'] : null;
            if ($diem === null) {
                $ct = $diemModel->getByDangKy($dk['MaDangKy'] ?? 0);
                $diem = $this->tinhDiemTrungBinh($ct);
            }
            $tongTinChi += $tc;
            if ($diem !== null) {
                $tongDiemTC += $diem * $tc;
                if ($diem >= 4.0) $tinChiDat += $tc;
                else $monRot++;
            }
        }
        $tongTCCoDiem = 0;
        foreach ($dangKys as $dk) {
            $d = isset($dk['DiemTongKet']) && $dk['DiemTongKet'] !== null ? (float)$dk['DiemTongKet'] : null;
            if ($d === null) {
                $ct = $diemModel->getByDangKy($dk['MaDangKy'] ?? 0);
                $d = $this->tinhDiemTrungBinh($ct);
            }
            if ($d !== null) $tongTCCoDiem += (int)($dk['SoTinChi'] ?? 0);
        }
        $tbToanKhoa = $tongTCCoDiem > 0 ? round($tongDiemTC / $tongTCCoDiem, 2) : null;
        $canhBao = [];
        if ($tbToanKhoa !== null && $tbToanKhoa < 2.0) {
            $canhBao[] = 'Điểm trung bình toàn khóa dưới 2.0 – Cảnh báo học lực yếu.';
        }
        if ($monRot >= 3) {
            $canhBao[] = 'Bạn có ' . $monRot . ' môn chưa đạt – Cần cải thiện kết quả học tập.';
        }
        if ($tbToanKhoa !== null && $tbToanKhoa >= 3.6) {
            $canhBao[] = 'Học lực khá giỏi – Giữ vững phong độ!';
        }
        require_once __DIR__ . '/../views/sinhvien/thongke.php';
    }

    /**
     * Xem các môn đang học / chưa học theo ngành của sinh viên
     */
    public function monHoc() {
        $sinhVien = $this->getSinhVienPortalData();
        $maSV = $sinhVien['MaSinhVien'] ?? '';
        $lopModel = $this->model('LopHanhChinhModel');
        $monHocModel = $this->model('MonHocModel');
        $dkhModel = $this->model('DangKyHocModel');
        $lhpModel = $this->model('LopHocPhanModel');

        $lop = $lopModel->getById($sinhVien['MaLop'] ?? '');
        $maNganh = $lop['MaNganh'] ?? null;
        $tenNganh = $lop['TenNganh'] ?? '';

        $monDangHoc = [];
        $monChuaHoc = [];
        $monDaHoc = [];

        if ($maNganh) {
            $monTheoNganh = $monHocModel->getByMaNganh($maNganh);
            $dangKys = $dkhModel->getByMaSinhVienWithDetails($maSV);
            $maMonDaDangKy = [];
            foreach ($dangKys as $dk) {
                $maMon = $dk['MaMonHoc'] ?? '';
                if ($maMon) $maMonDaDangKy[$maMon] = $dk;
            }

            $hocKyModel = $this->model('HocKyModel');
            $hocKys = $hocKyModel->readAll();
            $today = date('Y-m-d');
            $maHKHienTai = null;
            foreach ($hocKys as $hk) {
                $batDau = $hk['NgayBatDau'] ?? '';
                $ketThuc = $hk['NgayKetThuc'] ?? '';
                if ($batDau && $ketThuc && $today >= $batDau && $today <= $ketThuc) {
                    $maHKHienTai = $hk['MaHocKy'] ?? '';
                    break;
                }
            }

            foreach ($monTheoNganh as $m) {
                $maMon = $m['MaMonHoc'] ?? '';
                $dk = $maMonDaDangKy[$maMon] ?? null;
                if ($dk) {
                    $lhp = $lhpModel->getById($dk['MaLopHocPhan'] ?? '');
                    $maHK = $lhp['MaHocKy'] ?? '';
                    $row = array_merge($m, [
                        'MaLopHocPhan' => $dk['MaLopHocPhan'] ?? '',
                        'TenHocKy' => $dk['TenHocKy'] ?? '',
                        'NamHoc' => $dk['NamHoc'] ?? '',
                    ]);
                    if ($maHK === $maHKHienTai) {
                        $monDangHoc[] = $row;
                    } else {
                        $monDaHoc[] = $row;
                    }
                } else {
                    $monChuaHoc[] = $m;
                }
            }
        }

        require_once __DIR__ . '/../views/sinhvien/monhoc.php';
    }

    /**
     * Thời khóa biểu - Lịch học của sinh viên
     */
    public function lichHoc() {
        $sinhVien = $this->getSinhVienPortalData();
        $maSV = $sinhVien['MaSinhVien'] ?? '';
        $hocKyModel = $this->model('HocKyModel');
        $tkbModel = $this->model('ThoiKhoaBieuModel');
        $maHocKy = $_GET['maHocKy'] ?? null;
        $lichHoc = $tkbModel->getLichHocBySinhVien($maSV, $maHocKy);
        $hocKys = $hocKyModel->readAll();
        require_once __DIR__ . '/../views/sinhvien/lichhoc.php';
    }

    private function tinhDiemTrungBinh($diems) {
        $diemCC = $diemGK = $diemCK = null;
        foreach ($diems as $d) {
            $loai = strtoupper($d['MaLoaiDiem'] ?? $d['TenLoaiDiem'] ?? '');
            $soDiem = (float)($d['SoDiem'] ?? 0);
            if (strpos($loai, 'CC') !== false || $loai === 'CHUYÊN CẦN') $diemCC = $soDiem;
            elseif (strpos($loai, 'GK') !== false || $loai === 'GIỮA KỲ') $diemGK = $soDiem;
            elseif (strpos($loai, 'CK') !== false || $loai === 'CUỐI KỲ') $diemCK = $soDiem;
        }
        if ($diemCC !== null && $diemGK !== null && $diemCK !== null) {
            return round($diemCC * 0.1 + $diemGK * 0.3 + $diemCK * 0.6, 2);
        }
        return null;
    }

    private function diemSoSangChu($diemSo) {
        if ($diemSo === null) return '';
        if ($diemSo >= 9.0) return 'A+';
        if ($diemSo >= 8.5) return 'A';
        if ($diemSo >= 8.0) return 'B+';
        if ($diemSo >= 7.0) return 'B';
        if ($diemSo >= 6.5) return 'C+';
        if ($diemSo >= 5.5) return 'C';
        if ($diemSo >= 5.0) return 'D+';
        if ($diemSo >= 4.0) return 'D';
        return 'F';
    }

    /** Chuyển điểm thang 10 sang thang 4 (số) */
    private function diem10Sang4($diem10) {
        if ($diem10 === null) return 0;
        $d = (float)$diem10;
        if ($d >= 9) return 4.0;
        if ($d >= 8.5) return 3.7;
        if ($d >= 8) return 3.3;
        if ($d >= 7) return 3.0;
        if ($d >= 6.5) return 2.7;
        if ($d >= 6) return 2.3;
        if ($d >= 5.5) return 2.0;
        if ($d >= 5) return 1.5;
        if ($d >= 4) return 1.0;
        if ($d >= 3) return 0.7;
        return 0;
    }

    /** Xếp loại học lực theo thang 4 */
    private function xepLoaiThang4($diem4) {
        if ($diem4 === null) return '—';
        $d = (float)$diem4;
        if ($d >= 3.6) return 'Xuất sắc';
        if ($d >= 3.2) return 'Giỏi';
        if ($d >= 2.5) return 'Khá';
        if ($d >= 2.0) return 'Trung bình';
        if ($d >= 1.0) return 'Yếu';
        return 'Kém';
    }

    /** Xếp loại học tập theo thang 10 */
    private function xepLoaiThang10($diem10) {
        if ($diem10 === null) return '—';
        $d = (float)$diem10;
        if ($d >= 9.0) return 'Xuất sắc';
        if ($d >= 8.0) return 'Giỏi';
        if ($d >= 7.0) return 'Khá';
        if ($d >= 6.0) return 'Trung bình khá';
        if ($d >= 5.0) return 'Trung bình';
        if ($d >= 4.0) return 'Trung bình yếu';
        return 'Kém';
    }

    private function buildIndexData($error = '', $success = '') {
        return [
            'sinhviens' => $this->svModel->readAll(),
            'lops' => $this->lopModel->readAll(),
            'pageTitle' => 'Quản lý Sinh viên',
            'breadcrumb' => 'Sinh viên',
            'error' => $error,
            'success' => $success
        ];
    }

    public function index() {
        $data = $this->buildIndexData();
        require_once __DIR__ . '/../views/admin/sinhvien/index.php';
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect('SinhVien/index');
        }

        $input = [
            'MaSinhVien' => $this->getPost('MaSinhVien'),
            'HoTen' => $this->getPost('HoTen'),
            'NgaySinh' => $this->getPost('NgaySinh'),
            'GioiTinh' => $this->getPost('GioiTinh'),
            'DiaChi' => $this->getPost('DiaChi'),
            'Email' => $this->getPost('Email'),
            'SoDienThoai' => $this->getPost('SoDienThoai'),
            'MaLop' => $this->getPost('MaLop'),
            'TrangThaiHocTap' => $this->getPost('TrangThaiHocTap') ?: 'Đang học',
        ];

        // Validate
        $errors = $this->validate($input, [
            'MaSinhVien' => 'required|max:20',
            'HoTen' => 'required|max:100',
            'MaLop' => 'required',
            'Email' => 'email',
            'SoDienThoai' => 'phone'
        ]);

        // Validate ngày sinh
        if (!empty($input['NgaySinh'])) {
            $dobError = $this->validateDob($input['NgaySinh'], 16);
            if ($dobError) $errors['NgaySinh'] = $dobError;
        }

        if (!empty($errors)) {
            $data = $this->buildIndexData(implode(' ', $errors));
            require_once __DIR__ . '/../views/admin/sinhvien/index.php';
            return;
        }

        // Gán dữ liệu vào model
        $this->svModel->MaSinhVien = $input['MaSinhVien'];
        $this->svModel->HoTen = $input['HoTen'];
        $this->svModel->NgaySinh = $input['NgaySinh'] ?: null;
        $this->svModel->GioiTinh = $input['GioiTinh'] ?: null;
        $this->svModel->DiaChi = $input['DiaChi'] ?: null;
        $this->svModel->Email = $input['Email'] ?: null;
        $this->svModel->SoDienThoai = $input['SoDienThoai'] ?: null;
        $this->svModel->MaLop = $input['MaLop'];
        $this->svModel->TrangThaiHocTap = $input['TrangThaiHocTap'];

        $result = $this->svModel->create();
        if ($result === true) {
            // Tự động tạo tài khoản đăng nhập: tên đăng nhập = mã SV, mật khẩu mặc định 123456
            $userModel = $this->model('UserModel');
            if (!$userModel->existsByTenDangNhap($input['MaSinhVien'])) {
                $userModel->TenDangNhap = $input['MaSinhVien'];
                $userModel->MatKhau = password_hash('123456', PASSWORD_DEFAULT);
                $userModel->HoTen = $input['HoTen'];
                $userModel->Email = (!empty($input['Email']) && !$userModel->existsByEmail($input['Email'])) ? $input['Email'] : null;
                $userModel->SoDienThoai = $input['SoDienThoai'] ?: null;
                $userModel->VaiTro = 'SinhVien';
                $userModel->TrangThai = 1;
                $userModel->create();
            }
            $this->redirect('SinhVien/index');
        }

        $data = $this->buildIndexData($result);
        require_once __DIR__ . '/../views/admin/sinhvien/index.php';
    }

    public function edit($id) {
        $sv = $this->svModel->getById($id);
        if (!$sv) {
            $this->redirect('SinhVien/index');
        }

        $data = [
            'sinhvien' => $sv,
            'lops' => $this->lopModel->readAll(),
            'pageTitle' => 'Sửa sinh viên',
            'breadcrumb' => 'Sửa sinh viên',
            'error' => ''
        ];
        require_once __DIR__ . '/../views/admin/sinhvien/edit.php';
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('SinhVien/index');
        }

        $input = [
            'HoTen' => $this->getPost('HoTen'),
            'NgaySinh' => $this->getPost('NgaySinh'),
            'GioiTinh' => $this->getPost('GioiTinh'),
            'DiaChi' => $this->getPost('DiaChi'),
            'Email' => $this->getPost('Email'),
            'SoDienThoai' => $this->getPost('SoDienThoai'),
            'MaLop' => $this->getPost('MaLop'),
            'TrangThaiHocTap' => $this->getPost('TrangThaiHocTap') ?: 'Đang học',
        ];

        // Validate
        $errors = $this->validate($input, [
            'HoTen' => 'required|max:100',
            'MaLop' => 'required',
            'Email' => 'email',
            'SoDienThoai' => 'phone'
        ]);

        if (!empty($input['NgaySinh'])) {
            $dobError = $this->validateDob($input['NgaySinh'], 16);
            if ($dobError) $errors['NgaySinh'] = $dobError;
        }

        if (!empty($errors)) {
            $data = [
                'sinhvien' => array_merge(['MaSinhVien' => $id], $input),
                'lops' => $this->lopModel->readAll(),
                'pageTitle' => 'Sửa sinh viên',
                'breadcrumb' => 'Sửa sinh viên',
                'error' => implode(' ', $errors)
            ];
            require_once __DIR__ . '/../views/admin/sinhvien/edit.php';
            return;
        }

        // Gán dữ liệu vào model
        $this->svModel->MaSinhVien = $id;
        $this->svModel->HoTen = $input['HoTen'];
        $this->svModel->NgaySinh = $input['NgaySinh'] ?: null;
        $this->svModel->GioiTinh = $input['GioiTinh'] ?: null;
        $this->svModel->DiaChi = $input['DiaChi'] ?: null;
        $this->svModel->Email = $input['Email'] ?: null;
        $this->svModel->SoDienThoai = $input['SoDienThoai'] ?: null;
        $this->svModel->MaLop = $input['MaLop'];
        $this->svModel->TrangThaiHocTap = $input['TrangThaiHocTap'];

        $result = $this->svModel->update();
        if ($result === true) {
            $this->redirect('SinhVien/index');
        }

        $data = [
            'sinhvien' => array_merge(['MaSinhVien' => $id], $input),
            'lops' => $this->lopModel->readAll(),
            'pageTitle' => 'Sửa sinh viên',
            'breadcrumb' => 'Sửa sinh viên',
            'error' => $result
        ];
        require_once __DIR__ . '/../views/admin/sinhvien/edit.php';
    }

    public function delete($id) {
        $this->svModel->MaSinhVien = $id;
        $result = $this->svModel->delete();
        
        if ($result !== true) {
            $data = $this->buildIndexData($result);
            require_once __DIR__ . '/../views/admin/sinhvien/index.php';
            return;
        }
        
        $this->redirect('SinhVien/index');
    }

    /**
     * API: Lấy mã sinh viên tiếp theo (AJAX)
     */
    public function getNextId() {
        header('Content-Type: application/json');
        $nextId = $this->svModel->generateNextId('SV');
        echo json_encode(['success' => true, 'nextId' => $nextId]);
        exit;
    }
}