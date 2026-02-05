<?php
/**
 * YeuCauDoiMatKhauModel - Quản lý yêu cầu quên mật khẩu
 */
require_once __DIR__ . '/../core/Model.php';

class YeuCauDoiMatKhauModel extends Model {
    protected $table_name = "YEU_CAU_DOI_MAT_KHAU";
    protected $primaryKey = "ID";

    public $ID;
    public $TenDangNhap;
    public $MaNguoiDung;
    public $VaiTro;
    public $NgayYeuCau;
    public $TrangThai;
    public $MatKhauMoi;
    public $NguoiXuLy;
    public $NgayXuLy;
    public $GhiChu;

    /**
     * Tạo yêu cầu đổi mật khẩu mới
     */
    public function create() {
        try {
            $query = "INSERT INTO {$this->table_name} 
                      (TenDangNhap, MaNguoiDung, VaiTro, TrangThai)
                      VALUES (:TenDangNhap, :MaNguoiDung, :VaiTro, 'ChoXuLy')";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':TenDangNhap', $this->sanitize($this->TenDangNhap));
            $stmt->bindValue(':MaNguoiDung', $this->sanitize($this->MaNguoiDung));
            $stmt->bindValue(':VaiTro', $this->sanitize($this->VaiTro));
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("YeuCauDoiMatKhauModel::create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả yêu cầu đang chờ xử lý
     */
    public function getChoXuLy() {
        try {
            $query = "SELECT * FROM {$this->table_name} 
                      WHERE TrangThai = 'ChoXuLy' 
                      ORDER BY NgayYeuCau DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("YeuCauDoiMatKhauModel::getChoXuLy: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy tất cả yêu cầu
     */
    public function readAll() {
        try {
            $query = "SELECT * FROM {$this->table_name} 
                      ORDER BY NgayYeuCau DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("YeuCauDoiMatKhauModel::readAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy yêu cầu theo ID
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE ID = :ID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ID', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("YeuCauDoiMatKhauModel::getById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Kiểm tra yêu cầu đã tồn tại chưa (đang chờ xử lý)
     */
    public function hasRequestPending($tenDangNhap, $maNguoiDung) {
        try {
            $query = "SELECT COUNT(*) as count FROM {$this->table_name} 
                      WHERE TenDangNhap = :TenDangNhap 
                      AND MaNguoiDung = :MaNguoiDung 
                      AND TrangThai = 'ChoXuLy'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':TenDangNhap', $tenDangNhap);
            $stmt->bindValue(':MaNguoiDung', $maNguoiDung);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] ?? 0) > 0;
        } catch (PDOException $e) {
            error_log("YeuCauDoiMatKhauModel::hasRequestPending: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xác nhận yêu cầu (duyệt hoặc từ chối)
     */
    public function updateStatus($id, $trangThai, $nguoiXuLy, $matKhauMoi = null, $ghiChu = null) {
        try {
            $query = "UPDATE {$this->table_name} 
                      SET TrangThai = :TrangThai, 
                          NguoiXuLy = :NguoiXuLy, 
                          NgayXuLy = NOW(),
                          MatKhauMoi = :MatKhauMoi,
                          GhiChu = :GhiChu
                      WHERE ID = :ID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':TrangThai', $trangThai);
            $stmt->bindValue(':NguoiXuLy', $nguoiXuLy);
            $stmt->bindValue(':MatKhauMoi', $matKhauMoi);
            $stmt->bindValue(':GhiChu', $ghiChu);
            $stmt->bindValue(':ID', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("YeuCauDoiMatKhauModel::updateStatus: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa yêu cầu
     */
    public function delete() {
        try {
            $query = "DELETE FROM {$this->table_name} WHERE ID = :ID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':ID', $this->ID);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("YeuCauDoiMatKhauModel::delete: " . $e->getMessage());
            return false;
        }
    }
}
