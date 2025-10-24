<?php
include 'connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<p>Sản phẩm không tồn tại!</p>";
    exit;
}

// Dùng prepared statement cho an toàn
$stmt = $conn->prepare("SELECT * FROM san_pham WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "<p>Sản phẩm không tồn tại!</p>";
    exit;
}
$row = $result->fetch_assoc();
$stmt->close();

/*
 Xử lý ảnh:
 - Nếu là URL (http/https) thì dùng nguyên.
 - Nếu không thì giả sử DB lưu "tên file" -> kiểm tra uploads/<tên file> có tồn tại không.
 - Nếu không tồn tại -> dùng ảnh mặc định images/no-image.png
*/
$raw = isset($row['anh']) ? trim($row['anh']) : '';
$src = 'images/no-image.png'; // fallback mặc định

if ($raw !== '') {
    // nếu bắt đầu bằng http:// hoặc https:// -> giữ nguyên
    if (preg_match('#^https?://#i', $raw)) {
        $src = $raw;
    } else {
        // loại bỏ dấu / đầu nếu có
        $filename = ltrim($raw, '/\\');
        $localRelative = 'uploads/' . $filename; // đường dẫn dùng trong <img src="...">
        $localAbsolute = __DIR__ . '/' . $localRelative; // đường dẫn để kiểm tra file_exists trên server

        if (file_exists($localAbsolute) && is_file($localAbsolute)) {
            $src = $localRelative;
        } else {
            // thử thêm 1 khả năng: DB có thể đã lưu đường dẫn uploads/xyz
            $try = ltrim($raw, '/\\');
            if (file_exists(__DIR__ . '/' . $try) && is_file(__DIR__ . '/' . $try)) {
                $src = $try;
            } else {
                // vẫn không có -> giữ ảnh mặc định
                $src = 'images/no-image.png';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chi tiết sản phẩm - <?php echo htmlspecialchars($row['ten']); ?></title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
   <header>
      <?php include 'header.php'; ?>
   </header>

  <main class="container">
    <div class="product-detail">
      <img src="<?php echo htmlspecialchars($src); ?>" alt="<?php echo htmlspecialchars($row['ten']); ?>">
      <div class="product-info">
        <h2><?php echo htmlspecialchars($row['ten']); ?></h2>

        <!-- Giá sản phẩm -->
        <div class="price">
          <?php if (!empty($row['gia_khuyen_mai']) && $row['gia_khuyen_mai'] > 0): ?>
              <span class="old-price">
                  <?php echo number_format($row['gia'], 0, ',', '.'); ?>₫
              </span>
              <span class="sale-price">
                  <?php echo number_format($row['gia_khuyen_mai'], 0, ',', '.'); ?>₫
              </span>
          <?php else: ?>
              <span class="sale-price"><?php echo number_format($row['gia'], 0, ',', '.'); ?>₫</span>
          <?php endif; ?>
        </div>

        <ul>
          <li><strong>Loại hoa:</strong> <?= !empty($row['loai_hoa']) ? htmlspecialchars($row['loai_hoa']) : "Đang cập nhật"; ?></li>
          <li><strong>Màu sắc:</strong> <?= !empty($row['mau_sac']) ? htmlspecialchars($row['mau_sac']) : "Đang cập nhật"; ?></li>
          <li><strong>Số lượng cành:</strong> <?= isset($row['so_canh']) ? intval($row['so_canh']) : "Đang cập nhật"; ?></li>
          <li><strong>Xuất xứ:</strong> <?= !empty($row['xuat_xu']) ? htmlspecialchars($row['xuat_xu']) : "Đang cập nhật"; ?></li>
          <li><strong>Số lượng còn:</strong> <?= isset($row['so_luong']) ? intval($row['so_luong']) : "Đang cập nhật"; ?></li>
        </ul>

        <div class="desc">
          <strong>Mô tả sản phẩm:</strong><br>
          <?= !empty($row['mota']) ? nl2br(htmlspecialchars($row['mota'])) : "Chưa có mô tả cho sản phẩm này."; ?>
        </div>

        <form method="POST" action="gio-hang.php" class="actions">
          <input type="hidden" name="id" value="<?= $row['id']; ?>">
          <input type="hidden" name="name" value="<?= htmlspecialchars($row['ten']); ?>">
          <input type="hidden" name="price" value="<?= (!empty($row['gia_khuyen_mai']) && $row['gia_khuyen_mai'] > 0) ? $row['gia_khuyen_mai'] : $row['gia']; ?>">
          <input type="hidden" name="img" value="<?= htmlspecialchars($src); ?>">

          <label for="so_luong">Số lượng:</label>
          <input type="number" name="so_luong" value="1" min="1" max="<?= isset($row['so_luong']) ? intval($row['so_luong']) : 99; ?>" style="width:60px;">
          <button type="submit" style="margin-left:8px;">Thêm vào giỏ</button>
        </form>

        <div class="actions">
          <a href="https://www.facebook.com/bao.huu.56027281" target="_blank" class="btn-consult">Liên hệ tư vấn</a>
        </div>
      </div>
    </div>
  </main>

 <footer>
    <?php include 'footer.php'; ?>
 </footer>
</body>
</html>
<?php $conn->close(); ?>
