use qldiem;
-- 1. Bảng Khoa: Quản lý các khoa trong trường
CREATE TABLE KHOA (
    MaKhoa VARCHAR(10) PRIMARY KEY, -- Ví dụ: CNTT, KT
    TenKhoa NVARCHAR(100) NOT NULL,
    NgayThanhLap DATE,
    TruongKhoa NVARCHAR(100) -- Tên trưởng khoa
);

-- 2. Bảng Ngành: Một khoa có nhiều ngành
CREATE TABLE NGANH (
    MaNganh VARCHAR(10) PRIMARY KEY, -- Ví dụ: KTPM, HTTT
    TenNganh NVARCHAR(100) NOT NULL,
    MaKhoa VARCHAR(10) NOT NULL,
    FOREIGN KEY (MaKhoa) REFERENCES KHOA(MaKhoa)
);

-- 3. Bảng Giảng Viên: Người dạy và nhập điểm
CREATE TABLE GIANG_VIEN (
    MaGiangVien VARCHAR(20) PRIMARY KEY,
    HoTen NVARCHAR(100) NOT NULL,
    NgaySinh DATE,
    GioiTinh NVARCHAR(10), -- Nam/Nữ
    Email VARCHAR(100),
    SoDienThoai VARCHAR(15),
    HocVi NVARCHAR(50), -- Thạc sĩ, Tiến sĩ
    MaKhoa VARCHAR(10) NOT NULL,
    TrangThai BIT DEFAULT 1, -- 1: Đang công tác, 0: Đã nghỉ
    FOREIGN KEY (MaKhoa) REFERENCES KHOA(MaKhoa)
);
-- 4. Bảng Lớp Hành Chính: Lớp sinh hoạt của sinh viên (Ví dụ: K15-CNTT1)
CREATE TABLE LOP_HANH_CHINH (
    MaLop VARCHAR(20) PRIMARY KEY,
    TenLop NVARCHAR(100) NOT NULL,
    MaNganh VARCHAR(10) NOT NULL,
    KhoaHoc INT, -- Khóa học (Ví dụ: 2024, 2025)
    MaCoVan VARCHAR(20), -- Giảng viên cố vấn học tập
    FOREIGN KEY (MaNganh) REFERENCES NGANH(MaNganh),
    FOREIGN KEY (MaCoVan) REFERENCES GIANG_VIEN(MaGiangVien)
);

-- 5. Bảng Sinh Viên: Thông tin cá nhân
CREATE TABLE SINH_VIEN (
    MaSinhVien VARCHAR(20) PRIMARY KEY,
    HoTen NVARCHAR(100) NOT NULL,
    NgaySinh DATE NOT NULL,
    GioiTinh NVARCHAR(10),
    DiaChi NVARCHAR(255),
    Email VARCHAR(100),
    SoDienThoai VARCHAR(15),
    MaLop VARCHAR(20) NOT NULL,
    TrangThaiHocTap NVARCHAR(50) DEFAULT N'Đang học', -- Đang học, Bảo lưu, Thôi học
    FOREIGN KEY (MaLop) REFERENCES LOP_HANH_CHINH(MaLop)
);
-- 6. Bảng Môn Học: Danh mục các môn
CREATE TABLE MON_HOC (
    MaMonHoc VARCHAR(20) PRIMARY KEY,
    TenMonHoc NVARCHAR(100) NOT NULL,
    SoTinChi INT NOT NULL CHECK (SoTinChi > 0),
    SoTietLyThuyet INT DEFAULT 0,
    SoTietThucHanh INT DEFAULT 0,
    MaNganh VARCHAR(10), -- Môn này thuộc ngành nào quản lý
    FOREIGN KEY (MaNganh) REFERENCES NGANH(MaNganh)
);

-- 7. Bảng Học Kỳ: Quản lý thời gian đào tạo
CREATE TABLE HOC_KY (
    MaHocKy VARCHAR(10) PRIMARY KEY, -- Ví dụ: HK1_2024
    TenHocKy NVARCHAR(50), -- Học kỳ 1 năm 2024-2025
    NamHoc INT,
    NgayBatDau DATE,
    NgayKetThuc DATE
);

-- 8. Bảng Lớp Học Phần: Lớp thực tế mở ra để dạy môn học (Quan trọng)
-- Sinh viên đăng ký vào đây chứ không đăng ký vào Môn học chung chung
CREATE TABLE LOP_HOC_PHAN (
    MaLopHocPhan VARCHAR(20) PRIMARY KEY, -- Ví dụ: LHP001
    MaMonHoc VARCHAR(20) NOT NULL,
    MaHocKy VARCHAR(10) NOT NULL,
    MaGiangVien VARCHAR(20) NOT NULL, -- Giảng viên dạy lớp này
    PhongHoc NVARCHAR(50),
    SoLuongToiDa INT DEFAULT 60,
    TrangThai INT DEFAULT 1, -- 1: Đang mở, 0: Đã khóa sổ điểm
    FOREIGN KEY (MaMonHoc) REFERENCES MON_HOC(MaMonHoc),
    FOREIGN KEY (MaHocKy) REFERENCES HOC_KY(MaHocKy),
    FOREIGN KEY (MaGiangVien) REFERENCES GIANG_VIEN(MaGiangVien)
);
-- 9. Bảng Loại Điểm: Định nghĩa các đầu điểm (Chuyên cần, Giữa kỳ, Cuối kỳ...)
CREATE TABLE LOAI_DIEM (
    MaLoaiDiem VARCHAR(10) PRIMARY KEY, -- Ví dụ: CC, GK, TH, CK
    TenLoaiDiem NVARCHAR(50) NOT NULL
);

