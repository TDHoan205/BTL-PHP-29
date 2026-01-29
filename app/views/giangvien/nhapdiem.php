<?php
// Biến từ controller: $giangVien, $lopHocPhanList, $lopHocPhanSelected, $cauTrucDiem
$giangVienTen = $giangVien['HoTen'] ?? 'Giảng viên';
$giangVienMa = $giangVien['MaGiangVien'] ?? '';
$lopHocPhanList = $lopHocPhanList ?? [];
$lopHocPhanSelected = $lopHocPhanSelected ?? null;
$cauTrucDiem = $cauTrucDiem ?? [];
$baseUrl = defined('URLROOT') ? URLROOT : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập Điểm - Cổng Giảng Viên</title>
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
        .card { background: #fff; border-radius: 10px; box-shadow: 0 1px 3px rgba(15,23,42,0.08); padding: 20px; margin-bottom: 20px; }
        .card__title { font-size: 15px; font-weight: 600; margin-bottom: 15px; }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead { background: #f7fafc; }
        th, td { padding: 10px; border-bottom: 1px solid #edf2f7; text-align: left; }
        th { font-weight: 600; color: #4a5568; font-size: 12px; }
        tbody tr:hover { background: #f0f5ff; }
        .input-diem { width: 80px; padding: 6px 8px; border: 1px solid #cbd5e0; border-radius: 4px; font-size: 13px; text-align: center; }
        .input-diem:focus { outline: none; border-color: #2b6cb0; box-shadow: 0 0 0 3px rgba(43,108,176,0.1); }
        .input-diem.error { border-color: #e53e3e; }
        .btn { padding: 8px 16px; border-radius: 6px; border: none; font-size: 13px; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #2b6cb0; color: #fff; }
        .btn-primary:hover { background: #2c5282; }
        .btn-success { background: #38a169; color: #fff; }
        .btn-success:hover { background: #2f855a; }
        .btn-outline { background: #fff; color: #4c51bf; border: 1px solid #4c51bf; }
        .btn-outline:hover { background: #4c51bf; color: #fff; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .alert-error { background: #fed7d7; color: #742a2a; border: 1px solid #fc8181; }
        .text-muted { color: #a0aec0; font-size: 12px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #c6f6d5; color: #22543d; }
        .badge-warning { background: #feebc8; color: #744210; }
        .empty-state { padding: 40px; text-align: center; color: #a0aec0; }
        .cau-truc-info { background: #edf2f7; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 12px; }
        .cau-truc-info strong { color: #2d3748; }
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
            <a href="<?php echo $baseUrl; ?>/GiangVien/nhapDiem" class="nav-item nav-item--active"><div class="nav-item__icon">📝</div><div>Nhập điểm</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/traCuuDiem" class="nav-item"><div class="nav-item__icon">🔍</div><div>Tra cứu điểm</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/guiThongBao" class="nav-item"><div class="nav-item__icon">📧</div><div>Gửi thông báo</div></a>
            <div class="nav-item"><div class="nav-item__icon">📆</div><div>Lịch giảng dạy</div></div>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="topbar__title">Nhập & Quản lý Điểm</div>
                <div class="topbar__breadcrumb">Giảng dạy / Nhập điểm</div>
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
                <div class="content-header__title">Chọn lớp học phần để nhập điểm</div>
                <select class="select" id="selectLopHocPhan">
                    <option value="">-- Chọn lớp học phần --</option>
                    <?php foreach ($lopHocPhanList as $lop): ?>
                        <option value="<?php echo htmlspecialchars($lop['MaLopHocPhan']); ?>" <?php echo ($lopHocPhanSelected && $lop['MaLopHocPhan'] == $lopHocPhanSelected['MaLopHocPhan']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($lop['MaLopHocPhan'] . ' - ' . ($lop['TenMonHoc'] ?? $lop['MaMonHoc'] ?? '')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="alertContainer"></div>

            <?php if ($lopHocPhanSelected && !empty($cauTrucDiem)): ?>
                <div class="card">
                    <div class="card__title">Cấu trúc điểm môn học</div>
                    <div class="cau-truc-info">
                        <?php foreach ($cauTrucDiem as $ct): ?>
                            <strong><?php echo htmlspecialchars($ct['TenLoaiDiem']); ?>:</strong> Hệ số <?php echo number_format($ct['HeSo'], 2); ?>
                            <?php if ($ct['MoTa']): ?> (<?php echo htmlspecialchars($ct['MoTa']); ?>)<?php endif; ?>
                            <?php if ($ct !== end($cauTrucDiem)): ?> | <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card__title">Danh sách sinh viên và điểm</div>
                    <div class="table-wrapper">
                        <table id="tableDiem">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã SV</th>
                                    <th>Họ tên</th>
                                    <?php foreach ($cauTrucDiem as $ct): ?>
                                        <th><?php echo htmlspecialchars($ct['TenLoaiDiem']); ?><br><span class="text-muted">(Hệ số: <?php echo number_format($ct['HeSo'], 2); ?>)</span></th>
                                    <?php endforeach; ?>
                                    <th>Điểm tổng</th>
                                    <th>Điểm chữ</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="tbodySinhVien">
                                <!-- Sẽ được load bằng AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 16px; text-align: right;">
                        <button class="btn btn-success" id="btnSaveAll">Lưu tất cả điểm</button>
                    </div>
                </div>
            <?php elseif ($lopHocPhanSelected): ?>
                <div class="card">
                    <div class="empty-state">Môn học này chưa có cấu trúc điểm. Vui lòng liên hệ quản trị viên để thiết lập.</div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="empty-state">Vui lòng chọn lớp học phần để xem danh sách sinh viên và nhập điểm.</div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
(function() {
    const selectLop = document.getElementById('selectLopHocPhan');
    const tbody = document.getElementById('tbodySinhVien');
    const alertContainer = document.getElementById('alertContainer');
    const btnSaveAll = document.getElementById('btnSaveAll');
    let cauTrucDiem = <?php echo json_encode($cauTrucDiem, JSON_UNESCAPED_UNICODE); ?>;
    let currentMaLopHocPhan = null;

    function showAlert(message, type) {
        alertContainer.innerHTML = '<div class="alert alert-' + type + '">' + escapeHtml(message) + '</div>';
        setTimeout(() => { alertContainer.innerHTML = ''; }, 5000);
    }

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function tinhDiemTong(row) {
        let tong = 0;
        cauTrucDiem.forEach(function(ct, idx) {
            const input = row.querySelector('input[data-loai="' + ct.MaLoaiDiem + '"]');
            const diem = parseFloat(input.value) || 0;
            tong += diem * parseFloat(ct.HeSo);
        });
        return tong;
    }

    function chuyenDiemChu(diem) {
        if (diem >= 9) return 'A+';
        if (diem >= 8.5) return 'A';
        if (diem >= 8) return 'B+';
        if (diem >= 7) return 'B';
        if (diem >= 6.5) return 'C+';
        if (diem >= 6) return 'C';
        if (diem >= 5) return 'D+';
        if (diem >= 4) return 'D';
        return 'F';
    }

    function validateDiem(value) {
        const num = parseFloat(value);
        return !isNaN(num) && num >= 0 && num <= 10;
    }

    function loadSinhVienDiem(maLopHocPhan) {
        if (!maLopHocPhan) {
            tbody.innerHTML = '';
            return;
        }
        currentMaLopHocPhan = maLopHocPhan;
        tbody.innerHTML = '<tr><td colspan="' + (5 + cauTrucDiem.length) + '" style="text-align:center;padding:20px;">Đang tải...</td></tr>';

        fetch('<?php echo $baseUrl; ?>/GiangVien/getSinhVienDiem?maLopHocPhan=' + encodeURIComponent(maLopHocPhan))
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderTable(data.sinhVien);
                } else {
                    tbody.innerHTML = '<tr><td colspan="' + (5 + cauTrucDiem.length) + '" style="text-align:center;padding:20px;color:#a0aec0;">' + escapeHtml(data.message || 'Không có dữ liệu') + '</td></tr>';
                }
            })
            .catch(err => {
                tbody.innerHTML = '<tr><td colspan="' + (5 + cauTrucDiem.length) + '" style="text-align:center;padding:20px;color:#e53e3e;">Lỗi khi tải dữ liệu</td></tr>';
                console.error(err);
            });
    }

    function renderTable(sinhVien) {
        if (!sinhVien || sinhVien.length === 0) {
            tbody.innerHTML = '<tr><td colspan="' + (5 + cauTrucDiem.length) + '" style="text-align:center;padding:20px;color:#a0aec0;">Lớp học phần này chưa có sinh viên đăng ký.</td></tr>';
            return;
        }

        tbody.innerHTML = sinhVien.map(function(sv, idx) {
            let html = '<tr data-ma-dang-ky="' + escapeHtml(sv.MaDangKy || '') + '" data-ma-sinh-vien="' + escapeHtml(sv.MaSinhVien) + '">';
            html += '<td>' + (idx + 1) + '</td>';
            html += '<td>' + escapeHtml(sv.MaSinhVien) + '</td>';
            html += '<td>' + escapeHtml(sv.HoTen) + '</td>';

            cauTrucDiem.forEach(function(ct) {
                const diem = sv.diem && sv.diem[ct.MaLoaiDiem] ? sv.diem[ct.MaLoaiDiem].SoDiem : '';
                html += '<td><input type="number" step="0.01" min="0" max="10" class="input-diem" data-loai="' + escapeHtml(ct.MaLoaiDiem) + '" value="' + escapeHtml(diem) + '" placeholder="0-10"></td>';
            });

            const diemTong = sv.DiemTongKet || '';
            const diemChu = sv.DiemChu || '';
            html += '<td><strong id="diem-tong-' + idx + '">' + escapeHtml(diemTong ? diemTong.toFixed(2) : '') + '</strong></td>';
            html += '<td><span class="badge badge-success" id="diem-chu-' + idx + '">' + escapeHtml(diemChu) + '</span></td>';
            html += '<td><button class="btn btn-primary btn-sm" onclick="saveRow(this)">Lưu</button></td>';
            html += '</tr>';

            return html;
        }).join('');

        // Gắn event listener cho các input điểm
        tbody.querySelectorAll('.input-diem').forEach(function(input) {
            input.addEventListener('input', function() {
                if (!validateDiem(this.value)) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                    const row = this.closest('tr');
                    const tong = tinhDiemTong(row);
                    const idx = Array.from(row.parentNode.children).indexOf(row);
                    document.getElementById('diem-tong-' + idx).textContent = tong.toFixed(2);
                    document.getElementById('diem-chu-' + idx).textContent = chuyenDiemChu(tong);
                }
            });
        });
    }

    window.saveRow = function(btn) {
        const row = btn.closest('tr');
        const maDangKy = row.getAttribute('data-ma-dang-ky');
        if (!maDangKy) {
            showAlert('Không tìm thấy mã đăng ký', 'error');
            return;
        }

        const diemData = {};
        let hasError = false;
        cauTrucDiem.forEach(function(ct) {
            const input = row.querySelector('input[data-loai="' + ct.MaLoaiDiem + '"]');
            const value = input.value.trim();
            if (value) {
                if (!validateDiem(value)) {
                    input.classList.add('error');
                    hasError = true;
                } else {
                    diemData[ct.MaLoaiDiem] = parseFloat(value);
                }
            }
        });

        if (hasError) {
            showAlert('Vui lòng nhập điểm hợp lệ (0-10)', 'error');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Đang lưu...';

        fetch('<?php echo $baseUrl; ?>/GiangVien/saveDiem', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ maDangKy: maDangKy, diem: diemData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showAlert('Lưu điểm thành công!', 'success');
                loadSinhVienDiem(currentMaLopHocPhan);
            } else {
                showAlert(data.message || 'Lỗi khi lưu điểm', 'error');
            }
        })
        .catch(err => {
            showAlert('Lỗi kết nối', 'error');
            console.error(err);
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Lưu';
        });
    };

    if (btnSaveAll) {
        btnSaveAll.addEventListener('click', function() {
            const rows = tbody.querySelectorAll('tr[data-ma-dang-ky]');
            let saved = 0;
            rows.forEach(function(row) {
                const btn = row.querySelector('button');
                if (btn) {
                    saveRow(btn);
                    saved++;
                }
            });
            if (saved === 0) {
                showAlert('Không có dữ liệu để lưu', 'error');
            }
        });
    }

    if (selectLop) {
        selectLop.addEventListener('change', function() {
            const maLop = this.value;
            if (maLop) {
                window.location.href = '<?php echo $baseUrl; ?>/GiangVien/nhapDiem?maLopHocPhan=' + encodeURIComponent(maLop);
            } else {
                window.location.href = '<?php echo $baseUrl; ?>/GiangVien/nhapDiem';
            }
        });
    }

    // Tự động load nếu đã chọn lớp
    <?php if ($lopHocPhanSelected): ?>
    loadSinhVienDiem('<?php echo htmlspecialchars($lopHocPhanSelected['MaLopHocPhan']); ?>');
    <?php endif; ?>
})();
</script>
</body>
</html>
