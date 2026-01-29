<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h4><i class="fas fa-star me-2"></i>Quản lý Điểm</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
        <i class="fas fa-check me-2"></i>Phê duyệt điểm
    </button>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <select class="form-select" id="filterHocKy">
        <option value="">Chọn học kỳ</option>
        <?php if(isset($data['hockys'])): ?>
            <?php foreach($data['hockys'] as $hk): ?>
                <option value="<?= $hk['MaHocKy'] ?>"><?= $hk['TenHocKy'] ?> - <?= $hk['NamHoc'] ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <select class="form-select" id="filterLopHP">
        <option value="">Chọn lớp học phần</option>
        <?php if(isset($data['lophocphans'])): ?>
            <?php foreach($data['lophocphans'] as $lhp): ?>
                <option value="<?= $lhp['MaLopHocPhan'] ?>"><?= $lhp['TenLop'] ?? $lhp['MaLopHocPhan'] ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="Tìm sinh viên..." id="searchInput">
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-value"><?= $data['totalSV'] ?? 0 ?></div>
            <div class="stat-label">Tổng sinh viên</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value"><?= $data['passed'] ?? 0 ?></div>
            <div class="stat-label">Đậu môn</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="stat-value"><?= $data['failed'] ?? 0 ?></div>
            <div class="stat-label">Rớt môn</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
            <div class="stat-value"><?= $data['pending'] ?? 0 ?></div>
            <div class="stat-label">Chờ duyệt</div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Bảng điểm sinh viên</h5>
        <div>
            <button class="btn btn-outline-primary btn-sm me-2">
                <i class="fas fa-file-excel me-1"></i>Xuất Excel
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                <i class="fas fa-print me-1"></i>In
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <form action="index.php?url=Diem/updateAll" method="POST">
            <div class="table-responsive">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th>MSSV</th>
                            <th>Họ tên</th>
                            <th>Lớp HP</th>
                            <th width="80">Điểm QT</th>
                            <th width="80">Điểm GK</th>
                            <th width="80">Điểm CK</th>
                            <th>Điểm TB</th>
                            <th>Điểm chữ</th>
                            <th>Kết quả</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($data['bangdiem']) && count($data['bangdiem']) > 0): ?>
                            <?php foreach($data['bangdiem'] as $row): ?>
                            <tr>
                                <td><strong><?= $row['MSSV'] ?? $row['MaSinhVien'] ?? '' ?></strong></td>
                                <td><?= $row['HoTen'] ?? '' ?></td>
                                <td><?= $row['MaLopHocPhan'] ?? '' ?></td>
                                <td><input type="number" step="0.1" min="0" max="10" name="diem[<?= $row['ID'] ?? 0 ?>][qt]" value="<?= $row['DiemCC'] ?? $row['DiemQT'] ?? '' ?>" class="form-control form-control-sm text-center"></td>
                                <td><input type="number" step="0.1" min="0" max="10" name="diem[<?= $row['ID'] ?? 0 ?>][gk]" value="<?= $row['DiemGK'] ?? '' ?>" class="form-control form-control-sm text-center"></td>
                                <td><input type="number" step="0.1" min="0" max="10" name="diem[<?= $row['ID'] ?? 0 ?>][ck]" value="<?= $row['DiemCK'] ?? '' ?>" class="form-control form-control-sm text-center"></td>
                                <td class="text-center"><strong><?= $row['DiemTongKet'] ?? '-' ?></strong></td>
                                <td class="text-center"><?= $row['DiemChu'] ?? '-' ?></td>
                                <td class="text-center">
                                    <?php 
                                    $ketQua = $row['KetQua'] ?? '';
                                    if($ketQua == 'Đạt'): ?>
                                        <span class="grade-pass">Đạt</span>
                                    <?php elseif($ketQua): ?>
                                        <span class="grade-fail">Không đạt</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-pending">Chờ duyệt</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard-list"></i>
                                        <h5>Chưa có dữ liệu điểm</h5>
                                        <p>Vui lòng chọn học kỳ và lớp học phần để xem điểm</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if(isset($data['bangdiem']) && count($data['bangdiem']) > 0): ?>
            <div class="card-footer d-flex justify-content-end gap-2">
                <button type="submit" name="action" value="save" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Lưu điểm
                </button>
                <button type="submit" name="action" value="lock" class="btn btn-secondary">
                    <i class="fas fa-lock me-2"></i>Khóa điểm
                </button>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-check-double me-2"></i>Phê duyệt điểm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?url=Diem/approve" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn lớp học phần</label>
                        <select name="MaLopHocPhan" class="form-select" required>
                            <option value="">-- Chọn lớp --</option>
                            <?php if(isset($data['lophocphans'])): ?>
                                <?php foreach($data['lophocphans'] as $lhp): ?>
                                    <option value="<?= $lhp['MaLopHocPhan'] ?>"><?= $lhp['TenLop'] ?? $lhp['MaLopHocPhan'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Tất cả điểm của lớp học phần này sẽ được phê duyệt.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i>Phê duyệt</button>
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