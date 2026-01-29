<?php
// Biến từ controller: $giangVien, $hocKyList, $lopHocPhanList, $sinhVienLopHocPhan
$giangVienTen = $giangVien['HoTen'] ?? 'Giảng viên';
$giangVienMa = $giangVien['MaGiangVien'] ?? '';

$sinhVienDemo = !empty($sinhVienLopHocPhan) ? $sinhVienLopHocPhan : [
    ['MaSinhVien' => '20230001', 'HoTen' => 'Trần Đức Hoàn', 'LopHanhChinh' => 'CNTT K20A', 'Email' => 'hoan@example.com', 'SoDienThoai' => '0987654321', 'TrangThai' => 'Đang học'],
    ['MaSinhVien' => '20230022', 'HoTen' => 'Nguyễn Thị B', 'LopHanhChinh' => 'CNTT K20A', 'Email' => 'b@example.com', 'SoDienThoai' => '0912345678', 'TrangThai' => 'Đang học'],
];
$hocKyList = $hocKyList ?? [];
$lopHocPhanList = $lopHocPhanList ?? [];
$baseUrl = defined('URLROOT') ? URLROOT : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Giảng Viên - Lớp & Môn dạy</title>
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
        .nav-item__chevron { margin-left: auto; font-size: 11px; }
        .nav-children { margin-left: 10px; margin-top: 4px; }
        .nav-child { padding: 7px 10px; border-radius: 5px; font-size: 13px; color: #4a5568; cursor: pointer; }
        .nav-child:hover { background: #edf2ff; color: #2b6cb0; }
        .nav-child--active { background: #e2e8f0; color: #2b6cb0; font-weight: 600; }
        .main { display: flex; flex-direction: column; }
        .topbar { height: 60px; background: #fff; border-bottom: 1px solid #e3e6f0; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; }
        .topbar__left { display: flex; align-items: center; gap: 10px; }
        .topbar__title { font-size: 18px; font-weight: 600; color: #2d3748; }
        .topbar__breadcrumb { font-size: 12px; color: #a0aec0; white-space: nowrap; }
        .topbar__right { display: flex; align-items: center; gap: 18px; }
        .badge-pill { background: #e53e3e; color: #fff; border-radius: 999px; padding: 2px 7px; font-size: 11px; }
        .user-info { display: flex; align-items: center; gap: 8px; }
        .user-avatar { width: 34px; height: 34px; border-radius: 50%; background: #2b6cb0; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; }
        .user-meta { display: flex; flex-direction: column; }
        .user-meta__name { font-size: 13px; font-weight: 600; }
        .user-meta__id { font-size: 11px; color: #a0aec0; }
        .content { padding: 18px 24px 26px; }
        .content-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
        .content-header__title { font-size: 17px; font-weight: 600; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        .select { padding: 7px 9px; border-radius: 6px; border: 1px solid #cbd5e0; font-size: 13px; min-width: 210px; }
        .btn-outline { padding: 7px 11px; border-radius: 6px; border: 1px solid #4c51bf; background: #fff; color: #4c51bf; font-size: 13px; cursor: pointer; }
        .btn-outline:hover { background: #4c51bf; color: #fff; }
        .grid-2 { display: grid; grid-template-columns: 2.1fr 1.6fr; gap: 16px; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 1px 3px rgba(15,23,42,0.08); padding: 16px 18px 14px; }
        .card__header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px; }
        .card__title { font-size: 15px; font-weight: 600; }
        .card__subtitle { font-size: 12px; color: #a0aec0; margin-top: 2px; }
        .card__header-left { display: flex; flex-direction: column; }
        .pill { display: inline-flex; align-items: center; border-radius: 999px; background: #ebf4ff; color: #4c51bf; font-size: 11px; padding: 3px 9px; gap: 4px; }
        .table-wrapper { border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead { background: #f7fafc; }
        th, td { padding: 8px 10px; border-bottom: 1px solid #edf2f7; text-align: left; white-space: nowrap; }
        th { font-weight: 600; color: #4a5568; font-size: 12px; }
        tbody tr:hover { background: #f0f5ff; }
        tbody tr.selected { background: #e2e8ff; }
        .tag { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 999px; font-size: 11px; }
        .tag--success { background: #c6f6d5; color: #22543d; }
        .tag--muted { background: #edf2f7; color: #4a5568; }
        .text-muted { color: #a0aec0; font-size: 12px; }
        .mt-1 { margin-top: 4px; }
        .empty-state { padding: 20px; text-align: center; color: #a0aec0; font-size: 13px; }
        @media (max-width: 1024px) { .layout { grid-template-columns: 220px 1fr; } .grid-2 { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { .layout { grid-template-columns: 1fr; } .sidebar { display: none; } .topbar { padding: 0 16px; } .content { padding: 14px 16px 22px; } }
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
            <a href="<?php echo $baseUrl; ?>/GiangVien/dashboard" class="nav-item" style="text-decoration: none;"><div class="nav-item__icon">🏠</div><div>Bảng điều khiển</div></a>
            <div class="nav-section-title">Giảng dạy</div>
            <a href="<?php echo $baseUrl; ?>/GiangVien/dashboard" class="nav-item nav-item--active" style="text-decoration: none;">
                <div class="nav-item__icon">📚</div><div>Lớp & môn được dạy</div><div class="nav-item__chevron">▾</div>
            </a>
            <div class="nav-children">
                <a href="<?php echo $baseUrl; ?>/GiangVien/dashboard" class="nav-child nav-child--active" style="text-decoration: none;">Danh sách lớp và sinh viên</a>
            </div>
            <div class="nav-section-title">Khác</div>
            <a href="<?php echo $baseUrl; ?>/GiangVien/nhapDiem" class="nav-item" style="text-decoration: none;"><div class="nav-item__icon">📝</div><div>Nhập điểm</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/traCuuDiem" class="nav-item" style="text-decoration: none;"><div class="nav-item__icon">🔍</div><div>Tra cứu điểm</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/guiThongBao" class="nav-item" style="text-decoration: none;"><div class="nav-item__icon">📧</div><div>Gửi thông báo</div></a>
            <div class="nav-item"><div class="nav-item__icon">📆</div><div>Lịch giảng dạy</div></div>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar__left">
                <div>
                    <div class="topbar__title">Lớp & môn được dạy</div>
                    <div class="topbar__breadcrumb">Giảng dạy / Lớp học phần</div>
                </div>
            </div>
            <div class="topbar__right">
                <div class="text-muted">Thông báo <span class="badge-pill">0</span></div>
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
                <div class="content-header__title">Danh sách lớp học phần giảng dạy</div>
                <div class="filters">
                    <select class="select">
                        <?php foreach ($hocKyList as $hk): ?>
                            <option value="<?php echo htmlspecialchars($hk['value'] ?? ''); ?>"><?php echo htmlspecialchars($hk['label'] ?? ''); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn-outline">Xuất Excel danh sách lớp</button>
                </div>
            </div>

            <div class="grid-2">
                <section class="card">
                    <div class="card__header">
                        <div class="card__header-left">
                            <div class="card__title">Lớp học phần đang giảng dạy</div>
                            <div class="card__subtitle">Chọn 1 lớp học phần để xem danh sách sinh viên</div>
                        </div>
                        <div class="pill"><span>Đang dạy:</span><strong><?php echo count($lopHocPhanList); ?> lớp</strong></div>
                    </div>

                    <?php if (empty($lopHocPhanList)): ?>
                        <div class="empty-state">Hiện tại bạn chưa được phân công giảng dạy lớp học phần nào.</div>
                    <?php else: ?>
                        <div class="table-wrapper">
                            <table id="tableLopHocPhan">
                                <thead>
                                    <tr>
                                        <th>Mã lớp HP</th>
                                        <th>Môn học</th>
                                        <th>Lớp</th>
                                        <th>Số TC</th>
                                        <th>Sĩ số / ĐK</th>
                                        <th>Thứ - Tiết</th>
                                        <th>Phòng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($lopHocPhanList as $idx => $lop):
                                    $ma = $lop['MaLopHocPhan'] ?? '';
                                    $tenMon = $lop['TenMonHoc'] ?? ($lop['MaMonHoc'] ?? '');
                                    $maMon = $lop['MaMonHoc'] ?? '';
                                    $tenLop = $lop['TenLop'] ?? '';
                                    $tc = (int)($lop['SoTinChi'] ?? 0);
                                    $siSo = (int)($lop['SiSo'] ?? 0);
                                    $dk = (int)($lop['SoSinhVien'] ?? 0);
                                    $thu = $lop['Thu'] ?? '-';
                                    $tiet = $lop['TietHoc'] ?? '-';
                                    $phong = $lop['PhongHoc'] ?? '-';
                                ?>
                                    <tr data-index="<?php echo $idx; ?>" data-malop="<?php echo htmlspecialchars($ma); ?>">
                                        <td><?php echo htmlspecialchars($ma); ?></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($tenMon); ?></div>
                                            <?php if ($maMon): ?><div class="text-muted mt-1"><?php echo htmlspecialchars($maMon); ?></div><?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($tenLop); ?></td>
                                        <td><?php echo $tc ?: '-'; ?></td>
                                        <td><span class="tag tag--muted"><?php echo $dk; ?> / <?php echo $siSo ?: '-'; ?></span></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($thu); ?></div>
                                            <div class="text-muted mt-1"><?php echo htmlspecialchars($tiet); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($phong); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="card">
                    <div class="card__header">
                        <div class="card__header-left">
                            <div class="card__title">Danh sách sinh viên trong lớp</div>
                            <div class="card__subtitle" id="subtitleLopChon">Chưa chọn lớp học phần</div>
                        </div>
                        <button type="button" class="btn-outline" id="btnExportSinhVien" disabled>Xuất DS sinh viên</button>
                    </div>
                    <div id="wrapperSinhVien">
                        <div class="empty-state" id="emptySinhVien">Vui lòng chọn một lớp học phần ở bảng bên trái để xem danh sách sinh viên.</div>
                        <div class="table-wrapper" id="tableSinhVienWrapper" style="display:none;">
                            <table>
                                <thead>
                                    <tr><th>Mã SV</th><th>Họ tên</th><th>Lớp hành chính</th><th>Email</th><th>Số điện thoại</th><th>Trạng thái</th></tr>
                                </thead>
                                <tbody id="tbodySinhVien"></tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</div>

<script>
(function () {
    const SINH_VIEN_DEMO = <?php echo json_encode($sinhVienDemo, JSON_UNESCAPED_UNICODE); ?>;
    const tableLop = document.getElementById('tableLopHocPhan');
    const tbodySinhVien = document.getElementById('tbodySinhVien');
    const tableSinhVienWrapper = document.getElementById('tableSinhVienWrapper');
    const emptySinhVien = document.getElementById('emptySinhVien');
    const subtitleLopChon = document.getElementById('subtitleLopChon');
    const btnExport = document.getElementById('btnExportSinhVien');

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function renderSv(ds) {
        if (!ds || !ds.length) {
            tableSinhVienWrapper.style.display = 'none';
            emptySinhVien.style.display = 'block';
            emptySinhVien.textContent = 'Lớp học phần này hiện chưa có sinh viên đăng ký.';
            return;
        }
        tbodySinhVien.innerHTML = ds.map(function (sv) {
            return '<tr><td>' + escapeHtml(sv.MaSinhVien) + '</td><td>' + escapeHtml(sv.HoTen) + '</td><td>' + escapeHtml(sv.LopHanhChinh) + '</td><td>' + escapeHtml(sv.Email) + '</td><td>' + escapeHtml(sv.SoDienThoai || '') + '</td><td><span class="tag tag--success">' + escapeHtml(sv.TrangThai || '') + '</span></td></tr>';
        }).join('');
        emptySinhVien.style.display = 'none';
        tableSinhVienWrapper.style.display = 'block';
    }

    if (tableLop) {
        tableLop.addEventListener('click', function (e) {
            var row = e.target.closest('tr[data-index]');
            if (!row) return;
            var rows = tableLop.querySelectorAll('tbody tr');
            for (var i = 0; i < rows.length; i++) rows[i].classList.remove('selected');
            row.classList.add('selected');
            var ma = row.getAttribute('data-malop') || row.cells[0].textContent.trim();
            var tenMon = (row.cells[1].querySelector('div') || row.cells[1]).textContent.trim();
            var tenLop = row.cells[2].textContent.trim();
            subtitleLopChon.textContent = 'Lớp HP ' + ma + ' - ' + tenMon + (tenLop ? ' (' + tenLop + ')' : '');
            if (btnExport) btnExport.disabled = false;
            renderSv(SINH_VIEN_DEMO);
        });
    }
})();
</script>
</body>
</html>
