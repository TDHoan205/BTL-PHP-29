<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/HocKyModel.php';

class HocKyController {
    private $db;
    private $hockyModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->hockyModel = new HocKyModel($this->db);
    }

    public function index() {
        $hockys = $this->hockyModel->readAll();
        $data = [
            'hockys' => $hockys,
            'pageTitle' => 'Quản lý Học kỳ',
            'breadcrumb' => 'Học kỳ'
        ];
        require_once "../app/views/hocky/index.php";
    }

    public function create() {
        require_once "../views/hocky/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->hockyModel->MaHocKy = $_POST['MaHocKy'] ?? null;
            $this->hockyModel->TenHocKy = $_POST['TenHocKy'] ?? null;
            $this->hockyModel->NamHoc = $_POST['NamHoc'] ?? null;
            $this->hockyModel->NgayBatDau = $_POST['NgayBatDau'] ?? null;
            $this->hockyModel->NgayKetThuc = $_POST['NgayKetThuc'] ?? null;

            if ($this->hockyModel->create()) {
                header("Location: index.php?url=HocKy/index");
            } else {
                echo "Lỗi thêm học kỳ.";
            }
        }
    }

    public function edit($id) {
        $hocky = $this->hockyModel->getById($id);
        require_once "../views/hocky/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->hockyModel->MaHocKy = $id;
            $this->hockyModel->TenHocKy = $_POST['TenHocKy'] ?? null;
            $this->hockyModel->NamHoc = $_POST['NamHoc'] ?? null;
            $this->hockyModel->NgayBatDau = $_POST['NgayBatDau'] ?? null;
            $this->hockyModel->NgayKetThuc = $_POST['NgayKetThuc'] ?? null;

            if ($this->hockyModel->update()) {
                header("Location: index.php?url=HocKy/index");
            } else {
                echo "Lỗi cập nhật học kỳ.";
            }
        }
    }

    public function delete($id) {
        $this->hockyModel->MaHocKy = $id;
        if ($this->hockyModel->delete()) {
            header("Location: index.php?url=HocKy/index");
        } else {
            echo "Lỗi xóa học kỳ.";
        }
    }
}
?>