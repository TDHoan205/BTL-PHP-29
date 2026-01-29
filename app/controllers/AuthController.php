<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $db = new Database();
        $this->userModel = new UserModel($db->getConnection());
    }
    
    public function index() {
        $this->view("auth/login");
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Tìm user theo username
            $users = $this->userModel->readAll();
            $loggedIn = false;
            
            foreach ($users as $user) {
                if ($user['TenDangNhap'] == $username && $user['MatKhau'] == $password) {
                    $_SESSION['user_id'] = $user['MaUser'];
                    $_SESSION['user_name'] = $user['HoTen'];
                    $_SESSION['user_role'] = $user['VaiTro'];
                    $_SESSION['logged_in'] = true;
                    $loggedIn = true;
                    break;
                }
            }
            
            if ($loggedIn) {
                header("Location: index.php?url=Home/index");
                exit;
            } else {
                $this->view("auth/login", ['error' => 'Sai tên đăng nhập hoặc mật khẩu!']);
            }
        } else {
            $this->view("auth/login");
        }
    }
    
    public function logout() {
        session_destroy();
        header("Location: index.php?url=Auth/index");
        exit;
    }
}
