<?php

require_once "../config/Database.php";

class LopHanhChinh {
    private $conn;
    private $table_name = "LOP_HANH_CHINH";

    public $MaLop;
    public $TenLop;
    public $MaNganh;
    public $KhoaHoc;
    public $MaCoVan;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các lớp hành chính
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới lớp hành chính
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaLop=:MaLop, TenLop=:TenLop, MaNganh=:MaNganh, KhoaHoc=:KhoaHoc, MaCoVan=:MaCoVan";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaLop = htmlspecialchars(strip_tags($this->MaLop));
        $this->TenLop = htmlspecialchars(strip_tags($this->TenLop));
        $this->MaNganh = htmlspecialchars(strip_tags($this->MaNganh));
        $this->KhoaHoc = htmlspecialchars(strip_tags($this->KhoaHoc));
        $this->MaCoVan = htmlspecialchars(strip_tags($this->MaCoVan));

        // bind values
        $stmt->bindParam(":MaLop", $this->MaLop);
        $stmt->bindParam(":TenLop", $this->TenLop);
        $stmt->bindParam(":MaNganh", $this->MaNganh);
        $stmt->bindParam(":KhoaHoc", $this->KhoaHoc);
        $stmt->bindParam(":MaCoVan", $this->MaCoVan);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin lớp hành chính
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TenLop=:TenLop, MaNganh=:MaNganh, KhoaHoc=:KhoaHoc, MaCoVan=:MaCoVan WHERE MaLop=:MaLop";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaLop = htmlspecialchars(strip_tags($this->MaLop));
        $this->TenLop = htmlspecialchars(strip_tags($this->TenLop));
        $this->MaNganh = htmlspecialchars(strip_tags($this->MaNganh));
        $this->KhoaHoc = htmlspecialchars(strip_tags($this->KhoaHoc));
        $this->MaCoVan = htmlspecialchars(strip_tags($this->MaCoVan));

        // bind values
        $stmt->bindParam(":MaLop", $this->MaLop);
        $stmt->bindParam(":TenLop", $this->TenLop);
        $stmt->bindParam(":MaNganh", $this->MaNganh);
        $stmt->bindParam(":KhoaHoc", $this->KhoaHoc);
        $stmt->bindParam(":MaCoVan", $this->MaCoVan);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy thông tin một lớp hành chính theo mã lớp
    public function getById($maLop) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaLop = :MaLop";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaLop", $maLop);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa một lớp hành chính
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaLop = :MaLop";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaLop", $this->MaLop);
        return $stmt->execute();
    }

    // Tìm kiếm lớp hành chính theo tiêu chí
    public function search($criteria) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenLop LIKE :criteria OR MaNganh LIKE :criteria";
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $criteria = "%" . htmlspecialchars(strip_tags($criteria)) . "%";
        $stmt->bindParam(":criteria", $criteria);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>