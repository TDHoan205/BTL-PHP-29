<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Khoa.php';

class KhoaController {

    private $db;
    private $khoaModel;

    public function __construct() {
        $this->db = new Database();
        $this->khoaModel = new Khoa($this->db->getConnection());
    }

    // Lấy danh sách tất cả các khoa
    public function getAllKhoa() {
        return $this->khoaModel->readAll();
    }

    // Lấy thông tin một khoa theo mã khoa
    public function getKhoaById($maKhoa) {
        return $this->khoaModel->getById($maKhoa);
    }

    // Thêm một khoa mới
    public function addKhoa($data) {
        $this->khoaModel->MaKhoa = $data['MaKhoa'];
        $this->khoaModel->TenKhoa = $data['TenKhoa'];
        $this->khoaModel->NgayThanhLap = $data['NgayThanhLap'];
        $this->khoaModel->TruongKhoa = $data['TruongKhoa'];
        return $this->khoaModel->create();
    }

    // Cập nhật thông tin khoa
    public function updateKhoa($maKhoa, $data) {
        $this->khoaModel->MaKhoa = $maKhoa;
        $this->khoaModel->TenKhoa = $data['TenKhoa'];
        $this->khoaModel->NgayThanhLap = $data['NgayThanhLap'];
        $this->khoaModel->TruongKhoa = $data['TruongKhoa'];
        return $this->khoaModel->update();
    }

    // Xóa một khoa
    public function deleteKhoa($maKhoa) {
        $this->khoaModel->MaKhoa = $maKhoa;
        return $this->khoaModel->delete();
    }

    // Tìm kiếm khoa
    public function searchKhoa($criteria) {
        return $this->khoaModel->search($criteria);
    }
}

?>