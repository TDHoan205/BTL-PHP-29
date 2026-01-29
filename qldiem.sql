-- Tạo database với bảng mã hỗ trợ tiếng Việt đầy đủ
CREATE DATABASE IF NOT EXISTS qldiem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qldiem;

-- 1. Bảng Khoa
CREATE TABLE KHOA (
    MaKhoa VARCHAR(10) PRIMARY KEY,
    TenKhoa VARCHAR(100) NOT NULL,
    NgayThanhLap DATE,
    TruongKhoa VARCHAR(100)
);

-- 2. Bảng Ngành
CREATE TABLE NGANH (
    MaNganh VARCHAR(10) PRIMARY KEY,
    TenNganh VARCHAR(100) NOT NULL,
    MaKhoa VARCHAR(10) NOT NULL,
    FOREIGN KEY (MaKhoa) REFERENCES KHOA(MaKhoa)
);

-- 3. Bảng Giảng Viên
CREATE TABLE GIANG_VIEN (
    MaGiangVien VARCHAR(20) PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    NgaySinh DATE,
    GioiTinh VARCHAR(10),
    Email VARCHAR(100),
    SoDienThoai VARCHAR(15),
    HocVi VARCHAR(50),
    MaKhoa VARCHAR(10) NOT NULL,
    TrangThai BIT DEFAULT 1,
    FOREIGN KEY (MaKhoa) REFERENCES KHOA(MaKhoa)
);

-- 4. Bảng Lớp Hành Chính
CREATE TABLE LOP_HANH_CHINH (
    MaLop VARCHAR(20) PRIMARY KEY,
    TenLop VARCHAR(100) NOT NULL,
    MaNganh VARCHAR(10) NOT NULL,
    KhoaHoc INT,
    MaCoVan VARCHAR(20),
    FOREIGN KEY (MaNganh) REFERENCES NGANH(MaNganh),
    FOREIGN KEY (MaCoVan) REFERENCES GIANG_VIEN(MaGiangVien)
);

-- 5. Bảng Sinh Viên
CREATE TABLE SINH_VIEN (
    MaSinhVien VARCHAR(20) PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    NgaySinh DATE NOT NULL,
    GioiTinh VARCHAR(10),
    DiaChi VARCHAR(255),
    Email VARCHAR(100),
    SoDienThoai VARCHAR(15),
    MaLop VARCHAR(20) NOT NULL,
    TrangThaiHocTap VARCHAR(50) DEFAULT 'Đang học',
    FOREIGN KEY (MaLop) REFERENCES LOP_HANH_CHINH(MaLop)
);

-- 6. Bảng Môn Học
CREATE TABLE MON_HOC (
    MaMonHoc VARCHAR(20) PRIMARY KEY,
    TenMonHoc VARCHAR(100) NOT NULL,
    SoTinChi INT NOT NULL CHECK (SoTinChi > 0),
    SoTietLyThuyet INT DEFAULT 0,
    SoTietThucHanh INT DEFAULT 0,
    MaNganh VARCHAR(10),
    FOREIGN KEY (MaNganh) REFERENCES NGANH(MaNganh)
);

-- 7. Bảng Học Kỳ
CREATE TABLE HOC_KY (
    MaHocKy VARCHAR(10) PRIMARY KEY,
    TenHocKy VARCHAR(50),
    NamHoc INT,
    NgayBatDau DATE,
    NgayKetThuc DATE
);

-- 8. Bảng Lớp Học Phần
CREATE TABLE LOP_HOC_PHAN (
    MaLopHocPhan VARCHAR(20) PRIMARY KEY,
    MaMonHoc VARCHAR(20) NOT NULL,
    MaHocKy VARCHAR(10) NOT NULL,
    MaGiangVien VARCHAR(20) NOT NULL,
    PhongHoc VARCHAR(50),
    SoLuongToiDa INT DEFAULT 60,
    TrangThai INT DEFAULT 1,
    FOREIGN KEY (MaMonHoc) REFERENCES MON_HOC(MaMonHoc),
    FOREIGN KEY (MaHocKy) REFERENCES HOC_KY(MaHocKy),
    FOREIGN KEY (MaGiangVien) REFERENCES GIANG_VIEN(MaGiangVien)
);

-- 9. Bảng Loại Điểm
CREATE TABLE LOAI_DIEM (
    MaLoaiDiem VARCHAR(10) PRIMARY KEY,
    TenLoaiDiem VARCHAR(50) NOT NULL
);

