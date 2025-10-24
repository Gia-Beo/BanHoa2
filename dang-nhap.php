<?php
session_start();
require_once 'connect.php';

$thong_bao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'];
    $mat_khau = $_POST['mat_khau'];

    // Chỉ lấy thông tin người dùng theo email
    $stmt = $conn->prepare("SELECT * FROM khach_hang WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // So sánh mật khẩu nhập vào với mật khẩu đã mã hóa trong DB
       if (password_verify($mat_khau, $user['matkhau'])) {
    // Avatar mặc định nếu DB chưa có
    $avatar = !empty($user['avatar']) ? $user['avatar'] : "avatar.png";

    $_SESSION['khach_hang'] = [
        'id'    => $user['id'],
        'ten'   => $user['ten'],
        'email' => $user['email'],
        'sdt'   => $user['sdt'],
        'dia_chi' => $user['dia_chi'],
        'avatar'  => $avatar,
        'is_admin'=> $user['is_admin']
    ];

    $_SESSION['tenkh']   = $user['ten'];
    $_SESSION['is_admin'] = $user['is_admin'];

    if ($user['is_admin'] == 1) {
        header('Location: admin.php');
    } else {
        header('Location: index.php');
    }
    exit();
}

    $thong_bao = '❌ Sai email hoặc mật khẩu!';
}}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
  <div class="form-container">
    <h2>Đăng nhập</h2>
    <?php if (!empty($thong_bao)) : ?>
        <div class="error"><?php echo $thong_bao; ?></div>
    <?php endif; ?>
    <form action="dang-nhap.php" method="post">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="mat_khau" placeholder="Mật khẩu" required><br>
        <button type="submit">Đăng nhập</button>
    </form>
    <p style="text-align:center; margin-top:16px;">Chưa có tài khoản? <a href="dang-ki.php">Đăng ký ngay</a></p>
</div>

</body>
</html>
