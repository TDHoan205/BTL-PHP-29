-- Migration: Tạo bảng YEU_CAU_DOI_MAT_KHAU
-- Chạy file này nếu bảng chưa tồn tại

USE qldiem;

-- Tạo bảng YEU_CAU_DOI_MAT_KHAU nếu chưa có
CREATE TABLE IF NOT EXISTS `YEU_CAU_DOI_MAT_KHAU` (
  `ID` INT AUTO_INCREMENT PRIMARY KEY,
  `TenDangNhap` VARCHAR(50) NOT NULL,
  `MaNguoiDung` VARCHAR(20) NOT NULL,
  `VaiTro` ENUM('SinhVien', 'GiangVien') NOT NULL,
  `NgayYeuCau` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `TrangThai` ENUM('ChoXuLy', 'DaDuyet', 'TuChoi') DEFAULT 'ChoXuLy',
  `MatKhauMoi` VARCHAR(255) NULL,
  `NguoiXuLy` VARCHAR(50) NULL,
  `NgayXuLy` DATETIME NULL,
  `GhiChu` TEXT NULL,
  INDEX idx_trang_thai (`TrangThai`),
  INDEX idx_ten_dang_nhap (`TenDangNhap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
