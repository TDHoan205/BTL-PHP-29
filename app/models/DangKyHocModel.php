<?php

require_once __DIR__ . '/../config/Database.php';

class DangKyHocModel {
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
        $query = "INSERT INTO " . $this->table_name . " SET MaDangKy=:MaDangKy, MaSinhVien=:MaSinhVien, MaLopHocPhan=:MaLopHocPhan, NgayDangKy=:NgayDangKy, DiemTongKet=:DiemTongKet, DiemChu=:DiemChu, DiemSo=:DiemSo, KetQua=:KetQua";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaDangKy = htmlspecialchars(strip_tags($this->MaDangKy));
        $this->MaSinhVien = htmlspecialchars(strip_tags($this->MaSinhVien));
        $this->MaLopHocPhan = htmlspecialchars(strip_tags($this->MaLopHocPhan));
        $this->NgayDangKy = htmlspecialchars(strip_tags($this->NgayDangKy));
        $this->DiemTongKet = htmlspecialchars(strip_tags($this->DiemTongKet));
        $this->DiemChu = htmlspecialchars(strip_tags($this->DiemChu));
        $this->DiemSo = htmlspecialchars(strip_tags($this->DiemSo));
        $this->KetQua = htmlspecialchars(strip_tags($this->KetQua));

        // bind values
        $stmt->bindParam(":MaDangKy", $this->MaDangKy);
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        $stmt->bindParam(":NgayDangKy", $this->NgayDangKy);
        $stmt->bindParam(":DiemTongKet", $this->DiemTongKet);
        $stmt->bindParam(":DiemChu", $this->DiemChu);
        $stmt->bindParam(":DiemSo", $this->DiemSo);
        $stmt->bindParam(":KetQua", $this->KetQua);

        return $stmt->execute();
    }

    // Cập nhật đăng ký học
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET MaSinhVien=:MaSinhVien, MaLopHocPhan=:MaLopHocPhan, NgayDangKy=:NgayDangKy, DiemTongKet=:DiemTongKet, DiemChu=:DiemChu, DiemSo=:DiemSo, KetQua=:KetQua WHERE MaDangKy=:MaDangKy";
        $stmt = $this->conn->prepare($query);
        $this->MaDangKy = htmlspecialchars(strip_tags($this->MaDangKy));
        $this->MaSinhVien = htmlspecialchars(strip_tags($this->MaSinhVien));
        $this->MaLopHocPhan = htmlspecialchars(strip_tags($this->MaLopHocPhan));
        $this->NgayDangKy = htmlspecialchars(strip_tags($this->NgayDangKy));
        $this->DiemTongKet = htmlspecialchars(strip_tags($this->DiemTongKet));
        $this->DiemChu = htmlspecialchars(strip_tags($this->DiemChu));
        $this->DiemSo = htmlspecialchars(strip_tags($this->DiemSo));
        $this->KetQua = htmlspecialchars(strip_tags($this->KetQua));
        $stmt->bindParam(":MaDangKy", $this->MaDangKy);
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        $stmt->bindParam(":NgayDangKy", $this->NgayDangKy);
        $stmt->bindParam(":DiemTongKet", $this->DiemTongKet);
        $stmt->bindParam(":DiemChu", $this->DiemChu);
        $stmt->bindParam(":DiemSo", $this->DiemSo);
        $stmt->bindParam(":KetQua", $this->KetQua);
        return $stmt->execute();
    }

    // Xóa đăng ký học
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaDangKy = :MaDangKy";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaDangKy", $this->MaDangKy);
        return $stmt->execute();
    }
}