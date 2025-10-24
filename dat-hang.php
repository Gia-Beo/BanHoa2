<?php
session_start();
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("Giỏ hàng trống!");
}

$khach_hang = $_SESSION['khach_hang'] ?? null;

// Tổng tiền giỏ hàng
$tong_tien = 0;
foreach ($cart as $item) {
    $gia = $item['gia'] ?? $item['price'] ?? 0;
    $so_luong = $item['so_luong'] ?? $item['qty'] ?? 1;
    $tong_tien += $gia * $so_luong;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán VNPay</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
<div class="form-container">
    <h2>Thông tin đặt hàng và thanh toán</h2>
    
    <!-- Gửi sang file xu-ly-dat-hang.php -->
    <form action="xu-ly-dat-hang.php" method="post">

        <label for="ho_ten">Họ tên:</label>
        <input type="text" id="ho_ten" name="txt_billing_fullname" 
               value="<?= $khach_hang['ho_ten'] ?? '' ?>" required>

        <label for="so_dien_thoai">Số điện thoại:</label>
        <input type="text" id="so_dien_thoai" name="txt_billing_mobile" 
               value="<?= $khach_hang['so_dien_thoai'] ?? '' ?>" required>

        <label for="dia_chi">Địa chỉ:</label>
        <input type="text" id="dia_chi" name="txt_inv_addr1" 
               value="<?= $khach_hang['dia_chi'] ?? '' ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="txt_billing_email" required>

        <input type="hidden" name="amount" value="<?= (int)$tong_tien ?>">

        <button type="submit">Xác nhận & Thanh toán VNPay</button>
    </form>
</div>
</body>
</html>
