<?php
// Biến từ controller: $giangVien, $lopHocPhanList, $lopHocPhanSelected, $sinhVienList
$giangVienTen = $giangVien['HoTen'] ?? 'Giảng viên';
$giangVienMa = $giangVien['MaGiangVien'] ?? '';
$lopHocPhanList = $lopHocPhanList ?? [];
$lopHocPhanSelected = $lopHocPhanSelected ?? null;
$sinhVienList = $sinhVienList ?? [];
$baseUrl = defined('URLROOT') ? URLROOT : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gửi Thông Báo - Cổng Giảng Viên</title>
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
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #4a5568; font-size: 13px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 13px; font-family: inherit; }
        .form-group textarea { min-height: 150px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #2b6cb0; box-shadow: 0 0 0 3px rgba(43,108,176,0.1); }
        .checkbox-group { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 8px; }
        .checkbox-item { display: flex; align-items: center; gap: 6px; }
        .checkbox-item input[type="checkbox"] { width: auto; }
        .btn { padding: 10px 20px; border-radius: 6px; border: none; font-size: 13px; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #2b6cb0; color: #fff; }
        .btn-primary:hover { background: #2c5282; }
        .btn-success { background: #38a169; color: #fff; }
        .btn-success:hover { background: #2f855a; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .alert-error { background: #fed7d7; color: #742a2a; border: 1px solid #fc8181; }
        .empty-state { padding: 40px; text-align: center; color: #a0aec0; }
        .table-wrapper { overflow-x: auto; max-height: 300px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead { background: #f7fafc; position: sticky; top: 0; }
        th, td { padding: 8px; border-bottom: 1px solid #edf2f7; text-align: left; }
        th { font-weight: 600; color: #4a5568; font-size: 12px; }
        tbody tr:hover { background: #f0f5ff; }
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
            <a href="<?php echo $baseUrl; ?>/GiangVien/traCuuDiem" class="nav-item"><div class="nav-item__icon">🔍</div><div>Tra cứu điểm</div></a>
            <a href="<?php echo $baseUrl; ?>/GiangVien/guiThongBao" class="nav-item nav-item--active"><div class="nav-item__icon">📧</div><div>Gửi thông báo</div></a>
            <div class="nav-item"><div class="nav-item__icon">📆</div><div>Lịch giảng dạy</div></div>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="topbar__title">Gửi Thông Báo Cho Sinh Viên</div>
                <div class="topbar__breadcrumb">Giảng dạy / Gửi thông báo</div>
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
            <div id="alertContainer"></div>

            <div class="content-header">
                <div class="content-header__title">Chọn lớp học phần</div>
                <select class="select" id="selectLopHocPhan">
                    <option value="">-- Chọn lớp học phần --</option>
                    <?php foreach ($lopHocPhanList as $lop): ?>
                        <option value="<?php echo htmlspecialchars($lop['MaLopHocPhan']); ?>" <?php echo ($lopHocPhanSelected && $lop['MaLopHocPhan'] == $lopHocPhanSelected['MaLopHocPhan']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($lop['MaLopHocPhan'] . ' - ' . ($lop['TenMonHoc'] ?? $lop['MaMonHoc'] ?? '')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($lopHocPhanSelected && !empty($sinhVienList)): ?>
                <div class="card">
                    <div class="card__title">Danh sách sinh viên trong lớp</div>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll" onchange="toggleAll(this)"></th>
                                    <th>STT</th>
                                    <th>Mã SV</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sinhVienList as $idx => $sv): ?>
                                    <tr>
                                        <td><input type="checkbox" name="sinhVien[]" value="<?php echo htmlspecialchars($sv['MaSinhVien']); ?>" class="checkbox-sv"></td>
                                        <td><?php echo $idx + 1; ?></td>
                                        <td><?php echo htmlspecialchars($sv['MaSinhVien']); ?></td>
                                        <td><?php echo htmlspecialchars($sv['HoTen']); ?></td>
                                        <td><?php echo htmlspecialchars($sv['Email'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($sv['SoDienThoai'] ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card__title">Nội dung thông báo</div>
                    <form id="formThongBao" onsubmit="return guiThongBao(event)">
                        <div class="form-group">
                            <label>Tiêu đề *</label>
                            <input type="text" name="tieuDe" id="tieuDe" required placeholder="Nhập tiêu đề thông báo...">
                        </div>
                        <div class="form-group">
                            <label>Nội dung *</label>
                            <textarea name="noiDung" id="noiDung" required placeholder="Nhập nội dung thông báo..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Gửi đến</label>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="chonTatCa" checked onchange="toggleAllCheckboxes(this)">
                                    <label for="chonTatCa">Tất cả sinh viên</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="chonDau" onchange="filterByResult('dau')">
                                    <label for="chonDau">Sinh viên đậu</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="chonRot" onchange="filterByResult('rot')">
                                    <label for="chonRot">Sinh viên rớt</label>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right; margin-top: 20px;">
                            <button type="button" class="btn btn-outline" onclick="resetForm()">Reset</button>
                            <button type="submit" class="btn btn-success">📧 Gửi thông báo</button>
                        </div>
                    </form>
                </div>
            <?php elseif ($lopHocPhanSelected): ?>
                <div class="card">
                    <div class="empty-state">Lớp học phần này chưa có sinh viên đăng ký.</div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="empty-state">Vui lòng chọn lớp học phần để gửi thông báo.</div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.checkbox-sv');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

function toggleAllCheckboxes(checkbox) {
    const checkboxes = document.querySelectorAll('.checkbox-sv');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    document.getElementById('checkAll').checked = checkbox.checked;
}

function filterByResult(type) {
    toggleAllCheckboxes(document.getElementById('chonTatCa'));
}

function resetForm() {
    document.getElementById('formThongBao').reset();
    document.querySelectorAll('.checkbox-sv').forEach(cb => cb.checked = false);
}

function guiThongBao(e) {
    e.preventDefault();
    const maLopHocPhan = document.getElementById('selectLopHocPhan').value;
    const tieuDe = document.getElementById('tieuDe').value;
    const noiDung = document.getElementById('noiDung').value;
    const selectedSinhVien = Array.from(document.querySelectorAll('.checkbox-sv:checked')).map(cb => cb.value);

    if (!maLopHocPhan) {
        alert('Vui lòng chọn lớp học phần');
        return false;
    }

    if (selectedSinhVien.length === 0) {
        alert('Vui lòng chọn ít nhất một sinh viên');
        return false;
    }

    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Đang gửi...';

    fetch('<?php echo $baseUrl; ?>/GiangVien/guiThongBao', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            maLopHocPhan: maLopHocPhan,
            tieuDe: tieuDe,
            noiDung: noiDung,
            sinhVien: selectedSinhVien
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('alertContainer').innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            resetForm();
        } else {
            document.getElementById('alertContainer').innerHTML = '<div class="alert alert-error">' + (data.message || 'Lỗi khi gửi thông báo') + '</div>';
        }
    })
    .catch(err => {
        document.getElementById('alertContainer').innerHTML = '<div class="alert alert-error">Lỗi kết nối</div>';
        console.error(err);
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = '📧 Gửi thông báo';
    });

    return false;
}

const selectLop = document.getElementById('selectLopHocPhan');
if (selectLop) {
    selectLop.addEventListener('change', function() {
        const maLop = this.value;
        if (maLop) {
            window.location.href = '<?php echo $baseUrl; ?>/GiangVien/guiThongBao?maLopHocPhan=' + encodeURIComponent(maLop);
        } else {
            window.location.href = '<?php echo $baseUrl; ?>/GiangVien/guiThongBao';
        }
    });
}
</script>
</body>
</html>
