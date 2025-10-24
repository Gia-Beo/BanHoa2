<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: dang-nhap.php');
    exit();
}
$conn = mysqli_connect('localhost', 'root', '250302bao', 'cay_canh');
mysqli_set_charset($conn, "utf8");

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $sql = "DELETE FROM san_pham WHERE id = $id";
    mysqli_query($conn, $sql);
}
header("Location: rest_admin.php");
exit();
?>