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
    
    /**
     * Hiển thị trang đăng nhập (view bên ngoài admin)
     */
    public function index() {
        // Sử dụng view login bên ngoài admin
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Xử lý đăng nhập
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $selectedRole = $_POST['role'] ?? 'admin';
            
            // Validate input
            if (empty($username) || empty($password)) {
                $this->showLoginError('Vui lòng nhập đầy đủ thông tin!');
                return;
            }
            
            // Tìm user theo username
            $users = $this->userModel->readAll();
            $loggedIn = false;
            $userData = null;
            
            foreach ($users as $user) {
                if ($user['TenDangNhap'] !== $username) continue;
                
                $stored = $user['MatKhau'] ?? '';
                $verify = (strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0)
                    ? password_verify($password, $stored)
                    : ($stored === $password);
                    
                if ($verify) {
                    $userData = $user;
                    $loggedIn = true;
                    break;
                }
            }
            
            if ($loggedIn && $userData) {
                // Lưu session
                $_SESSION['user_id'] = $userData['MaUser'];
                $_SESSION['user_name'] = $userData['HoTen'];
                $_SESSION['user_role'] = $userData['VaiTro'];
                $_SESSION['logged_in'] = true;
                $_SESSION['login_type'] = $selectedRole;
                
                // Redirect theo role
                $this->redirectByRole($selectedRole, $userData['VaiTro']);
            } else {
                $this->showLoginError('Sai tên đăng nhập hoặc mật khẩu!');
            }
        } else {
            $this->index();
        }
    }
    
    /**
     * Redirect theo role đã chọn
     */
    private function redirectByRole($selectedRole, $userRole) {
        switch ($selectedRole) {
            case 'student':
                // Sau này có thể redirect đến trang sinh viên
                header('Location: index.php?url=Home/index');
                break;
            case 'teacher':
                // Sau này có thể redirect đến trang giảng viên
                header('Location: index.php?url=Home/index');
                break;
            case 'admin':
            default:
                header('Location: index.php?url=Home/index');
                break;
        }
        exit;
    }
    
    /**
     * Hiển thị lỗi đăng nhập
     */
    private function showLoginError($message) {
        $data = ['error' => $message];
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        // Xóa tất cả session data
        $_SESSION = [];
        
        // Xóa session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        
        session_destroy();
        
        header('Location: index.php?url=Auth/index');
        exit;
    }
}
