<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: dang-nhap.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', '250302bao', 'cay_canh');
mysqli_set_charset($conn, "utf8");

$id = (int)($_GET['id'] ?? 0);
$sql = "SELECT * FROM san_pham WHERE id = $id";
$result = mysqli_query($conn, $sql);
$sp = mysqli_fetch_assoc($result);

if (!$sp) {
    echo "Không tìm thấy sản phẩm!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = trim($_POST['ten'] ?? '');
    $gia = (int)($_POST['gia'] ?? 0);
    $mota = trim($_POST['mota'] ?? '');
    $anh = trim($_POST['anh'] ?? '');

    if ($anh == '') $anh = $sp['anh'];

    if ($ten && $gia > 0) {
        $sql_update = "UPDATE san_pham SET ten=?, gia=?, mota=?, anh=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt, "sissi", $ten, $gia, $mota, $anh, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: rest_admin.php");
        exit();
    } else {
        $err = "Vui lòng nhập đủ thông tin hợp lệ!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa Sản Phẩm</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
  <div class="form-container">
    <h2>Sửa Sản Phẩm</h2>
    <?php if (!empty($err)) echo "<div style='color:red;'>$err</div>"; ?>
    <form method="post">
      <input type="text" name="ten" value="<?= htmlspecialchars($sp['ten']) ?>" required>
      <input type="number" name="gia" value="<?= $sp['gia'] ?>" min="0" required>
      <input type="text" name="mota" value="<?= htmlspecialchars($sp['mota']) ?>">
      <input type="text" name="anh" value="<?= htmlspecialchars($sp['anh']) ?>" placeholder="Link ảnh (bỏ trống để giữ nguyên)">
      <button type="submit" class="btn">Cập nhật</button>
      <a href="rest_admin.php" class="btn-delete" style="background:#888;">Quay lại</a>
    </form>
  </div>
</body>
</html>
<?php
mysqli_close($conn);
?>