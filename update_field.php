<?php
$conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
$conn->set_charset("utf8");

$id = (int)$_POST['id'];
$field = $_POST['field'];
$value = $_POST['value'];


$allowed = ['ten','gia','gia_von','so_luong','loai_hoa','mau_sac','so_canh','xuat_xu','gioi_tinh','dip','mota'];
if (!in_array($field, $allowed)) {
    die("Trường không hợp lệ");
}


$sql = "UPDATE san_pham SET $field=? WHERE id=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi prepare: " . $conn->error);
}


$type = "s"; 
if (in_array($field, ['gia','gia_von','so_luong','so_canh'])) {
    $type = "i"; 
}

$stmt->bind_param($type."i", $value, $id);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Lỗi: " . $conn->error;
}
