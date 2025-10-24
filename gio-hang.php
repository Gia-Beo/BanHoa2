<?php
session_start();

// Xử lý thêm sản phẩm vào giỏ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $name = htmlspecialchars($_POST['name'] ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $qty = (int)($_POST['qty'] ?? $_POST['so_luong'] ?? 1);

    // Lấy ảnh từ database
    $conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
    mysqli_set_charset($conn, "utf8");
    $sql = "SELECT anh FROM san_pham WHERE id = $id";
    $res = $conn->query($sql);
    $img = '';
    if ($res && $res->num_rows == 1) {
        $row = $res->fetch_assoc();
        $img = $row['anh'];

        // Nếu không phải là link mạng và không bắt đầu bằng 'uploads/' thì thêm
        if (!filter_var($img, FILTER_VALIDATE_URL) && strpos($img, 'uploads/') !== 0) {
            $img = 'uploads/' . $img;
        }
    }
    $conn->close();

    // Thêm vào session giỏ hàng
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'qty' => $qty,
            'img' => $img
        ];
    }

    header("Location: gio-hang.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$tong = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Giỏ hàng của bạn</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>

<header>
  <?php include 'header.php'; ?>
</header>

<main class="container">
  <h2 style="margin-bottom:24px;">🛒 Giỏ hàng của bạn</h2>

  <?php if (empty($cart)): ?>
    <p>Chưa có sản phẩm nào trong giỏ hàng.</p>
    <a href="san-pham.php" class="btn">← Tiếp tục mua sắm</a>
  <?php else: ?>
    <div class="cart-product-grid">
      <?php foreach ($cart as $id => $item): 
        $idSanPham = $item['id'] ?? $id;
        $imgPath = $item['img'] ?? '';

        // Nếu không phải link mạng và file không tồn tại thì dùng ảnh mặc định
        if (!filter_var($imgPath, FILTER_VALIDATE_URL) && !file_exists($imgPath)) {
            $imgPath = "img/no-image.png";
        }

        $thanhtien = ($item['qty'] ?? 1) * ($item['price'] ?? 0);
        $tong += $thanhtien;
      ?>
        <div class="cart-card">
          <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($item['name'] ?? 'Sản phẩm') ?>" style="width:140px;height:140px;object-fit:cover;border-radius:8px;">
          <div class="cart-name"><?= htmlspecialchars($item['name'] ?? 'Tên sản phẩm') ?></div>
          <div class="cart-price">Giá: <?= number_format($item['price'] ?? 0, 0, ',', '.') ?>₫</div>
          <div class="cart-qty">Số lượng: <?= $item['qty'] ?? 1 ?></div>
          <div class="cart-total">Thành tiền: <?= number_format($thanhtien, 0, ',', '.') ?>₫</div>
          <a href="xoa-gio-hang.php?id=<?= $idSanPham ?>" class="btn-delete" onclick="return confirm('Xóa sản phẩm này khỏi giỏ?')">Xóa</a>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="margin-top:30px; text-align:right; font-size:18px;">
      <b>Tổng tiền: <span style="color:#705dee;"><?= number_format($tong, 0, ',', '.') ?>₫</span></b>
    </div>
    <div style="text-align:right; margin-top:20px;">
      <a href="dat-hang.php" class="btn">🧾 Đặt hàng</a>
    </div>
  <?php endif; ?>
</main>

<footer>
  <?php include 'footer.php'; ?>
</footer>

</body>
</html>
