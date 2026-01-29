<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h4><i class="fas fa-graduation-cap me-2"></i>Quản lý Ngành</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-2"></i>Thêm ngành
    </button>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput">
    </div>
    <select class="form-select" id="filterKhoa">
        <option value="">Tất cả khoa</option>
    </select>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách Ngành</h5>
        <span class="badge bg-primary"><?= count($data['nganhs'] ?? []) ?> ngành</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Mã Ngành</th>
                        <th>Tên Ngành</th>
                        <th>Khoa</th>
                        <th width="120" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['nganhs'])): ?>
                        <?php foreach($data['nganhs'] as $nganh): ?>
                        <tr>
                            <td><strong><?= $nganh['MaNganh'] ?></strong></td>
                            <td><?= $nganh['TenNganh'] ?></td>
                            <td><span class="badge bg-info-light"><?= $nganh['MaKhoa'] ?? '' ?></span></td>
                            <td class="text-center">
                                <a href="index.php?url=Nganh/edit/<?= $nganh['MaNganh'] ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?url=Nganh/delete/<?= $nganh['MaNganh'] ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-graduation-cap"></i>
                                    <h5>Chưa có dữ liệu</h5>
                                    <p>Bấm nút "Thêm ngành" để tạo mới</p>
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
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Thêm Ngành Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?url=Nganh/store" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã Ngành <span class="text-danger">*</span></label>
                        <input type="text" name="MaNganh" class="form-control" placeholder="VD: NGANH01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên Ngành <span class="text-danger">*</span></label>
                        <input type="text" name="TenNganh" class="form-control" placeholder="Nhập tên ngành" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Khoa</label>
                        <input type="text" name="MaKhoa" class="form-control" placeholder="Mã khoa">
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
