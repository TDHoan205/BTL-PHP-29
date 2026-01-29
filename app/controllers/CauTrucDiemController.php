<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/CauTrucDiem.php';

class CauTrucDiemController {

    private $db;
    private $cauTrucDiemModel;

    public function __construct() {
        $this->db = new Database();
        $this->cauTrucDiemModel = new CauTrucDiem($this->db->getConnection());
    }

    // Lấy danh sách tất cả các cấu trúc điểm
    public function getAllCauTrucDiem() {
        return $this->cauTrucDiemModel->readAll();
    }

    // Lấy thông tin một cấu trúc điểm theo mã
    public function getCauTrucDiemById($maCauTruc) {
        return $this->cauTrucDiemModel->getById($maCauTruc);
    }

    // Thêm một cấu trúc điểm mới
    public function addCauTrucDiem($data) {
        $this->cauTrucDiemModel->MaCauTruc = $data['MaCauTruc'];
        $this->cauTrucDiemModel->TenCauTruc = $data['TenCauTruc'];
        $this->cauTrucDiemModel->HeSo = $data['HeSo'];
        return $this->cauTrucDiemModel->create();
    }

    // Cập nhật thông tin cấu trúc điểm
    public function updateCauTrucDiem($maCauTruc, $data) {
        $this->cauTrucDiemModel->MaCauTruc = $maCauTruc;
        $this->cauTrucDiemModel->TenCauTruc = $data['TenCauTruc'];
        $this->cauTrucDiemModel->HeSo = $data['HeSo'];
        return $this->cauTrucDiemModel->update();
    }

    // Xóa một cấu trúc điểm
    public function deleteCauTrucDiem($maCauTruc) {
        $this->cauTrucDiemModel->MaCauTruc = $maCauTruc;
        return $this->cauTrucDiemModel->delete();
    }

    // Tìm kiếm cấu trúc điểm
    public function searchCauTrucDiem($criteria) {
        return $this->cauTrucDiemModel->search($criteria);
    }
}
?>