<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: dang-nhap.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', '250302bao', 'cay_canh');
mysqli_set_charset($conn, "utf8");


if (isset($_GET['xoa'])) {
    $xoa_id = (int) $_GET['xoa'];
    mysqli_query($conn, "DELETE FROM don_thue_hoa WHERE id = $xoa_id");
    header("Location: admin-don-thue.php");
    exit();
}

// Lấy danh sách đơn
$sql = "SELECT * FROM don_thue_hoa ORDER BY ngay_gui DESC";
$ds_don = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản Lý Đơn Thuê | Admin</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <header>
    <h1>Quản lý đơn thuê cây/hoa</h1>
    <a href="admin.php" class="button">← Quay lại</a>
  </header>

  <main class="main-content">
    <div class="table-wrapper">
      <table class="order-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Điện thoại</th>
            <th>Tên SP</th>
            <th>Thời gian thuê</th>
            <th>Hình thức</th>
            <th>Ghi chú</th>
            <th>Ngày gửi</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($don = mysqli_fetch_assoc($ds_don)) : ?>
            <tr>
              <td><?= $don['id'] ?></td>
              <td><?= htmlspecialchars($don['ho_ten']) ?></td>
              <td><?= htmlspecialchars($don['so_dien_thoai']) ?></td>
              <td><?= htmlspecialchars($don['ten_san_pham']) ?></td>
              <td><?= htmlspecialchars($don['thoi_gian']) ?></td>
              <td><?= htmlspecialchars($don['hinh_thuc_thue']) ?></td>
              <td><?= nl2br(htmlspecialchars($don['ghi_chu'])) ?></td>
              <td><?= $don['ngay_gui'] ?></td>
              <td>
                <a href="?xoa=<?= $don['id'] ?>" class="btn-delete" onclick="return confirm('Xoá đơn thuê này?')">Xoá</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>

  <?php mysqli_close($conn); ?>
</body>
</html>
