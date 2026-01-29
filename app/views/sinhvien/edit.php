<?php require_once '../app/views/layouts/header.php'; ?>

<?php if (isset($data['info']) && !empty($data['info'])): 
    $sv = $data['info']; 
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Cập nhật thông tin sinh viên</h6>
    </div>
    <div class="card-body">
        <form action="../../SinhVien/update" method="POST">
            <input type="hidden" name="id" value="<?= $sv['ID'] ?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Mã Sinh Viên</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="mssv" value="<?= $sv['MSSV'] ?>" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Họ và Tên</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="hoten" value="<?= $sv['HoTen'] ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Lớp</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="lop" value="<?= $sv['Lop'] ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Khoa</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="khoa" value="<?= $sv['Khoa'] ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu Thay Đổi</button>
                    <a href="../../SinhVien/index" class="btn btn-secondary">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
    <div class="alert alert-danger">Không tìm thấy sinh viên! <a href="../../SinhVien/index">Quay lại</a></div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>