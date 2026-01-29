<?php
/**
 * SinhVienController - Quản lý Sinh viên
 */
require_once __DIR__ . '/../core/Controller.php';

class SinhVienController extends Controller {
    private $svModel;
    private $lopModel;

    public function __construct() {
        parent::__construct();
        $this->svModel = $this->model('SinhVienModel');
        $this->lopModel = $this->model('LopHanhChinhModel');
    }

    private function buildIndexData($error = '', $success = '') {
        return [
            'sinhviens' => $this->svModel->readAll(),
            'lops' => $this->lopModel->readAll(),
            'pageTitle' => 'Quản lý Sinh viên',
            'breadcrumb' => 'Sinh viên',
            'error' => $error,
            'success' => $success
        ];
    }

    public function index() {
        $data = $this->buildIndexData();
        require_once __DIR__ . '/../views/admin/sinhvien/index.php';
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect('SinhVien/index');
        }

        $input = [
            'MaSinhVien' => $this->getPost('MaSinhVien'),
            'HoTen' => $this->getPost('HoTen'),
            'NgaySinh' => $this->getPost('NgaySinh'),
            'GioiTinh' => $this->getPost('GioiTinh'),
            'DiaChi' => $this->getPost('DiaChi'),
            'Email' => $this->getPost('Email'),
            'SoDienThoai' => $this->getPost('SoDienThoai'),
            'MaLop' => $this->getPost('MaLop'),
            'TrangThaiHocTap' => $this->getPost('TrangThaiHocTap') ?: 'Đang học',
        ];

        // Validate
        $errors = $this->validate($input, [
            'MaSinhVien' => 'required|max:20',
            'HoTen' => 'required|max:100',
            'MaLop' => 'required',
            'Email' => 'email',
            'SoDienThoai' => 'phone'
        ]);

        // Validate ngày sinh
        if (!empty($input['NgaySinh'])) {
            $dobError = $this->validateDob($input['NgaySinh'], 16);
            if ($dobError) $errors['NgaySinh'] = $dobError;
        }

        if (!empty($errors)) {
            $data = $this->buildIndexData(implode(' ', $errors));
            require_once __DIR__ . '/../views/admin/sinhvien/index.php';
            return;
        }

        // Gán dữ liệu vào model
        $this->svModel->MaSinhVien = $input['MaSinhVien'];
        $this->svModel->HoTen = $input['HoTen'];
        $this->svModel->NgaySinh = $input['NgaySinh'] ?: null;
        $this->svModel->GioiTinh = $input['GioiTinh'] ?: null;
        $this->svModel->DiaChi = $input['DiaChi'] ?: null;
        $this->svModel->Email = $input['Email'] ?: null;
        $this->svModel->SoDienThoai = $input['SoDienThoai'] ?: null;
        $this->svModel->MaLop = $input['MaLop'];
        $this->svModel->TrangThaiHocTap = $input['TrangThaiHocTap'];

        $result = $this->svModel->create();
        if ($result === true) {
            $this->redirect('SinhVien/index');
        }

        $data = $this->buildIndexData($result);
        require_once __DIR__ . '/../views/admin/sinhvien/index.php';
    }

    public function edit($id) {
        $sv = $this->svModel->getById($id);
        if (!$sv) {
            $this->redirect('SinhVien/index');
        }

        $data = [
            'sinhvien' => $sv,
            'lops' => $this->lopModel->readAll(),
            'pageTitle' => 'Sửa sinh viên',
            'breadcrumb' => 'Sửa sinh viên',
            'error' => ''
        ];
        require_once __DIR__ . '/../views/admin/sinhvien/edit.php';
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('SinhVien/index');
        }

        $input = [
            'HoTen' => $this->getPost('HoTen'),
            'NgaySinh' => $this->getPost('NgaySinh'),
            'GioiTinh' => $this->getPost('GioiTinh'),
            'DiaChi' => $this->getPost('DiaChi'),
            'Email' => $this->getPost('Email'),
            'SoDienThoai' => $this->getPost('SoDienThoai'),
            'MaLop' => $this->getPost('MaLop'),
            'TrangThaiHocTap' => $this->getPost('TrangThaiHocTap') ?: 'Đang học',
        ];

        // Validate
        $errors = $this->validate($input, [
            'HoTen' => 'required|max:100',
            'MaLop' => 'required',
            'Email' => 'email',
            'SoDienThoai' => 'phone'
        ]);

        if (!empty($input['NgaySinh'])) {
            $dobError = $this->validateDob($input['NgaySinh'], 16);
            if ($dobError) $errors['NgaySinh'] = $dobError;
        }

        if (!empty($errors)) {
            $data = [
                'sinhvien' => array_merge(['MaSinhVien' => $id], $input),
                'lops' => $this->lopModel->readAll(),
                'pageTitle' => 'Sửa sinh viên',
                'breadcrumb' => 'Sửa sinh viên',
                'error' => implode(' ', $errors)
            ];
            require_once __DIR__ . '/../views/admin/sinhvien/edit.php';
            return;
        }

        // Gán dữ liệu vào model
        $this->svModel->MaSinhVien = $id;
        $this->svModel->HoTen = $input['HoTen'];
        $this->svModel->NgaySinh = $input['NgaySinh'] ?: null;
        $this->svModel->GioiTinh = $input['GioiTinh'] ?: null;
        $this->svModel->DiaChi = $input['DiaChi'] ?: null;
        $this->svModel->Email = $input['Email'] ?: null;
        $this->svModel->SoDienThoai = $input['SoDienThoai'] ?: null;
        $this->svModel->MaLop = $input['MaLop'];
        $this->svModel->TrangThaiHocTap = $input['TrangThaiHocTap'];

        $result = $this->svModel->update();
        if ($result === true) {
            $this->redirect('SinhVien/index');
        }

        $data = [
            'sinhvien' => array_merge(['MaSinhVien' => $id], $input),
            'lops' => $this->lopModel->readAll(),
            'pageTitle' => 'Sửa sinh viên',
            'breadcrumb' => 'Sửa sinh viên',
            'error' => $result
        ];
        require_once __DIR__ . '/../views/admin/sinhvien/edit.php';
    }

    public function delete($id) {
        $this->svModel->MaSinhVien = $id;
        $result = $this->svModel->delete();
        
        if ($result !== true) {
            $data = $this->buildIndexData($result);
            require_once __DIR__ . '/../views/admin/sinhvien/index.php';
            return;
        }
        
        $this->redirect('SinhVien/index');
    }
}