<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quên Mật Khẩu - UNISCORE</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo (defined('URLROOT') ? rtrim(URLROOT, '/') : '') . '/favicon.svg'; ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: float 30s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }
        
        .forgot-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }
        
        .forgot-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 50px 40px;
            text-align: center;
        }
        
        .logo-container {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
            transform: rotate(-5deg);
            transition: transform 0.3s ease;
        }
        
        .logo-container:hover {
            transform: rotate(0deg) scale(1.05);
        }
        
        .logo-container i {
            font-size: 40px;
            color: white;
        }
        
        .forgot-card h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .forgot-card .subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 35px;
            line-height: 1.6;
        }
        
        .role-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .role-btn {
            flex: 1;
            padding: 15px 10px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .role-btn:hover {
            border-color: var(--primary-color);
            background: #f8fafc;
        }
        
        .role-btn.active {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(37, 99, 235, 0.05));
        }
        
        .role-btn i {
            font-size: 24px;
            color: var(--primary-color);
            display: block;
            margin-bottom: 8px;
        }
        
        .role-btn span {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: color 0.3s;
        }
        
        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        
        .input-wrapper input:focus + i,
        .input-wrapper:focus-within i {
            color: var(--primary-color);
        }
        
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 25px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.35);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }
        
        .back-link {
            margin-top: 20px;
            color: #64748b;
            font-size: 14px;
        }
        
        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            background: white;
            top: -100px;
            right: -100px;
        }
        
        .shape-2 {
            width: 200px;
            height: 200px;
            background: white;
            bottom: -50px;
            left: -50px;
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="logo-container">
                <i class="fas fa-key"></i>
            </div>
            <h2>Quên mật khẩu?</h2>
            <p class="subtitle">Nhập tên đăng nhập và mã sinh viên/giảng viên của bạn.<br>Admin sẽ xác nhận và cấp lại mật khẩu mới.</p>
            
            <?php if(isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <?php if(isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            
            <!-- Role Selector -->
            <div class="role-selector">
                <div class="role-btn active" data-role="GiangVien" onclick="selectRole(this)">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Giảng viên</span>
                </div>
                <div class="role-btn" data-role="SinhVien" onclick="selectRole(this)">
                    <i class="fas fa-user-graduate"></i>
                    <span>Sinh viên</span>
                </div>
            </div>
            
            <form action="index.php?url=Auth/submitForgotPassword" method="POST">
                <input type="hidden" name="vaiTro" id="selectedRole" value="GiangVien">
                
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <div class="input-wrapper">
                        <input type="text" name="username" placeholder="Nhập tên đăng nhập" required>
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label id="maNguoiDungLabel">Mã Giảng Viên</label>
                    <div class="input-wrapper">
                        <input type="text" name="maNguoiDung" id="maNguoiDung" placeholder="Nhập mã giảng viên" required>
                        <i class="fas fa-id-card"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    Gửi yêu cầu
                </button>
            </form>
            
            <p class="back-link">
                <a href="index.php?url=Auth/index"><i class="fas fa-arrow-left"></i> Quay lại đăng nhập</a>
            </p>
            
            <p class="footer-text" style="margin-top: 20px; color: #94a3b8; font-size: 13px;">
                © <?= date('Y') ?> <strong style="color: #2563eb;">UNISCORE</strong> - Quản lý điểm sinh viên
            </p>
        </div>
    </div>
    
    <script>
        function selectRole(element) {
            document.querySelectorAll('.role-btn').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');
            const role = element.dataset.role;
            document.getElementById('selectedRole').value = role;
            
            // Update label and placeholder
            const label = document.getElementById('maNguoiDungLabel');
            const input = document.getElementById('maNguoiDung');
            if (role === 'SinhVien') {
                label.textContent = 'Mã Sinh Viên';
                input.placeholder = 'Nhập mã sinh viên';
            } else {
                label.textContent = 'Mã Giảng Viên';
                input.placeholder = 'Nhập mã giảng viên';
            }
        }
    </script>
</body>
</html>
