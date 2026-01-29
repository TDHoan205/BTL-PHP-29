<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h4><i class="fas fa-users-cog me-2"></i>Quản lý Tài Khoản</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-2"></i>Thêm tài khoản
    </button>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <select class="form-select" id="filterRole">
        <option value="">Tất cả vai trò</option>
        <option value="admin">Admin</option>
        <option value="teacher">Giảng viên</option>
        <option value="student">Sinh viên</option>
    </select>
    <select class="form-select" id="filterStatus">
        <option value="">Tất cả trạng thái</option>
        <option value="active">Hoạt động</option>
        <option value="inactive">Đã khóa</option>
    </select>
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput">
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-value"><?= $data['totalUsers'] ?? count($data['users'] ?? []) ?></div>
            <div class="stat-label">Tổng tài khoản</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-user-shield"></i></div>
            <div class="stat-value"><?= $data['totalAdmin'] ?? 0 ?></div>
            <div class="stat-label">Quản trị viên</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-value"><?= $data['totalTeacher'] ?? 0 ?></div>
            <div class="stat-label">Giảng viên</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-value"><?= $data['totalStudent'] ?? 0 ?></div>
            <div class="stat-label">Sinh viên</div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách Tài Khoản</h5>
        <span class="badge bg-primary"><?= count($data['users'] ?? []) ?> tài khoản</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Đăng nhập cuối</th>
                        <th width="130" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['users'])): ?>
                        <?php foreach($data['users'] as $u): ?>
                        <tr>
                            <td><?= $u['ID'] ?? $u['MaUser'] ?? '' ?></td>
                            <td><strong><?= $u['Username'] ?? $u['TenDangNhap'] ?? '' ?></strong></td>
                            <td><?= $u['HoTen'] ?? '' ?></td>
                            <td><?= $u['Email'] ?? '' ?></td>
                            <td>
                                <?php 
                                $role = strtolower($u['Role'] ?? $u['VaiTro'] ?? 'student');
                                if($role == 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php elseif($role == 'teacher'): ?>
                                    <span class="badge bg-success">Giảng viên</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Sinh viên</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(($u['TrangThai'] ?? 1) == 1): ?>
                                    <span class="status-active">Hoạt động</span>
                                <?php else: ?>
                                    <span class="status-inactive">Đã khóa</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $u['LastLogin'] ?? '-' ?></td>
                            <td class="text-center">
                                <a href="index.php?url=User/edit/<?= $u['ID'] ?? $u['MaUser'] ?? '' ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if(strtolower($u['Role'] ?? '') != 'admin'): ?>
                                <a href="index.php?url=User/delete/<?= $u['ID'] ?? $u['MaUser'] ?? '' ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h5>Chưa có tài khoản nào</h5>
                                    <p>Bấm nút "Thêm tài khoản" để tạo mới</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Thêm Tài Khoản Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?url=User/store" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" name="Username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="Password" class="form-control" placeholder="Nhập mật khẩu" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" name="HoTen" class="form-control" placeholder="Nhập họ tên">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="Email" class="form-control" placeholder="Nhập email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select name="Role" class="form-select" required>
                                    <option value="student">Sinh viên</option>
                                    <option value="teacher">Giảng viên</option>
                                    <option value="admin">Quản trị viên</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Liên kết với</label>
                                <input type="text" name="LinkID" class="form-control" placeholder="MSSV hoặc Mã GV (nếu có)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Tạo tài khoản</button>
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

document.getElementById('filterRole').addEventListener('change', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#dataTable tbody tr');
    rows.forEach(row => {
        let role = row.querySelector('.badge')?.textContent.toLowerCase() || '';
        if(filter === '' || role.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>