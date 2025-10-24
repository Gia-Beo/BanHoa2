<?php
session_start();

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? (int)$_POST['price'] : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
$img = isset($_POST['img']) ? trim($_POST['img']) : ''; // ✅ Lấy ảnh

if ($id <= 0 || $name == '' || $price < 0 || $qty <= 0) {
    header("Location: san-pham.php");
    exit;
}

// Nếu ảnh không phải là URL, thêm tiền tố 'uploads/'
if (!filter_var($img, FILTER_VALIDATE_URL) && strpos($img, 'uploads/') !== 0) {
    $img = 'uploads/' . $img;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$id] = [
        'name' => $name,
        'price' => $price,
        'qty' => $qty,
        'img' => $img // ✅ Lưu ảnh vào session
    ];
}

header("Location: gio-hang.php");
exit;
?>
