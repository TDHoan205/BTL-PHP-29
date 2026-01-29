<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h4><i class="fas fa-user-graduate me-2"></i>Quản lý Sinh viên</h4>
    <div>
        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-excel me-2"></i>Import Excel
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-2"></i>Thêm sinh viên
        </button>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="Tìm kiếm sinh viên..." id="searchInput">
    </div>
    <select class="form-select" id="filterLop">
        <option value="">Tất cả lớp</option>
        <?php if(isset($data['lops'])): ?>
            <?php foreach($data['lops'] as $lop): ?>
                <option value="<?= $lop['MaLop'] ?>"><?= $lop['TenLop'] ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>MSSV</th>
                        <th>Họ tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Lớp</th>
                        <th>Trạng thái</th>
                        <th width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data['sinhviens']) && count($data['sinhviens']) > 0): ?>
                        <?php foreach($data['sinhviens'] as $sv): ?>
                        <tr>
                            <td><strong><?= $sv['MaSinhVien'] ?></strong></td>
                            <td><?= $sv['HoTen'] ?></td>
                            <td><?= isset($sv['NgaySinh']) ? date('d/m/Y', strtotime($sv['NgaySinh'])) : '' ?></td>
                            <td><?= $sv['GioiTinh'] ?? '' ?></td>
                            <td><?= $sv['Email'] ?? '' ?></td>
                            <td><?= $sv['SoDienThoai'] ?? '' ?></td>
                            <td><span class="badge bg-primary-light"><?= $sv['MaLop'] ?? '' ?></span></td>
                            <td>
                                <?php if(isset($sv['TrangThaiHocTap']) && $sv['TrangThaiHocTap'] == 'Đang học'): ?>
                                    <span class="status-active">Đang học</span>
                                <?php else: ?>
                                    <span class="status-inactive"><?= $sv['TrangThaiHocTap'] ?? 'N/A' ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="index.php?url=SinhVien/edit/<?= $sv['MaSinhVien'] ?>" class="btn btn-sm btn-warning btn-action" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?url=SinhVien/delete/<?= $sv['MaSinhVien'] ?>" class="btn btn-sm btn-danger btn-action" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fas fa-user-graduate"></i>
                                    <h5>Chưa có sinh viên nào</h5>
                                    <p>Nhấn nút "Thêm sinh viên" để bắt đầu</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Thêm sinh viên mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?url=SinhVien/store" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã sinh viên <span class="text-danger">*</span></label>
                            <input type="text" name="MaSinhVien" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="HoTen" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="NgaySinh" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giới tính</label>
                            <select name="GioiTinh" class="form-select">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="Email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="SoDienThoai" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="DiaChi" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lớp hành chính</label>
                            <select name="MaLop" class="form-select">
                                <option value="">-- Chọn lớp --</option>
                                <?php if(isset($data['lops'])): ?>
                                    <?php foreach($data['lops'] as $lop): ?>
                                        <option value="<?= $lop['MaLop'] ?>"><?= $lop['TenLop'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái học tập</label>
                        <select name="TrangThaiHocTap" class="form-select">
                            <option value="Đang học">Đang học</option>
                            <option value="Bảo lưu">Bảo lưu</option>
                            <option value="Thôi học">Thôi học</option>
                            <option value="Tốt nghiệp">Tốt nghiệp</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-excel me-2"></i>Import từ Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?url=SinhVien/import" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p class="text-muted">Chọn file Excel (.xlsx) chứa danh sách sinh viên.</p>
                    <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-upload me-2"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#dataTable tbody tr');
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>