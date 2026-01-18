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

    // Xóa ngành
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaNganh = :MaNganh";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaNganh = htmlspecialchars(strip_tags($this->MaNganh));

        // bind id
        $stmt->bindParam(":MaNganh", $this->MaNganh);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>