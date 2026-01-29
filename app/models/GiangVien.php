<?php

require_once __DIR__ . '/../config/Database.php';

class GiangVien {
    private $conn;
    private $table_name = "GIANG_VIEN";

    public $MaGiangVien;
    public $HoTen;
    public $NgaySinh;
    public $GioiTinh;
    public $Email;
    public $SoDienThoai;
    public $HocVi;
    public $MaKhoa;
    public $TrangThai;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả giảng viên
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới giảng viên
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaGiangVien=:MaGiangVien, HoTen=:HoTen, NgaySinh=:NgaySinh, GioiTinh=:GioiTinh, Email=:Email, SoDienThoai=:SoDienThoai, HocVi=:HocVi, MaKhoa=:MaKhoa, TrangThai=:TrangThai";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaGiangVien = htmlspecialchars(strip_tags($this->MaGiangVien));
        $this->HoTen = htmlspecialchars(strip_tags($this->HoTen));
        $this->NgaySinh = htmlspecialchars(strip_tags($this->NgaySinh));
        $this->GioiTinh = htmlspecialchars(strip_tags($this->GioiTinh));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->SoDienThoai = htmlspecialchars(strip_tags($this->SoDienThoai));
        $this->HocVi = htmlspecialchars(strip_tags($this->HocVi));
        $this->MaKhoa = htmlspecialchars(strip_tags($this->MaKhoa));
        $this->TrangThai = htmlspecialchars(strip_tags($this->TrangThai));

        // bind values
        $stmt->bindParam(":MaGiangVien", $this->MaGiangVien);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":NgaySinh", $this->NgaySinh);
        $stmt->bindParam(":GioiTinh", $this->GioiTinh);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":HocVi", $this->HocVi);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);
        $stmt->bindParam(":TrangThai", $this->TrangThai);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin giảng viên
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET HoTen=:HoTen, NgaySinh=:NgaySinh, GioiTinh=:GioiTinh, Email=:Email, SoDienThoai=:SoDienThoai, HocVi=:HocVi, MaKhoa=:MaKhoa, TrangThai=:TrangThai WHERE MaGiangVien=:MaGiangVien";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaGiangVien = htmlspecialchars(strip_tags($this->MaGiangVien));
        $this->HoTen = htmlspecialchars(strip_tags($this->HoTen));
        $this->NgaySinh = htmlspecialchars(strip_tags($this->NgaySinh));
        $this->GioiTinh = htmlspecialchars(strip_tags($this->GioiTinh));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->SoDienThoai = htmlspecialchars(strip_tags($this->SoDienThoai));
        $this->HocVi = htmlspecialchars(strip_tags($this->HocVi));
        $this->MaKhoa = htmlspecialchars(strip_tags($this->MaKhoa));
        $this->TrangThai = htmlspecialchars(strip_tags($this->TrangThai));

        // bind values
        $stmt->bindParam(":MaGiangVien", $this->MaGiangVien);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":NgaySinh", $this->NgaySinh);
        $stmt->bindParam(":GioiTinh", $this->GioiTinh);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":HocVi", $this->HocVi);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);
        $stmt->bindParam(":TrangThai", $this->TrangThai);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy thông tin một giảng viên theo mã giảng viên
    public function getById($maGiangVien) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaGiangVien = :MaGiangVien";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaGiangVien", $maGiangVien);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa một giảng viên
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaGiangVien = :MaGiangVien";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaGiangVien", $this->MaGiangVien);
        return $stmt->execute();
    }

    // Tìm kiếm giảng viên theo tiêu chí
    public function search($criteria) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE HoTen LIKE :criteria OR Email LIKE :criteria";
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $criteria = "%" . htmlspecialchars(strip_tags($criteria)) . "%";
        $stmt->bindParam(":criteria", $criteria);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>