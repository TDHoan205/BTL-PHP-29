<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/LopHocPhan.php';

class LopHocPhanController {

    private $db;
    private $lopHocPhanModel;

    public function __construct() {
        $this->db = new Database();
        $this->lopHocPhanModel = new LopHocPhan($this->db->getConnection());
    }

    // Lấy danh sách tất cả các lớp học phần
    public function getAllLopHocPhan() {
        return $this->lopHocPhanModel->readAll();
    }

    // Lấy thông tin một lớp học phần theo mã lớp học phần
    public function getLopHocPhanById($maLopHocPhan) {
        return $this->lopHocPhanModel->getById($maLopHocPhan);
    }

    // Thêm một lớp học phần mới
    public function addLopHocPhan($data) {
        $this->lopHocPhanModel->MaLopHocPhan = $data['MaLopHocPhan'];
        $this->lopHocPhanModel->TenLop = $data['TenLop'];
        $this->lopHocPhanModel->MaMonHoc = $data['MaMonHoc'];
        $this->lopHocPhanModel->MaGiangVien = $data['MaGiangVien'];
        return $this->lopHocPhanModel->create();
    }

    // Cập nhật thông tin lớp học phần
    public function updateLopHocPhan($maLopHocPhan, $data) {
        $this->lopHocPhanModel->MaLopHocPhan = $maLopHocPhan;
        $this->lopHocPhanModel->TenLop = $data['TenLop'];
        $this->lopHocPhanModel->MaMonHoc = $data['MaMonHoc'];
        $this->lopHocPhanModel->MaGiangVien = $data['MaGiangVien'];
        return $this->lopHocPhanModel->update();
    }

    // Xóa một lớp học phần
    public function deleteLopHocPhan($maLopHocPhan) {
        $this->lopHocPhanModel->MaLopHocPhan = $maLopHocPhan;
        return $this->lopHocPhanModel->delete();
    }

    // Tìm kiếm lớp học phần
    public function searchLopHocPhan($criteria) {
        return $this->lopHocPhanModel->search($criteria);
    }
}

?>