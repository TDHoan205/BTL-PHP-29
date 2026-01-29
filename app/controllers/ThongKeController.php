<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/SinhVienModel.php';
require_once __DIR__ . '/../models/GiangVienModel.php';
require_once __DIR__ . '/../models/LopHocPhanModel.php';
require_once __DIR__ . '/../models/MonHocModel.php';
require_once __DIR__ . '/../models/DangKyHocModel.php';

class ThongKeController {
    private $db;
    private $svModel;
    private $gvModel;
    private $lhpModel;
    private $mhModel;
    private $dkhModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->svModel = new SinhVienModel($this->db);
        $this->gvModel = new GiangVienModel($this->db);
        $this->lhpModel = new LopHocPhanModel($this->db);
        $this->mhModel = new MonHocModel($this->db);
        $this->dkhModel = new DangKyHocModel($this->db);
    }

    public function index() {
        // Lấy số liệu thống kê
        $sinhviens = $this->svModel->readAll();
        $giangviens = $this->gvModel->readAll();
        $lophocphans = $this->lhpModel->readAll();
        $monhocs = $this->mhModel->readAll();
        $dangkyhocs = $this->dkhModel->readAll();
        
        // Tính toán thống kê
        $totalSV = count($sinhviens);
        $totalGV = count($giangviens);
        $totalLHP = count($lophocphans);
        $totalMH = count($monhocs);
        $totalDK = count($dangkyhocs);
        
        // Tính tỷ lệ đậu/rớt từ đăng ký học
        $passed = 0;
        $failed = 0;
        foreach ($dangkyhocs as $dk) {
            if (isset($dk['KetQua'])) {
                if ($dk['KetQua'] == 'Đạt') {
                    $passed++;
                } elseif ($dk['KetQua'] == 'Không đạt') {
                    $failed++;
                }
            }
        }
        
        $passRate = $totalDK > 0 ? round(($passed / $totalDK) * 100, 1) : 0;
        
        // Thống kê theo giới tính sinh viên
        $maleCount = 0;
        $femaleCount = 0;
        foreach ($sinhviens as $sv) {
            if (isset($sv['GioiTinh'])) {
                if ($sv['GioiTinh'] == 'Nam') {
                    $maleCount++;
                } else {
                    $femaleCount++;
                }
            }
        }
        
        $data = [
            'totalSV' => $totalSV,
            'totalGV' => $totalGV,
            'totalLHP' => $totalLHP,
            'totalMH' => $totalMH,
            'totalDK' => $totalDK,
            'passed' => $passed,
            'failed' => $failed,
            'passRate' => $passRate,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'sinhviens' => $sinhviens,
            'giangviens' => $giangviens,
            'pageTitle' => 'Thống kê',
            'breadcrumb' => 'Thống kê'
        ];
        
        require_once "../app/views/thongke/index.php";
    }
}
?>
