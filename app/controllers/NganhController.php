<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Nganh.php';

class NganhController {

    private $db;
    private $nganhModel;

    public function __construct() {
        $this->db = new Database();
        $this->nganhModel = new Nganh($this->db->getConnection());
    }

    // Lấy danh sách tất cả các ngành
    public function getAllNganh() {
        return $this->nganhModel->readAll();
    }

    // Lấy thông tin một ngành theo mã ngành
    public function getNganhById($maNganh) {
        return $this->nganhModel->getById($maNganh);
    }

    // Thêm một ngành mới
    public function addNganh($data) {
        $this->nganhModel->MaNganh = $data['MaNganh'];
        $this->nganhModel->TenNganh = $data['TenNganh'];
        $this->nganhModel->MaKhoa = $data['MaKhoa'];
        return $this->nganhModel->create();
    }

    // Cập nhật thông tin ngành
    public function updateNganh($maNganh, $data) {
        $this->nganhModel->MaNganh = $maNganh;
        $this->nganhModel->TenNganh = $data['TenNganh'];
        $this->nganhModel->MaKhoa = $data['MaKhoa'];
        return $this->nganhModel->update();
    }

    // Xóa một ngành
    public function deleteNganh($maNganh) {
        $this->nganhModel->MaNganh = $maNganh;
        return $this->nganhModel->delete();
    }

    // Tìm kiếm ngành
    public function searchNganh($criteria) {
        return $this->nganhModel->search($criteria);
    }
}

?>