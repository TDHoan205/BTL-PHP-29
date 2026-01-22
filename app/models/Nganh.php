<?php

require_once "../config/Database.php";

class Nganh {
    private $conn;
    private $table_name = "NGANH";

    public $MaNganh;
    public $TenNganh;
    public $MaKhoa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các ngành
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới một ngành
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaNganh=:MaNganh, TenNganh=:TenNganh, MaKhoa=:MaKhoa";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaNganh = htmlspecialchars(strip_tags($this->MaNganh));
        $this->TenNganh = htmlspecialchars(strip_tags($this->TenNganh));
        $this->MaKhoa = htmlspecialchars(strip_tags($this->MaKhoa));

        // bind values
        $stmt->bindParam(":MaNganh", $this->MaNganh);
        $stmt->bindParam(":TenNganh", $this->TenNganh);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin ngành
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TenNganh=:TenNganh, MaKhoa=:MaKhoa WHERE MaNganh=:MaNganh";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaNganh = htmlspecialchars(strip_tags($this->MaNganh));
        $this->TenNganh = htmlspecialchars(strip_tags($this->TenNganh));
        $this->MaKhoa = htmlspecialchars(strip_tags($this->MaKhoa));

        // bind values
        $stmt->bindParam(":MaNganh", $this->MaNganh);
        $stmt->bindParam(":TenNganh", $this->TenNganh);
        $stmt->bindParam(":MaKhoa", $this->MaKhoa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy thông tin một ngành theo mã ngành
    public function getById($maNganh) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaNganh = :MaNganh";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNganh", $maNganh);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa một ngành
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaNganh = :MaNganh";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaNganh", $this->MaNganh);
        return $stmt->execute();
    }

    // Tìm kiếm ngành theo tiêu chí
    public function search($criteria) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenNganh LIKE :criteria OR MaKhoa LIKE :criteria";
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $criteria = "%" . htmlspecialchars(strip_tags($criteria)) . "%";
        $stmt->bindParam(":criteria", $criteria);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>