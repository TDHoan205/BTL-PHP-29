<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/SinhVien.php';

class SinhVienController {

    private $db;
    private $sinhVienModel;

    public function __construct() {
        $this->db = new Database();
        $this->sinhVienModel = new SinhVien($this->db->getConnection());
    }

    // Lấy danh sách tất cả sinh viên
    public function getAllSinhVien() {
        return $this->sinhVienModel->readAll();
    }

    // Lấy thông tin một sinh viên theo mã sinh viên
    public function getSinhVienById($maSinhVien) {
        return $this->sinhVienModel->getById($maSinhVien);
    }

    // Thêm một sinh viên mới
    public function addSinhVien($data) {
        $this->sinhVienModel->MaSinhVien = $data['MaSinhVien'];
        $this->sinhVienModel->HoTen = $data['HoTen'];
        $this->sinhVienModel->NgaySinh = $data['NgaySinh'];
        $this->sinhVienModel->GioiTinh = $data['GioiTinh'];
        $this->sinhVienModel->Email = $data['Email'];
        $this->sinhVienModel->SoDienThoai = $data['SoDienThoai'];
        $this->sinhVienModel->MaLop = $data['MaLop'];
        return $this->sinhVienModel->create();
    }

    // Cập nhật thông tin sinh viên
    public function updateSinhVien($maSinhVien, $data) {
        $this->sinhVienModel->MaSinhVien = $maSinhVien;
        $this->sinhVienModel->HoTen = $data['HoTen'];
        $this->sinhVienModel->NgaySinh = $data['NgaySinh'];
        $this->sinhVienModel->GioiTinh = $data['GioiTinh'];
        $this->sinhVienModel->Email = $data['Email'];
        $this->sinhVienModel->SoDienThoai = $data['SoDienThoai'];
        $this->sinhVienModel->MaLop = $data['MaLop'];
        return $this->sinhVienModel->update();
    }

    // Xóa một sinh viên
    public function deleteSinhVien($maSinhVien) {
        $this->sinhVienModel->MaSinhVien = $maSinhVien;
        return $this->sinhVienModel->delete();
    }
}

?>