    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: dang-nhap.php');
        exit();
    }

    $conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
    mysqli_set_charset($conn, "utf8");

    $result = $conn->query("SELECT * FROM banners ORDER BY created_at DESC");
    ?>

    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Quản lý Banner | Admin</title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>
        <div class="admin-menu">
            <h2>QUẢN LÝ BANNER</h2>

            <form action="xu-ly-them-banner.php" method="POST" enctype="multipart/form-data" class="form-banner">
                <label>Tiêu đề:</label>
                <input type="text" name="tieu_de" required>

                <label>Hình ảnh:</label>
                <input type="file" name="hinh_anh" accept="image/*" required>

                <button type="submit" class="btn">Thêm banner</button>
            </form>

            <h3>Danh sách banner</h3>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Hình ảnh</th>
                    <th>Thời gian</th>
                    <th>Xoá</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['tieu_de'] ?></td>
                        <td><img src="uploads/<?= $row['hinh_anh'] ?>" width="150"></td>
                        <td><?= $row['created_at'] ?></td>
                        <td><a href="xoa-banner.php?id=<?= $row['id'] ?>" onclick="return confirm('Xoá banner này?')">Xoá</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <br>
            <a href="admin.php" class="btn">← Quay lại trang Admin</a>
        </div>
    </body>
    </html>
