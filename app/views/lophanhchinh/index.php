<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h4><i class="fas fa-users me-2"></i>Quản lý Lớp Hành Chính</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-2"></i>Thêm lớp
    </button>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput">
    </div>
    <select class="form-select" id="filterNganh">
        <option value="">Tất cả ngành</option>
    </select>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách Lớp Hành Chính</h5>
        <span class="badge bg-primary"><?= count($data['lophanhchinhs'] ?? []) ?> lớp</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Mã Lớp</th>
                        <th>Tên Lớp</th>
                        <th>Ngành</th>
                        <th>Khóa học</th>
                        <th>Cố vấn</th>
                        <th>Sĩ số</th>
                        <th width="120" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['lophanhchinhs'])): ?>
                        <?php foreach($data['lophanhchinhs'] as $lop): ?>
                        <tr>
                            <td><strong><?= $lop['MaLop'] ?></strong></td>
                            <td><?= $lop['TenLop'] ?></td>
                            <td><span class="badge bg-info-light"><?= $lop['MaNganh'] ?? '' ?></span></td>
                            <td><?= $lop['KhoaHoc'] ?? '' ?></td>
                            <td><?= $lop['MaCoVan'] ?? '' ?></td>
                            <td><span class="badge bg-success"><?= $lop['SiSo'] ?? 0 ?> SV</span></td>
                            <td class="text-center">
                                <a href="index.php?url=LopHanhChinh/edit/<?= $lop['MaLop'] ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?url=LopHanhChinh/delete/<?= $lop['MaLop'] ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h5>Chưa có lớp nào</h5>
                                    <p>Bấm nút "Thêm lớp" để tạo mới</p>
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
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Thêm Lớp Hành Chính</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?url=LopHanhChinh/store" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã Lớp <span class="text-danger">*</span></label>
                        <input type="text" name="MaLop" class="form-control" placeholder="VD: LOP01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên Lớp <span class="text-danger">*</span></label>
                        <input type="text" name="TenLop" class="form-control" placeholder="Nhập tên lớp" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngành</label>
                        <input type="text" name="MaNganh" class="form-control" placeholder="Mã ngành">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Khóa học</label>
                        <input type="text" name="KhoaHoc" class="form-control" placeholder="VD: K66">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cố vấn học tập</label>
                        <input type="text" name="MaCoVan" class="form-control" placeholder="Mã giảng viên cố vấn">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Lưu lại</button>
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
