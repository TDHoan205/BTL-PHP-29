<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/KhoaModel.php';

class KhoaController {
    private $db;
    private $khoaModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->khoaModel = new KhoaModel($this->db);
    }

    public function index() {
        $khoas = $this->khoaModel->readAll();
        $data = [
            'khoas' => $khoas,
            'pageTitle' => 'Quản lý Khoa',
            'breadcrumb' => 'Khoa'
        ];
        require_once "../app/views/khoa/index.php";
    }

    public function create() {
        require_once "../views/khoa/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->khoaModel->MaKhoa = $_POST['MaKhoa'] ?? null;
            $this->khoaModel->TenKhoa = $_POST['TenKhoa'] ?? null;
            $this->khoaModel->NgayThanhLap = $_POST['NgayThanhLap'] ?? null;
            $this->khoaModel->TruongKhoa = $_POST['TruongKhoa'] ?? null;

            if ($this->khoaModel->create()) {
                header("Location: index.php?url=Khoa/index");
            } else {
                echo "Lỗi thêm khoa.";
            }
        }
    }

    public function edit($id) {
        $khoa = $this->khoaModel->getById($id);
        require_once "../views/khoa/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->khoaModel->MaKhoa = $id; // Model dùng MaKhoa làm khóa cập nhật
            $this->khoaModel->TenKhoa = $_POST['TenKhoa'] ?? null;
            $this->khoaModel->NgayThanhLap = $_POST['NgayThanhLap'] ?? null;
            $this->khoaModel->TruongKhoa = $_POST['TruongKhoa'] ?? null;

            if ($this->khoaModel->update()) {
                header("Location: index.php?url=Khoa/index");
            } else {
                echo "Lỗi cập nhật khoa.";
            }
        }
    }

    public function delete($id) {
        $this->khoaModel->MaKhoa = $id;
        if ($this->khoaModel->delete()) {
            header("Location: index.php?url=Khoa/index");
        } else {
            echo "Lỗi xóa khoa.";
        }
    }
}