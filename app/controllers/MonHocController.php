<?php

require_once '../config/Database.php';
require_once '../models/MonHoc.php';

class MonHocController {

    private $db;
    private $monHocModel;

    public function __construct() {
        $this->db = new Database();
        $this->monHocModel = new MonHoc($this->db->getConnection());
    }

    // Lấy danh sách tất cả các môn học
    public function getAllMonHoc() {
        return $this->monHocModel->readAll();
    }

    // Lấy thông tin một môn học theo mã môn học
    public function getMonHocById($maMonHoc) {
        return $this->monHocModel->getById($maMonHoc);
    }

    // Thêm một môn học mới
    public function addMonHoc($data) {
        $this->monHocModel->MaMonHoc = $data['MaMonHoc'];
        $this->monHocModel->TenMonHoc = $data['TenMonHoc'];
        $this->monHocModel->SoTinChi = $data['SoTinChi'];
        return $this->monHocModel->create();
    }

    // Cập nhật thông tin môn học
    public function updateMonHoc($maMonHoc, $data) {
        $this->monHocModel->MaMonHoc = $maMonHoc;
        $this->monHocModel->TenMonHoc = $data['TenMonHoc'];
        $this->monHocModel->SoTinChi = $data['SoTinChi'];
        return $this->monHocModel->update();
    }

    // Xóa một môn học
    public function deleteMonHoc($maMonHoc) {
        $this->monHocModel->MaMonHoc = $maMonHoc;
        return $this->monHocModel->delete();
    }

    // Tìm kiếm môn học
    public function searchMonHoc($criteria) {
        return $this->monHocModel->search($criteria);
    }
}

?>