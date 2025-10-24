<?php
include 'connect.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: dang-nhap.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo doanh thu & tồn kho</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background: #f7fafd;
            border-right: 1.5px solid #e4e9ef;
            z-index: 100;
        }
        .sidebar .sidebar-title {
            font-weight: bold;
            font-size: 1.25rem;
            padding: 24px 0 18px 0;
            text-align: center;
            letter-spacing: 1px;
            color: #1976d2;
        }
        .sidebar .menu {
            margin: 0; padding: 0;
            list-style: none;
        }
        .sidebar .menu li {
            margin: 6px 0;
        }
        .sidebar .menu a {
            display: block;
            padding: 12px 24px;
            color: #1c2331;
            font-size: 1.07rem;
            font-weight: 500;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar .menu a:hover, .sidebar .menu a.active {
            background: #e8f0fe;
            color: #1976d2;
        }
        .container-main {
            margin-left: 220px;
            padding: 36px 24px 24px 24px;
            min-height: 100vh;
            background: #f8fafc;
        }
        h1 {
            font-size: 1.65rem;
            color: #1976d2;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }
        .table-admin {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 16px #e1e5ee55;
            margin-bottom: 30px;
            font-size: 1.08rem;
            overflow: hidden;
        }
        .table-admin th, .table-admin td {
            padding: 13px 12px;
            text-align: center;
            border-bottom: 1px solid #f1f1f1;
        }
        .table-admin th {
            background: #f5f8fa;
            color: #213547;
            font-weight: 700;
        }
        .table-admin tr:nth-child(even) {
            background: #fafbfc;
        }
        .table-admin tr:hover {
            background: #f0f5ff;
        }
        .bold-row {
            font-weight: bold;
            background: #f3f3f3;
        }
        .highlight {
            font-weight: bold;
            background: #e8ffe8 !important;
            color: #2e7d32;
        }
        @media (max-width: 850px) {
            .sidebar {position: static; width: 100%; height: auto;}
            .container-main {margin-left: 0; padding: 18px;}
        }
        @media (max-width: 650px) {
            .table-admin th, .table-admin td {padding: 7px 2px; font-size: 0.96rem;}
            .container-main {padding: 3px;}
            h1 {font-size: 1.05rem;}
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-title">QUẢN TRỊ</div>
    <ul class="menu">
        <li><a href="admin.php?page=dashboard"><span>Tổng quan</span></a></li>
        <li><a href="admin.php?page=thuehoa"><span>Thuê hoa</span></a></li>
        <li><a href="admin.php?page=sanpham"><span>Quản lý sản phẩm</span></a></li>
        <li><a href="admin.php?page=donhang"><span>Quản lý đơn hàng</span></a></li>
        <li><a href="admin.php?page=baocao" class="active"><span>Báo Cáo</span></a></li>
        <li><a href="logout.php"><span>Đăng xuất</span></a></li>
    </ul>
</div>
<div class="container-main">
    <h1>BÁO CÁO DOANH THU, TỒN KHO & LỢI NHUẬN</h1>
    <table class="table-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá bán</th>
                <th>Giá vốn</th>
                <th>Số lượng ban đầu</th>
                <th>Đã bán</th>
                <th>Tồn kho</th>
                <th>Doanh thu</th>
                <th>Lợi nhuận</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT 
                        sp.id,
                        sp.ten,
                        sp.gia,
                        sp.gia_von,
                        sp.so_luong AS so_luong_ban_dau,
                        IFNULL(SUM(ctdh.so_luong), 0) AS da_ban,
                        (sp.so_luong - IFNULL(SUM(ctdh.so_luong), 0)) AS ton_kho,
                        IFNULL(SUM(ctdh.so_luong * ctdh.don_gia), 0) AS tong_tien,
                        IFNULL(SUM(ctdh.so_luong * (ctdh.don_gia - sp.gia_von)), 0) AS loi_nhuan
                    FROM san_pham sp
                    LEFT JOIN chi_tiet_don_hang ctdh ON sp.id = ctdh.san_pham_id
                    GROUP BY sp.id, sp.ten, sp.gia, sp.gia_von, sp.so_luong
                    ORDER BY sp.id DESC";
            $result = $conn->query($sql);
            $total_all = 0;
            $total_profit = 0;
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $total_all += $row['tong_tien'];
                    $total_profit += $row['loi_nhuan'];
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['ten']}</td>
                            <td>" . number_format($row['gia']) . "đ</td>
                            <td>" . number_format($row['gia_von']) . "đ</td>
                            <td>{$row['so_luong_ban_dau']}</td>
                            <td>{$row['da_ban']}</td>
                            <td>{$row['ton_kho']}</td>
                            <td>" . number_format($row['tong_tien']) . "đ</td>
                            <td>" . number_format($row['loi_nhuan']) . "đ</td>
                          </tr>";
                }
                echo "<tr class='bold-row'>
                        <td colspan='7' style='text-align:right;'>Tổng doanh thu:</td>
                        <td>" . number_format($total_all) . "đ</td>
                        <td></td>
                      </tr>";
                echo "<tr class='highlight'>
                        <td colspan='8' style='text-align:right;'>Tổng lợi nhuận:</td>
                        <td>" . number_format($total_profit) . "đ</td>
                      </tr>";
            } else {
                echo "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>