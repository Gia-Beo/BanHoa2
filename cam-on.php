
<?php
session_start();


if (!isset($_SESSION['don_hang_thanh_cong'])) {
    header("Location: index.php");
    exit();
}


unset($_SESSION['cart']);
unset($_SESSION['don_hang_thanh_cong']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="thank-you">
        <h1>🎉 Cảm ơn bạn đã đặt hàng!</h1>
        <p>Đơn hàng của bạn đã được ghi nhận. Chúng tôi sẽ liên hệ sớm.</p>
        <a href="index.php">Quay về trang chủ</a>
    </div>
</body>
</html>
