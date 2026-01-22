<?php

require_once '../config/Database.php';
require_once '../models/GiangVien.php';

class GiangVienController {

    private $db;
    private $giangVienModel;

    public function __construct() {
        $this->db = new Database();
        $this->giangVienModel = new GiangVien($this->db->getConnection());
    }

    // Lấy danh sách tất cả giảng viên
    public function getAllGiangVien() {
        return $this->giangVienModel->readAll();
    }

    // Lấy thông tin một giảng viên theo mã giảng viên
    public function getGiangVienById($maGiangVien) {
        return $this->giangVienModel->getById($maGiangVien);
    }

    // Thêm một giảng viên mới
    public function addGiangVien($data) {
        $this->giangVienModel->MaGiangVien = $data['MaGiangVien'];
        $this->giangVienModel->HoTen = $data['HoTen'];
        $this->giangVienModel->NgaySinh = $data['NgaySinh'];
        $this->giangVienModel->GioiTinh = $data['GioiTinh'];
        $this->giangVienModel->Email = $data['Email'];
        $this->giangVienModel->SoDienThoai = $data['SoDienThoai'];
        $this->giangVienModel->HocVi = $data['HocVi'];
        $this->giangVienModel->MaKhoa = $data['MaKhoa'];
        $this->giangVienModel->TrangThai = $data['TrangThai'];
        return $this->giangVienModel->create();
    }

    // Cập nhật thông tin giảng viên
    public function updateGiangVien($maGiangVien, $data) {
        $this->giangVienModel->MaGiangVien = $maGiangVien;
        $this->giangVienModel->HoTen = $data['HoTen'];
        $this->giangVienModel->NgaySinh = $data['NgaySinh'];
        $this->giangVienModel->GioiTinh = $data['GioiTinh'];
        $this->giangVienModel->Email = $data['Email'];
        $this->giangVienModel->SoDienThoai = $data['SoDienThoai'];
        $this->giangVienModel->HocVi = $data['HocVi'];
        $this->giangVienModel->MaKhoa = $data['MaKhoa'];
        $this->giangVienModel->TrangThai = $data['TrangThai'];
        return $this->giangVienModel->update();
    }

    // Xóa một giảng viên
    public function deleteGiangVien($maGiangVien) {
        $this->giangVienModel->MaGiangVien = $maGiangVien;
        return $this->giangVienModel->delete();
    }

    // Tìm kiếm giảng viên
    public function searchGiangVien($criteria) {
        return $this->giangVienModel->search($criteria);
    }
}

?>