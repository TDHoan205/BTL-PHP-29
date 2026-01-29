<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/LopHanhChinhModel.php';

class LopHanhChinhController {
    private $db;
    private $lhcModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->lhcModel = new LopHanhChinhModel($this->db);
    }

    public function index() {
        $lophanhchinhs = $this->lhcModel->readAll();
        $data = [
            'lophanhchinhs' => $lophanhchinhs,
            'pageTitle' => 'Quản lý Lớp hành chính',
            'breadcrumb' => 'Lớp hành chính'
        ];
        require_once "../app/views/lophanhchinh/index.php";
    }

    public function create() {
        require_once "../views/lophanhchinh/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->lhcModel->MaLop = $_POST['MaLop'] ?? null;
            $this->lhcModel->TenLop = $_POST['TenLop'] ?? null;
            $this->lhcModel->MaNganh = $_POST['MaNganh'] ?? null;
            $this->lhcModel->KhoaHoc = $_POST['KhoaHoc'] ?? null;
            $this->lhcModel->MaCoVan = $_POST['MaCoVan'] ?? null;

            if ($this->lhcModel->create()) {
                header("Location: index.php?url=LopHanhChinh/index");
            } else {
                echo "Lỗi thêm lớp hành chính.";
            }
        }
    }

    public function edit($id) {
        $lop = $this->lhcModel->getById($id);
        require_once "../views/lophanhchinh/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->lhcModel->MaLop = $id;
            $this->lhcModel->TenLop = $_POST['TenLop'] ?? null;
            $this->lhcModel->MaNganh = $_POST['MaNganh'] ?? null;
            $this->lhcModel->KhoaHoc = $_POST['KhoaHoc'] ?? null;
            $this->lhcModel->MaCoVan = $_POST['MaCoVan'] ?? null;

            if ($this->lhcModel->update()) {
                header("Location: index.php?url=LopHanhChinh/index");
            } else {
                echo "Lỗi cập nhật lớp hành chính.";
            }
        }
    }

    public function delete($id) {
        $this->lhcModel->MaLop = $id;
        if ($this->lhcModel->delete()) {
            header("Location: index.php?url=LopHanhChinh/index");
        } else {
            echo "Lỗi xóa lớp hành chính.";
        }
    }
}
?>