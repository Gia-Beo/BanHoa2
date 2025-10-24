<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'connect.php';

// Lấy ID đơn hàng từ URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID đơn hàng không hợp lệ!";
    exit();
}
$order_id = (int)$_GET['id'];

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "Không tìm thấy đơn hàng!";
    exit();
}

// Lấy danh sách sản phẩm trong đơn
$stmt = $conn->prepare("
    SELECT ct.*, sp.ten, sp.anh, sp.mota
    FROM chi_tiet_don_hang ct
    JOIN san_pham sp ON ct.san_pham_id = sp.id
    WHERE ct.don_hang_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Map trạng thái
$status_labels = [
    'dang_xu_ly' => 'Chờ xử lý',
    'dang_van_chuyen' => 'Đang giao',
    'thanh_cong' => 'Hoàn tất',
    'da_huy' => 'Đã hủy'
];
$statuses = ['dang_xu_ly','dang_van_chuyen','thanh_cong','da_huy'];
$current_status = $order['trang_thai'];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?= $order['id'] ?></title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="order">
      <div class="order-header">
        <h3>Đơn hàng #<?= $order['id'] ?></h3>
        <span class="order-status <?= $order['trang_thai'] ?>">
          <?= $status_labels[$order['trang_thai']] ?? 'Không xác định' ?>
        </span>
      </div>
      <div class="order-details">
        <b>Khách hàng:</b> <?= htmlspecialchars($order['ho_ten']) ?> |
        <b>SĐT:</b> <?= htmlspecialchars($order['so_dien_thoai']) ?> |
        <b>Địa chỉ:</b> <?= htmlspecialchars($order['dia_chi']) ?><br>
        Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['ngay'])) ?> &nbsp; | &nbsp;
        Tổng tiền: <b><?= number_format($order['tong_tien'], 0, ',', '.') ?>đ</b>
      </div>
      <!-- Timeline trạng thái -->
      <div class="order-timeline">
        <?php
        $reached = false;
        foreach ($statuses as $i => $status) {
            $active = !$reached ? 'active' : '';
            if ($status === $current_status) $reached = true;
            ?>
            <div class="timeline-step">
              <div class="timeline-dot <?= $active ?>"></div>
              <div class="timeline-label <?= $active ?>"><?= $status_labels[$status] ?></div>
            </div>
            <?php if ($i < count($statuses) - 1): ?>
                <div class="timeline-line"></div>
            <?php endif; ?>
        <?php } ?>
      </div>
      <div class="order-items">
        <?php if (empty($products)): ?>
            <div>Đơn hàng không có sản phẩm nào.</div>
        <?php else: foreach($products as $item): ?>
        <div class="order-item">
          <img src="<?= !empty($item['anh']) ? htmlspecialchars($item['anh']) : 'img/default.png' ?>" alt="<?= htmlspecialchars($item['ten']) ?>" />
          <div class="order-item-info">
            <div class="order-item-title"><?= htmlspecialchars($item['ten']) ?></div>
            <div class="order-item-desc"><?= htmlspecialchars($item['mota']) ?></div>
            <div class="order-item-qty">Số lượng: <?= $item['so_luong'] ?></div>
            <div class="order-item-price"><?= number_format($item['don_gia'],0,',','.') ?>đ</div>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>
      <a href="order_admin.php" class="btn-back">Quay lại</a>
    </div>
    
</body>
</html>