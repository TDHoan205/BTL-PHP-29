<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/SinhVienModel.php';

class SinhVienController {
    private $db;
    private $svModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
            $this->svModel = new SinhVienModel($this->db);
    }

    public function index() {
        $sinhviens = $this->svModel->readAll();
        $data = [
            'sinhviens' => $sinhviens,
            'pageTitle' => 'Quản lý Sinh viên',
            'breadcrumb' => 'Sinh viên'
        ];
        require_once "../app/views/sinhvien/index.php";
    }

    public function create() {
        require_once "../views/sinhvien/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->svModel->MaSinhVien = $_POST['MaSinhVien'] ?? null;
            $this->svModel->HoTen = $_POST['HoTen'] ?? null;
            $this->svModel->NgaySinh = $_POST['NgaySinh'] ?? null;
            $this->svModel->GioiTinh = $_POST['GioiTinh'] ?? null;
            $this->svModel->DiaChi = $_POST['DiaChi'] ?? null;
            $this->svModel->Email = $_POST['Email'] ?? null;
            $this->svModel->SoDienThoai = $_POST['SoDienThoai'] ?? null;
            $this->svModel->MaLop = $_POST['MaLop'] ?? null;
            $this->svModel->TrangThaiHocTap = $_POST['TrangThaiHocTap'] ?? null;

            if ($this->svModel->create()) {
                header("Location: index.php?url=SinhVien/index");
            } else {
                echo "Lỗi thêm sinh viên.";
            }
        }
    }

    public function edit($id) {
        $sv = $this->svModel->getById($id);
        require_once "../views/sinhvien/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->svModel->MaSinhVien = $id;
            $this->svModel->HoTen = $_POST['HoTen'] ?? null;
            $this->svModel->NgaySinh = $_POST['NgaySinh'] ?? null;
            $this->svModel->GioiTinh = $_POST['GioiTinh'] ?? null;
            $this->svModel->DiaChi = $_POST['DiaChi'] ?? null;
            $this->svModel->Email = $_POST['Email'] ?? null;
            $this->svModel->SoDienThoai = $_POST['SoDienThoai'] ?? null;
            $this->svModel->MaLop = $_POST['MaLop'] ?? null;
            $this->svModel->TrangThaiHocTap = $_POST['TrangThaiHocTap'] ?? null;

            if ($this->svModel->update()) {
                header("Location: index.php?url=SinhVien/index");
            } else {
                echo "Lỗi cập nhật sinh viên.";
            }
        }
    }

    public function delete($id) {
        $this->svModel->MaSinhVien = $id;
        if ($this->svModel->delete()) {
            header("Location: index.php?url=SinhVien/index");
        } else {
            echo "Lỗi xóa sinh viên.";
        }
    }
}
?>