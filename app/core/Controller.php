<?php
/**
 * Base Controller Class
 * Cung cấp các phương thức chung cho tất cả Controllers
 */
class Controller {
    protected $db;

    public function __construct() {
        require_once __DIR__ . '/../config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Load model
     * @param string $model Tên model cần load
     * @return object Model instance
     */
    public function model(string $model): object {
        $modelFile = __DIR__ . '/../models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model($this->db);
        }
        throw new \RuntimeException("Model file $modelFile not found.");
    }

    /**
     * Load view
     */
    public function view($view, $data = []) {
        $viewFile = __DIR__ . '/../views/admin/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View file $viewFile not found.");
        }
    }

    /**
     * Redirect to another URL
     */
    protected function redirect($url) {
        header("Location: index.php?url=$url");
        exit;
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Check if user has required role
     */
    protected function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    /**
     * Require authentication
     */
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('Auth/index');
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole($role) {
        $this->requireLogin();
        if (!$this->hasRole($role)) {
            $this->redirect('Home/index');
        }
    }

    /**
     * Validate input data
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? '';
            $fieldRules = explode('|', $ruleSet);
            
            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && empty(trim($value))) {
                    $errors[$field] = "Trường này là bắt buộc!";
                    break;
                }
                
                if (strpos($rule, 'min:') === 0 && !empty($value)) {
                    $min = (int) substr($rule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = "Phải có ít nhất $min ký tự!";
                        break;
                    }
                }
                
                if (strpos($rule, 'max:') === 0 && !empty($value)) {
                    $max = (int) substr($rule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = "Không được vượt quá $max ký tự!";
                        break;
                    }
                }
                
                if ($rule === 'email' && !empty($value)) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = "Email không đúng định dạng!";
                        break;
                    }
                }
                
                if ($rule === 'phone' && !empty($value)) {
                    if (!preg_match('/^[0-9]{9,11}$/', $value)) {
                        $errors[$field] = "Số điện thoại không hợp lệ!";
                        break;
                    }
                }
                
                if ($rule === 'numeric' && !empty($value)) {
                    if (!is_numeric($value)) {
                        $errors[$field] = "Phải là số!";
                        break;
                    }
                }
                
                if ($rule === 'date' && !empty($value)) {
                    $date = DateTime::createFromFormat('Y-m-d', $value);
                    if (!$date) {
                        $errors[$field] = "Ngày không hợp lệ!";
                        break;
                    }
                }
            }
        }
        
        return $errors;
    }

    /**
     * Validate date of birth
     */
    protected function validateDob($dob, $minAge = 10) {
        if (empty($dob)) return null;
        
        $birth = DateTime::createFromFormat('Y-m-d', $dob);
        $today = new DateTime('today');
        
        if (!$birth) {
            return "Ngày sinh không hợp lệ!";
        }
        
        if ($birth > $today) {
            return "Ngày sinh không được ở tương lai!";
        }
        
        $age = $birth->diff($today)->y;
        if ($age < $minAge) {
            return "Tuổi phải từ $minAge trở lên!";
        }
        
        return null;
    }

    /**
     * Sanitize input
     */
    protected function sanitize($value) {
        if ($value === null || $value === '') {
            return null;
        }
        return htmlspecialchars(strip_tags(trim($value)));
    }

    /**
     * Get POST data
     */
    protected function getPost($key, $default = '') {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    /**
     * Check if request is POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Get database connection
     */
    protected function getDb() {
        return $this->db;
    }
}