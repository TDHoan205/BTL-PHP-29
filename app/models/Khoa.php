<?php

require_once "../config/Database.php";

class Khoa {
    private $conn;
    private $table_name = "KHOA";

    public $MaKhoa;
    public $TenKhoa;
    public $NgayThanhLap;
    public $TruongKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các khoa
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới một khoa
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaKhoa=:MaKhoa, TenKhoa=:TenKhoa, NgayThanhLap=:NgayThanhLap, TruongKhoa=:TruongKhoa";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaKhoa = htmlspecialchars(strip_tags($this->MaKhoa));
        $this->TenKhoa = htmlspecialchars(strip_tags($this->TenKhoa));
        $this->NgayThanhLap = htmlspecialchars(strip_tags($this->NgayThanhLap));
        $this->TruongKhoa = htmlspecialchars(strip_tags($this->TruongKhoa));

        // bind values
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);
        $stmt->bindParam(":TenKhoa", $this->TenKhoa);
        $stmt->bindParam(":NgayThanhLap", $this->NgayThanhLap);
        $stmt->bindParam(":TruongKhoa", $this->TruongKhoa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin khoa
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TenKhoa=:TenKhoa, NgayThanhLap=:NgayThanhLap, TruongKhoa=:TruongKhoa WHERE MaKhoa=:MaKhoa";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaKhoa = htmlspecialchars(strip_tags($this->MaKhoa));
        $this->TenKhoa = htmlspecialchars(strip_tags($this->TenKhoa));
        $this->NgayThanhLap = htmlspecialchars(strip_tags($this->NgayThanhLap));
        $this->TruongKhoa = htmlspecialchars(strip_tags($this->TruongKhoa));

        // bind values
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);
        $stmt->bindParam(":TenKhoa", $this->TenKhoa);
        $stmt->bindParam(":NgayThanhLap", $this->NgayThanhLap);
        $stmt->bindParam(":TruongKhoa", $this->TruongKhoa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy thông tin một khoa theo mã khoa
    public function getById($maKhoa) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaKhoa = :MaKhoa";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaKhoa", $maKhoa);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa một khoa
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaKhoa = :MaKhoa";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);
        return $stmt->execute();
    }

    // Tìm kiếm khoa theo tiêu chí
    public function search($criteria) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenKhoa LIKE :criteria OR TruongKhoa LIKE :criteria";
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $criteria = "%" . htmlspecialchars(strip_tags($criteria)) . "%";
        $stmt->bindParam(":criteria", $criteria);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>