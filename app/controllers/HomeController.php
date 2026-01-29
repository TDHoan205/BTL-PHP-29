<?php

require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {
    
    public function index() {
        $base = defined('URLROOT') ? rtrim(URLROOT, '/') : '';
        header('Location: ' . ($base ? $base . '/' : '') . 'GiangVien/dashboard');
        exit;
    }
}

?>
