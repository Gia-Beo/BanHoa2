<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: dang-nhap.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê - Báo cáo | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    
    <div class="admin-menu">
        <h2>THỐNG KÊ - BÁO CÁO</h2>
        <div style="text-align:center;padding:30px 0;color:#888;">
            <i class="fa fa-bar-chart" style="font-size:40px;margin-bottom:16px;"></i><br>
            Chức năng này đang phát triển...
        </div>
        <div style="text-align:center;">
            <a href="admin.php" class="btn">← Quay lại Admin</a>
        </div>
    </div>
    
</body>
</html>