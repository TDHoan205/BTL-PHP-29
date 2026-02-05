<?php
/**
 * UserModel - Quản lý dữ liệu Người dùng
 */
require_once __DIR__ . '/../core/Model.php';

class UserModel extends Model {
    protected $table_name = "USER";
    protected $primaryKey = "MaUser";

    public $MaUser;
    public $TenDangNhap;
    public $MatKhau;
    public $HoTen;
    public $Email;
    public $SoDienThoai;
    public $VaiTro;
    public $TrangThai;
    public $NgayTao;
    public $NgayCapNhat;
    public $Avatar;

    /**
     * Lấy thông tin một người dùng theo ID
     */
    public function getById($userId) {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE MaUser = :MaUser";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":MaUser", $userId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Kiểm tra tên đăng nhập đã tồn tại chưa
     * @param string $username Tên đăng nhập
     * @param int|null $excludeId MaUser cần loại trừ (khi sửa, để giữ tên hiện tại)
     * @return bool
     */
    public function existsByTenDangNhap($username, $excludeId = null) {
        try {
            $query = "SELECT 1 FROM `{$this->table_name}` WHERE TenDangNhap = :TenDangNhap";
            $params = [':TenDangNhap' => trim($username)];
            if ($excludeId !== null && $excludeId !== '') {
                $query .= " AND MaUser != :MaUser";
                $params[':MaUser'] = (int) $excludeId;
            }
            $query .= " LIMIT 1";
            $stmt = $this->conn->prepare($query);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();
            return (bool) $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error in existsByTenDangNhap: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra email đã được sử dụng chưa
     * @param string $email Email
     * @param int|null $excludeId MaUser cần loại trừ (khi sửa)
     * @return bool
     */
    public function existsByEmail($email, $excludeId = null) {
        if (empty(trim($email))) return false;
        try {
            $query = "SELECT 1 FROM `{$this->table_name}` WHERE Email = :Email AND Email IS NOT NULL AND Email != ''";
            $params = [':Email' => trim($email)];
            if ($excludeId !== null && $excludeId !== '') {
                $query .= " AND MaUser != :MaUser";
                $params[':MaUser'] = (int) $excludeId;
            }
            $query .= " LIMIT 1";
            $stmt = $this->conn->prepare($query);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();
            return (bool) $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error in existsByEmail: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin người dùng theo tên đăng nhập
     */
    public function getByUsername($username) {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE TenDangNhap = :TenDangNhap";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":TenDangNhap", $username);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getByUsername: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo mới người dùng
     */
    public function create() {
        try {
            $query = "INSERT INTO {$this->table_name} 
                      SET TenDangNhap=:TenDangNhap, MatKhau=:MatKhau, HoTen=:HoTen, 
                          Email=:Email, SoDienThoai=:SoDienThoai, VaiTro=:VaiTro, 
                          TrangThai=:TrangThai, NgayTao=:NgayTao, NgayCapNhat=:NgayCapNhat";
            $stmt = $this->conn->prepare($query);

            // Hash password nếu chưa được hash
            $password = $this->MatKhau;
            if (strpos($password, '$2y$') !== 0 && strpos($password, '$2a$') !== 0) {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }

            $stmt->bindValue(":TenDangNhap", $this->sanitize($this->TenDangNhap));
            $stmt->bindValue(":MatKhau", $password);
            $stmt->bindValue(":HoTen", $this->sanitize($this->HoTen));
            $stmt->bindValue(":Email", $this->sanitize($this->Email) ?: null);
            $stmt->bindValue(":SoDienThoai", $this->sanitize($this->SoDienThoai) ?: null);
            $stmt->bindValue(":VaiTro", $this->sanitize($this->VaiTro) ?: 'user');
            $stmt->bindValue(":TrangThai", $this->sanitize($this->TrangThai) ?: 'Hoạt động');
            $stmt->bindValue(":NgayTao", date('Y-m-d H:i:s'));
            $stmt->bindValue(":NgayCapNhat", date('Y-m-d H:i:s'));

            if ($stmt->execute()) {
                return true;
            }
            return "Không thể tạo người dùng. Vui lòng thử lại!";
        } catch (PDOException $e) {
            return $this->handlePdoException($e, 'UserModel::create');
        }
    }

    /**
     * Cập nhật người dùng
     */
    public function update() {
        try {
            // Kiểm tra nếu có cập nhật mật khẩu
            if (!empty($this->MatKhau)) {
                $query = "UPDATE {$this->table_name} 
                          SET TenDangNhap=:TenDangNhap, MatKhau=:MatKhau, HoTen=:HoTen, 
                              Email=:Email, SoDienThoai=:SoDienThoai, VaiTro=:VaiTro, 
                              TrangThai=:TrangThai, Avatar=:Avatar, NgayCapNhat=:NgayCapNhat 
                          WHERE MaUser=:MaUser";
            } else {
                $query = "UPDATE {$this->table_name} 
                          SET TenDangNhap=:TenDangNhap, HoTen=:HoTen, 
                              Email=:Email, SoDienThoai=:SoDienThoai, VaiTro=:VaiTro, 
                              TrangThai=:TrangThai, Avatar=:Avatar, NgayCapNhat=:NgayCapNhat 
                          WHERE MaUser=:MaUser";
            }
            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(":MaUser", (int)$this->MaUser);
            $stmt->bindValue(":TenDangNhap", $this->sanitize($this->TenDangNhap));
            $stmt->bindValue(":HoTen", $this->sanitize($this->HoTen));
            $stmt->bindValue(":Email", $this->sanitize($this->Email) ?: null);
            $stmt->bindValue(":SoDienThoai", $this->sanitize($this->SoDienThoai) ?: null);
            $stmt->bindValue(":VaiTro", $this->sanitize($this->VaiTro) ?: 'user');
            $stmt->bindValue(":TrangThai", $this->sanitize($this->TrangThai) ?: 'Hoạt động');
            $stmt->bindValue(":Avatar", $this->sanitize($this->Avatar) ?: null);
            $stmt->bindValue(":NgayCapNhat", date('Y-m-d H:i:s'));

            if (!empty($this->MatKhau)) {
                $password = $this->MatKhau;
                if (strpos($password, '$2y$') !== 0 && strpos($password, '$2a$') !== 0) {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                }
                $stmt->bindValue(":MatKhau", $password);
            }

            if ($stmt->execute()) {
                return true;
            }
            return "Không thể cập nhật người dùng. Vui lòng thử lại!";
        } catch (PDOException $e) {
            return $this->handlePdoException($e, 'UserModel::update');
        }
    }

    /**
     * Xóa người dùng
     */
    public function delete() {
        try {
            $query = "DELETE FROM {$this->table_name} WHERE MaUser = :MaUser";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(":MaUser", (int)$this->MaUser);

            if ($stmt->execute()) {
                return true;
            }
            return "Không thể xóa người dùng. Vui lòng thử lại!";
        } catch (PDOException $e) {
            return $this->handlePdoException($e, 'UserModel::delete');
        }
    }

    /**
     * Xác thực người dùng
     */
    public function authenticate($username, $password) {
        $user = $this->getByUsername($username);
        if (!$user) {
            return false;
        }

        $stored = $user['MatKhau'] ?? '';
        // Kiểm tra mật khẩu (hỗ trợ cả hash và plain text)
        $verify = (strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0)
            ? password_verify($password, $stored)
            : ($stored === $password);

        return $verify ? $user : false;
    }
}