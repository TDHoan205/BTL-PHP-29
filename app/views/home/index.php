<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h4><i class="fas fa-tachometer-alt me-2"></i>Tổng quan Hệ thống</h4>
    <div>
        <span class="text-muted">Ngày: <?= date('d/m/Y') ?></span>
    </div>
</div>

<!-- Main Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-value"><?= $data['totalSV'] ?? 0 ?></div>
            <div class="stat-label">Sinh viên</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-value"><?= $data['totalGV'] ?? 0 ?></div>
            <div class="stat-label">Giảng viên</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-book"></i></div>
            <div class="stat-value"><?= $data['totalMH'] ?? 0 ?></div>
            <div class="stat-label">Môn học</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-layer-group"></i></div>
            <div class="stat-value"><?= $data['totalLHP'] ?? 0 ?></div>
            <div class="stat-label">Lớp học phần</div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Thống kê Kết quả</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-3 border rounded">
                            <div class="fs-3 fw-bold text-success"><?= $data['passRate'] ?? 0 ?>%</div>
                            <div class="text-muted">Tỷ lệ đậu</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded">
                            <div class="fs-3 fw-bold text-info"><?= $data['avgScore'] ?? 0 ?></div>
                            <div class="text-muted">Điểm TB</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded">
                            <div class="fs-3 fw-bold text-warning"><?= $data['pending'] ?? 0 ?></div>
                            <div class="text-muted">Chờ duyệt</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Học kỳ hiện tại</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-3">
                    <div class="fs-4 fw-bold text-primary"><?= $data['currentHocKy'] ?? 'Học kỳ 2' ?></div>
                    <div class="text-muted"><?= $data['currentNamHoc'] ?? 'Năm học 2024-2025' ?></div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Lớp HP mở</small>
                            <div class="fw-bold"><?= $data['activeLHP'] ?? 0 ?></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">SV đăng ký</small>
                            <div class="fw-bold"><?= $data['totalDangKy'] ?? 0 ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activities -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Truy cập nhanh</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="index.php?url=SinhVien" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-user-graduate mb-2 d-block fs-4"></i>
                            Sinh viên
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="index.php?url=GiangVien" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-chalkboard-teacher mb-2 d-block fs-4"></i>
                            Giảng viên
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="index.php?url=Diem" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-star mb-2 d-block fs-4"></i>
                            Quản lý điểm
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="index.php?url=LopHocPhan" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-layer-group mb-2 d-block fs-4"></i>
                            Lớp học phần
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Hoạt động gần đây</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if(!empty($data['recentActivities'])): ?>
                        <?php foreach(array_slice($data['recentActivities'], 0, 5) as $activity): ?>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-circle text-primary me-2" style="font-size: 8px;"></i>
                            <div>
                                <div><?= $activity['description'] ?? '' ?></div>
                                <small class="text-muted"><?= $activity['time'] ?? '' ?></small>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center text-muted py-4">
                            <i class="fas fa-inbox fs-3 mb-2 d-block"></i>
                            Chưa có hoạt động nào
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>