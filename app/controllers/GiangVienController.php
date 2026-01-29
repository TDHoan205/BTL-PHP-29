<?php
/**
 * GiangVienController - Quản lý Giảng viên
 */
require_once __DIR__ . '/../core/Controller.php';

class GiangVienController extends Controller {
    private $gvModel;
    private $khoaModel;

    public function __construct() {
        parent::__construct();
        $this->gvModel = $this->model('GiangVienModel');
        $this->khoaModel = $this->model('KhoaModel');
    }

    /**
     * Hiển thị danh sách giảng viên
     */
    public function index() {
        $data = $this->buildIndexData();
        require_once __DIR__ . '/../views/admin/giangvien/index.php';
    }

    /**
     * Xây dựng dữ liệu cho trang index
     */
    private function buildIndexData($error = '', $success = '') {
        return [
            'giangviens' => $this->gvModel->readAll(),
            'khoas' => $this->khoaModel->readAll(),
            'pageTitle' => 'Quản lý Giảng viên',
            'breadcrumb' => 'Giảng viên',
            'error' => $error,
            'success' => $success
        ];
    }

    /**
     * Thêm mới giảng viên
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('GiangVien/index');
        }

        $input = [
            'MaGiangVien' => $this->getPost('MaGiangVien'),
            'HoTen' => $this->getPost('HoTen'),
            'NgaySinh' => $this->getPost('NgaySinh'),
            'GioiTinh' => $this->getPost('GioiTinh'),
            'Email' => $this->getPost('Email'),
            'SoDienThoai' => $this->getPost('SoDienThoai'),
            'HocVi' => $this->getPost('HocVi'),
            'MaKhoa' => $this->getPost('MaKhoa'),
        ];

        // Validate
        $errors = $this->validate($input, [
            'MaGiangVien' => 'required|max:20',
            'HoTen' => 'required|max:100',
            'Email' => 'email',
            'SoDienThoai' => 'phone'
        ]);

        // Validate ngày sinh
        if (!empty($input['NgaySinh'])) {
            $dobError = $this->validateDob($input['NgaySinh'], 22);
            if ($dobError) $errors['NgaySinh'] = $dobError;
        }

        if (!empty($errors)) {
            $data = $this->buildIndexData(implode(' ', $errors));
            require_once __DIR__ . '/../views/admin/giangvien/index.php';
            return;
        }

        // Gán dữ liệu vào model
        $this->gvModel->MaGiangVien = $input['MaGiangVien'];
        $this->gvModel->HoTen = $input['HoTen'];
        $this->gvModel->NgaySinh = $input['NgaySinh'] ?: null;
        $this->gvModel->GioiTinh = $input['GioiTinh'] ?: null;
        $this->gvModel->Email = $input['Email'] ?: null;
        $this->gvModel->SoDienThoai = $input['SoDienThoai'] ?: null;
        $this->gvModel->HocVi = $input['HocVi'] ?: null;
        $this->gvModel->MaKhoa = $input['MaKhoa'] ?: null;
        $this->gvModel->TrangThai = 'Đang làm việc';

        $result = $this->gvModel->create();
        if ($result === true) {
            $this->redirect('GiangVien/index');
        }

        $data = $this->buildIndexData($result);
        require_once __DIR__ . '/../views/admin/giangvien/index.php';
    }

    /**
     * Hiển thị form sửa giảng viên
     */
    public function edit($id) {
        $gv = $this->gvModel->getById($id);
        if (!$gv) {
            $this->redirect('GiangVien/index');
        }

        $data = [
            'giangvien' => $gv,
            'khoas' => $this->khoaModel->readAll(),
            'pageTitle' => 'Sửa giảng viên',
            'breadcrumb' => 'Sửa giảng viên',
            'error' => ''
        ];
        require_once __DIR__ . '/../views/admin/giangvien/edit.php';
    }

    /**
     * Cập nhật giảng viên
     */
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('GiangVien/index');
        }

        $input = [
            'HoTen' => $this->getPost('HoTen'),
            'NgaySinh' => $this->getPost('NgaySinh'),
            'GioiTinh' => $this->getPost('GioiTinh'),
            'Email' => $this->getPost('Email'),
            'SoDienThoai' => $this->getPost('SoDienThoai'),
            'HocVi' => $this->getPost('HocVi'),
            'MaKhoa' => $this->getPost('MaKhoa'),
            'TrangThai' => $this->getPost('TrangThai'),
        ];

        // Validate
        $errors = $this->validate($input, [
            'HoTen' => 'required|max:100',
            'Email' => 'email',
            'SoDienThoai' => 'phone'
        ]);

        if (!empty($input['NgaySinh'])) {
            $dobError = $this->validateDob($input['NgaySinh'], 22);
            if ($dobError) $errors['NgaySinh'] = $dobError;
        }

        if (!empty($errors)) {
            $data = [
                'giangvien' => array_merge(['MaGiangVien' => $id], $input),
                'khoas' => $this->khoaModel->readAll(),
                'pageTitle' => 'Sửa giảng viên',
                'breadcrumb' => 'Sửa giảng viên',
                'error' => implode(' ', $errors)
            ];
            require_once __DIR__ . '/../views/admin/giangvien/edit.php';
            return;
        }

        // Gán dữ liệu vào model
        $this->gvModel->MaGiangVien = $id;
        $this->gvModel->HoTen = $input['HoTen'];
        $this->gvModel->NgaySinh = $input['NgaySinh'] ?: null;
        $this->gvModel->GioiTinh = $input['GioiTinh'] ?: null;
        $this->gvModel->Email = $input['Email'] ?: null;
        $this->gvModel->SoDienThoai = $input['SoDienThoai'] ?: null;
        $this->gvModel->HocVi = $input['HocVi'] ?: null;
        $this->gvModel->MaKhoa = $input['MaKhoa'] ?: null;
        $this->gvModel->TrangThai = $input['TrangThai'] ?: 'Đang làm việc';

        $result = $this->gvModel->update();
        if ($result === true) {
            $this->redirect('GiangVien/index');
        }

        $data = [
            'giangvien' => array_merge(['MaGiangVien' => $id], $input),
            'khoas' => $this->khoaModel->readAll(),
            'pageTitle' => 'Sửa giảng viên',
            'breadcrumb' => 'Sửa giảng viên',
            'error' => $result
        ];
        require_once __DIR__ . '/../views/admin/giangvien/edit.php';
    }

    /**
     * Xóa giảng viên
     */
    public function delete($id) {
        $this->gvModel->MaGiangVien = $id;
        $result = $this->gvModel->delete();
        
        if ($result !== true) {
            // Có lỗi khi xóa, quay lại với thông báo lỗi
            $data = $this->buildIndexData($result);
            require_once __DIR__ . '/../views/admin/giangvien/index.php';
            return;
        }
        
        $this->redirect('GiangVien/index');
    }
}