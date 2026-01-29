<?php
class App {
    // Default: Chuyển đến trang đăng nhập
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];
    
    // Controllers không cần đăng nhập
    protected $publicControllers = ['Auth'];

    public function __construct() {
        $url = $this->parseUrl();
        $requestedController = $url[0] ?? 'Auth';
        
        // Kiểm tra đăng nhập
        $isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
        $isPublicController = in_array($requestedController, $this->publicControllers);
        
        // Nếu chưa đăng nhập và không phải trang public -> redirect về login
        if (!$isLoggedIn && !$isPublicController) {
            header('Location: index.php?url=Auth/index');
            exit;
        }
        
        // Nếu đã đăng nhập và đang ở trang Auth -> redirect về Home
        if ($isLoggedIn && $requestedController === 'Auth' && ($url[1] ?? 'index') === 'index') {
            header('Location: index.php?url=Home/index');
            exit;
        }

        // Kiểm tra file controller
        if ($url != null && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Kiểm tra method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return null; 
    }
}
?>