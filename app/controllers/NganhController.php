<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/NganhModel.php';

class NganhController {
    private $db;
    private $nganhModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
            $this->nganhModel = new NganhModel($this->db);
    }

    public function index() {
        $nganhs = $this->nganhModel->readAll();
        $data = [
            'nganhs' => $nganhs,
            'pageTitle' => 'Quản lý Ngành',
            'breadcrumb' => 'Ngành'
        ];
        require_once "../app/views/nganh/index.php";
    }

    public function create() {
        require_once "../views/nganh/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->nganhModel->MaNganh = $_POST['MaNganh'] ?? null;
            $this->nganhModel->TenNganh = $_POST['TenNganh'] ?? null;
            $this->nganhModel->MaKhoa = $_POST['MaKhoa'] ?? null;

            if ($this->nganhModel->create()) {
                header("Location: index.php?url=Nganh/index");
            } else {
                echo "Lỗi thêm ngành.";
            }
        }
    }

    public function edit($id) {
        $nganh = $this->nganhModel->getById($id);
        require_once "../views/nganh/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->nganhModel->MaNganh = $id;
            $this->nganhModel->TenNganh = $_POST['TenNganh'] ?? null;
            $this->nganhModel->MaKhoa = $_POST['MaKhoa'] ?? null;

            if ($this->nganhModel->update()) {
                header("Location: index.php?url=Nganh/index");
            } else {
                echo "Lỗi cập nhật ngành.";
            }
        }
    }

    public function delete($id) {
        $this->nganhModel->MaNganh = $id;
        if ($this->nganhModel->delete()) {
            header("Location: index.php?url=Nganh/index");
        } else {
            echo "Lỗi xóa ngành.";
        }
    }
}
?>