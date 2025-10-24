<?php
session_start();
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['khach_hang'])) {
    header("Location: dang-nhap.php");
    exit();
}

$user_id = $_SESSION['khach_hang']['id'];

// Lấy thông tin khách hàng
$sql = "SELECT * FROM khach_hang WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Xử lý cập nhật thông tin + avatar
if (isset($_POST['update'])) {
    $ten = $_POST['ten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];
    $avatar_name = $user['avatar']; // giữ avatar cũ nếu không đổi

    // Nếu có upload avatar mới
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Giới hạn loại file + dung lượng < 2MB
        if (in_array($file_type, ["jpg", "jpeg", "png"]) && $_FILES["avatar"]["size"] < 2*1024*1024) {
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                $avatar_name = $file_name;
            }
        }
    }

    // Update vào DB
    $sql = "UPDATE khach_hang SET ten=?, email=?, sdt=?, dia_chi=?, avatar=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $ten, $email, $sdt, $dia_chi, $avatar_name, $user_id);

    if ($stmt->execute()) {
        // Cập nhật lại session đầy đủ
        $_SESSION['khach_hang'] = [
            'id' => $user_id,
            'ten' => $ten,
            'email' => $email,
            'sdt' => $sdt,
            'dia_chi' => $dia_chi,
            'avatar' => $avatar_name
        ];
        header("Location: profile.php");
        exit();
    } else {
        echo "❌ Lỗi khi cập nhật thông tin.";
    }
}

// Avatar mặc định
$avatar = !empty($user['avatar']) ? "uploads/" . $user['avatar'] : "uploads/avatar.png";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin khách hàng</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="profile-container">
        <h2>Thông tin khách hàng</h2>

        <!-- Form chung cho Avatar + Thông tin -->
        <form action="profile.php" method="POST" enctype="multipart/form-data" class="upload-form">
            <!-- Avatar -->
            <img src="<?php echo $avatar; ?>" alt="Avatar" class="avatar">
            <input type="file" name="avatar" accept="image/*">

            <!-- Thông tin -->
            <div class="profile-info">
                <p><strong>Họ tên:</strong> 
                    <input type="text" name="ten" value="<?php echo $user['ten']; ?>" required>
                </p>
                <p><strong>Email:</strong> 
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                </p>
                <p><strong>Số điện thoại:</strong> 
                    <input type="text" name="sdt" value="<?php echo $user['sdt']; ?>">
                </p>
                <p><strong>Địa chỉ:</strong> 
                    <input type="text" name="dia_chi" value="<?php echo $user['dia_chi']; ?>">
                </p>
            </div>

            <button type="submit" name="update">Cập nhật</button>
            <a href="index.php" class="btn-back">Quay lại</a>
        </form>
    </div>
</body>
</html>