-- 10. Bảng Cấu Trúc Điểm
-- Thay IDENTITY bằng AUTO_INCREMENT
CREATE TABLE CAU_TRUC_DIEM (
    ID INT AUTO_INCREMENT PRIMARY KEY, 
    MaMonHoc VARCHAR(20) NOT NULL,
    MaLoaiDiem VARCHAR(10) NOT NULL,
    HeSo FLOAT NOT NULL CHECK (HeSo > 0 AND HeSo <= 1),
    MoTa VARCHAR(100),
    FOREIGN KEY (MaMonHoc) REFERENCES MON_HOC(MaMonHoc),
    FOREIGN KEY (MaLoaiDiem) REFERENCES LOAI_DIEM(MaLoaiDiem)
);

-- 11. Bảng Đăng Ký Học
-- Thay IDENTITY bằng AUTO_INCREMENT và GETDATE() bằng CURRENT_TIMESTAMP
CREATE TABLE DANG_KY_HOC (
    MaDangKy INT AUTO_INCREMENT PRIMARY KEY,
    MaSinhVien VARCHAR(20) NOT NULL,
    MaLopHocPhan VARCHAR(20) NOT NULL,
    NgayDangKy DATETIME DEFAULT CURRENT_TIMESTAMP,
    DiemTongKet FLOAT,
    DiemChu VARCHAR(2),
    DiemSo FLOAT,
    KetQua BIT,
    FOREIGN KEY (MaSinhVien) REFERENCES SINH_VIEN(MaSinhVien),
    FOREIGN KEY (MaLopHocPhan) REFERENCES LOP_HOC_PHAN(MaLopHocPhan),
    CONSTRAINT UQ_SinhVien_Lop UNIQUE (MaSinhVien, MaLopHocPhan)
);

-- 12. Bảng Chi Tiết Điểm
CREATE TABLE CHI_TIET_DIEM (
    MaChiTiet INT AUTO_INCREMENT PRIMARY KEY,
    MaDangKy INT NOT NULL,
    MaLoaiDiem VARCHAR(10) NOT NULL,
    SoDiem DECIMAL(4, 2) CHECK (SoDiem >= 0 AND SoDiem <= 10),
    NgayNhap DATETIME DEFAULT CURRENT_TIMESTAMP,
    NguoiNhap VARCHAR(20),
    FOREIGN KEY (MaDangKy) REFERENCES DANG_KY_HOC(MaDangKy),
    FOREIGN KEY (MaLoaiDiem) REFERENCES LOAI_DIEM(MaLoaiDiem)
);

