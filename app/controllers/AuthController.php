<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/RememberTokenModel.php';

class AuthController extends Controller {
    private $userModel;
    private $rememberTokenModel;
    
    public function __construct() {
        $db = new Database();
        $this->userModel = new UserModel($db->getConnection());
        $this->rememberTokenModel = new RememberTokenModel($db->getConnection());
    }
    
    /**
     * Hiển thị trang đăng nhập (view bên ngoài admin)
     */
    public function index() {
        // Không tự động đăng nhập nữa
        // Chỉ hiển thị form login bình thường
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
            $remember = isset($_POST['remember']);
            
            // Validate input
            if (empty($username) || empty($password)) {
                $this->showLoginError('Vui lòng nhập đầy đủ thông tin!');
                return;
            }
            
            // Kiểm tra có token "ghi nhớ" cho vai trò này không
            $autoLoginAttempted = false;
            if ($remember && isset($_COOKIE['remember_token'])) {
                $token = $_COOKIE['remember_token'];
                $tokenData = $this->rememberTokenModel->findByToken($token);
                
                // Chỉ tự động đăng nhập nếu vai trò khớp
                if ($tokenData && $this->mapLoginTypeToRole($selectedRole) === $tokenData['VaiTro']) {
                    // Token hợp lệ và vai trò khớp
                    $savedUsername = $tokenData['TenDangNhap'];
                    
                    // Tìm user trong database
                    $users = $this->userModel->readAll();
                    foreach ($users as $user) {
                        if ($user['TenDangNhap'] === $savedUsername) {
                            // Lưu session
                            $_SESSION['user_id'] = $user['MaUser'];
                            $_SESSION['user_name'] = $user['HoTen'];
                            $_SESSION['user_role'] = $user['VaiTro'];
                            $_SESSION['user_avatar'] = $user['Avatar'] ?? null;
                            $_SESSION['logged_in'] = true;
                            $_SESSION['login_type'] = $selectedRole;
                            
                            // Redirect theo role
                            $this->redirectByRole($selectedRole, $user['VaiTro']);
                            return;
                        }
                    }
                }
            }
            
            // Tìm user theo username (đăng nhập bình thường)
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
                $_SESSION['user_avatar'] = $userData['Avatar'] ?? null;
                $_SESSION['logged_in'] = true;
                $_SESSION['login_type'] = $selectedRole;
                
                // Xử lý ghi nhớ đăng nhập
                if ($remember) {
                    // Tạo token ngẫu nhiên an toàn
                    $token = bin2hex(random_bytes(32)); // 64 ký tự hex
                    $hashedToken = hash('sha256', $token); // Hash token trước khi lưu DB
                    
                    // Xóa token cũ của user này với vai trò này
                    $roleEnum = $this->mapLoginTypeToRole($selectedRole);
                    $this->rememberTokenModel->deleteByUsernameAndRole($username, $roleEnum);
                    
                    // Lưu token vào database
                    $this->rememberTokenModel->TenDangNhap = $username;
                    $this->rememberTokenModel->Token = $hashedToken;
                    $this->rememberTokenModel->VaiTro = $roleEnum;
                    $this->rememberTokenModel->NgayHetHan = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 ngày
                    $this->rememberTokenModel->UserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                    $this->rememberTokenModel->IPAddress = $_SERVER['REMOTE_ADDR'] ?? '';
                    $this->rememberTokenModel->create();
                    
                    // Lưu token vào cookie
                    $cookieExpire = time() + (30 * 24 * 60 * 60);
                    setcookie('remember_token', $hashedToken, $cookieExpire, '/', '', false, true);
                } else {
                    // Xóa cookie và token nếu không tick
                    if (isset($_COOKIE['remember_token'])) {
                        $this->rememberTokenModel->deleteByToken($_COOKIE['remember_token']);
                        setcookie('remember_token', '', time() - 3600, '/');
                    }
                }
                
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
     * Map login type sang role enum
     */
    private function mapLoginTypeToRole($loginType) {
        $roleMap = [
            'admin' => 'Admin',
            'teacher' => 'GiangVien',
            'student' => 'SinhVien'
        ];
        return $roleMap[$loginType] ?? 'Admin';
    }
    
    /**
     * Redirect theo role đã chọn và phân quyền trong DB
     */
    private function redirectByRole($selectedRole, $userRole) {
        $baseUrl = defined('URLROOT') ? rtrim(URLROOT, '/') : '';
        $userRoleNorm = trim($userRole ?? '');
        $isGiangVien = (strcasecmp($userRoleNorm, 'GiangVien') === 0);
        $isAdmin = (strcasecmp($userRoleNorm, 'Admin') === 0 || strcasecmp($userRoleNorm, 'QuanLy') === 0);
        $isSinhVien = (strcasecmp($userRoleNorm, 'SinhVien') === 0);

        switch ($selectedRole) {
            case 'teacher':
                if ($isGiangVien) {
                    header('Location: ' . $baseUrl . '/GiangVien/dashboard');
                    exit;
                }
                $this->showLoginError('Bạn không có quyền đăng nhập với vai trò Giảng viên. Tài khoản của bạn không thuộc vai trò giảng viên.');
                return;
            case 'student':
                if ($isSinhVien) {
                    header('Location: ' . $baseUrl . '/SinhVien/dashboard');
                    exit;
                }
                $this->showLoginError('Bạn không có quyền đăng nhập với vai trò Sinh viên.');
                return;
            case 'admin':
            default:
                if ($isAdmin) {
                    header('Location: ' . $baseUrl . '/Home/index');
                    exit;
                }
                $this->showLoginError('Bạn không có quyền đăng nhập với vai trò Quản trị.');
                return;
        }
    }
    
    /**
     * Hiển thị lỗi đăng nhập
     */
    private function showLoginError($message) {
        $data = ['error' => $message];
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Kết thúc phiên khi đóng tab (gọi từ JavaScript sendBeacon)
     * Không redirect - chỉ hủy session để lần mở sau phải đăng nhập lại
     */
    public function sessionEnd() {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header('Content-Type: text/plain');
        echo 'OK';
        exit;
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        // Xóa token "ghi nhớ đăng nhập" khỏi database
        if (isset($_COOKIE['remember_token'])) {
            $this->rememberTokenModel->deleteByToken($_COOKIE['remember_token']);
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
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
    
    /**
     * Hiển thị trang quên mật khẩu
     */
    public function forgotPassword() {
        require_once __DIR__ . '/../views/auth/forgotpassword.php';
    }
    
    /**
     * Xử lý yêu cầu quên mật khẩu
     */
    public function submitForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: index.php?url=Auth/forgotPassword');
            exit;
        }
        
        require_once __DIR__ . '/../models/YeuCauDoiMatKhauModel.php';
        require_once __DIR__ . '/../models/SinhVienModel.php';
        require_once __DIR__ . '/../models/GiangVienModel.php';
        
        $username = trim($_POST['username'] ?? '');
        $maNguoiDung = trim($_POST['maNguoiDung'] ?? '');
        $vaiTro = trim($_POST['vaiTro'] ?? 'GiangVien');
        
        if (empty($username) || empty($maNguoiDung)) {
            $error = 'Vui lòng nhập đầy đủ thông tin!';
            require_once __DIR__ . '/../views/auth/forgotpassword.php';
            return;
        }
        
        // Kiểm tra user có tồn tại không
        $users = $this->userModel->readAll();
        $userExists = false;
        foreach ($users as $user) {
            if ($user['TenDangNhap'] === $username) {
                $userExists = true;
                break;
            }
        }
        
        if (!$userExists) {
            $error = 'Tên đăng nhập không tồn tại trong hệ thống!';
            require_once __DIR__ . '/../views/auth/forgotpassword.php';
            return;
        }
        
        // Kiểm tra mã sinh viên hoặc giảng viên
        $db = new Database();
        $conn = $db->getConnection();
        $isValid = false;
        
        if ($vaiTro === 'SinhVien') {
            $svModel = new SinhVienModel($conn);
            $sv = $svModel->getById($maNguoiDung);
            $isValid = ($sv !== null);
        } else {
            $gvModel = new GiangVienModel($conn);
            $gv = $gvModel->getById($maNguoiDung);
            $isValid = ($gv !== null);
        }
        
        if (!$isValid) {
            $error = 'Mã ' . ($vaiTro === 'SinhVien' ? 'sinh viên' : 'giảng viên') . ' không đúng!';
            require_once __DIR__ . '/../views/auth/forgotpassword.php';
            return;
        }
        
        // Kiểm tra đã có yêu cầu chưa
        $yeuCauModel = new YeuCauDoiMatKhauModel($conn);
        if ($yeuCauModel->hasRequestPending($username, $maNguoiDung)) {
            $error = 'Bạn đã có yêu cầu đang chờ xử lý. Vui lòng chờ admin xác nhận!';
            require_once __DIR__ . '/../views/auth/forgotpassword.php';
            return;
        }
        
        // Tạo yêu cầu mới
        $yeuCauModel->TenDangNhap = $username;
        $yeuCauModel->MaNguoiDung = $maNguoiDung;
        $yeuCauModel->VaiTro = $vaiTro;
        
        if ($yeuCauModel->create()) {
            $success = 'Yêu cầu của bạn đã được gửi! Vui lòng chờ admin xác nhận và cấp lại mật khẩu.';
            require_once __DIR__ . '/../views/auth/forgotpassword.php';
        } else {
            $error = 'Có lỗi xảy ra! Vui lòng thử lại sau.';
            require_once __DIR__ . '/../views/auth/forgotpassword.php';
        }
    }
}
