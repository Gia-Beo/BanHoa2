<?php
session_start();

// Hủy toàn bộ session
session_unset();
session_destroy();

// Quay lại trang chủ
header("Location: index.php");
exit();
