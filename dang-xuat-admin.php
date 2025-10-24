<?php
session_start();

// Xoá toàn bộ session (bao gồm is_admin)
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập
header('Location: dang-nhap.php');
exit();
?>
