-- ============================================================================
-- MIGRATION TỔNG HỢP - HỆ THỐNG QUẢN LÝ ĐIỂM UNISCORE
-- ============================================================================
-- File này gộp tất cả các migration thành 1 file duy nhất
-- Chạy file này sau khi đã import file qldiem.sql chính
-- Thứ tự thực hiện: qldiem.sql → migration_all.sql
-- 
-- Nội dung:
--   1. Thêm cột Avatar cho bảng USER
--   2. Xóa loại điểm Thường xuyên (TX) và Thực hành (TH)
--   3. Tạo bảng DIEM_DANH (điểm danh)
--   4. Tạo bảng THOI_KHOA_BIEU (thời khóa biểu)
--   5. Tạo bảng YEU_CAU_DOI_MAT_KHAU (quên mật khẩu)
--   6. Cập nhật cấu trúc điểm cho các môn học
--
-- Ngày tạo: 05/02/2026
-- ============================================================================

USE qldiem;

-- ============================================================================
-- PHẦN 1: THÊM CỘT AVATAR CHO BẢNG USER
-- ============================================================================
-- Thêm cột lưu trữ đường dẫn ảnh đại diện người dùng
-- Nếu cột đã tồn tại sẽ báo lỗi - có thể bỏ qua

ALTER TABLE `USER` 
ADD COLUMN IF NOT EXISTS `Avatar` VARCHAR(255) NULL 
COMMENT 'Đường dẫn file ảnh đại diện (vd: uploads/avatars/avt_1_xxx.jpg)';


-- ============================================================================
-- PHẦN 2: XÓA LOẠI ĐIỂM THƯỜNG XUYÊN (TX) VÀ THỰC HÀNH (TH)
-- ============================================================================
-- Xóa dữ liệu điểm TX và TH theo thứ tự đúng (tránh lỗi foreign key)

-- 2.1. Xóa điểm Thường xuyên (TX)
DELETE FROM `CHI_TIET_DIEM` WHERE `MaLoaiDiem` = 'TX';
DELETE FROM `CAU_TRUC_DIEM` WHERE `MaLoaiDiem` = 'TX';
DELETE FROM `LOAI_DIEM` WHERE `MaLoaiDiem` = 'TX';

-- 2.2. Xóa điểm Thực hành (TH)
DELETE FROM `CHI_TIET_DIEM` WHERE `MaLoaiDiem` = 'TH';
DELETE FROM `CAU_TRUC_DIEM` WHERE `MaLoaiDiem` = 'TH';
DELETE FROM `LOAI_DIEM` WHERE `MaLoaiDiem` = 'TH';


-- ============================================================================
-- PHẦN 3: CẬP NHẬT CẤU TRÚC ĐIỂM CHO MÔN HỌC
-- ============================================================================
-- Sau khi xóa TX và TH, cập nhật lại hệ số điểm cho các môn
-- Cấu trúc mới: CC 10%, GK 30%, CK 60%

UPDATE `CAU_TRUC_DIEM` SET `HeSo` = 0.1 WHERE `MaMonHoc` = 'MH001' AND `MaLoaiDiem` = 'CC';
UPDATE `CAU_TRUC_DIEM` SET `HeSo` = 0.3 WHERE `MaMonHoc` = 'MH001' AND `MaLoaiDiem` = 'GK';
UPDATE `CAU_TRUC_DIEM` SET `HeSo` = 0.6 WHERE `MaMonHoc` = 'MH001' AND `MaLoaiDiem` = 'CK';

-- Xóa TH nếu còn tồn tại
DELETE FROM `CAU_TRUC_DIEM` WHERE `MaMonHoc` = 'MH001' AND `MaLoaiDiem` = 'TH';


-- ============================================================================
-- PHẦN 4: TẠO BẢNG DIEM_DANH (ĐIỂM DANH)
-- ============================================================================
-- Bảng lưu trữ thông tin điểm danh từng buổi học của sinh viên
-- Công thức: 1 tín chỉ = 5 buổi + 3 buổi học bù = 8 buổi
--           2 tín chỉ = 10 buổi + 3 buổi học bù = 13 buổi
--           Tổng quát: SoTinChi × 5 + 3

