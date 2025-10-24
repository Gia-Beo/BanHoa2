<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: dang-nhap.php');
    exit();
}
echo "<h2>Chào mừng bạn đến trang quản trị admin!</h2>";
echo "<ul>
        <li><a href='quanly_user.php'>Quản lý tài khoản</a></li>
        <li><a href='quanly_sanpham.php'>Quản lý sản phẩm</a></li>
        <li><a href='quanly_donhang.php'>Quản lý đơn hàng</a></li>
        <li><a href='logout.php'>Đăng xuất</a></li>
      </ul>";
?>