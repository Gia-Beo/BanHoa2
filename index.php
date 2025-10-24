<?php
$conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");

// Lấy sản phẩm nổi bật
$sql = "
SELECT sp.*, IFNULL(SUM(ctdh.so_luong),0) AS da_ban
FROM san_pham sp
LEFT JOIN chi_tiet_don_hang ctdh ON sp.id = ctdh.san_pham_id
GROUP BY sp.id
ORDER BY sp.noi_bat DESC, da_ban DESC, sp.id DESC
LIMIT 8";
$result = $conn->query($sql);

// Lấy banner
$banner_qr = $conn->query("SELECT * FROM banners ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang Chủ - Cây Cảnh</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="main.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display:700,400&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Dancing+Script&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'header.php'; ?>

  <!-- Hero -->
  <div class="gioi-thieu-hero">
    <div class="gioi-thieu-hero-text">
      <h1>Không Gian Hoa Cho Mỗi Ngày</h1>
      <p>Chúng tôi luôn cung cấp cho bạn những loại hoa đẹp nhất</p>
      <a href="san-pham.php" class="btn">Khám phá ngay</a>
    </div>
    <div class="gioi-thieu-hero-img">
      <img src="img/anh.jpg" alt="Cây cảnh tone hồng">
    </div>
  </div>

  <!-- Banner -->
  <?php if ($banner_qr->num_rows > 0): ?>
    <div class="homepage-banner-container">
      <?php while ($banner = $banner_qr->fetch_assoc()): ?>
        <div class="banner-slide">
          <img src="uploads/<?= htmlspecialchars($banner['hinh_anh']) ?>" alt="<?= htmlspecialchars($banner['tieu_de']) ?>">
        </div>
      <?php endwhile; ?>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        let currentBanner = 0;
        const banners = document.querySelectorAll('.banner-slide');
        if (banners.length > 0) {
          banners[0].classList.add('active');
          setInterval(() => {
            banners[currentBanner].classList.remove('active');
            currentBanner = (currentBanner + 1) % banners.length;
            banners[currentBanner].classList.add('active');
          }, 4000);
        }
      });
    </script>
  <?php else: ?>
    <div class="banner">
      <div>
        <div class="banner-title">Fresh Flowers &amp; Feeling Love</div>
        <div class="banner-desc">Đem thiên nhiên vào không gian sống của bạn cùng WEBCAYCANH</div>
        <a href="san-pham.php" class="btn">Shop Now</a>
      </div>
    </div>
  <?php endif; ?>

  <!-- Sản phẩm nổi bật -->
  <main class="container">
    <h2 class="section-title">Sản Phẩm Nổi Bật</h2>
    <div class="product-grid">
      <?php while($sp = $result->fetch_assoc()): ?>
        <?php
          // Xử lý ảnh sản phẩm
          $anh = 'images/no-image.png';
          if (!empty($sp['anh'])) {
              if (preg_match('#^https?://#i', $sp['anh'])) {
                  $anh = $sp['anh'];
              } elseif (file_exists(__DIR__ . '/uploads/' . $sp['anh'])) {
                  $anh = 'uploads/' . $sp['anh'];
              }
          }
        ?>
        <div class="product-card">
          <a href="chi-tiet.php?id=<?= $sp['id'] ?>">
            <img src="<?= htmlspecialchars($anh) ?>" alt="<?= htmlspecialchars($sp['ten']) ?>">
            <div class="product-info">
              <div class="product-name"><?= htmlspecialchars($sp['ten']) ?></div>
             <div class="product-price">
  <?php if (!empty($sp['gia_khuyen_mai']) && $sp['gia_khuyen_mai'] > 0): ?>
    <span style="text-decoration:line-through; color:gray; margin-right:6px;">
        <?= number_format($sp['gia'], 0, ',', '.'); ?>₫
    </span>
    <span style="color:red; font-weight:bold;">
        <?= number_format($sp['gia_khuyen_mai'], 0, ',', '.'); ?>₫
    </span>
  <?php else: ?>
    <?= number_format($sp['gia'], 0, ',', '.'); ?>₫
  <?php endif; ?>
</div>

            </div>
          </a>
          <form method="post" action="gio-hang.php">
            <input type="hidden" name="id" value="<?= $sp['id'] ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($sp['ten']) ?>">
           <input type="hidden" name="price" 
       value="<?= (!empty($sp['gia_khuyen_mai']) && $sp['gia_khuyen_mai'] > 0) ? $sp['gia_khuyen_mai'] : $sp['gia']; ?>">

            <input type="hidden" name="qty" value="1">
            <button type="submit" class="btn btn-add">Thêm vào giỏ</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</body>
</html>
<?php $conn->close(); ?>
