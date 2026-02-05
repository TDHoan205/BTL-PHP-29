<?php
/**
 * DiemController - Quản lý Điểm
 */
require_once __DIR__ . '/../core/Controller.php';

class DiemController extends Controller {
    private $diemModel;
    private $dangKyModel;
    private $lopHPModel;
    private $hocKyModel;

    public function __construct() {
        parent::__construct();
        $this->diemModel = $this->model('ChiTietDiemModel');
        $this->dangKyModel = $this->model('DangKyHocModel');
        $this->lopHPModel = $this->model('LopHocPhanModel');
        $this->hocKyModel = $this->model('HocKyModel');
    }

    public function index() {
        $lophocphans = $this->lopHPModel->readAllWithDetails();
        $hockys = $this->hocKyModel->readAll();
        
        $bangdiem = [];
        $filterLop = isset($_GET['lop']) ? $this->sanitize($_GET['lop']) : (isset($_GET['maLopHocPhan']) ? $this->sanitize($_GET['maLopHocPhan']) : null);
        
        if ($filterLop) {
            $bangdiem = $this->getBangDiemByLop($filterLop);
            foreach ($bangdiem as &$row) {
                $diemTB = $this->tinhDiemTB($row);
                $row['DiemTongKet'] = $diemTB !== null ? number_format($diemTB, 2) : null;
                $row['DiemChu'] = $this->diemSoSangChu($diemTB);
                $row['KetQua'] = $diemTB !== null ? ($diemTB >= 4.0 ? 'Đạt' : 'Không đạt') : null;
            }
            unset($row);
        }
        
        // Tính thống kê
        $passed = 0;
        $failed = 0;
        $pending = 0;
        
        foreach ($bangdiem as $sv) {
            $diemTB = $this->tinhDiemTB($sv);
            if ($diemTB === null) {
                $pending++;
            } elseif ($diemTB >= 4.0) {
                $passed++;
            } else {
                $failed++;
            }
        }
        
        $data = [
            'bangdiem' => $bangdiem,
            'lophocphans' => $lophocphans,
            'hockys' => $hockys,
            'filterLop' => $filterLop,
            'totalSV' => count($bangdiem),
            'passed' => $passed,
            'failed' => $failed,
            'pending' => $pending,
            'pageTitle' => 'Quản lý Điểm',
            'breadcrumb' => 'Điểm',
            'error' => '',
            'success' => ''
        ];
        
        require_once __DIR__ . '/../views/admin/diem/index.php';
    }
    
