<?php
$conn = mysqli_connect('localhost', 'root', '250302bao', 'cay_canh');
mysqli_set_charset($conn, 'utf8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $san_pham_id = (int)($_POST['san_pham_id'] ?? 0);
    $ten_san_pham = trim($_POST['ten_san_pham'] ?? '');
    $ho_ten = trim($_POST['ho_ten'] ?? '');
    $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
    $hinh_thuc_thue = $_POST['hinh_thuc_thue'] ?? '';
    $thoi_gian = trim($_POST['thoi_gian'] ?? '');
    $ghi_chu = trim($_POST['ghi_chu'] ?? '');

    // Kiểm tra dữ liệu hợp lệ
    if ($ho_ten && $so_dien_thoai && $hinh_thuc_thue) {
        $sql = "INSERT INTO don_thue_hoa 
                (san_pham_id, ten_san_pham, ho_ten, so_dien_thoai, hinh_thuc_thue, thoi_gian, ghi_chu) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "issssss", $san_pham_id, $ten_san_pham, $ho_ten, $so_dien_thoai, $hinh_thuc_thue, $thoi_gian, $ghi_chu);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Có thể chuyển đến trang cảm ơn hoặc thông báo thành công
        echo "<h2>✅ Gửi yêu cầu thuê hoa thành công!</h2>";
        echo "<p><a href='cho-thue-cay-canh.php'>← Quay lại trang thuê</a></p>";
    } else {
        echo "<h3 style='color:red;'>❌ Vui lòng nhập đầy đủ thông tin!</h3>";
    }
} else {
    header("Location: cho-thue-cay-canh.php");
    exit();
}

mysqli_close($conn);
?>
