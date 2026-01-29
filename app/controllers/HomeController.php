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
        
        // Lấy count từ model
        $sinhviens = $svModel->readAll();
        $giangviens = $gvModel->readAll();
        $lophocphans = $lhpModel->readAll();
        $monhocs = $mhModel->readAll();
        
        $totalSV = count($sinhviens);
        $totalGV = count($giangviens);
        $totalLHP = count($lophocphans);
        $totalMH = count($monhocs);

        // Truyền data sang view home/index.php
        $this->view("home/index", [
            'totalSV' => $totalSV,
            'totalGV' => $totalGV,
            'totalLHP' => $totalLHP,
            'totalMH' => $totalMH,
            'passRate' => 95,
            'avgScore' => 7.5,
            'pending' => 12,
            'currentHocKy' => 'Học kỳ 2',
            'currentNamHoc' => 'Năm học 2024-2025',
            'activeLHP' => $totalLHP,
            'totalDangKy' => 0,
            'pageTitle' => 'Dashboard',
            'breadcrumb' => 'Trang chủ'
        ]);
    }
}
?>
<?php

require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {
    
    public function index() {
        $base = defined('URLROOT') ? rtrim(URLROOT, '/') : '';
        header('Location: ' . ($base ? $base . '/' : '') . 'GiangVien/dashboard');
        exit;
    }
}

?>
