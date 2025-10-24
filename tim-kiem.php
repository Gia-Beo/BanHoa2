<?php
$conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$tu_khoa = $_GET['tu_khoa'] ?? '';

$sql = "SELECT * FROM san_pham WHERE ten LIKE '%" . $conn->real_escape_string($tu_khoa) . "%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Kết quả tìm kiếm</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
  <header><?php include 'header.php'; ?></header>
  <div class="container">
    <h2>Kết quả tìm kiếm cho: <em><?= htmlspecialchars($tu_khoa) ?></em></h2>
    <div class="product-grid">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="product-card">
            <a href="chi-tiet.php?id=<?= $row['id'] ?>">
              <?php
              $anh = trim($row['anh']);
              if (preg_match('/^https?:\/\//', $anh)) {
                  $duong_dan_anh = $anh; // ảnh online
              } else {
                  $anh = basename($anh); // chỉ lấy tên file, tránh trùng đường dẫn
                  $duong_dan_anh = 'img/products/' . $anh;
              }
              ?>
              <img src="<?= htmlspecialchars($duong_dan_anh) ?>" alt="<?= htmlspecialchars($row['ten']) ?>">

              <div class="product-info">
                <div class="product-name"><?= htmlspecialchars($row['ten']) ?></div>
                <div class="product-price"><?= number_format($row['gia'], 0, ',', '.') ?> đ</div>
              </div>
            </a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Không tìm thấy sản phẩm nào phù hợp.</p>
      <?php endif; ?>
    </div>
    <a href="index.php" class="btn">← Quay lại trang chủ</a>
  </div>
  <footer><?php include 'footer.php'; ?></footer>
</body>
</html>

<?php $conn->close(); ?>
