<?php
// Bắt đầu session (rất quan trọng cho đăng nhập/đăng xuất sau này)
session_start();

// Gọi file khởi tạo (init) từ thư mục app
require_once '../app/init.php';

// Khởi chạy ứng dụng
$app = new App();
?>