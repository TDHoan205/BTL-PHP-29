<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/DangKyHocModel.php';

class DangKyHocController {
    private $db;
    private $dkhModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->dkhModel = new DangKyHocModel($this->db);
    }

    public function index() {
        $dangkyhocs = $this->dkhModel->readAll();
        $data = [
            'dangkyhocs' => $dangkyhocs,
            'pageTitle' => 'Quản lý Đăng ký học',
            'breadcrumb' => 'Đăng ký học'
        ];
        require_once "../app/views/dangkyhoc/index.php";
    }

    public function create() {
        require_once "../views/dangkyhoc/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Model của bạn yêu cầu nhập MaDangKy thủ công
            $this->dkhModel->MaDangKy = $_POST['MaDangKy'] ?? null;
            $this->dkhModel->MaSinhVien = $_POST['MaSinhVien'] ?? null;
            $this->dkhModel->MaLopHocPhan = $_POST['MaLopHocPhan'] ?? null;
            
            if ($this->dkhModel->create()) {
                header("Location: index.php?url=DangKyHoc/index");
            } else {
                echo "Lỗi đăng ký học.";
            }
        }
    }

    public function edit($id) {
        $dkh = $this->dkhModel->getById($id);
        require_once "../views/dangkyhoc/edit.php";
    }

    public function update($id) {
         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->dkhModel->MaDangKy = $id;
            $this->dkhModel->MaSinhVien = $_POST['MaSinhVien'] ?? null;
            $this->dkhModel->MaLopHocPhan = $_POST['MaLopHocPhan'] ?? null;

            if ($this->dkhModel->update()) {
                header("Location: index.php?url=DangKyHoc/index");
            } else {
                echo "Lỗi cập nhật đăng ký.";
            }
        }
    }

    public function delete($id) {
        $this->dkhModel->MaDangKy = $id;
        if ($this->dkhModel->delete()) {
            header("Location: index.php?url=DangKyHoc/index");
        } else {
            echo "Lỗi hủy đăng ký.";
        }
    }
}
?>