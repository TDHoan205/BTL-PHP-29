<?php
// Biến từ controller: $giangVien, $hocKyList, $lopHocPhanList, $sinhVienLopHocPhan
$giangVienTen = $giangVien['HoTen'] ?? 'Giảng viên';
$giangVienMa = $giangVien['MaGiangVien'] ?? '';

$hocKyList = $hocKyList ?? [];
$lopHocPhanList = $lopHocPhanList ?? [];
$baseUrl = defined('URLROOT') ? URLROOT : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNISCORE - Cổng Giảng Viên</title>
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
            <a href="<?php echo $baseUrl; ?>/GiangVien/lichDay" class="nav-item" style="text-decoration: none;"><div class="nav-item__icon">📆</div><div>Lịch giảng dạy</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/diemDanh" class="nav-item" style="text-decoration: none;"><div class="nav-item__icon">📋</div><div>Điểm danh</div></a>
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
                <div class="topbar-dropdown" id="userDropdown">
                    <div class="topbar-dropdown__trigger user-info" id="userDropdownTrigger" aria-expanded="false">
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
                <div class="content-header__title">Danh sách lớp học phần giảng dạy</div>
                <div class="filters">
                    <select class="select">
                        <?php foreach ($hocKyList as $hk): ?>
                            <option value="<?php echo htmlspecialchars($hk['value'] ?? ''); ?>"><?php echo htmlspecialchars($hk['label'] ?? ''); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn-outline" id="btnExportLop" onclick="exportLopHocPhan()">Xuất Excel danh sách lớp</button>
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
                                    $daDangKy = (int)($lop['SiSo'] ?? 0);
                                    $siSoToiDa = (int)($lop['SoLuongToiDa'] ?? 0);
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
                                        <td><span class="tag tag--muted"><?php echo $daDangKy; ?> / <?php echo $siSoToiDa ?: '-'; ?></span></td>
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
                        <div style="display: flex; gap: 8px;">
                            <button type="button" class="btn-outline" id="btnExportSinhVien" disabled onclick="exportSinhVien()">Xuất DS sinh viên</button>
                            <button type="button" class="btn-outline" id="btnExportDiem" disabled onclick="exportDiem()" style="border-color: #38a169; color: #38a169;">Xuất điểm</button>
                        </div>
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
var selectedMaLopHocPhan = null;

function exportLopHocPhan() {
    var baseUrl = <?php echo json_encode($baseUrl, JSON_UNESCAPED_UNICODE); ?>;
    window.location.href = baseUrl + '/GiangVien/exportLopHocPhan';
}

function exportSinhVien() {
    if (!selectedMaLopHocPhan) {
        alert('Vui lòng chọn một lớp học phần trước');
        return;
    }
    var baseUrl = <?php echo json_encode($baseUrl, JSON_UNESCAPED_UNICODE); ?>;
    window.location.href = baseUrl + '/GiangVien/exportSinhVien?maLopHocPhan=' + encodeURIComponent(selectedMaLopHocPhan);
}

function exportDiem() {
    if (!selectedMaLopHocPhan) {
        alert('Vui lòng chọn một lớp học phần trước');
        return;
    }
    var baseUrl = <?php echo json_encode($baseUrl, JSON_UNESCAPED_UNICODE); ?>;
    window.location.href = baseUrl + '/GiangVien/exportDiem?maLopHocPhan=' + encodeURIComponent(selectedMaLopHocPhan);
}

(function () {
    const baseUrl = <?php echo json_encode($baseUrl, JSON_UNESCAPED_UNICODE); ?>;
    const tableLop = document.getElementById('tableLopHocPhan');
    const tbodySinhVien = document.getElementById('tbodySinhVien');
    const tableSinhVienWrapper = document.getElementById('tableSinhVienWrapper');
    const emptySinhVien = document.getElementById('emptySinhVien');
    const subtitleLopChon = document.getElementById('subtitleLopChon');
    const btnExport = document.getElementById('btnExportSinhVien');
    const btnExportDiem = document.getElementById('btnExportDiem');

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

    function loadSinhVienLopHocPhan(maLopHocPhan) {
        if (!maLopHocPhan) {
            emptySinhVien.style.display = 'block';
            emptySinhVien.textContent = 'Vui lòng chọn một lớp học phần ở bảng bên trái để xem danh sách sinh viên.';
            tableSinhVienWrapper.style.display = 'none';
            return;
        }
        emptySinhVien.style.display = 'block';
        emptySinhVien.textContent = 'Đang tải danh sách sinh viên...';
        tableSinhVienWrapper.style.display = 'none';
        fetch(baseUrl + '/GiangVien/getSinhVienLopHocPhan?maLopHocPhan=' + encodeURIComponent(maLopHocPhan))
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success && data.sinhVien) {
                    renderSv(data.sinhVien);
                } else {
                    emptySinhVien.textContent = data.message || 'Không tải được danh sách sinh viên.';
                    tableSinhVienWrapper.style.display = 'none';
                }
            })
            .catch(function() {
                emptySinhVien.textContent = 'Lỗi khi tải danh sách sinh viên.';
                tableSinhVienWrapper.style.display = 'none';
            });
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
            selectedMaLopHocPhan = ma;
            if (btnExport) btnExport.disabled = false;
            if (btnExportDiem) btnExportDiem.disabled = false;
            loadSinhVienLopHocPhan(ma);
        });
    }

    (function() {
        var dropdown = document.getElementById('userDropdown');
        var trigger = document.getElementById('userDropdownTrigger');
        if (dropdown && trigger) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('is-open');
            });
            document.addEventListener('click', function() {
                dropdown.classList.remove('is-open');
            });
            dropdown.querySelector('.topbar-dropdown__menu') && dropdown.querySelector('.topbar-dropdown__menu').addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    })();
})();
</script>
</body>
</html>
