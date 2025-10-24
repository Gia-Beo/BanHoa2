<?php
// Kết nối CSDL
$conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
mysqli_set_charset($conn, "utf8");

// Kiểm tra dữ liệu gửi lên từ form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["hinh_anh"]) && isset($_POST["tieu_de"])) {
    $tieu_de = $_POST["tieu_de"];
    $hinh_anh = $_FILES["hinh_anh"]["name"];
    $tmp = $_FILES["hinh_anh"]["tmp_name"];

    // Tạo thư mục uploads nếu chưa có
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Đường dẫn lưu ảnh
    $duong_dan_luu = $upload_dir . basename($hinh_anh);

    // Di chuyển ảnh vào thư mục
    if (move_uploaded_file($tmp, $duong_dan_luu)) {
        // Thêm bản ghi vào bảng banners
        $sql = "INSERT INTO banners (tieu_de, hinh_anh) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $tieu_de, $hinh_anh);
        if ($stmt->execute()) {
            // Thành công → quay lại trang banner
            header("Location: banner_admin.php");
            exit();
        } else {
            echo "Lỗi truy vấn: " . $conn->error;
        }
    } else {
        echo "Không thể tải ảnh lên. Vui lòng thử lại.";
    }
} else {
    echo "Dữ liệu không hợp lệ!";
}
?>
