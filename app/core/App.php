<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        if ($url && isset($url[0])) {
            $controllerName = $url[0];
            $file1 = '../app/controllers/' . $controllerName . '.php';
            $file2 = '../app/controllers/' . $controllerName . 'Controller.php';
            
            if (file_exists($file1)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } elseif (file_exists($file2)) {
                $this->controller = $controllerName . 'Controller';
                unset($url[0]);
            }
        }

        $controllerFile = '../app/controllers/' . $this->controller . '.php';
        if (!file_exists($controllerFile)) {
            $base = defined('URLROOT') ? rtrim(URLROOT, '/') : '';
            header('Location: ' . ($base ? $base . '/' : ''));
            exit;
        }

        require_once $controllerFile;
        $this->controller = new $this->controller;

        if ($url && isset($url[1])) {
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
        return [];
    }
}
?>