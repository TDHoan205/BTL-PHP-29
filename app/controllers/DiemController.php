<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/ChiTietDiemModel.php';
require_once __DIR__ . '/../models/DangKyHocModel.php';
require_once __DIR__ . '/../models/LopHocPhanModel.php';
require_once __DIR__ . '/../models/HocKyModel.php';

class DiemController {
    private $db;
    private $diemModel;
    private $dangKyModel;
    private $lopHPModel;
    private $hocKyModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->diemModel = new ChiTietDiemModel($this->db);
        $this->dangKyModel = new DangKyHocModel($this->db);
        $this->lopHPModel = new LopHocPhanModel($this->db);
        $this->hocKyModel = new HocKyModel($this->db);
    }

    public function index() {
        // Lấy danh sách lớp học phần và học kỳ cho filter
        $lophocphans = $this->lopHPModel->readAll();
        $hockys = $this->hocKyModel->readAll();
        
        // Lấy dữ liệu điểm nếu có filter
        $bangdiem = [];
        $filterLop = $_GET['lop'] ?? null;
        
        if ($filterLop) {
            $bangdiem = $this->getBangDiemByLop($filterLop);
        }
        
        $data = [
            'bangdiem' => $bangdiem,
            'lophocphans' => $lophocphans,
            'hockys' => $hockys,
            'totalSV' => count($bangdiem),
            'passed' => 0,
            'failed' => 0,
            'pending' => count($bangdiem),
            'pageTitle' => 'Quản lý Điểm',
            'breadcrumb' => 'Điểm'
        ];
        
        require_once "../app/views/diem/index.php";
    }
    
    private function getBangDiemByLop($maLop) {
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
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maLop', $maLop);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAll() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? 'save';
            $diemData = $_POST['diem'] ?? [];
            
            foreach ($diemData as $id => $diem) {
                // Xử lý cập nhật điểm tại đây
                // Có thể cần tạo/update các bản ghi CHI_TIET_DIEM
            }
            
            header("Location: index.php?url=Diem/index");
            exit;
        }
    }
    
    public function approve() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maLop = $_POST['MaLopHocPhan'] ?? null;
            
            if ($maLop) {
                // Xử lý phê duyệt điểm cho lớp học phần
                // Cập nhật trạng thái điểm
            }
            
            header("Location: index.php?url=Diem/index");
            exit;
        }
    }
}
?>
