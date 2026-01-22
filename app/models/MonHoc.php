<?php

require_once "../config/Database.php";

class MonHoc {
    private $conn;
    private $table_name = "MON_HOC";

    public $MaMonHoc;
    public $TenMonHoc;
    public $SoTinChi;
    public $SoTietLyThuyet;
    public $SoTietThucHanh;
    public $MaNganh;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả các môn học
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một môn học theo mã
    public function getById($maMonHoc) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaMonHoc = :MaMonHoc";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MaMonHoc", $maMonHoc);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới môn học
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET MaMonHoc=:MaMonHoc, TenMonHoc=:TenMonHoc, SoTinChi=:SoTinChi, SoTietLyThuyet=:SoTietLyThuyet, SoTietThucHanh=:SoTietThucHanh, MaNganh=:MaNganh";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));
        $this->TenMonHoc = htmlspecialchars(strip_tags($this->TenMonHoc));
        $this->SoTinChi = htmlspecialchars(strip_tags($this->SoTinChi));
        $this->SoTietLyThuyet = htmlspecialchars(strip_tags($this->SoTietLyThuyet));
        $this->SoTietThucHanh = htmlspecialchars(strip_tags($this->SoTietThucHanh));
        $this->MaNganh = htmlspecialchars(strip_tags($this->MaNganh));

        // bind values
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);
        $stmt->bindParam(":TenMonHoc", $this->TenMonHoc);
        $stmt->bindParam(":SoTinChi", $this->SoTinChi);
        $stmt->bindParam(":SoTietLyThuyet", $this->SoTietLyThuyet);
        $stmt->bindParam(":SoTietThucHanh", $this->SoTietThucHanh);
        $stmt->bindParam(":MaNganh", $this->MaNganh);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin môn học
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET TenMonHoc=:TenMonHoc, SoTinChi=:SoTinChi, SoTietLyThuyet=:SoTietLyThuyet, SoTietThucHanh=:SoTietThucHanh, MaNganh=:MaNganh WHERE MaMonHoc=:MaMonHoc";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));
        $this->TenMonHoc = htmlspecialchars(strip_tags($this->TenMonHoc));
        $this->SoTinChi = htmlspecialchars(strip_tags($this->SoTinChi));
        $this->SoTietLyThuyet = htmlspecialchars(strip_tags($this->SoTietLyThuyet));
        $this->SoTietThucHanh = htmlspecialchars(strip_tags($this->SoTietThucHanh));
        $this->MaNganh = htmlspecialchars(strip_tags($this->MaNganh));

        // bind values
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);
        $stmt->bindParam(":TenMonHoc", $this->TenMonHoc);
        $stmt->bindParam(":SoTinChi", $this->SoTinChi);
        $stmt->bindParam(":SoTietLyThuyet", $this->SoTietLyThuyet);
        $stmt->bindParam(":SoTietThucHanh", $this->SoTietThucHanh);
        $stmt->bindParam(":MaNganh", $this->MaNganh);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa môn học
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaMonHoc = :MaMonHoc";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->MaMonHoc = htmlspecialchars(strip_tags($this->MaMonHoc));

        // bind id
        $stmt->bindParam(":MaMonHoc", $this->MaMonHoc);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tìm kiếm môn học theo tiêu chí
    public function search($criteria) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE TenMonHoc LIKE :criteria OR MaNganh LIKE :criteria";
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $criteria = "%" . htmlspecialchars(strip_tags($criteria)) . "%";
        $stmt->bindParam(":criteria", $criteria);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>