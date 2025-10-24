<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: dang-nhap.php');
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản trị Admin - Website Bán Cây Cảnh</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- MENU BÊN TRÁI -->
        <div class="sidebar">
            <h2>QUẢN TRỊ</h2>
            <ul>
                <li><a href="admin.php?page=dashboard">Tổng quan</a></li>
                 <li><a href="admin-don-thue.php" > thuê hoa</a></li>
                <li><a href="rest_admin.php">Quản lý sản phẩm</a></li>
                <li><a href="admin.php?page=donhang">Quản lý đơn hàng</a></li>
                
                <li><a href="admin.php?page=baocao">Báo Cáo</a></li>

                <li><a href="dang-xuat-admin.php">Đăng xuất</a></li>
            </ul>
        </div>

        <!-- NỘI DUNG BÊN PHẢI -->
        <div class="content">
            <?php
            if ($page === 'dashboard') {
                echo '<h2>Chào mừng đến trang quản trị</h2>';
                echo '<p>Chọn một mục bên trái để quản lý.</p>';
            } elseif ($page === 'sanpham') {
                include 'rest_admin.php'; // Quản lý sản phẩm
            } elseif ($page === 'donhang') {
                include 'order_admin.php'; // Quản lý đơn hàng
            } 
            elseif ($page === 'baocao') {
    include 'bao-cao.php';
}
 else {
                echo '<p>Không tìm thấy trang yêu cầu.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
