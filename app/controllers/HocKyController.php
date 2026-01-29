<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/HocKy.php';

class HocKyController {

    private $db;
    private $hocKyModel;

    public function __construct() {
        $this->db = new Database();
        $this->hocKyModel = new HocKy($this->db->getConnection());
    }

    // Lấy danh sách tất cả các học kỳ
    public function getAllHocKy() {
        return $this->hocKyModel->readAll();
    }

    // Lấy thông tin một học kỳ theo mã
    public function getHocKyById($maHocKy) {
        return $this->hocKyModel->getById($maHocKy);
    }

    // Thêm một học kỳ mới
    public function addHocKy($data) {
        $this->hocKyModel->MaHocKy = $data['MaHocKy'];
        $this->hocKyModel->TenHocKy = $data['TenHocKy'];
        return $this->hocKyModel->create();
    }

    // Cập nhật thông tin học kỳ
    public function updateHocKy($maHocKy, $data) {
        $this->hocKyModel->MaHocKy = $maHocKy;
        $this->hocKyModel->TenHocKy = $data['TenHocKy'];
        return $this->hocKyModel->update();
    }

    // Xóa một học kỳ
    public function deleteHocKy($maHocKy) {
        $this->hocKyModel->MaHocKy = $maHocKy;
        return $this->hocKyModel->delete();
    }

    // Tìm kiếm học kỳ
    public function searchHocKy($criteria) {
        return $this->hocKyModel->search($criteria);
    }
}

?>