<?php

require_once "../config/Database.php";

class ChiTietDiem {
    private $conn;
    private $table_name = "CHI_TIET_DIEM";

    public $MaChiTiet;
    public $MaDangKy;
    public $MaLoaiDiem;
    public $SoDiem;
    public $NgayNhap;
    public $NguoiNhap;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả chi tiết điểm
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo mới chi tiết điểm
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaDangKy=:MaDangKy, MaLoaiDiem=:MaLoaiDiem, SoDiem=:SoDiem, NgayNhap=:NgayNhap, NguoiNhap=:NguoiNhap";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaDangKy = htmlspecialchars(strip_tags($this->MaDangKy));
        $this->MaLoaiDiem = htmlspecialchars(strip_tags($this->MaLoaiDiem));
        $this->SoDiem = htmlspecialchars(strip_tags($this->SoDiem));
        $this->NgayNhap = htmlspecialchars(strip_tags($this->NgayNhap));
        $this->NguoiNhap = htmlspecialchars(strip_tags($this->NguoiNhap));

        // bind values
        $stmt->bindParam(":MaDangKy", $this->MaDangKy);
        $stmt->bindParam(":MaLoaiDiem", $this->MaLoaiDiem);
        $stmt->bindParam(":SoDiem", $this->SoDiem);
        $stmt->bindParam(":NgayNhap", $this->NgayNhap);
        $stmt->bindParam(":NguoiNhap", $this->NguoiNhap);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật chi tiết điểm
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET MaDangKy=:MaDangKy, MaLoaiDiem=:MaLoaiDiem, SoDiem=:SoDiem, NgayNhap=:NgayNhap, NguoiNhap=:NguoiNhap WHERE MaChiTiet=:MaChiTiet";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaChiTiet = htmlspecialchars(strip_tags($this->MaChiTiet));
        $this->MaDangKy = htmlspecialchars(strip_tags($this->MaDangKy));
        $this->MaLoaiDiem = htmlspecialchars(strip_tags($this->MaLoaiDiem));
        $this->SoDiem = htmlspecialchars(strip_tags($this->SoDiem));
        $this->NgayNhap = htmlspecialchars(strip_tags($this->NgayNhap));
        $this->NguoiNhap = htmlspecialchars(strip_tags($this->NguoiNhap));

        // bind values
        $stmt->bindParam(":MaChiTiet", $this->MaChiTiet);
        $stmt->bindParam(":MaDangKy", $this->MaDangKy);
        $stmt->bindParam(":MaLoaiDiem", $this->MaLoaiDiem);
        $stmt->bindParam(":SoDiem", $this->SoDiem);
        $stmt->bindParam(":NgayNhap", $this->NgayNhap);
        $stmt->bindParam(":NguoiNhap", $this->NguoiNhap);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa chi tiết điểm
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaChiTiet = :MaChiTiet";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaChiTiet = htmlspecialchars(strip_tags($this->MaChiTiet));

        // bind id
        $stmt->bindParam(":MaChiTiet", $this->MaChiTiet);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>