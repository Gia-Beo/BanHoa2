<?php
session_start();
if (!isset($_SESSION['makh']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}
$conn = new mysqli("localhost", "root", "250302bao", "cay_canh");

$message = '';
// Lấy danh sách danh mục
$danhmuc = $conn->query("SELECT * FROM danh_muc");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tensp = $_POST['tensp'];
    $madm = $_POST['madm'];
    $gia = $_POST['gia'];
    // Xử lý upload hình
    $img_name = '';
    if (!empty($_FILES['hinhanh']['name'])) {
        $img_name = time().'_'.basename($_FILES['hinhanh']['name']);
        move_uploaded_file($_FILES['hinhanh']['tmp_name'], "uploads/$img_name");
    }
    $sql = "INSERT INTO san_pham (tensp, madm, gia, hinhanh) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sids", $tensp, $madm, $gia, $img_name);
    if ($stmt->execute()) {
        $message = "Đã thêm sản phẩm!";
    } else {
        $message = "Lỗi: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
   
    <div class="form-container">
        <h2>Thêm sản phẩm</h2>
        <?php if ($message) echo "<div style='color:green;'>$message</div>"; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="tensp" placeholder="Tên sản phẩm" required>
            <select name="madm" required>
                <option value="">--Chọn danh mục--</option>
                <?php while($dm = $danhmuc->fetch_assoc()): ?>
                    <option value="<?php echo $dm['madm']; ?>"><?php echo htmlspecialchars($dm['ten_danhmuc']); ?></option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="gia" placeholder="Giá" required>
            <input type="file" name="hinhanh" accept="image/*">
            <button type="submit">Thêm</button>
        </form>
        <div style="text-align:center;margin-top:12px;">
            <a href="admin_products.php" style="color:#228b22;">← Quay lại</a>
        </div>
    </div>
    
</body>
</html>