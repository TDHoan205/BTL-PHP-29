<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h4><i class="fas fa-book me-2"></i>Quản lý Môn học</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-2"></i>Thêm môn học
    </button>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="Tìm kiếm môn học..." id="searchInput">
    </div>
    <select class="form-select" id="filterNganh">
        <option value="">Tất cả ngành</option>
    </select>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách Môn học</h5>
        <span class="badge bg-primary"><?= count($data['monhocs'] ?? []) ?> môn</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Mã môn</th>
                        <th>Tên môn học</th>
                        <th>Số tín chỉ</th>
                        <th>LT</th>
                        <th>TH</th>
                        <th>Ngành</th>
                        <th width="120" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['monhocs'])): ?>
                        <?php foreach($data['monhocs'] as $mh): ?>
                        <tr>
                            <td><strong><?= $mh['MaMonHoc'] ?></strong></td>
                            <td><?= $mh['TenMonHoc'] ?? $mh['TenMon'] ?? '' ?></td>
                            <td><span class="badge bg-info"><?= $mh['SoTinChi'] ?? 0 ?> TC</span></td>
                            <td><?= $mh['SoTietLyThuyet'] ?? 0 ?></td>
                            <td><?= $mh['SoTietThucHanh'] ?? 0 ?></td>
                            <td><?= $mh['MaNganh'] ?? '' ?></td>
                            <td class="text-center">
                                <a href="index.php?url=MonHoc/edit/<?= $mh['MaMonHoc'] ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?url=MonHoc/delete/<?= $mh['MaMonHoc'] ?>" 
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
                                    <i class="fas fa-book"></i>
                                    <h5>Chưa có môn học nào</h5>
                                    <p>Bấm nút "Thêm môn học" để tạo mới</p>
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
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Thêm Môn học Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?url=MonHoc/store" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã môn <span class="text-danger">*</span></label>
                        <input type="text" name="MaMonHoc" class="form-control" placeholder="VD: MH001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên môn học <span class="text-danger">*</span></label>
                        <input type="text" name="TenMonHoc" class="form-control" placeholder="Nhập tên môn học" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Số tín chỉ</label>
                                <input type="number" name="SoTinChi" class="form-control" value="3" min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tiết LT</label>
                                <input type="number" name="SoTietLyThuyet" class="form-control" value="30" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tiết TH</label>
                                <input type="number" name="SoTietThucHanh" class="form-control" value="15" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngành</label>
                        <input type="text" name="MaNganh" class="form-control" placeholder="Mã ngành">
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
