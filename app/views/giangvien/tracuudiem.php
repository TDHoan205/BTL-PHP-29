<?php
// Biến từ controller: $giangVien, $lopHocPhanList, $lopHocPhanSelected, $sinhVienDiem, $thongKe, $loaiDiemList
$giangVienTen = $giangVien['HoTen'] ?? 'Giảng viên';
$giangVienMa = $giangVien['MaGiangVien'] ?? '';
$lopHocPhanList = $lopHocPhanList ?? [];
$lopHocPhanSelected = $lopHocPhanSelected ?? null;
$sinhVienDiem = $sinhVienDiem ?? [];
$thongKe = $thongKe ?? ['tong' => 0, 'dau' => 0, 'rot' => 0, 'chuaCoDiem' => 0];
$loaiDiemList = $loaiDiemList ?? [];
$baseUrl = defined('URLROOT') ? URLROOT : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra Cứu Điểm - Cổng Giảng Viên</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f6fa; color: #222; }
        .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .sidebar { background: #fff; border-right: 1px solid #e3e6f0; padding: 18px 16px; display: flex; flex-direction: column; }
        .sidebar__brand { display: flex; align-items: center; margin-bottom: 24px; }
        .sidebar__logo { width: 34px; height: 34px; border-radius: 6px; background: linear-gradient(135deg, #007bff, #00b894); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; margin-right: 10px; }
        .sidebar__title { font-size: 18px; font-weight: 700; color: #0062cc; }
        .sidebar__subtitle { font-size: 11px; color: #6c757d; }
        .sidebar__nav { margin-top: 8px; flex: 1; overflow-y: auto; }
        .nav-section-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #a0aec0; margin: 16px 0 8px; }
        .nav-item { display: flex; align-items: center; padding: 9px 10px; border-radius: 6px; font-size: 14px; color: #4a5568; cursor: pointer; transition: background 0.2s, color 0.2s; text-decoration: none; }
        .nav-item:hover { background: #edf2ff; color: #2b6cb0; }
        .nav-item--active { background: #2b6cb0; color: white; }
        .nav-item__icon { width: 20px; margin-right: 8px; text-align: center; }
        .main { display: flex; flex-direction: column; }
        .topbar { height: 60px; background: #fff; border-bottom: 1px solid #e3e6f0; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; }
        .topbar__title { font-size: 18px; font-weight: 600; color: #2d3748; }
        .topbar__breadcrumb { font-size: 12px; color: #a0aec0; white-space: nowrap; }
        .topbar__right { display: flex; align-items: center; gap: 18px; }
        .user-info { display: flex; align-items: center; gap: 8px; }
        .user-avatar { width: 34px; height: 34px; border-radius: 50%; background: #2b6cb0; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; }
        .user-meta { display: flex; flex-direction: column; }
        .user-meta__name { font-size: 13px; font-weight: 600; }
        .user-meta__id { font-size: 11px; color: #a0aec0; }
        .content { padding: 18px 24px 26px; }
        .content-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        .content-header__title { font-size: 17px; font-weight: 600; }
        .select { padding: 7px 9px; border-radius: 6px; border: 1px solid #cbd5e0; font-size: 13px; min-width: 250px; }
        .btn { padding: 8px 16px; border-radius: 6px; border: none; font-size: 13px; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #2b6cb0; color: #fff; }
        .btn-primary:hover { background: #2c5282; }
        .btn-success { background: #38a169; color: #fff; }
        .btn-success:hover { background: #2f855a; }
        .btn-outline { background: #fff; color: #4c51bf; border: 1px solid #4c51bf; }
        .btn-outline:hover { background: #4c51bf; color: #fff; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 1px 3px rgba(15,23,42,0.08); padding: 20px; margin-bottom: 20px; }
        .card__title { font-size: 15px; font-weight: 600; margin-bottom: 15px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 20px; border-radius: 10px; }
        .stat-card.success { background: linear-gradient(135deg, #38a169 0%, #2f855a 100%); }
        .stat-card.danger { background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); }
        .stat-card.warning { background: linear-gradient(135deg, #d69e2e 0%, #b7791f 100%); }
        .stat-card__label { font-size: 12px; opacity: 0.9; margin-bottom: 8px; }
        .stat-card__value { font-size: 32px; font-weight: 700; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
        .filter-group { display: flex; align-items: center; gap: 8px; }
        .filter-group label { font-size: 13px; color: #4a5568; }
        .input-small { padding: 6px 8px; border-radius: 4px; border: 1px solid #cbd5e0; font-size: 13px; width: 120px; }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead { background: #f7fafc; }
        th, td { padding: 10px; border-bottom: 1px solid #edf2f7; text-align: left; }
        th { font-weight: 600; color: #4a5568; font-size: 12px; }
        tbody tr:hover { background: #f0f5ff; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #c6f6d5; color: #22543d; }
        .badge-danger { background: #fed7d7; color: #742a2a; }
        .badge-warning { background: #feebc8; color: #744210; }
        .empty-state { padding: 40px; text-align: center; color: #a0aec0; }
        .text-center { text-align: center; }
        @media (max-width: 768px) { .layout { grid-template-columns: 1fr; } .sidebar { display: none; } }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="sidebar__brand">
            <div class="sidebar__logo">GV</div>
            <div>
                <div class="sidebar__title">Cổng Giảng Viên</div>
                <div class="sidebar__subtitle">Quản lý giảng dạy & lớp học</div>
            </div>
        </div>
        <nav class="sidebar__nav">
            <div class="nav-section-title">Tổng quan</div>
            <a href="<?php echo $baseUrl; ?>/GiangVien/dashboard" class="nav-item"><div class="nav-item__icon">🏠</div><div>Bảng điều khiển</div></a>
            <div class="nav-section-title">Giảng dạy</div>
            <a href="<?php echo $baseUrl; ?>/GiangVien/dashboard" class="nav-item"><div class="nav-item__icon">📚</div><div>Lớp & môn được dạy</div></a>
            <div class="nav-section-title">Khác</div>
            <a href="<?php echo $baseUrl; ?>/GiangVien/nhapDiem" class="nav-item"><div class="nav-item__icon">📝</div><div>Nhập điểm</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/traCuuDiem" class="nav-item nav-item--active"><div class="nav-item__icon">🔍</div><div>Tra cứu điểm</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/guiThongBao" class="nav-item"><div class="nav-item__icon">📧</div><div>Gửi thông báo</div></a>
            <div class="nav-item"><div class="nav-item__icon">📆</div><div>Lịch giảng dạy</div></div>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="topbar__title">Tra Cứu & Theo Dõi Điểm</div>
                <div class="topbar__breadcrumb">Giảng dạy / Tra cứu điểm</div>
            </div>
            <div class="topbar__right">
                <div class="user-info">
                    <div class="user-avatar"><?php echo strtoupper(mb_substr($giangVienTen, 0, 1, 'UTF-8')); ?></div>
                    <div class="user-meta">
                        <div class="user-meta__name"><?php echo htmlspecialchars($giangVienTen); ?></div>
                        <div class="user-meta__id"><?php echo htmlspecialchars($giangVienMa); ?></div>
                    </div>
                </div>
            </div>
        </header>

        <main class="content">
            <div class="content-header">
                <div class="content-header__title">Chọn lớp học phần để tra cứu điểm</div>
                <div style="display: flex; gap: 10px;">
                    <select class="select" id="selectLopHocPhan">
                        <option value="">-- Chọn lớp học phần --</option>
                        <?php foreach ($lopHocPhanList as $lop): ?>
                            <option value="<?php echo htmlspecialchars($lop['MaLopHocPhan']); ?>" <?php echo ($lopHocPhanSelected && $lop['MaLopHocPhan'] == $lopHocPhanSelected['MaLopHocPhan']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($lop['MaLopHocPhan'] . ' - ' . ($lop['TenMonHoc'] ?? $lop['MaMonHoc'] ?? '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($lopHocPhanSelected): ?>
                        <button class="btn btn-success" onclick="xuatExcel()">📊 Xuất Excel</button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($lopHocPhanSelected): ?>
                <!-- Thống kê nhanh -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card__label">Tổng số sinh viên</div>
                        <div class="stat-card__value"><?php echo $thongKe['tong']; ?></div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-card__label">Đậu</div>
                        <div class="stat-card__value"><?php echo $thongKe['dau']; ?></div>
                    </div>
                    <div class="stat-card danger">
                        <div class="stat-card__label">Rớt</div>
                        <div class="stat-card__value"><?php echo $thongKe['rot']; ?></div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-card__label">Chưa có điểm</div>
                        <div class="stat-card__value"><?php echo $thongKe['chuaCoDiem']; ?></div>
                    </div>
                </div>

                <!-- Bộ lọc -->
                <div class="card">
                    <div class="card__title">Lọc sinh viên</div>
                    <div class="filters">
                        <div class="filter-group">
                            <label>Theo điểm:</label>
                            <select class="input-small" id="filterDiem">
                                <option value="">Tất cả</option>
                                <option value="dau">Đậu (≥4.0)</option>
                                <option value="rot">Rớt (<4.0)</option>
                                <option value="chuaCoDiem">Chưa có điểm</option>
                                <option value="tren8">Trên 8.0</option>
                                <option value="tren6">Trên 6.0</option>
                                <option value="duoi4">Dưới 4.0</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Tìm kiếm:</label>
                            <input type="text" class="input-small" id="searchInput" placeholder="Mã SV hoặc tên..." style="width: 200px;">
                        </div>
                        <button class="btn btn-outline" onclick="applyFilters()">Lọc</button>
                        <button class="btn btn-outline" onclick="resetFilters()">Reset</button>
                    </div>
                </div>

                <!-- Bảng điểm -->
                <div class="card">
                    <div class="card__title">Bảng điểm lớp học phần</div>
                    <div class="table-wrapper">
                        <table id="tableDiem">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã SV</th>
                                    <th>Họ tên</th>
                                    <th>Lớp hành chính</th>
                                    <?php foreach ($loaiDiemList as $ld): ?>
                                        <th><?php echo htmlspecialchars($ld['TenLoaiDiem']); ?></th>
                                    <?php endforeach; ?>
                                    <th>Điểm tổng</th>
                                    <th>Điểm chữ</th>
                                    <th>Kết quả</th>
                                </tr>
                            </thead>
                            <tbody id="tbodySinhVien">
                                <?php if (empty($sinhVienDiem)): ?>
                                    <tr><td colspan="<?php echo 4 + count($loaiDiemList) + 3; ?>" class="text-center empty-state">Lớp học phần này chưa có sinh viên đăng ký.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($sinhVienDiem as $idx => $sv): ?>
                                        <tr data-ma-sv="<?php echo htmlspecialchars($sv['MaSinhVien']); ?>" 
                                            data-ten="<?php echo htmlspecialchars(strtolower($sv['HoTen'])); ?>"
                                            data-diem-tong="<?php echo $sv['DiemTongKet'] ?? ''; ?>"
                                            data-ket-qua="<?php echo ($sv['DiemTongKet'] ?? 0) >= 4 ? 'dau' : (($sv['DiemTongKet'] ?? null) === null ? 'chuaCoDiem' : 'rot'); ?>">
                                            <td><?php echo $idx + 1; ?></td>
                                            <td><?php echo htmlspecialchars($sv['MaSinhVien']); ?></td>
                                            <td><?php echo htmlspecialchars($sv['HoTen']); ?></td>
                                            <td><?php echo htmlspecialchars($sv['MaLop'] ?? ''); ?></td>
                                            <?php foreach ($loaiDiemList as $ld): ?>
                                                <td class="text-center">
                                                    <?php echo isset($sv['diem'][$ld['TenLoaiDiem']]) ? number_format($sv['diem'][$ld['TenLoaiDiem']]['SoDiem'], 2) : '-'; ?>
                                                </td>
                                            <?php endforeach; ?>
                                            <td class="text-center"><strong><?php echo $sv['DiemTongKet'] ? number_format($sv['DiemTongKet'], 2) : '-'; ?></strong></td>
                                            <td class="text-center">
                                                <?php if ($sv['DiemChu']): ?>
                                                    <span class="badge badge-<?php echo ($sv['DiemTongKet'] ?? 0) >= 4 ? 'success' : 'danger'; ?>">
                                                        <?php echo htmlspecialchars($sv['DiemChu']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($sv['DiemTongKet'] === null): ?>
                                                    <span class="badge badge-warning">Chưa có điểm</span>
                                                <?php elseif ($sv['DiemTongKet'] >= 4): ?>
                                                    <span class="badge badge-success">Đậu</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Rớt</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="empty-state">Vui lòng chọn lớp học phần để xem bảng điểm.</div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
(function() {
    const selectLop = document.getElementById('selectLopHocPhan');
    const filterDiem = document.getElementById('filterDiem');
    const searchInput = document.getElementById('searchInput');
    const tbody = document.getElementById('tbodySinhVien');

    function applyFilters() {
        const filterValue = filterDiem ? filterDiem.value : '';
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const rows = tbody.querySelectorAll('tr[data-ma-sv]');

        rows.forEach(function(row) {
            let show = true;
            const maSv = row.getAttribute('data-ma-sv').toLowerCase();
            const ten = row.getAttribute('data-ten');
            const ketQua = row.getAttribute('data-ket-qua');
            const diemTong = parseFloat(row.getAttribute('data-diem-tong')) || null;

            // Lọc theo điểm
            if (filterValue) {
                if (filterValue === 'dau' && ketQua !== 'dau') show = false;
                else if (filterValue === 'rot' && ketQua !== 'rot') show = false;
                else if (filterValue === 'chuaCoDiem' && ketQua !== 'chuaCoDiem') show = false;
                else if (filterValue === 'tren8' && (diemTong === null || diemTong < 8)) show = false;
                else if (filterValue === 'tren6' && (diemTong === null || diemTong < 6)) show = false;
                else if (filterValue === 'duoi4' && (diemTong === null || diemTong >= 4)) show = false;
            }

            // Tìm kiếm
            if (show && searchValue) {
                if (!maSv.includes(searchValue) && !ten.includes(searchValue)) {
                    show = false;
                }
            }

            row.style.display = show ? '' : 'none';
        });
    }

    function resetFilters() {
        if (filterDiem) filterDiem.value = '';
        if (searchInput) searchInput.value = '';
        const rows = tbody.querySelectorAll('tr[data-ma-sv]');
        rows.forEach(function(row) { row.style.display = ''; });
    }

    window.applyFilters = applyFilters;
    window.resetFilters = resetFilters;

    function xuatExcel() {
        const maLop = selectLop ? selectLop.value : '';
        if (!maLop) {
            alert('Vui lòng chọn lớp học phần');
            return;
        }
        window.location.href = '<?php echo $baseUrl; ?>/GiangVien/xuatExcel?maLopHocPhan=' + encodeURIComponent(maLop);
    }
    window.xuatExcel = xuatExcel;

    if (selectLop) {
        selectLop.addEventListener('change', function() {
            const maLop = this.value;
            if (maLop) {
                window.location.href = '<?php echo $baseUrl; ?>/GiangVien/traCuuDiem?maLopHocPhan=' + encodeURIComponent(maLop);
            } else {
                window.location.href = '<?php echo $baseUrl; ?>/GiangVien/traCuuDiem';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') applyFilters();
        });
    }
})();
</script>
</body>
</html>
