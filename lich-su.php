<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'connect.php';

// Nếu chưa đăng nhập -> chuyển đến đăng nhập
if (!isset($_SESSION['khach_hang'])) {
    header("Location: dang-nhap.php");
    exit();
}

$khach_hang_id = $_SESSION['khach_hang']['id'];

// Lấy danh sách đơn hàng của khách
$sql = "SELECT id, ngay, tong_tien, trang_thai 
        FROM don_hang 
        WHERE khach_hang_id = ? 
        ORDER BY ngay DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $khach_hang_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử mua hàng</title>
    <link rel="stylesheet" href="main.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: "Segoe UI", sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            padding: 20px 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .order-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            background: #fff;
            transition: 0.3s;
        }
        .order-card:hover {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .order-header {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            color: #007bff;
        }
        .order-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
            color: #fff;
        }
        .status-dang_xu_ly { background: #f0ad4e; }
        .status-dang_van_chuyen { background: #5bc0de; }
        .status-thanh_cong { background: #5cb85c; }
        .status-da_huy { background: #d9534f; }

        .order-items {
            margin-top: 10px;
        }
        .order-item {
            display: flex;
            align-items: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .order-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-right: 15px;
        }
        .order-item-info {
            flex: 1;
        }
        .order-item-info h4 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        .order-item-info p {
            margin: 3px 0;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Lịch sử mua hàng</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($order = $result->fetch_assoc()): ?>
            <div class="order-card">
                <div class="order-header">
                    Đơn hàng #<?= $order['id'] ?> - <?= date("d/m/Y H:i", strtotime($order['ngay'])) ?>
                </div>
                <div><strong>Tổng tiền:</strong> <?= number_format($order['tong_tien'], 0, ',', '.') ?> đ</div>
                <div>
                    <strong>Trạng thái:</strong>
                    <span class="order-status status-<?= str_replace(' ', '_', $order['trang_thai']) ?>">
                        <?= ucfirst($order['trang_thai']) ?>
                    </span>
                </div>

                <!-- Chi tiết sản phẩm -->
                <div class="order-items">
                    <?php
                    $sql_items = "SELECT sp.ten, sp.anh, ct.so_luong, ct.don_gia
                                  FROM chi_tiet_don_hang ct
                                  JOIN san_pham sp ON ct.san_pham_id = sp.id
                                  WHERE ct.don_hang_id = ?";
                    $stmt_items = $conn->prepare($sql_items);
                    $stmt_items->bind_param("i", $order['id']);
                    $stmt_items->execute();
                    $items = $stmt_items->get_result();

                    while ($item = $items->fetch_assoc()):
                        // Kiểm tra ảnh: nếu là URL thì giữ nguyên, còn nếu là file thì thêm đường dẫn uploads/
                        $imgPath = (preg_match('/^https?:\/\//', $item['anh']))
                            ? $item['anh']
                            : 'uploads/' . $item['anh'];
                    ?>
                    <div class="order-item">
                        <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($item['ten']) ?>">
                        <div class="order-item-info">
                            <h4><?= htmlspecialchars($item['ten']) ?></h4>
                            <p>Số lượng: <?= $item['so_luong'] ?></p>
                            <p>Giá: <?= number_format($item['don_gia'], 0, ',', '.') ?> đ</p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>
</body>
</html>