-- 13. Bảng User
-- Tên bảng USER phải đặt trong dấu ` ` vì trùng từ khóa hệ thống của MySQL
CREATE TABLE `USER` (
    MaUser INT AUTO_INCREMENT PRIMARY KEY,
    TenDangNhap VARCHAR(50) NOT NULL UNIQUE,
    MatKhau VARCHAR(255) NOT NULL,
    HoTen VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE,
    SoDienThoai VARCHAR(15),
    VaiTro VARCHAR(50) NOT NULL,
    TrangThai BIT DEFAULT 1,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
USE qldiem;

-- 1. Thêm dữ liệu Bảng KHOA
INSERT INTO KHOA (MaKhoa, TenKhoa, NgayThanhLap, TruongKhoa) VALUES
('CNTT', 'Công nghệ Thông tin', '2005-08-15', 'TS. Nguyễn Văn A'),
('KT', 'Kinh tế', '2005-08-15', 'TS. Trần Thị B'),
('NN', 'Ngoại ngữ', '2006-09-05', 'ThS. Lê Văn C'),
('XD', 'Xây dựng', '2007-01-10', 'TS. Phạm Văn D'),
('DL', 'Du lịch', '2008-05-20', 'ThS. Hoàng Thị E');

-- 2. Thêm dữ liệu Bảng NGANH
INSERT INTO NGANH (MaNganh, TenNganh, MaKhoa) VALUES
('CNPM', 'Kỹ thuật Phần mềm', 'CNTT'),
('HTTT', 'Hệ thống Thông tin', 'CNTT'),
('QTKD', 'Quản trị Kinh doanh', 'KT'),
('NNA', 'Ngôn ngữ Anh', 'NN'),
('XDDD', 'Xây dựng Dân dụng', 'XD');

-- 3. Thêm dữ liệu Bảng GIANG_VIEN
INSERT INTO GIANG_VIEN (MaGiangVien, HoTen, NgaySinh, GioiTinh, Email, SoDienThoai, HocVi, MaKhoa, TrangThai) VALUES
('GV001', 'Nguyễn Văn Hùng', '1980-05-10', 'Nam', 'hungnv@uni.edu.vn', '0912345678', 'Tiến sĩ', 'CNTT', 1),
('GV002', 'Trần Thị Mai', '1985-08-20', 'Nữ', 'maitt@uni.edu.vn', '0987654321', 'Thạc sĩ', 'KT', 1),
('GV003', 'Lê Thanh Sơn', '1978-12-15', 'Nam', 'sonlt@uni.edu.vn', '0909090909', 'Tiến sĩ', 'XD', 1),
('GV004', 'Phạm Lan Anh', '1990-03-25', 'Nữ', 'anhpl@uni.edu.vn', '0911223344', 'Thạc sĩ', 'NN', 1),
('GV005', 'Hoàng Văn Nam', '1982-07-30', 'Nam', 'namhv@uni.edu.vn', '0998877665', 'Thạc sĩ', 'CNTT', 1);

-- 4. Thêm dữ liệu Bảng LOP_HANH_CHINH
INSERT INTO LOP_HANH_CHINH (MaLop, TenLop, MaNganh, KhoaHoc, MaCoVan) VALUES
('D21CNPM01', 'Đại học CNPM K21 Lớp 1', 'CNPM', 21, 'GV001'),
('D21HTTT01', 'Đại học HTTT K21 Lớp 1', 'HTTT', 21, 'GV005'),
('D22QTKD01', 'Đại học QTKD K22 Lớp 1', 'QTKD', 22, 'GV002'),
('D22NNA01', 'Đại học NNA K22 Lớp 1', 'NNA', 22, 'GV004'),
('D23XDDD01', 'Đại học XDDD K23 Lớp 1', 'XDDD', 23, 'GV003');

-- 5. Thêm dữ liệu Bảng SINH_VIEN
INSERT INTO SINH_VIEN (MaSinhVien, HoTen, NgaySinh, GioiTinh, DiaChi, Email, SoDienThoai, MaLop, TrangThaiHocTap) VALUES
('SV001', 'Nguyễn Thị Hương', '2003-01-15', 'Nữ', 'Hà Nội', 'huongnt@st.uni.edu.vn', '0345678901', 'D21CNPM01', 'Đang học'),
('SV002', 'Trần Văn Bình', '2003-05-20', 'Nam', 'Nam Định', 'binhtv@st.uni.edu.vn', '0345678902', 'D21CNPM01', 'Đang học'),
('SV003', 'Lê Thị Thu', '2004-09-10', 'Nữ', 'Hải Phòng', 'thult@st.uni.edu.vn', '0345678903', 'D22QTKD01', 'Đang học'),
('SV004', 'Phạm Minh Tuấn', '2004-12-05', 'Nam', 'Đà Nẵng', 'tuanpm@st.uni.edu.vn', '0345678904', 'D22NNA01', 'Bảo lưu'),
('SV005', 'Hoàng Văn Đức', '2005-03-30', 'Nam', 'TP.HCM', 'duchv@st.uni.edu.vn', '0345678905', 'D23XDDD01', 'Đang học');

-- 6. Thêm dữ liệu Bảng MON_HOC
INSERT INTO MON_HOC (MaMonHoc, TenMonHoc, SoTinChi, SoTietLyThuyet, SoTietThucHanh, MaNganh) VALUES
('MH001', 'Lập trình Java', 3, 30, 15, 'CNPM'),
('MH002', 'Cơ sở dữ liệu', 3, 30, 15, 'HTTT'),
('MH003', 'Kế toán đại cương', 2, 30, 0, 'QTKD'),
('MH004', 'Tiếng Anh chuyên ngành 1', 2, 30, 0, 'NNA'),
('MH005', 'Sức bền vật liệu', 3, 45, 0, 'XDDD');

-- 7. Thêm dữ liệu Bảng HOC_KY
INSERT INTO HOC_KY (MaHocKy, TenHocKy, NamHoc, NgayBatDau, NgayKetThuc) VALUES
('HK1_2324', 'Học kỳ 1 Năm 2023-2024', 2023, '2023-09-05', '2024-01-15'),
('HK2_2324', 'Học kỳ 2 Năm 2023-2024', 2023, '2024-02-15', '2024-06-30'),
('HKH_2324', 'Học kỳ Hè Năm 2023-2024', 2023, '2024-07-01', '2024-08-15'),
('HK1_2425', 'Học kỳ 1 Năm 2024-2025', 2024, '2024-09-05', '2025-01-15'),
('HK2_2425', 'Học kỳ 2 Năm 2024-2025', 2024, '2025-02-15', '2025-06-30');

-- 8. Thêm dữ liệu Bảng LOP_HOC_PHAN
INSERT INTO LOP_HOC_PHAN (MaLopHocPhan, MaMonHoc, MaHocKy, MaGiangVien, PhongHoc, SoLuongToiDa, TrangThai) VALUES
('LHP001', 'MH001', 'HK1_2324', 'GV001', 'A101', 60, 1),
('LHP002', 'MH002', 'HK1_2324', 'GV005', 'PM02', 40, 1),
('LHP003', 'MH003', 'HK1_2324', 'GV002', 'B202', 80, 1),
('LHP004', 'MH004', 'HK2_2324', 'GV004', 'C303', 30, 1),
('LHP005', 'MH005', 'HK2_2324', 'GV003', 'D404', 50, 1);

-- 9. Thêm dữ liệu Bảng LOAI_DIEM
INSERT INTO LOAI_DIEM (MaLoaiDiem, TenLoaiDiem) VALUES
('CC', 'Chuyên cần'),
('TX', 'Thường xuyên'),
('GK', 'Giữa kỳ'),
('TH', 'Thực hành'),
('CK', 'Cuối kỳ');

-- 10. Thêm dữ liệu Bảng CAU_TRUC_DIEM
-- Ví dụ: Môn Java (MH001) có CC(10%), GK(20%), TH(20%), CK(50%)
-- Môn CSDL (MH002) có CC(10%), CK(90%)
INSERT INTO CAU_TRUC_DIEM (MaMonHoc, MaLoaiDiem, HeSo, MoTa) VALUES
('MH001', 'CC', 0.1, 'Điểm danh'),
('MH001', 'GK', 0.2, 'Thi viết giữa kỳ'),
('MH001', 'TH', 0.2, 'Bài lab'),
('MH001', 'CK', 0.5, 'Thi máy cuối kỳ'),
('MH002', 'CK', 0.6, 'Thi viết cuối kỳ');

-- 11. Thêm dữ liệu Bảng DANG_KY_HOC
-- SV001 và SV002 học Java (LHP001), SV003 học Kế toán (LHP003)
INSERT INTO DANG_KY_HOC (MaSinhVien, MaLopHocPhan, DiemTongKet, DiemChu, DiemSo, KetQua) VALUES
('SV001', 'LHP001', 8.5, 'A', 3.7, 1),
('SV002', 'LHP001', 6.0, 'C', 2.0, 1),
('SV003', 'LHP003', 9.0, 'A+', 4.0, 1),
('SV001', 'LHP002', 4.0, 'D', 1.0, 1), -- SV001 học thêm môn CSDL
('SV005', 'LHP005', NULL, NULL, NULL, NULL); -- Đang học chưa có điểm tổng kết

-- 12. Thêm dữ liệu Bảng CHI_TIET_DIEM
-- Giả sử MaDangKy lần lượt là 1, 2, 3, 4, 5
-- Nhập điểm cho SV001 học LHP001 (Java)
INSERT INTO CHI_TIET_DIEM (MaDangKy, MaLoaiDiem, SoDiem, NguoiNhap) VALUES
(1, 'CC', 10.0, 'GV001'),
(1, 'GK', 8.0, 'GV001'),
(1, 'TH', 8.5, 'GV001'),
(1, 'CK', 8.5, 'GV001'),
(2, 'CK', 6.0, 'GV001'); -- Điểm cuối kỳ cho SV002

-- 13. Thêm dữ liệu Bảng USER
-- Mật khẩu nên được mã hóa, đây là ví dụ demo để plaintext
INSERT INTO `USER` (TenDangNhap, MatKhau, HoTen, Email, SoDienThoai, VaiTro, TrangThai) VALUES
('admin', '123456', 'Quản Trị Viên', 'admin@uni.edu.vn', '0901010101', 'Admin', 1),
('gv001', '123456', 'Nguyễn Văn Hùng', 'hungnv@uni.edu.vn', '0912345678', 'GiangVien', 1),
('sv001', '123456', 'Nguyễn Thị Hương', 'huongnt@st.uni.edu.vn', '0345678901', 'SinhVien', 1),
('daotao', '123456', 'Phòng Đào Tạo', 'pdt@uni.edu.vn', '0902020202', 'QuanLy', 1),
('gv002', '123456', 'Trần Thị Mai', 'maitt@uni.edu.vn', '0987654321', 'GiangVien', 1);