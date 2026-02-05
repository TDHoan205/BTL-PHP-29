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
    <title>Tra Cứu Điểm - UNISCORE Giảng Viên</title>
    <link rel="icon" type="image/svg+xml" href="<?= rtrim($baseUrl ?? '', '/') ?>/favicon.svg">
    <link href="<?= rtrim($baseUrl ?? '', '/') ?>/css/giangvien.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>window.APP_BASE_URL = '<?= defined("URLROOT") ? rtrim(URLROOT, "/") : "" ?>';</script>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="sidebar__brand">
            <img src="<?= rtrim($baseUrl ?? '', '/') ?>/favicon.svg" alt="UNISCORE" class="sidebar__logo" style="width: 34px; height: 34px; border-radius: 6px;">
            <div>
                <div class="sidebar__title" style="color: #d4af37;">UNISCORE</div>
                <div class="sidebar__subtitle">Cổng Giảng Viên</div>
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
            <a href="<?php echo $baseUrl; ?>/GiangVien/lichDay" class="nav-item"><div class="nav-item__icon">📆</div><div>Lịch giảng dạy</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/diemDanh" class="nav-item"><div class="nav-item__icon">📋</div><div>Điểm danh</div></a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="topbar__title">Tra Cứu & Theo Dõi Điểm</div>
                <div class="topbar__breadcrumb">Giảng dạy / Tra cứu điểm</div>
            </div>
            <div class="topbar__right">
                <div class="topbar-dropdown" id="userDropdown">
                    <div class="topbar-dropdown__trigger user-info" id="userDropdownTrigger">
                        <div class="user-avatar"><?php echo strtoupper(mb_substr($giangVienTen, 0, 1, 'UTF-8')); ?></div>
                        <div class="user-meta">
                            <div class="user-meta__name"><?php echo htmlspecialchars($giangVienTen); ?></div>
                            <div class="user-meta__id"><?php echo htmlspecialchars($giangVienMa); ?></div>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 11px; color: #a0aec0;"></i>
                    </div>
                    <div class="topbar-dropdown__menu" role="menu">
                        <a class="topbar-dropdown__item" href="<?php echo $baseUrl; ?>/Profile/index"><i class="fas fa-user"></i> Hồ sơ</a>
                        <a class="topbar-dropdown__item" href="<?php echo $baseUrl; ?>/Profile/settings"><i class="fas fa-cog"></i> Cài đặt</a>
                        <div class="topbar-dropdown__divider"></div>
                        <a class="topbar-dropdown__item topbar-dropdown__item--danger" href="<?php echo $baseUrl; ?>/Auth/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
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

    (function() {
        var dropdown = document.getElementById('userDropdown');
        var trigger = document.getElementById('userDropdownTrigger');
        if (dropdown && trigger) {
            trigger.addEventListener('click', function(e) { e.stopPropagation(); dropdown.classList.toggle('is-open'); });
            document.addEventListener('click', function() { dropdown.classList.remove('is-open'); });
            dropdown.querySelector('.topbar-dropdown__menu') && dropdown.querySelector('.topbar-dropdown__menu').addEventListener('click', function(e) { e.stopPropagation(); });
        }
    })();
})();
</script>
</body>
</html>
