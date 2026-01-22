<?php

require_once '../config/Database.php';
require_once '../models/User.php';

class UserController {

    private $db;
    private $userModel;

    public function __construct() {
        $this->db = new Database();
        $this->userModel = new User($this->db->getConnection());
    }

    // Lấy danh sách tất cả người dùng
    public function getAllUsers() {
        return $this->userModel->readAll();
    }

    // Lấy thông tin một người dùng theo ID
    public function getUserById($userId) {
        return $this->userModel->getById($userId);
    }

    // Thêm một người dùng mới
    public function addUser($data) {
        $this->userModel->MaUser = $data['MaUser'];
        $this->userModel->TenDangNhap = $data['TenDangNhap'];
        $this->userModel->MatKhau = $data['MatKhau'];
        $this->userModel->HoTen = $data['HoTen'];
        $this->userModel->Email = $data['Email'];
        $this->userModel->SoDienThoai = $data['SoDienThoai'];
        $this->userModel->VaiTro = $data['VaiTro'];
        $this->userModel->TrangThai = $data['TrangThai'];
        $this->userModel->NgayTao = $data['NgayTao'];
        $this->userModel->NgayCapNhat = $data['NgayCapNhat'];
        return $this->userModel->create();
    }

    // Cập nhật thông tin người dùng
    public function updateUser($userId, $data) {
        $this->userModel->MaUser = $userId;
        $this->userModel->TenDangNhap = $data['TenDangNhap'];
        $this->userModel->MatKhau = $data['MatKhau'];
        $this->userModel->HoTen = $data['HoTen'];
        $this->userModel->Email = $data['Email'];
        $this->userModel->SoDienThoai = $data['SoDienThoai'];
        $this->userModel->VaiTro = $data['VaiTro'];
        $this->userModel->TrangThai = $data['TrangThai'];
        $this->userModel->NgayCapNhat = $data['NgayCapNhat'];
        return $this->userModel->update();
    }

    // Xóa một người dùng
    public function deleteUser($userId) {
        $this->userModel->MaUser = $userId;
        return $this->userModel->delete();
    }
}

?>