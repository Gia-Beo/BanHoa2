<?php
include 'connect.php';


// Biến lưu kết quả
$ket_qua = [];

// Khi người dùng bấm submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gioi_tinh = $_POST["gioi_tinh"];
    $moi_quan_he = $_POST["moi_quan_he"];
    $dip_tang = $_POST["dip_tang"];
    $ngan_sach = $_POST["ngan_sach"];

    // Xử lý ngân sách
    if ($ngan_sach == "100-200") {
        $gia_min = 100000; $gia_max = 200000;
    } elseif ($ngan_sach == "200-500") {
        $gia_min = 200000; $gia_max = 500000;
    } else {
        $gia_min = 500000; $gia_max = 10000000;
    }

    // Truy vấn sản phẩm phù hợp
    $sql = "SELECT * FROM san_pham 
            WHERE gioi_tinh LIKE ? 
              AND dip LIKE ? 
              AND gia BETWEEN ? AND ?
            LIMIT 12";

    $stmt = $conn->prepare($sql);
    $gioi_tinh_wild = "%$gioi_tinh%";
    $dip_wild = "%$dip_tang%";
    $stmt->bind_param("ssii", $gioi_tinh_wild, $dip_wild, $gia_min, $gia_max);
    $stmt->execute();
    $result = $stmt->get_result();
    $ket_qua = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tư vấn chọn cây cảnh</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
    <header>
      <?php include 'header.php'; ?>
   </header>
<div class="container">
    <h2 class="section-title">Tư vấn Hoa</h2>
    
    <!-- Form tư vấn -->
    <form method="POST" action="tu-van-cay-canh.php" class="advice-form">
        <div class="form-group">
            <label>Người nhận là:</label>
            <select name="gioi_tinh" required>
                <option value="">-- Chọn giới tính --</option>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>
        </div>
        <div class="form-group">
            <label>Mối quan hệ:</label>
            <select name="moi_quan_he" required>
                <option value="">-- Chọn mối quan hệ --</option>
                <option value="Bạn bè">Bạn bè</option>
                <option value="Người yêu">Người yêu</option>
                <option value="Sếp">Sếp</option>
                <option value="Mẹ">Mẹ</option>
                <option value="Cha">Cha</option>
                <option value="Khác">Khác</option>
            </select>
        </div>
        <div class="form-group">
            <label>Dịp tặng:</label>
            <select name="dip_tang" required>
                <option value="">-- Chọn dịp --</option>
                <option value="Sinh nhật">Sinh nhật</option>
                <option value="Valentine">Valentine</option>
                <option value="8/3">8/3</option>
                <option value="20/10">20/10</option>
                <option value="Tốt nghiệp">Tốt nghiệp</option>
                <option value="Khác">Khác</option>
            </select>
        </div>
        <div class="form-group">
            <label>Ngân sách:</label>
            <select name="ngan_sach" required>
                <option value="">-- Chọn ngân sách --</option>
                <option value="100-200">100.000đ - 200.000đ</option>
                <option value="200-500">200.000đ - 500.000đ</option>
                <option value="tren-500">Trên 500.000đ</option>
            </select>
        </div>
        <button type="submit" class="btn">Gợi ý ngay</button>
    </form>

    <!-- Hiển thị kết quả -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <h3 class="section-title">Gợi ý sản phẩm phù hợp:</h3>
        <?php if (!empty($ket_qua)): ?>
            <div class="product-grid">
                <?php foreach ($ket_qua as $sp): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($sp['anh']); ?>" alt="<?php echo htmlspecialchars($sp['ten']); ?>">
                        <h4><?php echo htmlspecialchars($sp['ten']); ?></h4>
                        <p><?php echo number_format($sp['gia'], 0, ',', '.'); ?>đ</p>
                        <a href="chi-tiet.php?id=<?php echo $sp['id']; ?>" class="btn btn-add">Xem chi tiết</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm phù hợp.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
