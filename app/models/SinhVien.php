<?php

require_once "../config/Database.php";

class SinhVien {
    private $conn;
    private $table_name = "SINH_VIEN";

    public $MaSinhVien;
    public $HoTen;
    public $NgaySinh;
    public $GioiTinh;
    public $DiaChi;
    public $Email;
    public $SoDienThoai;
    public $MaLop;
    public $TrangThaiHocTap;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả sinh viên
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới sinh viên
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaSinhVien=:MaSinhVien, HoTen=:HoTen, NgaySinh=:NgaySinh, GioiTinh=:GioiTinh, DiaChi=:DiaChi, Email=:Email, SoDienThoai=:SoDienThoai, MaLop=:MaLop, TrangThaiHocTap=:TrangThaiHocTap";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaSinhVien = htmlspecialchars(strip_tags($this->MaSinhVien));
        $this->HoTen = htmlspecialchars(strip_tags($this->HoTen));
        $this->NgaySinh = htmlspecialchars(strip_tags($this->NgaySinh));
        $this->GioiTinh = htmlspecialchars(strip_tags($this->GioiTinh));
        $this->DiaChi = htmlspecialchars(strip_tags($this->DiaChi));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->SoDienThoai = htmlspecialchars(strip_tags($this->SoDienThoai));
        $this->MaLop = htmlspecialchars(strip_tags($this->MaLop));
        $this->TrangThaiHocTap = htmlspecialchars(strip_tags($this->TrangThaiHocTap));

        // bind values
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":NgaySinh", $this->NgaySinh);
        $stmt->bindParam(":GioiTinh", $this->GioiTinh);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":MaLop", $this->MaLop);
        $stmt->bindParam(":TrangThaiHocTap", $this->TrangThaiHocTap);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin sinh viên
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET HoTen=:HoTen, NgaySinh=:NgaySinh, GioiTinh=:GioiTinh, DiaChi=:DiaChi, Email=:Email, SoDienThoai=:SoDienThoai, MaLop=:MaLop, TrangThaiHocTap=:TrangThaiHocTap WHERE MaSinhVien=:MaSinhVien";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaSinhVien = htmlspecialchars(strip_tags($this->MaSinhVien));
        $this->HoTen = htmlspecialchars(strip_tags($this->HoTen));
        $this->NgaySinh = htmlspecialchars(strip_tags($this->NgaySinh));
        $this->GioiTinh = htmlspecialchars(strip_tags($this->GioiTinh));
        $this->DiaChi = htmlspecialchars(strip_tags($this->DiaChi));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->SoDienThoai = htmlspecialchars(strip_tags($this->SoDienThoai));
        $this->MaLop = htmlspecialchars(strip_tags($this->MaLop));
        $this->TrangThaiHocTap = htmlspecialchars(strip_tags($this->TrangThaiHocTap));

        // bind values
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":NgaySinh", $this->NgaySinh);
        $stmt->bindParam(":GioiTinh", $this->GioiTinh);
        $stmt->bindParam(":DiaChi", $this->DiaChi);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":MaLop", $this->MaLop);
        $stmt->bindParam(":TrangThaiHocTap", $this->TrangThaiHocTap);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy thông tin một sinh viên theo mã sinh viên
    public function getById($maSinhVien) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaSinhVien = :MaSinhVien";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaSinhVien", $maSinhVien);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa một sinh viên
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaSinhVien = :MaSinhVien";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaSinhVien", $this->MaSinhVien);
        return $stmt->execute();
    }
}

?>