CREATE TABLE IF NOT EXISTS `DIEM_DANH` (
    `ID` INT AUTO_INCREMENT PRIMARY KEY,
    `MaDangKy` INT NOT NULL COMMENT 'ID đăng ký học của sinh viên',
    `MaLopHocPhan` VARCHAR(20) NOT NULL COMMENT 'Mã lớp học phần',
    `BuoiThu` TINYINT NOT NULL COMMENT 'Buổi thứ mấy trong môn (giới hạn: SoTinChi × 5 + 3)',
    `NgayDiemDanh` DATE NOT NULL COMMENT 'Ngày điểm danh',
    `CoMat` BIT DEFAULT 1 COMMENT '1 = Có mặt, 0 = Vắng mặt',
    `GhiChu` VARCHAR(255) NULL COMMENT 'Ghi chú (vắng có phép, vắng không phép, ...)',
    `NguoiDiemDanh` VARCHAR(20) NULL COMMENT 'Mã giảng viên điểm danh',
    `NgayTao` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo bản ghi',
    
    FOREIGN KEY (`MaDangKy`) REFERENCES `DANG_KY_HOC`(`MaDangKy`) ON DELETE CASCADE,
    FOREIGN KEY (`MaLopHocPhan`) REFERENCES `LOP_HOC_PHAN`(`MaLopHocPhan`) ON DELETE CASCADE,
    UNIQUE KEY `UQ_DangKy_Buoi` (`MaDangKy`, `BuoiThu`) COMMENT 'Mỗi sinh viên chỉ điểm danh 1 lần/buổi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Bảng điểm danh sinh viên. Công thức: SoTinChi × 5 + 3 buổi';


-- ============================================================================
-- PHẦN 5: TẠO BẢNG THOI_KHOA_BIEU (THỜI KHÓA BIỂU)
-- ============================================================================
-- Bảng lưu trữ lịch học của các lớp học phần

CREATE TABLE IF NOT EXISTS `THOI_KHOA_BIEU` (
    `ID` INT AUTO_INCREMENT PRIMARY KEY,
    `MaLopHocPhan` VARCHAR(20) NOT NULL COMMENT 'Mã lớp học phần',
    `Thu` TINYINT NOT NULL COMMENT 'Thứ trong tuần (2=Thứ 2, 3=Thứ 3, ..., 7=Thứ 7)',
    `TietBatDau` TINYINT NOT NULL COMMENT 'Tiết bắt đầu (1-10)',
    `TietKetThuc` TINYINT NOT NULL COMMENT 'Tiết kết thúc (1-10)',
    `PhongHoc` VARCHAR(50) NULL COMMENT 'Phòng học (vd: A101, PM02, ...)',
    
    FOREIGN KEY (`MaLopHocPhan`) REFERENCES `LOP_HOC_PHAN`(`MaLopHocPhan`) ON DELETE CASCADE,
    INDEX `idx_lop_thu` (`MaLopHocPhan`, `Thu`) COMMENT 'Index cho truy vấn lịch theo lớp và thứ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Bảng thời khóa biểu - lịch học của các lớp học phần';

-- Dữ liệu mẫu thời khóa biểu (Bỏ comment nếu cần thêm dữ liệu test)
/*
INSERT INTO `THOI_KHOA_BIEU` (`MaLopHocPhan`, `Thu`, `TietBatDau`, `TietKetThuc`, `PhongHoc`) VALUES
('LHP001', 2, 1, 2, 'A101'),
('LHP001', 4, 3, 4, 'A101'),
('LHP002', 3, 1, 3, 'PM02'),
('LHP003', 2, 5, 6, 'B202'),
('LHP004', 5, 1, 2, 'C303'),
('LHP005', 4, 7, 9, 'D404');
*/


-- ============================================================================
-- PHẦN 6: TẠO BẢNG YEU_CAU_DOI_MAT_KHAU (QUÊN MẬT KHẨU)
-- ============================================================================
-- Bảng quản lý yêu cầu đổi mật khẩu từ sinh viên/giảng viên
-- Quy trình: Người dùng gửi yêu cầu → Admin duyệt → Gửi email mật khẩu mới
-- Admin sẽ duyệt và tạo mật khẩu mới cho người dùng

-- Xóa bảng cũ nếu cần cập nhật cấu trúc mới
-- DROP TABLE IF EXISTS `YEU_CAU_DOI_MAT_KHAU`;

CREATE TABLE IF NOT EXISTS `YEU_CAU_DOI_MAT_KHAU` (
    `ID` INT AUTO_INCREMENT PRIMARY KEY,
    `MaUser` INT NOT NULL COMMENT 'Mã người dùng trong bảng USER',
    `TenDangNhap` VARCHAR(50) NOT NULL COMMENT 'Tên đăng nhập của người yêu cầu',
    `Email` VARCHAR(100) NOT NULL COMMENT 'Email để gửi mật khẩu mới',
    `HoTen` VARCHAR(100) NOT NULL COMMENT 'Họ tên người yêu cầu',
    `VaiTro` VARCHAR(20) NOT NULL COMMENT 'Vai trò: Admin, GiangVien, SinhVien',
    `LyDo` TEXT NULL COMMENT 'Lý do quên mật khẩu (tùy chọn)',
    `NgayYeuCau` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian gửi yêu cầu',
    `TrangThai` ENUM('ChoXuLy', 'DaDuyet', 'TuChoi') DEFAULT 'ChoXuLy' COMMENT 'Trạng thái xử lý',
    `NguoiXuLy` INT NULL COMMENT 'MaUser của admin xử lý',
    `NgayXuLy` DATETIME NULL COMMENT 'Thời gian admin xử lý',
    `GhiChuAdmin` TEXT NULL COMMENT 'Ghi chú từ admin (lý do từ chối, ...)',
    
    INDEX `idx_trang_thai` (`TrangThai`) COMMENT 'Index cho truy vấn theo trạng thái',
    INDEX `idx_ma_user` (`MaUser`) COMMENT 'Index cho tìm kiếm theo user',
    INDEX `idx_ngay_yeu_cau` (`NgayYeuCau`) COMMENT 'Index cho sắp xếp theo ngày',
    CONSTRAINT `fk_yc_user` FOREIGN KEY (`MaUser`) REFERENCES `USER`(`MaUser`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Bảng quản lý yêu cầu đổi mật khẩu - tính năng Quên mật khẩu';

-- Thêm cột YeuCauDoiMatKhau vào bảng USER để đánh dấu cần đổi mật khẩu
ALTER TABLE `USER` 
ADD COLUMN IF NOT EXISTS `YeuCauDoiMatKhau` TINYINT(1) DEFAULT 0 
COMMENT 'Đánh dấu người dùng cần đổi mật khẩu khi đăng nhập (1 = bắt buộc đổi)';


-- ============================================================================
-- PHẦN 7: TẠO BẢNG REMEMBER_TOKENS (GHI NHỚ ĐĂNG NHẬP AN TOÀN)
-- ============================================================================
-- Bảng lưu token cho tính năng "Ghi nhớ đăng nhập"
-- Giải pháp an toàn: Không lưu mật khẩu, chỉ lưu token ngẫu nhiên

CREATE TABLE IF NOT EXISTS `REMEMBER_TOKENS` (
    `ID` INT AUTO_INCREMENT PRIMARY KEY,
    `TenDangNhap` VARCHAR(50) NOT NULL COMMENT 'Tên đăng nhập của user',
    `Token` VARCHAR(255) NOT NULL COMMENT 'Token ngẫu nhiên (hash)',
    `VaiTro` ENUM('Admin', 'GiangVien', 'SinhVien') NOT NULL COMMENT 'Vai trò đăng nhập',
    `NgayTao` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo token',
    `NgayHetHan` DATETIME NOT NULL COMMENT 'Thời gian hết hạn (30 ngày)',
    `UserAgent` VARCHAR(255) NULL COMMENT 'Thông tin trình duyệt (bảo mật)',
    `IPAddress` VARCHAR(45) NULL COMMENT 'Địa chỉ IP (bảo mật)',
    
    UNIQUE KEY `unique_token` (`Token`),
    INDEX `idx_username` (`TenDangNhap`),
    INDEX `idx_expiry` (`NgayHetHan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Bảng lưu token "Ghi nhớ đăng nhập" - An toàn, không lưu mật khẩu';


-- ============================================================================
-- HOÀN TẤT MIGRATION
-- ============================================================================
-- Kiểm tra kết quả:
--   1. SHOW TABLES; → Kiểm tra các bảng mới đã được tạo
--   2. DESC DIEM_DANH; → Xem cấu trúc bảng điểm danh
--   3. DESC THOI_KHOA_BIEU; → Xem cấu trúc bảng thời khóa biểu
--   4. DESC YEU_CAU_DOI_MAT_KHAU; → Xem cấu trúc bảng yêu cầu đổi mật khẩu
--   5. DESC REMEMBER_TOKENS; → Xem cấu trúc bảng ghi nhớ đăng nhập
--   6. SELECT * FROM LOAI_DIEM; → Kiểm tra TX và TH đã bị xóa
--   7. DESC USER; → Kiểm tra cột Avatar và YeuCauDoiMatKhau đã được thêm
--
-- Nếu có lỗi:
--   - Lỗi "Duplicate column name 'Avatar'" → Cột Avatar đã tồn tại, bỏ qua
--   - Lỗi "Duplicate column name 'YeuCauDoiMatKhau'" → Cột đã tồn tại, bỏ qua
--   - Lỗi "Table already exists" → Bảng đã tồn tại, bỏ qua
--   - Lỗi foreign key → Kiểm tra file qldiem.sql đã import đầy đủ chưa
-- ============================================================================

SELECT 'Migration completed successfully!' AS Status;
