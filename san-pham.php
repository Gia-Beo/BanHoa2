<?php
include 'connect.php';

// Số sản phẩm mỗi trang
$limit = 16;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// Đếm tổng sản phẩm
$total_sql = "SELECT COUNT(*) AS total FROM san_pham";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Lấy dữ liệu sản phẩm
$sql = "SELECT * FROM san_pham LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
<header>
    <?php include 'header.php'; ?>
</header>
<main class="container">
    <h2>Danh sách sản phẩm</h2>
    <div class="product-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <?php
                // Xử lý đường dẫn ảnh
                $img = $row['anh'];
                if (preg_match('/^https?:\/\//', $img)) {
                    $src = $img; // ảnh online
                } else {
                    $src = "uploads/" . $img; // ảnh upload từ máy
                }
            ?>
            <div class="product-card">
                <a href="chi-tiet.php?id=<?php echo $row['id']; ?>">
                    <img src="<?php echo htmlspecialchars($src); ?>" alt="<?php echo htmlspecialchars($row['ten']); ?>">
                </a>
                <div class="product-info">
                    <div class="product-name"><?php echo htmlspecialchars($row['ten']); ?></div>
                    <div class="product-price">
                        <?php if (!empty($row['gia_khuyen_mai']) && $row['gia_khuyen_mai'] > 0): ?>
                            <span style="text-decoration:line-through; color:gray; margin-right:6px;">
                                <?php echo number_format($row['gia'], 0, ',', '.'); ?>₫
                            </span>
                            <span style="color:red; font-weight:bold;">
                                <?php echo number_format($row['gia_khuyen_mai'], 0, ',', '.'); ?>₫
                            </span>
                        <?php else: ?>
                            <?php echo number_format($row['gia'], 0, ',', '.'); ?>₫
                        <?php endif; ?>
                    </div>
                </div>
                <div class="product-actions">
                    <form method="post" action="them-gio-hang.php">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['ten']); ?>">
                        <input type="hidden" name="price" value="<?php echo (!empty($row['gia_khuyen_mai']) && $row['gia_khuyen_mai'] > 0) ? $row['gia_khuyen_mai'] : $row['gia']; ?>">
                        <input type="hidden" name="qty" value="1">
                        <input type="hidden" name="img" value="<?php echo htmlspecialchars($src); ?>">
                        <button type="submit" class="btn btn-add">Thêm vào giỏ</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Phân trang -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">« Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" 
               class="<?php echo ($i == $page) ? 'active' : ''; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Sau »</a>
        <?php endif; ?>
    </div>
</main>

<footer>
    <?php include 'footer.php'; ?>
</footer>
</body>
</html>
<?php $conn->close(); ?>
