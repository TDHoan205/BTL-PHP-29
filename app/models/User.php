<?php

require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    private $table_name = "USER";

    public $MaUser;
    public $TenDangNhap;
    public $MatKhau;
    public $HoTen;
    public $Email;
    public $SoDienThoai;
    public $VaiTro;
    public $TrangThai;
    public $NgayTao;
    public $NgayCapNhat;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả người dùng
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một người dùng theo ID
    public function getById($userId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaUser = :MaUser";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaUser", $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới người dùng
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET TenDangNhap=:TenDangNhap, MatKhau=:MatKhau, HoTen=:HoTen, Email=:Email, SoDienThoai=:SoDienThoai, VaiTro=:VaiTro, TrangThai=:TrangThai, NgayTao=:NgayTao, NgayCapNhat=:NgayCapNhat";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->TenDangNhap = htmlspecialchars(strip_tags($this->TenDangNhap));
        $this->MatKhau = htmlspecialchars(strip_tags($this->MatKhau));
        $this->HoTen = htmlspecialchars(strip_tags($this->HoTen));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->SoDienThoai = htmlspecialchars(strip_tags($this->SoDienThoai));
        $this->VaiTro = htmlspecialchars(strip_tags($this->VaiTro));
        $this->TrangThai = htmlspecialchars(strip_tags($this->TrangThai));
        $this->NgayTao = htmlspecialchars(strip_tags($this->NgayTao));
        $this->NgayCapNhat = htmlspecialchars(strip_tags($this->NgayCapNhat));

        // bind values
        $stmt->bindParam(":TenDangNhap", $this->TenDangNhap);
        $stmt->bindParam(":MatKhau", $this->MatKhau);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":VaiTro", $this->VaiTro);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":NgayTao", $this->NgayTao);
        $stmt->bindParam(":NgayCapNhat", $this->NgayCapNhat);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin người dùng
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TenDangNhap=:TenDangNhap, MatKhau=:MatKhau, HoTen=:HoTen, Email=:Email, SoDienThoai=:SoDienThoai, VaiTro=:VaiTro, TrangThai=:TrangThai, NgayCapNhat=:NgayCapNhat WHERE MaUser=:MaUser";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaUser = htmlspecialchars(strip_tags($this->MaUser));
        $this->TenDangNhap = htmlspecialchars(strip_tags($this->TenDangNhap));
        $this->MatKhau = htmlspecialchars(strip_tags($this->MatKhau));
        $this->HoTen = htmlspecialchars(strip_tags($this->HoTen));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->SoDienThoai = htmlspecialchars(strip_tags($this->SoDienThoai));
        $this->VaiTro = htmlspecialchars(strip_tags($this->VaiTro));
        $this->TrangThai = htmlspecialchars(strip_tags($this->TrangThai));
        $this->NgayCapNhat = htmlspecialchars(strip_tags($this->NgayCapNhat));

        // bind values
        $stmt->bindParam(":MaUser", $this->MaUser);
        $stmt->bindParam(":TenDangNhap", $this->TenDangNhap);
        $stmt->bindParam(":MatKhau", $this->MatKhau);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":SoDienThoai", $this->SoDienThoai);
        $stmt->bindParam(":VaiTro", $this->VaiTro);
        $stmt->bindParam(":TrangThai", $this->TrangThai);
        $stmt->bindParam(":NgayCapNhat", $this->NgayCapNhat);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa người dùng
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaUser = :MaUser";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaUser = htmlspecialchars(strip_tags($this->MaUser));

        // bind id
        $stmt->bindParam(":MaUser", $this->MaUser);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>