<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/GiangVienModel.php';

class GiangVienController {
    private $db;
    private $gvModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->gvModel = new GiangVienModel($this->db);
    }

    public function index() {
        $giangviens = $this->gvModel->readAll();
        $data = [
            'giangviens' => $giangviens,
            'pageTitle' => 'Quản lý Giảng viên',
            'breadcrumb' => 'Giảng viên'
        ];
        require_once "../app/views/giangvien/index.php";
    }

    public function create() {
        require_once "../app/views/giangvien/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->gvModel->MaGiangVien = $_POST['MaGiangVien'] ?? null;
            $this->gvModel->HoTen = $_POST['HoTen'] ?? null;
            $this->gvModel->NgaySinh = $_POST['NgaySinh'] ?? null;
            $this->gvModel->GioiTinh = $_POST['GioiTinh'] ?? null;
            $this->gvModel->Email = $_POST['Email'] ?? null;
            $this->gvModel->SoDienThoai = $_POST['SoDienThoai'] ?? null;
            $this->gvModel->HocVi = $_POST['HocVi'] ?? null;
            $this->gvModel->MaKhoa = $_POST['MaKhoa'] ?? null;
            $this->gvModel->TrangThai = $_POST['TrangThai'] ?? null;

            if ($this->gvModel->create()) {
                header("Location: index.php?url=GiangVien/index");
            } else {
                echo "Lỗi thêm giảng viên.";
            }
        }
    }

    public function edit($id) {
        $gv = $this->gvModel->getById($id);
        $data = ['giangvien' => $gv];
        require_once "../app/views/giangvien/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->gvModel->MaGiangVien = $id;
            $this->gvModel->HoTen = $_POST['HoTen'] ?? null;
            $this->gvModel->NgaySinh = $_POST['NgaySinh'] ?? null;
            $this->gvModel->GioiTinh = $_POST['GioiTinh'] ?? null;
            $this->gvModel->Email = $_POST['Email'] ?? null;
            $this->gvModel->SoDienThoai = $_POST['SoDienThoai'] ?? null;
            $this->gvModel->HocVi = $_POST['HocVi'] ?? null;
            $this->gvModel->MaKhoa = $_POST['MaKhoa'] ?? null;
            $this->gvModel->TrangThai = $_POST['TrangThai'] ?? null;

            if ($this->gvModel->update()) {
                header("Location: index.php?url=GiangVien/index");
            } else {
                echo "Lỗi cập nhật giảng viên.";
            }
        }
    }

    public function delete($id) {
        $this->gvModel->MaGiangVien = $id;
        if ($this->gvModel->delete()) {
            header("Location: index.php?url=GiangVien/index");
        } else {
            echo "Lỗi xóa giảng viên.";
        }
    }
}
?>