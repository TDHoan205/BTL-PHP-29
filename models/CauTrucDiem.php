<?php

require_once "../config/Database.php";

class CauTrucDiem {
    private $conn;
    private $table_name = "CAU_TRUC_DIEM";

    public $ID;
    public $MaMonHoc;
    public $MaLoaiDiem;
    public $HeSo;
    public $MoTa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả cấu trúc điểm
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới cấu trúc điểm
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaMonHoc=:MaMonHoc, MaLoaiDiem=:MaLoaiDiem, HeSo=:HeSo, MoTa=:MoTa";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));
        $this->MaLoaiDiem = htmlspecialchars(strip_tags($this->MaLoaiDiem));
        $this->HeSo = htmlspecialchars(strip_tags($this->HeSo));
        $this->MoTa = htmlspecialchars(strip_tags($this->MoTa));

        // bind values
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);
        $stmt->bindParam(":MaLoaiDiem", $this->MaLoaiDiem);
        $stmt->bindParam(":HeSo", $this->HeSo);
        $stmt->bindParam(":MoTa", $this->MoTa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật cấu trúc điểm
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET MaMonHoc=:MaMonHoc, MaLoaiDiem=:MaLoaiDiem, HeSo=:HeSo, MoTa=:MoTa WHERE ID=:ID";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->ID = htmlspecialchars(strip_tags($this->ID));
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));
        $this->MaLoaiDiem = htmlspecialchars(strip_tags($this->MaLoaiDiem));
        $this->HeSo = htmlspecialchars(strip_tags($this->HeSo));
        $this->MoTa = htmlspecialchars(strip_tags($this->MoTa));

        // bind values
        $stmt->bindParam(":ID", $this->ID);
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);
        $stmt->bindParam(":MaLoaiDiem", $this->MaLoaiDiem);
        $stmt->bindParam(":HeSo", $this->HeSo);
        $stmt->bindParam(":MoTa", $this->MoTa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa cấu trúc điểm
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID = :ID";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->ID = htmlspecialchars(strip_tags($this->ID));

        // bind id
        $stmt->bindParam(":ID", $this->ID);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>