<?php

require_once "../config/Database.php";

class LopHocPhan {
    private $conn;
    private $table_name = "LOP_HOC_PHAN";

    public $MaLopHocPhan;
    public $TenLop;
    public $MaMonHoc;
    public $MaGiangVien;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các lớp học phần
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới lớp học phần
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaLopHocPhan=:MaLopHocPhan, MaMonHoc=:MaMonHoc, MaGiangVien=:MaGiangVien, TenLop=:TenLop";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaLopHocPhan = htmlspecialchars(strip_tags($this->MaLopHocPhan));
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));
        $this->MaGiangVien = htmlspecialchars(strip_tags($this->MaGiangVien));
        $this->TenLop = htmlspecialchars(strip_tags($this->TenLop));

        // bind values
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);
        $stmt->bindParam(":MaGiangVien", $this->MaGiangVien);
        $stmt->bindParam(":TenLop", $this->TenLop);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin lớp học phần
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET MaMonHoc=:MaMonHoc, MaGiangVien=:MaGiangVien, TenLop=:TenLop WHERE MaLopHocPhan=:MaLopHocPhan";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaLopHocPhan = htmlspecialchars(strip_tags($this->MaLopHocPhan));
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));
        $this->MaGiangVien = htmlspecialchars(strip_tags($this->MaGiangVien));
        $this->TenLop = htmlspecialchars(strip_tags($this->TenLop));

        // bind values
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);
        $stmt->bindParam(":MaGiangVien", $this->MaGiangVien);
        $stmt->bindParam(":TenLop", $this->TenLop);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy thông tin một lớp học phần theo mã lớp học phần
    public function getById($maLopHocPhan) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaLopHocPhan = :MaLopHocPhan";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaLopHocPhan", $maLopHocPhan);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa một lớp học phần
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaLopHocPhan = :MaLopHocPhan";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        return $stmt->execute();
    }

    // Tìm kiếm lớp học phần theo tiêu chí
    public function search($criteria) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenLop LIKE :criteria OR MaMonHoc LIKE :criteria";
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $criteria = "%" . htmlspecialchars(strip_tags($criteria)) . "%";
        $stmt->bindParam(":criteria", $criteria);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>