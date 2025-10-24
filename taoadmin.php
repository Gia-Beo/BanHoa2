<?php
$conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->query("DELETE FROM khach_hang WHERE email='admin@gmail.com'");

$matkhau = password_hash('admin', PASSWORD_DEFAULT);
$sql = "INSERT INTO khach_hang (ten, email, matkhau, is_admin)
        VALUES ('admin', 'admin@gmail.com', '$matkhau', 1)";
if ($conn->query($sql) === TRUE) {
    echo "Tạo tài khoản admin thành công!";
} else {
    echo "Lỗi: " . $conn->error;
}
$conn->close();
?>