<?php
// Kết nối cơ sở dữ liệu
include 'connect.php';

// Lấy dữ liệu từ form
$gioi_tinh = $_POST['gioi_tinh'] ?? '';
$moi_quan_he = $_POST['moi_quan_he'] ?? '';
$dip_tang = $_POST['dip_tang'] ?? '';
$ngan_sach = $_POST['ngan_sach'] ?? '';
$thoi_gian = $_POST['thoi_gian'] ?? '';

// Gợi ý sản phẩm dựa trên ngân sách và dịp tặng
$sql = "SELECT * FROM san_pham WHERE gia <= ? AND loai_hoa LIKE ? LIMIT 6";

// Chuẩn bị và thực thi truy vấn
$stmt = $conn->prepare($sql);
$search_dip = '%' . $dip_tang . '%';
$stmt->bind_param("is", $ngan_sach, $search_dip);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Gợi ý sản phẩm</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2 style="text-align: center; margin: 40px 0;">Gợi ý sản phẩm phù hợp với bạn</h2>

        <div class="product-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="img/<?php echo $row['anh']; ?>" alt="<?php echo $row['ten']; ?>">
                        <h3><?php echo $row['ten']; ?></h3>
                        <p><?php echo number_format($row['gia']); ?>đ</p>
                        <a href="chi-tiet.php?id=<?php echo $row['id']; ?>" class="btn">Xem chi tiết</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Không tìm thấy sản phẩm phù hợp.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
