<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h4><i class="fas fa-clipboard-list me-2"></i>Quản lý Đăng ký học</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-2"></i>Thêm đăng ký
    </button>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="Tìm theo MSSV..." id="searchInput">
    </div>
    <select class="form-select" id="filterLopHP">
        <option value="">Tất cả lớp HP</option>
    </select>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-value"><?= count($data['dangkyhocs'] ?? []) ?></div>
            <div class="stat-label">Tổng đăng ký</div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách Đăng ký học</h5>
        <span class="badge bg-primary"><?= count($data['dangkyhocs'] ?? []) ?> đăng ký</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Mã ĐK</th>
                        <th>MSSV</th>
                        <th>Lớp HP</th>
                        <th>Ngày đăng ký</th>
                        <th>Điểm TK</th>
                        <th>Điểm chữ</th>
                        <th>Kết quả</th>
                        <th width="120" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['dangkyhocs'])): ?>
                        <?php foreach($data['dangkyhocs'] as $dk): ?>
                        <tr>
                            <td><strong><?= $dk['MaDangKy'] ?></strong></td>
                            <td><?= $dk['MaSinhVien'] ?></td>
                            <td><span class="badge bg-info"><?= $dk['MaLopHocPhan'] ?></span></td>
                            <td><?= isset($dk['NgayDangKy']) ? date('d/m/Y', strtotime($dk['NgayDangKy'])) : '' ?></td>
                            <td><strong><?= $dk['DiemTongKet'] ?? '-' ?></strong></td>
                            <td><?= $dk['DiemChu'] ?? '-' ?></td>
                            <td>
                                <?php if(($dk['KetQua'] ?? '') == 'Đạt'): ?>
                                    <span class="grade-pass">Đạt</span>
                                <?php elseif(isset($dk['KetQua']) && $dk['KetQua']): ?>
                                    <span class="grade-fail">Không đạt</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="index.php?url=DangKyHoc/edit/<?= $dk['MaDangKy'] ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?url=DangKyHoc/delete/<?= $dk['MaDangKy'] ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list"></i>
                                    <h5>Chưa có đăng ký nào</h5>
                                    <p>Bấm nút "Thêm đăng ký" để tạo mới</p>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Thêm Đăng ký học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?url=DangKyHoc/store" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã đăng ký <span class="text-danger">*</span></label>
                        <input type="text" name="MaDangKy" class="form-control" placeholder="VD: DK001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">MSSV <span class="text-danger">*</span></label>
                        <input type="text" name="MaSinhVien" class="form-control" placeholder="Nhập mã sinh viên" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lớp học phần <span class="text-danger">*</span></label>
                        <input type="text" name="MaLopHocPhan" class="form-control" placeholder="Mã lớp học phần" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Đăng ký</button>
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
