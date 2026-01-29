<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/LopHanhChinh.php';

class LopHanhChinhController {

    private $db;
    private $lopHanhChinhModel;

    public function __construct() {
        $this->db = new Database();
        $this->lopHanhChinhModel = new LopHanhChinh($this->db->getConnection());
    }

    // Lấy danh sách tất cả các lớp hành chính
    public function getAllLopHanhChinh() {
        return $this->lopHanhChinhModel->readAll();
    }

    // Lấy thông tin một lớp hành chính theo mã lớp
    public function getLopHanhChinhById($maLop) {
        return $this->lopHanhChinhModel->getById($maLop);
    }

    // Thêm một lớp hành chính mới
    public function addLopHanhChinh($data) {
        $this->lopHanhChinhModel->MaLop = $data['MaLop'];
        $this->lopHanhChinhModel->TenLop = $data['TenLop'];
        $this->lopHanhChinhModel->MaNganh = $data['MaNganh'];
        $this->lopHanhChinhModel->KhoaHoc = $data['KhoaHoc'];
        $this->lopHanhChinhModel->MaCoVan = $data['MaCoVan'];
        return $this->lopHanhChinhModel->create();
    }

    // Cập nhật thông tin lớp hành chính
    public function updateLopHanhChinh($maLop, $data) {
        $this->lopHanhChinhModel->MaLop = $maLop;
        $this->lopHanhChinhModel->TenLop = $data['TenLop'];
        $this->lopHanhChinhModel->MaNganh = $data['MaNganh'];
        $this->lopHanhChinhModel->KhoaHoc = $data['KhoaHoc'];
        $this->lopHanhChinhModel->MaCoVan = $data['MaCoVan'];
        return $this->lopHanhChinhModel->update();
    }

    // Xóa một lớp hành chính
    public function deleteLopHanhChinh($maLop) {
        $this->lopHanhChinhModel->MaLop = $maLop;
        return $this->lopHanhChinhModel->delete();
    }

    // Tìm kiếm lớp hành chính
    public function searchLopHanhChinh($criteria) {
        return $this->lopHanhChinhModel->search($criteria);
    }
}

?>