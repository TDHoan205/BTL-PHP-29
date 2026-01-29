<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../config/Database.php';

class HomeController extends Controller {
    public function index() {
        // Load các model để lấy số liệu thống kê
        $svModel = $this->model("SinhVienModel");
        $gvModel = $this->model("GiangVienModel");
        $lhpModel = $this->model("LopHocPhanModel");
        $mhModel = $this->model("MonHocModel");
        $khoaModel = $this->model("KhoaModel");
        $nganhModel = $this->model("NganhModel");
        $hocKyModel = $this->model("HocKyModel");
        $dangKyModel = $this->model("DangKyHocModel");
        $diemModel = $this->model("ChiTietDiemModel");
        
        // Lấy dữ liệu từ model
        $sinhviens = $svModel->readAll();
        $giangviens = $gvModel->readAll();
        $lophocphans = $lhpModel->readAll();
        $monhocs = $mhModel->readAll();
        $khoas = $khoaModel->readAll();
        $nganhs = $nganhModel->readAll();
        $hockys = $hocKyModel->readAll();
        $dangkys = $dangKyModel->readAll();
        
        // Tính thống kê cơ bản
        $totalSV = count($sinhviens);
        $totalGV = count($giangviens);
        $totalLHP = count($lophocphans);
        $totalMH = count($monhocs);
        $totalKhoa = count($khoas);
        $totalNganh = count($nganhs);
        $totalDangKy = count($dangkys);
        
        // Tính học kỳ hiện tại
        $currentHocKy = 'Chưa xác định';
        $currentNamHoc = '';
        $today = date('Y-m-d');
        foreach ($hockys as $hk) {
            if (isset($hk['NgayBatDau'], $hk['NgayKetThuc'])) {
                if ($today >= $hk['NgayBatDau'] && $today <= $hk['NgayKetThuc']) {
                    $currentHocKy = $hk['TenHocKy'] ?? 'Học kỳ ' . ($hk['MaHocKy'] ?? '');
                    $currentNamHoc = $hk['NamHoc'] ?? '';
                    break;
                }
            }
        }
        
        // Tính thống kê điểm
        $passCount = 0;
        $failCount = 0;
        $pendingCount = 0;
        $totalScore = 0;
        $scoreCount = 0;
        
        // Lấy chi tiết điểm cho từng đăng ký học
        foreach ($dangkys as $dk) {
            $diems = $diemModel->getByDangKy($dk['MaDangKy'] ?? 0);
            
            if (empty($diems)) {
                $pendingCount++;
                continue;
            }
            
            // Tính điểm TB (CC*10% + GK*30% + CK*60%)
            $diemCC = null;
            $diemGK = null;
            $diemCK = null;
            
            foreach ($diems as $d) {
                $loai = strtoupper($d['MaLoaiDiem'] ?? '');
                if (strpos($loai, 'CC') !== false) $diemCC = $d['Diem'];
                elseif (strpos($loai, 'GK') !== false) $diemGK = $d['Diem'];
                elseif (strpos($loai, 'CK') !== false) $diemCK = $d['Diem'];
            }
            
            if ($diemCC !== null && $diemGK !== null && $diemCK !== null) {
                $diemTB = $diemCC * 0.1 + $diemGK * 0.3 + $diemCK * 0.6;
                $totalScore += $diemTB;
                $scoreCount++;
                
                if ($diemTB >= 4.0) {
                    $passCount++;
                } else {
                    $failCount++;
                }
            } else {
                $pendingCount++;
            }
        }
        
        // Tính tỷ lệ đậu và điểm TB
        $totalGraded = $passCount + $failCount;
        $passRate = $totalGraded > 0 ? round(($passCount / $totalGraded) * 100, 1) : 0;
        $avgScore = $scoreCount > 0 ? round($totalScore / $scoreCount, 2) : 0;
        
        // Thống kê sinh viên theo khoa
        $svByKhoa = [];
        foreach ($khoas as $khoa) {
            $count = 0;
            foreach ($sinhviens as $sv) {
                // Đếm SV theo khoa (thông qua ngành hoặc lớp)
                // Đây là logic đơn giản, có thể mở rộng sau
                $count++;
            }
            $svByKhoa[] = [
                'name' => $khoa['TenKhoa'] ?? '',
                'count' => rand(10, 50) // Demo data
            ];
        }
        
        // Thống kê top môn học được đăng ký nhiều nhất
        $topMonHoc = [];
        foreach (array_slice($monhocs, 0, 5) as $mh) {
            $topMonHoc[] = [
                'name' => $mh['TenMonHoc'] ?? '',
                'count' => rand(20, 100) // Demo data
            ];
        }
        
        // Hoạt động gần đây (demo)
        $recentActivities = [
            ['description' => 'Sinh viên mới đăng ký học phần', 'time' => '5 phút trước', 'icon' => 'user-plus', 'color' => 'primary'],
            ['description' => 'Cập nhật điểm lớp Lập trình Web', 'time' => '15 phút trước', 'icon' => 'edit', 'color' => 'success'],
            ['description' => 'Mở lớp học phần mới', 'time' => '1 giờ trước', 'icon' => 'chalkboard', 'color' => 'info'],
            ['description' => 'Thêm giảng viên mới', 'time' => '2 giờ trước', 'icon' => 'user-tie', 'color' => 'warning'],
            ['description' => 'Cập nhật thông tin sinh viên', 'time' => '3 giờ trước', 'icon' => 'user-edit', 'color' => 'secondary'],
        ];

        // Truyền data sang view home/index.php
        $this->view("home/index", [
            // Thống kê chính
            'totalSV' => $totalSV,
            'totalGV' => $totalGV,
            'totalLHP' => $totalLHP,
            'totalMH' => $totalMH,
            'totalKhoa' => $totalKhoa,
            'totalNganh' => $totalNganh,
            'totalDangKy' => $totalDangKy,
            
            // Thống kê điểm
            'passRate' => $passRate,
            'avgScore' => $avgScore,
            'passCount' => $passCount,
            'failCount' => $failCount,
            'pending' => $pendingCount,
            'totalGraded' => $totalGraded,
            
            // Học kỳ hiện tại
            'currentHocKy' => $currentHocKy,
            'currentNamHoc' => $currentNamHoc,
            'activeLHP' => $totalLHP,
            
            // Biểu đồ data
            'svByKhoa' => $svByKhoa,
            'topMonHoc' => $topMonHoc,
            
            // Hoạt động gần đây
            'recentActivities' => $recentActivities,
            
            // Page info
            'pageTitle' => 'Dashboard',
            'breadcrumb' => 'Trang chủ'
        ]);
    }
}
?>