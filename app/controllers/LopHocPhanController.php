<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/LopHocPhanModel.php';

class LopHocPhanController {
    private $db;
    private $lhpModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->lhpModel = new LopHocPhanModel($this->db);
    }

    public function index() {
        $lophps = $this->lhpModel->readAll();
        $data = [
            'lophps' => $lophps,
            'pageTitle' => 'Quản lý Lớp học phần',
            'breadcrumb' => 'Lớp học phần'
        ];
        require_once "../app/views/lophocphan/index.php";
    }

    public function create() {
        require_once "../app/views/lophocphan/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->lhpModel->MaLopHocPhan = $_POST['MaLopHocPhan'] ?? null;
            $this->lhpModel->TenLop = $_POST['TenLop'] ?? null;
            $this->lhpModel->MaMonHoc = $_POST['MaMonHoc'] ?? null;
            $this->lhpModel->MaGiangVien = $_POST['MaGiangVien'] ?? null;

            if ($this->lhpModel->create()) {
                header("Location: index.php?url=LopHocPhan/index");
            } else {
                echo "Lỗi thêm lớp học phần.";
            }
        }
    }

    public function edit($id) {
        $lhp = $this->lhpModel->getById($id);
        $data = ['lophocphan' => $lhp];
        require_once "../app/views/lophocphan/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->lhpModel->MaLopHocPhan = $id;
            $this->lhpModel->TenLop = $_POST['TenLop'] ?? null;
            $this->lhpModel->MaMonHoc = $_POST['MaMonHoc'] ?? null;
            $this->lhpModel->MaGiangVien = $_POST['MaGiangVien'] ?? null;

            if ($this->lhpModel->update()) {
                header("Location: index.php?url=LopHocPhan/index");
            } else {
                echo "Lỗi cập nhật lớp học phần.";
            }
        }
    }

    public function delete($id) {
        $this->lhpModel->MaLopHocPhan = $id;
        if ($this->lhpModel->delete()) {
            header("Location: index.php?url=LopHocPhan/index");
        } else {
            echo "Lỗi xóa lớp học phần.";
        }
    }
}
?>