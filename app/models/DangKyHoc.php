<?php

require_once "../config/Database.php";


class DangKyHoc {
    private $conn;
    private $table_name = "DANG_KY_HOC";

    public $MaDangKy;
    public $MaSinhVien;
    public $MaLopHocPhan;
    public $NgayDangKy;
    public $DiemTongKet;
    public $DiemChu;
    public $DiemSo;
    public $KetQua;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các đăng ký học
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một đăng ký học theo mã
    public function getById($maDangKy) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaDangKy = :MaDangKy";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaDangKy", $maDangKy);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới đăng ký học
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (MaDangKy, MaSinhVien, MaLopHocPhan) VALUES (:MaDangKy, :MaSinhVien, :MaLopHocPhan)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaDangKy", $this->MaDangKy);
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        return $stmt->execute();
    }

    // Cập nhật thông tin đăng ký học
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET MaSinhVien = :MaSinhVien, MaLopHocPhan = :MaLopHocPhan WHERE MaDangKy = :MaDangKy";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaDangKy", $this->MaDangKy);
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        return $stmt->execute();
    }

    // Xóa đăng ký học
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaDangKy = :MaDangKy";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaDangKy", $this->MaDangKy);
        return $stmt->execute();
    }

    // Tìm kiếm đăng ký học theo tiêu chí
    public function search($criteria) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaSinhVien LIKE :criteria OR MaLopHocPhan LIKE :criteria";
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $criteria = "%" . htmlspecialchars(strip_tags($criteria)) . "%";
        $stmt->bindParam(":criteria", $criteria);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>