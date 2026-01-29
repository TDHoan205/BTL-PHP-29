<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/DangKyHoc.php';

class DangKyHocController {

    private $db;
    private $dangKyHocModel;

    public function __construct() {
        $this->db = new Database();
        $this->dangKyHocModel = new DangKyHoc($this->db->getConnection());
    }

    // Lấy danh sách tất cả các đăng ký học
    public function getAllDangKyHoc() {
        return $this->dangKyHocModel->readAll();
    }

    // Lấy thông tin một đăng ký học theo mã
    public function getDangKyHocById($maDangKy) {
        return $this->dangKyHocModel->getById($maDangKy);
    }

    // Thêm một đăng ký học mới
    public function addDangKyHoc($data) {
        $this->dangKyHocModel->MaDangKy = $data['MaDangKy'];
        $this->dangKyHocModel->MaSinhVien = $data['MaSinhVien'];
        $this->dangKyHocModel->MaLopHocPhan = $data['MaLopHocPhan'];
        return $this->dangKyHocModel->create();
    }

    // Cập nhật thông tin đăng ký học
    public function updateDangKyHoc($maDangKy, $data) {
        $this->dangKyHocModel->MaDangKy = $maDangKy;
        $this->dangKyHocModel->MaSinhVien = $data['MaSinhVien'];
        $this->dangKyHocModel->MaLopHocPhan = $data['MaLopHocPhan'];
        return $this->dangKyHocModel->update();
    }

    // Xóa một đăng ký học
    public function deleteDangKyHoc($maDangKy) {
        $this->dangKyHocModel->MaDangKy = $maDangKy;
        return $this->dangKyHocModel->delete();
    }

    // Tìm kiếm đăng ký học
    public function searchDangKyHoc($criteria) {
        return $this->dangKyHocModel->search($criteria);
    }
}

?>