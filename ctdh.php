<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: dang-nhap.php");
    exit();
}

include 'connect.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng | Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include 'admin-menu.php'; ?>

<div class="admin-container">
    <h2>Danh sách đơn hàng</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Ngày</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM don_hang ORDER BY ngay DESC";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <?= htmlspecialchars($row['ho_ten']) ?><br>
                    <?= htmlspecialchars($row['so_dien_thoai']) ?><br>
                    <?= nl2br(htmlspecialchars($row['dia_chi'])) ?>
                </td>
                <td><?= $row['ngay'] ?></td>
                <td><?= number_format($row['tong_tien']) ?> đ</td>
                <td><?= htmlspecialchars($row['trang_thai']) ?></td>
                <td><a href="order_detail.php?id=<?= $row['id'] ?>">Xem</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
