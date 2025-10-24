<?php
session_start();

// Lấy giỏ hàng
$cart = $_SESSION['cart'] ?? [];

// Kiểm tra đăng nhập
if (!isset($_SESSION['khach_hang'])) {
    die("Bạn chưa đăng nhập!");
}

// Kiểm tra giỏ hàng
if (empty($cart)) {
    die("Giỏ hàng trống!");
}

// Lấy ID khách hàng
$khach_hang_id = $_SESSION['khach_hang']['id'] ?? null;

// Lấy thông tin từ form
$ho_ten = $_POST['txt_billing_fullname'] ?? '';
$so_dien_thoai = $_POST['txt_billing_mobile'] ?? '';
$dia_chi = $_POST['txt_inv_addr1'] ?? '';
$email = $_POST['txt_billing_email'] ?? '';

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// ✅ Tính tổng tiền
$tong_tien = 0;
foreach ($cart as $sp) {
    $gia = $sp['gia'] ?? $sp['price'] ?? 0;
    $so_luong = $sp['so_luong'] ?? $sp['qty'] ?? 1;
    $tong_tien += $gia * $so_luong;
}

// ✅ Lưu đơn hàng
$sql = "INSERT INTO don_hang (khach_hang_id, ho_ten, so_dien_thoai, dia_chi, tong_tien, ngay, trang_thai)
        VALUES (?, ?, ?, ?, ?, NOW(), 'dang_xu_ly')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssi", $khach_hang_id, $ho_ten, $so_dien_thoai, $dia_chi, $tong_tien);
$stmt->execute();
$don_hang_id = $stmt->insert_id;

// ✅ Lưu chi tiết đơn hàng
foreach ($cart as $id => $sp) {
    $gia = $sp['gia'] ?? $sp['price'] ?? 0;
    $so_luong = $sp['so_luong'] ?? $sp['qty'] ?? 1;
    $sql_ct = "INSERT INTO chi_tiet_don_hang (don_hang_id, san_pham_id, so_luong, don_gia)
               VALUES (?, ?, ?, ?)";
    $stmt_ct = $conn->prepare($sql_ct);
    $stmt_ct->bind_param("iiii", $don_hang_id, $id, $so_luong, $gia);
    $stmt_ct->execute();
}

// ✅ Lưu tổng tiền vào session cho VNPay
$_SESSION['tong_tien'] = $tong_tien;
$_SESSION['don_hang_id'] = $don_hang_id;

// Đóng kết nối
$conn->close();
?>

<!-- ✅ Tự động gửi form sang VNPay -->
<form id="vnpay_form" action="vnpay_php/vnpay_create_payment.php" method="POST">
    <input type="hidden" name="amount" value="<?= $tong_tien ?>">
    <input type="hidden" name="order_id" value="<?= $don_hang_id ?>">
    <input type="hidden" name="order_desc" value="Thanh toán đơn hàng #<?= $don_hang_id ?>">
    <input type="hidden" name="order_type" value="billpayment">
    <input type="hidden" name="language" value="vn">
    <input type="hidden" name="bank_code" value="">
    <input type="hidden" name="txtexpire" value="<?= date('YmdHis', strtotime('+15 minutes')) ?>">
    <input type="hidden" name="txt_billing_mobile" value="<?= $so_dien_thoai ?>">
    <input type="hidden" name="txt_billing_email" value="<?= $email ?>">
    <input type="hidden" name="txt_billing_fullname" value="<?= $ho_ten ?>">
    <input type="hidden" name="txt_inv_addr1" value="<?= $dia_chi ?>">
</form>

<script>
document.getElementById("vnpay_form").submit();
</script>
