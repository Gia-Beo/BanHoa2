<?php
require 'connect.php';

$thong_bao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = trim($_POST['ten']);
    $email = trim($_POST['email']);
    $mat_khau = $_POST['mat_khau'];

    // Kiểm tra email đã tồn tại chưa (dùng prepare)
    $stmt = $conn->prepare("SELECT id FROM khach_hang WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $thong_bao = "❌ Email đã tồn tại!";
    } else {
        // Mã hóa mật khẩu trước khi lưu
        $hashed_password = password_hash($mat_khau, PASSWORD_DEFAULT);

        // Dùng prepare để chèn dữ liệu
        $stmt = $conn->prepare("INSERT INTO khach_hang (ten, email, matkhau) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $ten, $email, $hashed_password);

        if ($stmt->execute()) {
            header('Location: dang-nhap.php');
            exit();
        } else {
            $thong_bao = "❌ Đăng ký thất bại! Vui lòng thử lại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="form-container">
        <h2>Đăng Ký</h2>
        <form method="post">
            <input type="text" name="ten" placeholder="Họ tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mat_khau" placeholder="Mật khẩu" required>
            <button type="submit" class="btn-submit">Đăng ký</button>
        </form>
        <p class="msg"><?= $thong_bao ?></p>
        <p style="text-align:center; margin-top:16px;">Đã có tài khoản? <a href="dang-nhap.php">Đăng nhập</a></p>
    </div>
</body>
</html>
