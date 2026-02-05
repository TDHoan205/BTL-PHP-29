<?php
$giangVienTen = $giangVien['HoTen'] ?? 'Giảng viên';
$giangVienMa = $giangVien['MaGiangVien'] ?? '';
$lopHocPhanList = $lopHocPhanList ?? [];
$lopHocPhanSelected = $lopHocPhanSelected ?? null;
$bangDiemDanh = $bangDiemDanh ?? [];
$baseUrl = defined('URLROOT') ? URLROOT : '';
$maLopHocPhan = $_GET['maLopHocPhan'] ?? null;
$success = isset($_GET['success']);
$sync = isset($_GET['sync']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điểm danh - UNISCORE Giảng Viên</title>
    <link rel="icon" type="image/svg+xml" href="<?= rtrim($baseUrl ?? '', '/') ?>/favicon.svg">
    <link href="<?= rtrim($baseUrl ?? '', '/') ?>/css/giangvien.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        <div class="nav-section-title">Tổng quan</div>
        <a href="<?= $baseUrl ?>/GiangVien/dashboard" class="nav-item"><div class="nav-item__icon">🏠</div><div>Bảng điều khiển</div></a>
        <div class="nav-section-title">Giảng dạy</div>
        <a href="<?= $baseUrl ?>/GiangVien/dashboard" class="nav-item"><div class="nav-item__icon">📚</div><div>Lớp & môn được dạy</div></a>
        <div class="nav-section-title">Khác</div>
        <a href="<?= $baseUrl ?>/GiangVien/nhapDiem" class="nav-item"><div class="nav-item__icon">📝</div><div>Nhập điểm</div></a>
        <a href="<?= $baseUrl ?>/GiangVien/traCuuDiem" class="nav-item"><div class="nav-item__icon">🔍</div><div>Tra cứu điểm</div></a>
        <a href="<?= $baseUrl ?>/GiangVien/guiThongBao" class="nav-item"><div class="nav-item__icon">📧</div><div>Gửi thông báo</div></a>
        <a href="<?= $baseUrl ?>/GiangVien/lichDay" class="nav-item"><div class="nav-item__icon">📆</div><div>Lịch giảng dạy</div></a>
        <a href="<?= $baseUrl ?>/GiangVien/diemDanh" class="nav-item nav-item--active"><div class="nav-item__icon">📋</div><div>Điểm danh</div></a>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <div class="topbar__title">Điểm danh</div>
                <div class="topbar__breadcrumb">Bảng điểm danh - Điểm chuyên cần theo % tham gia buổi học (1 tín = 5 ca = 15 tiết)</div>
            </div>
        </header>

        <main class="content">
            <?php if ($success): ?>
            <div class="alert-success"><i class="fas fa-check-circle me-2"></i>Đã lưu điểm danh thành công.</div>
            <?php endif; ?>
            <?php if ($sync): ?>
            <div class="alert-success"><i class="fas fa-sync me-2"></i>Đã đồng bộ điểm chuyên cần vào bảng điểm.</div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'limit'): ?>
            <div class="alert-error" style="padding: 12px; margin-bottom: 16px; background: #fee; border-left: 4px solid #c00; color: #900;"><i class="fas fa-exclamation-triangle me-2"></i>Số buổi điểm danh vượt quá giới hạn cho phép!</div>
            <?php endif; ?>

            <div class="content-header">
                <div class="content-header__title">Chọn lớp học phần</div>
                <form method="get" action="" style="display: flex; gap: 8px;">
                    <input type="hidden" name="url" value="GiangVien/diemDanh">
                    <select name="maLopHocPhan" class="select" onchange="this.form.submit()">
                        <option value="">-- Chọn lớp học phần --</option>
                        <?php foreach ($lopHocPhanList as $lhp): ?>
                            <option value="<?= htmlspecialchars($lhp['MaLopHocPhan']) ?>" <?= ($maLopHocPhan === $lhp['MaLopHocPhan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lhp['MaLopHocPhan'] . ' - ' . ($lhp['TenMonHoc'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <?php if ($lopHocPhanSelected): ?>
            <div class="card">
                <div class="card__title">Bảng điểm danh - <?= htmlspecialchars($lopHocPhanSelected['TenMonHoc'] ?? $lopHocPhanSelected['MaMonHoc'] ?? '') ?> (<?= htmlspecialchars($maLopHocPhan) ?>)</div>
                <p style="font-size: 12px; color: #718096; margin-bottom: 12px;">1 tín chỉ = 5 ca = 15 tiết. Điểm chuyên cần = % tham gia buổi học × 10</p>

                <?php if (empty($bangDiemDanh)): ?>
                <div class="empty-state">Chưa có sinh viên đăng ký lớp học phần này.</div>
                <?php else: ?>
                <form action="index.php?url=GiangVien/saveDiemDanh" method="POST">
                    <input type="hidden" name="MaLopHocPhan" value="<?= htmlspecialchars($maLopHocPhan) ?>">
                    <?php 
                        $soTinChi = (int)($lopHocPhanSelected['SoTinChi'] ?? 1);
                        $soBuoiToiDa = $soTinChi * 5 + 3; // Số tín * 5 + 3 buổi học bù
                    ?>
                    <div class="form-buoi">
                        <label>Điểm danh buổi thứ:</label>
                        <select name="BuoiThu" required class="select" style="width: 150px;">
                            <?php for ($i = 1; $i <= $soBuoiToiDa; $i++): ?>
                                <option value="<?= $i ?>">Buổi <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <span style="font-size: 12px; color: #718096; margin: 0 8px;">(Tối đa <?= $soBuoiToiDa ?> buổi: <?= $soTinChi ?> tín × 5 + 3 buổi học bù)</span>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu điểm danh buổi này</button>
                    </div>
                    <div class="table-wrapper" style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã SV</th>
                                    <th>Tên SV</th>
                                    <th>Mã học phần</th>
                                    <th>Buổi có mặt</th>
                                    <th>Tổng buổi</th>
                                    <th>% tham gia</th>
                                    <th>Điểm CC</th>
                                    <th>Có mặt buổi này</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bangDiemDanh as $i => $r): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($r['MaSinhVien'] ?? '') ?></strong></td>
                                    <td><?= htmlspecialchars($r['HoTen'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($r['MaLopHocPhan'] ?? $r['MaMonHoc'] ?? '') ?></td>
                                    <td><?= (int)($r['SoBuoiCoMat'] ?? 0) ?></td>
                                    <td><?= (int)($r['TongBuoi'] ?? 0) ?></td>
                                    <td><?= number_format($r['PhanTramThamGia'] ?? 0, 1) ?>%</td>
                                    <td><strong><?= $r['DiemChuyenCan'] !== null ? number_format($r['DiemChuyenCan'], 2) : '-' ?></strong></td>
                                    <td>
                                        <input type="checkbox" name="coMat[<?= $r['MaDangKy'] ?>]" value="1" class="checkbox-co-mat" checked>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>

                <div style="margin-top: 16px;">
                    <a href="index.php?url=GiangVien/dongBoDiemCC&maLopHocPhan=<?= urlencode($maLopHocPhan) ?>" class="btn btn-secondary" onclick="return confirm('Đồng bộ điểm chuyên cần từ điểm danh vào bảng điểm?');">
                        <i class="fas fa-sync"></i> Đồng bộ điểm CC vào bảng điểm
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php elseif ($maLopHocPhan): ?>
            <div class="card">
                <div class="empty-state">Bạn không có quyền xem lớp học phần này.</div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="empty-state">
                    <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                    <p>Vui lòng chọn lớp học phần để xem bảng điểm danh.</p>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>
</body>
</html>
