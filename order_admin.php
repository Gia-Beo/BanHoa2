<?php
// Chỉ start session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: dang-nhap.php");
    exit();
}

// Xử lý cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'], $_POST['status'])) {
    $id = (int)$_POST['update_id'];
    $status = $_POST['status'];

    // Validate ENUM
    $allowed_status = ['dang_xu_ly','dang_van_chuyen','thanh_cong','da_huy'];
    if (!in_array($status, $allowed_status)) {
        $status = 'dang_xu_ly';
    }

    $stmt = $conn->prepare("UPDATE don_hang SET trang_thai = ? WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("si", $status, $id);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    $message = "Đã cập nhật trạng thái đơn hàng #$id.";
}

// Truy vấn danh sách đơn hàng
$sql = "SELECT * FROM don_hang ORDER BY ngay DESC";
$result = $conn->query($sql);

// Map ENUM → label tiếng Việt để hiển thị
$status_labels = [
    'dang_xu_ly' => 'Chờ xử lý',
    'dang_van_chuyen' => 'Đang giao',
    'thanh_cong' => 'Hoàn tất',
    'da_huy' => 'Đã hủy'
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng - Admin</title>
    <link rel="stylesheet" href="main.css">
    <style>
    .btn-detail {
        background: #fff;
        color: #d13c8a;
        border: 1px solid #e68ab7;
        border-radius: 8px;
        padding: 7px 16px;
        font-weight: bold;
        font-size: 1rem;
        margin-left: 8px;
        transition: background 0.2s, color 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-detail:hover {
        background: #e68ab7;
        color: #fff;
    }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-content">
            <h1>Danh sách đơn hàng</h1>

            <?php if (isset($message)): ?>
                <p class="success"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <table class="table-order">
  <thead>
    <tr>
      <th>ID</th>
      <th>Khách hàng</th>
      <th>SĐT</th>
      <th>Địa chỉ</th>
      <th>Ngày đặt</th>
      <th>Tổng tiền</th>
      <th>Trạng thái</th>
      <th>Chi tiết</th>
    </tr>
  </thead>
 <tbody>
    <?php while($order = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $order['id'] ?></td>
      <td><?= htmlspecialchars($order['ho_ten']) ?></td>
      <td><?= htmlspecialchars($order['so_dien_thoai']) ?></td>
      <td><?= htmlspecialchars($order['dia_chi']) ?></td>
      <td><?= date('d/m/Y H:i', strtotime($order['ngay'])) ?></td>
      <td><?= number_format($order['tong_tien'],0,',','.') ?>đ</td>
      <td>
        <span class="status-badge status-<?= $order['trang_thai'] ?>">
          <?= $status_labels[$order['trang_thai']] ?>
        </span>
      </td>
      <td>
        <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn-detail">Chi tiết</a>
      </td>
    </tr>
    <?php endwhile; ?>
</tbody>
</table>
            <?php else: ?>
                <p>Không có đơn hàng nào.</p>
            <?php endif; ?>
        </div>
      
    </div>
        <a href="admin.php" class="btn-back">Quay lại</a>
</body>
</html>