<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $db;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
            $this->userModel = new UserModel($this->db);
    }

    public function index() {
        $users = $this->userModel->readAll();
        $data = [
            'users' => $users,
            'pageTitle' => 'Quản lý Tài khoản',
            'breadcrumb' => 'Tài khoản'
        ];
        require_once "../app/views/user/index.php";
    }

    public function create() {
        require_once "../app/views/user/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->userModel->TenDangNhap = $_POST['TenDangNhap'] ?? null;
            $this->userModel->MatKhau = $_POST['MatKhau'] ?? null; // Lưu ý: Nên mã hóa (hash) mật khẩu trước khi lưu
            $this->userModel->HoTen = $_POST['HoTen'] ?? null;
            $this->userModel->Email = $_POST['Email'] ?? null;
            $this->userModel->SoDienThoai = $_POST['SoDienThoai'] ?? null;
            $this->userModel->VaiTro = $_POST['VaiTro'] ?? null;
            $this->userModel->TrangThai = $_POST['TrangThai'] ?? 1;
            $this->userModel->NgayTao = date('Y-m-d H:i:s');
            $this->userModel->NgayCapNhat = date('Y-m-d H:i:s');

            if ($this->userModel->create()) {
                header("Location: index.php?url=User/index");
            } else {
                echo "Lỗi thêm người dùng.";
            }
        }
    }

    public function edit($id) {
        $user = $this->userModel->getById($id);
        require_once "../views/user/edit.php";
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->userModel->MaUser = $id;
            $this->userModel->TenDangNhap = $_POST['TenDangNhap'] ?? null;
            $this->userModel->MatKhau = $_POST['MatKhau'] ?? null;
            $this->userModel->HoTen = $_POST['HoTen'] ?? null;
            $this->userModel->Email = $_POST['Email'] ?? null;
            $this->userModel->SoDienThoai = $_POST['SoDienThoai'] ?? null;
            $this->userModel->VaiTro = $_POST['VaiTro'] ?? null;
            $this->userModel->TrangThai = $_POST['TrangThai'] ?? null;
            $this->userModel->NgayCapNhat = date('Y-m-d H:i:s');

            if ($this->userModel->update()) {
                header("Location: index.php?url=User/index");
            } else {
                echo "Lỗi cập nhật người dùng.";
            }
        }
    }

    public function delete($id) {
        $this->userModel->MaUser = $id;
        if ($this->userModel->delete()) {
            header("Location: index.php?url=User/index");
        } else {
            echo "Lỗi xóa người dùng.";
        }
    }
}
?>