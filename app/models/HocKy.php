<?php

require_once __DIR__ . '/../config/Database.php';

class HocKy {
    private $conn;
    private $table_name = "HOC_KY";

    public $MaHocKy;
    public $TenHocKy;
    public $NamHoc;
    public $NgayBatDau;
    public $NgayKetThuc;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các học kỳ
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một học kỳ theo mã
    public function getById($maHocKy) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaHocKy = :MaHocKy";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaHocKy", $maHocKy);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới học kỳ
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaHocKy=:MaHocKy, TenHocKy=:TenHocKy, NamHoc=:NamHoc, NgayBatDau=:NgayBatDau, NgayKetThuc=:NgayKetThuc";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaHocKy = htmlspecialchars(strip_tags($this->MaHocKy));
        $this->TenHocKy = htmlspecialchars(strip_tags($this->TenHocKy));
        $this->NamHoc = htmlspecialchars(strip_tags($this->NamHoc));
        $this->NgayBatDau = htmlspecialchars(strip_tags($this->NgayBatDau));
        $this->NgayKetThuc = htmlspecialchars(strip_tags($this->NgayKetThuc));

        // bind values
        $stmt->bindParam(":MaHocKy", $this->MaHocKy);
        $stmt->bindParam(":TenHocKy", $this->TenHocKy);
        $stmt->bindParam(":NamHoc", $this->NamHoc);
        $stmt->bindParam(":NgayBatDau", $this->NgayBatDau);
        $stmt->bindParam(":NgayKetThuc", $this->NgayKetThuc);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin học kỳ
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TenHocKy=:TenHocKy, NamHoc=:NamHoc, NgayBatDau=:NgayBatDau, NgayKetThuc=:NgayKetThuc WHERE MaHocKy=:MaHocKy";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaHocKy = htmlspecialchars(strip_tags($this->MaHocKy));
        $this->TenHocKy = htmlspecialchars(strip_tags($this->TenHocKy));
        $this->NamHoc = htmlspecialchars(strip_tags($this->NamHoc));
        $this->NgayBatDau = htmlspecialchars(strip_tags($this->NgayBatDau));
        $this->NgayKetThuc = htmlspecialchars(strip_tags($this->NgayKetThuc));

        // bind values
        $stmt->bindParam(":MaHocKy", $this->MaHocKy);
        $stmt->bindParam(":TenHocKy", $this->TenHocKy);
        $stmt->bindParam(":NamHoc", $this->NamHoc);
        $stmt->bindParam(":NgayBatDau", $this->NgayBatDau);
        $stmt->bindParam(":NgayKetThuc", $this->NgayKetThuc);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa học kỳ
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaHocKy = :MaHocKy";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaHocKy = htmlspecialchars(strip_tags($this->MaHocKy));

        // bind id
        $stmt->bindParam(":MaHocKy", $this->MaHocKy);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tìm kiếm học kỳ theo tiêu chí
    public function search($criteria) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenHocKy LIKE :criteria OR NamHoc LIKE :criteria";
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $criteria = "%" . htmlspecialchars(strip_tags($criteria)) . "%";
        $stmt->bindParam(":criteria", $criteria);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>