-- 10. Bảng Cấu Trúc Điểm: Quy định trọng số cho từng môn học
-- Bảng này trả lời câu hỏi: Môn Toán Cao Cấp tính điểm như thế nào?
CREATE TABLE CAU_TRUC_DIEM (
    ID INT IDENTITY(1,1) PRIMARY KEY, -- Auto increment
    MaMonHoc VARCHAR(20) NOT NULL,
    MaLoaiDiem VARCHAR(10) NOT NULL,
    HeSo FLOAT NOT NULL CHECK (HeSo > 0 AND HeSo <= 1), -- Ví dụ: 0.1, 0.4, 0.5
    MoTa NVARCHAR(100), -- Ghi chú thêm (VD: Thi trắc nghiệm)
    FOREIGN KEY (MaMonHoc) REFERENCES MON_HOC(MaMonHoc),
    FOREIGN KEY (MaLoaiDiem) REFERENCES LOAI_DIEM(MaLoaiDiem)
);

-- 11. Bảng Đăng Ký Học: Sinh viên đăng ký lớp học phần nào
CREATE TABLE DANG_KY_HOC (
    MaDangKy INT IDENTITY(1,1) PRIMARY KEY,
    MaSinhVien VARCHAR(20) NOT NULL,
    MaLopHocPhan VARCHAR(20) NOT NULL,
    NgayDangKy DATETIME DEFAULT GETDATE(),
    DiemTongKet FLOAT, -- Điểm tổng kết hệ 10 (tự động tính hoặc nhập sau)
    DiemChu VARCHAR(2), -- A, B+, C...
    DiemSo FLOAT, -- Quy đổi hệ 4 (4.0, 3.5...)
    KetQua BIT, -- 1: Qua môn, 0: Trượt
    FOREIGN KEY (MaSinhVien) REFERENCES SINH_VIEN(MaSinhVien),
    FOREIGN KEY (MaLopHocPhan) REFERENCES LOP_HOC_PHAN(MaLopHocPhan),
    CONSTRAINT UQ_SinhVien_Lop UNIQUE (MaSinhVien, MaLopHocPhan) -- Một SV chỉ đăng ký 1 lần cho 1 lớp
);

-- 12. Bảng Chi Tiết Điểm: Lưu điểm thực tế của sinh viên
CREATE TABLE CHI_TIET_DIEM (
    MaChiTiet INT IDENTITY(1,1) PRIMARY KEY,
    MaDangKy INT NOT NULL, -- Liên kết với bảng Đăng Ký Học
    MaLoaiDiem VARCHAR(10) NOT NULL,
    SoDiem DECIMAL(4, 2) CHECK (SoDiem >= 0 AND SoDiem <= 10), -- Lưu số lẻ (VD: 8.5)
    NgayNhap DATETIME DEFAULT GETDATE(),
    NguoiNhap VARCHAR(20), -- Mã giảng viên nhập
    FOREIGN KEY (MaDangKy) REFERENCES DANG_KY_HOC(MaDangKy),
    FOREIGN KEY (MaLoaiDiem) REFERENCES LOAI_DIEM(MaLoaiDiem)
);

-- 13. Bảng User: Quản lý thông tin đăng nhập và phân quyền
CREATE TABLE USER (
    MaUser INT IDENTITY(1,1) PRIMARY KEY, -- ID tự tăng
    TenDangNhap VARCHAR(50) NOT NULL UNIQUE, -- Tên đăng nhập (username)
    MatKhau VARCHAR(255) NOT NULL, -- Mật khẩu (hashed)
    HoTen NVARCHAR(100) NOT NULL, -- Họ và tên người dùng
    Email VARCHAR(100) UNIQUE, -- Email (nếu cần)
    SoDienThoai VARCHAR(15), -- Số điện thoại
    VaiTro NVARCHAR(50) NOT NULL, -- Vai trò (Admin, Giảng viên, Sinh viên, ...)
    TrangThai BIT DEFAULT 1, -- 1: Hoạt động, 0: Bị khóa
    NgayTao DATETIME DEFAULT GETDATE(), -- Ngày tạo tài khoản
    NgayCapNhat DATETIME DEFAULT GETDATE() -- Ngày cập nhật cuối
);