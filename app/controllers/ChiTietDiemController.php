<?php

require_once '../config/Database.php';
require_once '../models/ChiTietDiem.php';

class ChiTietDiemController {

    private $db;
    private $chiTietDiemModel;

    public function __construct() {
        $this->db = new Database();
        $this->chiTietDiemModel = new ChiTietDiem($this->db->getConnection());
    }

    // Lấy danh sách tất cả các chi tiết điểm
    public function getAllChiTietDiem() {
        return $this->chiTietDiemModel->readAll();
    }

    // Lấy thông tin một chi tiết điểm theo mã
    public function getChiTietDiemById($maChiTiet) {
        return $this->chiTietDiemModel->getById($maChiTiet);
    }

    // Thêm một chi tiết điểm mới
    public function addChiTietDiem($data) {
        $this->chiTietDiemModel->MaChiTiet = $data['MaChiTiet'];
        $this->chiTietDiemModel->MaSinhVien = $data['MaSinhVien'];
        $this->chiTietDiemModel->MaMonHoc = $data['MaMonHoc'];
        $this->chiTietDiemModel->Diem = $data['Diem'];
        return $this->chiTietDiemModel->create();
    }

    // Cập nhật thông tin chi tiết điểm
    public function updateChiTietDiem($maChiTiet, $data) {
        $this->chiTietDiemModel->MaChiTiet = $maChiTiet;
        $this->chiTietDiemModel->MaSinhVien = $data['MaSinhVien'];
        $this->chiTietDiemModel->MaMonHoc = $data['MaMonHoc'];
        $this->chiTietDiemModel->Diem = $data['Diem'];
        return $this->chiTietDiemModel->update();
    }

    // Xóa một chi tiết điểm
    public function deleteChiTietDiem($maChiTiet) {
        $this->chiTietDiemModel->MaChiTiet = $maChiTiet;
        return $this->chiTietDiemModel->delete();
    }

    // Tìm kiếm chi tiết điểm
    public function searchChiTietDiem($criteria) {
        return $this->chiTietDiemModel->search($criteria);
    }
}

?>