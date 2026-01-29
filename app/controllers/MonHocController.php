<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/MonHocModel.php';

class MonHocController {
    private $db;
    private $monHocModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->monHocModel = new MonHocModel($this->db);
    }

    public function index() {
        $monhocs = $this->monHocModel->readAll();
        $data = [
            'monhocs' => $monhocs,
            'pageTitle' => 'Quản lý Môn học',
            'breadcrumb' => 'Môn học'
        ];
        require_once "../app/views/monhoc/index.php";
    }

    public function create() {
        require_once "../views/monhoc/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->monHocModel->MaMonHoc = $_POST['MaMonHoc'] ?? null;
            $this->monHocModel->TenMonHoc = $_POST['TenMonHoc'] ?? null;
            $this->monHocModel->SoTinChi = $_POST['SoTinChi'] ?? 0;
            $this->monHocModel->SoTietLyThuyet = $_POST['SoTietLyThuyet'] ?? 0;
            $this->monHocModel->SoTietThucHanh = $_POST['SoTietThucHanh'] ?? 0;
            $this->monHocModel->MaNganh = $_POST['MaNganh'] ?? null;

            if ($this->monHocModel->create()) {
                header("Location: index.php?url=MonHoc/index");
            } else {
                echo "Lỗi thêm môn học.";
            }
        }
    }

    public function edit($id) {
        $monHoc = $this->monHocModel->getById($id);
        require_once "../views/monhoc/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->monHocModel->MaMonHoc = $id;
            $this->monHocModel->TenMonHoc = $_POST['TenMonHoc'] ?? null;
            $this->monHocModel->SoTinChi = $_POST['SoTinChi'] ?? 0;
            $this->monHocModel->SoTietLyThuyet = $_POST['SoTietLyThuyet'] ?? 0;
            $this->monHocModel->SoTietThucHanh = $_POST['SoTietThucHanh'] ?? 0;
            $this->monHocModel->MaNganh = $_POST['MaNganh'] ?? null;

            if ($this->monHocModel->update()) {
                header("Location: index.php?url=MonHoc/index");
            } else {
                echo "Lỗi cập nhật môn học.";
            }
        }
    }

    public function delete($id) {
        $this->monHocModel->MaMonHoc = $id;
        if ($this->monHocModel->delete()) {
            header("Location: index.php?url=MonHoc/index");
        } else {
            echo "Lỗi xóa môn học.";
        }
    }
}
?>