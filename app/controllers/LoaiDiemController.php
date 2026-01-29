<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/LoaiDiem.php';

class LoaiDiemController {

    private $db;
    private $loaiDiemModel;

    public function __construct() {
        $this->db = new Database();
        $this->loaiDiemModel = new LoaiDiem($this->db->getConnection());
    }

    // Lấy danh sách tất cả các loại điểm
    public function getAllLoaiDiem() {
        return $this->loaiDiemModel->readAll();
    }

    // Lấy thông tin một loại điểm theo mã
    public function getLoaiDiemById($maLoaiDiem) {
        return $this->loaiDiemModel->getById($maLoaiDiem);
    }

    // Thêm một loại điểm mới
    public function addLoaiDiem($data) {
        $this->loaiDiemModel->MaLoaiDiem = $data['MaLoaiDiem'];
        $this->loaiDiemModel->TenLoaiDiem = $data['TenLoaiDiem'];
        return $this->loaiDiemModel->create();
    }

    // Cập nhật thông tin loại điểm
    public function updateLoaiDiem($maLoaiDiem, $data) {
        $this->loaiDiemModel->MaLoaiDiem = $maLoaiDiem;
        $this->loaiDiemModel->TenLoaiDiem = $data['TenLoaiDiem'];
        return $this->loaiDiemModel->update();
    }

    // Xóa một loại điểm
    public function deleteLoaiDiem($maLoaiDiem) {
        $this->loaiDiemModel->MaLoaiDiem = $maLoaiDiem;
        return $this->loaiDiemModel->delete();
    }

    // Tìm kiếm loại điểm
    public function searchLoaiDiem($criteria) {
        return $this->loaiDiemModel->search($criteria);
    }
}

?>