    /**
     * Tính điểm trung bình theo công thức chuẩn
     */
    private function tinhDiemTB($sv) {
        $diemCC = isset($sv['DiemCC']) ? (float) $sv['DiemCC'] : null;
        $diemGK = isset($sv['DiemGK']) ? (float) $sv['DiemGK'] : null;
        $diemCK = isset($sv['DiemCK']) ? (float) $sv['DiemCK'] : null;
        
        if ($diemCC === null && $diemGK === null && $diemCK === null) {
            return null;
        }
        
        // Công thức: CC*10% + GK*30% + CK*60%
        $cc = $diemCC ?? 0;
        $gk = $diemGK ?? 0;
        $ck = $diemCK ?? 0;
        
        return round($cc * 0.1 + $gk * 0.3 + $ck * 0.6, 2);
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
    
    /**
     * Lấy bảng điểm theo lớp học phần
     */
    private function getBangDiemByLop($maLop) {
        $db = $this->getDb();
        $query = "SELECT dk.MaDangKy as ID, sv.MaSinhVien as MSSV, sv.HoTen, 
                         lhp.MaLopHocPhan,
                         MAX(CASE WHEN ld.TenLoaiDiem = 'Chuyên cần' OR ld.TenLoaiDiem = 'Quá trình' THEN ctd.SoDiem END) as DiemCC,
                         MAX(CASE WHEN ld.TenLoaiDiem = 'Giữa kỳ' THEN ctd.SoDiem END) as DiemGK,
                         MAX(CASE WHEN ld.TenLoaiDiem = 'Cuối kỳ' THEN ctd.SoDiem END) as DiemCK
                  FROM DANG_KY_HOC dk
                  JOIN SINH_VIEN sv ON dk.MaSinhVien = sv.MaSinhVien
                  JOIN LOP_HOC_PHAN lhp ON dk.MaLopHocPhan = lhp.MaLopHocPhan
                  LEFT JOIN CHI_TIET_DIEM ctd ON dk.MaDangKy = ctd.MaDangKy
                  LEFT JOIN LOAI_DIEM ld ON ctd.MaLoaiDiem = ld.MaLoaiDiem
                  WHERE dk.MaLopHocPhan = :maLop
                  GROUP BY dk.MaDangKy, sv.MaSinhVien, sv.HoTen, lhp.MaLopHocPhan";
        
        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':maLop', $maLop);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Cập nhật điểm hàng loạt
     */
    public function updateAll() {
        if (!$this->isPost()) {
            $this->redirect('Diem/index');
        }
        
        $diemData = $_POST['diem'] ?? [];
        $filterLop = $this->getPost('MaLopHocPhan');
        $errors = [];
        
        foreach ($diemData as $maDangKy => $diem) {
            // Validate điểm
            foreach (['DiemCC', 'DiemGK', 'DiemCK'] as $loai) {
                if (isset($diem[$loai]) && $diem[$loai] !== '') {
                    $val = (float) $diem[$loai];
                    if ($val < 0 || $val > 10) {
                        $errors[] = "Điểm phải từ 0 đến 10.";
                        break 2;
                    }
                }
            }
        }
        
        if (!empty($errors)) {
            $data = [
                'bangdiem' => $filterLop ? $this->getBangDiemByLop($filterLop) : [],
                'lophocphans' => $this->lopHPModel->readAll(),
                'hockys' => $this->hocKyModel->readAll(),
                'filterLop' => $filterLop,
                'totalSV' => 0,
                'passed' => 0,
                'failed' => 0,
                'pending' => 0,
                'pageTitle' => 'Quản lý Điểm',
                'breadcrumb' => 'Điểm',
                'error' => implode(' ', $errors),
                'success' => ''
            ];
            require_once __DIR__ . '/../views/admin/diem/index.php';
            return;
        }
        
        // Xử lý cập nhật điểm
        foreach ($diemData as $maDangKy => $diem) {
            $this->updateDiemForDangKy($maDangKy, $diem);
        }
        
        $this->redirect('Diem/index' . ($filterLop ? '?lop=' . urlencode($filterLop) : ''));
    }
    
    /**
     * Cập nhật điểm cho một đăng ký học
     */
    private function updateDiemForDangKy($maDangKy, $diem) {
        // Logic cập nhật điểm chi tiết
        // TODO: Implement based on cấu trúc điểm cụ thể
    }
    
    /**
     * Phê duyệt điểm
     */
    public function approve() {
        if (!$this->isPost()) {
            $this->redirect('Diem/index');
        }
        
        $maLop = $this->getPost('MaLopHocPhan');
        
        if (empty($maLop)) {
            $data = [
                'bangdiem' => [],
                'lophocphans' => $this->lopHPModel->readAll(),
                'hockys' => $this->hocKyModel->readAll(),
                'filterLop' => null,
                'totalSV' => 0,
                'passed' => 0,
                'failed' => 0,
                'pending' => 0,
                'pageTitle' => 'Quản lý Điểm',
                'breadcrumb' => 'Điểm',
                'error' => 'Vui lòng chọn lớp học phần.',
                'success' => ''
            ];
            require_once __DIR__ . '/../views/admin/diem/index.php';
            return;
        }
        
        // TODO: Implement logic phê duyệt điểm
        
        $this->redirect('Diem/index?lop=' . urlencode($maLop));
    }

    /**
     * Xuất bảng điểm sinh viên ra Excel (CSV)
     */
    public function exportExcel() {
        $filterLop = isset($_GET['lop']) ? $this->sanitize($_GET['lop']) : null;
        if (empty($filterLop)) {
            header('Location: index.php?url=Diem/index');
            exit;
        }
        $bangdiem = $this->getBangDiemByLop($filterLop);
        foreach ($bangdiem as &$row) {
            $diemTB = $this->tinhDiemTB($row);
            $row['DiemTongKet'] = $diemTB !== null ? round($diemTB, 2) : null;
            $row['DiemChu'] = $this->diemSoSangChu($diemTB);
            $row['KetQua'] = $diemTB !== null ? ($diemTB >= 4.0 ? 'Đạt' : 'Không đạt') : null;
        }
        unset($row);

        $filename = 'BangDiem_' . $filterLop . '_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        fputcsv($output, ['STT', 'MSSV', 'Họ tên', 'Lớp HP', 'Điểm QT', 'Điểm GK', 'Điểm CK', 'Điểm TB', 'Điểm chữ', 'Kết quả']);
        $stt = 1;
        foreach ($bangdiem as $r) {
            fputcsv($output, [
                $stt++,
                $r['MSSV'] ?? '',
                $r['HoTen'] ?? '',
                $r['MaLopHocPhan'] ?? '',
                $r['DiemCC'] ?? '',
                $r['DiemGK'] ?? '',
                $r['DiemCK'] ?? '',
                $r['DiemTongKet'] ?? '',
                $r['DiemChu'] ?? '',
                $r['KetQua'] ?? ''
            ]);
        }
        fclose($output);
        exit;
    }
}
