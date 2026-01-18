<?php

require_once "../config/Database.php";

class LopHocPhan {
    private $conn;
    private $table_name = "LOP_HOC_PHAN";

    public $MaLopHocPhan;
    public $MaMonHoc;
    public $MaHocKy;
    public $MaGiangVien;
    public $PhongHoc;
    public $SoLuongToiDa;
    public $TrangThai;

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
        $query = "INSERT INTO " . $this->table_name . " SET MaLopHocPhan=:MaLopHocPhan, MaMonHoc=:MaMonHoc, MaHocKy=:MaHocKy, MaGiangVien=:MaGiangVien, PhongHoc=:PhongHoc, SoLuongToiDa=:SoLuongToiDa, TrangThai=:TrangThai";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaLopHocPhan = htmlspecialchars(strip_tags($this->MaLopHocPhan));
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));
        $this->MaHocKy = htmlspecialchars(strip_tags($this->MaHocKy));
        $this->MaGiangVien = htmlspecialchars(strip_tags($this->MaGiangVien));
        $this->PhongHoc = htmlspecialchars(strip_tags($this->PhongHoc));
        $this->SoLuongToiDa = htmlspecialchars(strip_tags($this->SoLuongToiDa));
        $this->TrangThai = htmlspecialchars(strip_tags($this->TrangThai));

        // bind values
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);
        $stmt->bindParam(":MaHocKy", $this->MaHocKy);
        $stmt->bindParam(":MaGiangVien", $this->MaGiangVien);
        $stmt->bindParam(":PhongHoc", $this->PhongHoc);
        $stmt->bindParam(":SoLuongToiDa", $this->SoLuongToiDa);
        $stmt->bindParam(":TrangThai", $this->TrangThai);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin lớp học phần
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET MaMonHoc=:MaMonHoc, MaHocKy=:MaHocKy, MaGiangVien=:MaGiangVien, PhongHoc=:PhongHoc, SoLuongToiDa=:SoLuongToiDa, TrangThai=:TrangThai WHERE MaLopHocPhan=:MaLopHocPhan";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaLopHocPhan = htmlspecialchars(strip_tags($this->MaLopHocPhan));
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));
        $this->MaHocKy = htmlspecialchars(strip_tags($this->MaHocKy));
        $this->MaGiangVien = htmlspecialchars(strip_tags($this->MaGiangVien));
        $this->PhongHoc = htmlspecialchars(strip_tags($this->PhongHoc));
        $this->SoLuongToiDa = htmlspecialchars(strip_tags($this->SoLuongToiDa));
        $this->TrangThai = htmlspecialchars(strip_tags($this->TrangThai));

        // bind values
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);
        $stmt->bindParam(":MaHocKy", $this->MaHocKy);
        $stmt->bindParam(":MaGiangVien", $this->MaGiangVien);
        $stmt->bindParam(":PhongHoc", $this->PhongHoc);
        $stmt->bindParam(":SoLuongToiDa", $this->SoLuongToiDa);
        $stmt->bindParam(":TrangThai", $this->TrangThai);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa lớp học phần
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaLopHocPhan = :MaLopHocPhan";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaLopHocPhan = htmlspecialchars(strip_tags($this->MaLopHocPhan));

        // bind id
        $stmt->bindParam(":MaLopHocPhan", $this->MaLopHocPhan);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>