<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$conn = new mysqli("localhost", "root", "250302bao", "cay_canh");
$conn->set_charset("utf8");

$message = '';

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $ten = $_POST['ten'];
    $gia = (int)$_POST['gia'];
    $gia_von = (int)$_POST['gia_von'];
    $so_luong = (int)$_POST['so_luong'];
    $mota = $_POST['mota'];
    $loai_hoa = $_POST['loai_hoa'];
    $mau_sac = $_POST['mau_sac'];
    $so_canh = (int)$_POST['so_canh'];
    $xuat_xu = $_POST['xuat_xu'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $dip = $_POST['dip'];
    $gia_khuyen_mai = !empty($_POST['gia_khuyen_mai']) ? (int)$_POST['gia_khuyen_mai'] : NULL;

    // Xử lý ảnh: ưu tiên upload file, nếu không thì lấy link ảnh
    $img_name = '';
    if (!empty($_FILES['anh']['name'])) {
        $img_name = time().'_'.basename($_FILES['anh']['name']);
        move_uploaded_file($_FILES['anh']['tmp_name'], "uploads/$img_name");
    } elseif (!empty($_POST['link_anh'])) {
        $img_name = trim($_POST['link_anh']); // Lưu nguyên URL
    }

    $sql = "INSERT INTO san_pham (ten, gia, gia_von, so_luong, mota, loai_hoa, mau_sac, so_canh, xuat_xu, gioi_tinh, dip, anh, gia_khuyen_mai) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiississssi", 
        $ten, $gia, $gia_von, $so_luong, $mota, $loai_hoa, $mau_sac, $so_canh, 
        $xuat_xu, $gioi_tinh, $dip, $img_name, $gia_khuyen_mai
    );
    if ($stmt->execute()) {
        $message = "✅ Đã thêm sản phẩm mới!";
    } else {
        $message = "❌ Lỗi: " . $conn->error;
    }
}

// Xử lý xóa
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM san_pham WHERE id=$id");
    header("Location: rest_admin.php");
    exit;
}

// Lấy sản phẩm
$result = $conn->query("SELECT * FROM san_pham ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="main.css">
    <style>
        table {width:100%; border-collapse: collapse; margin-top:20px;}
        table, th, td {border:1px solid #ddd;}
        th, td {padding:8px; text-align:center;}
        th {background:#f2f2f2;}
        img {max-width:80px;}
        .actions a {margin:0 5px; text-decoration:none; color:#0066cc;}
        .actions a.delete {color:red;}
        .edit-btn {cursor:pointer; margin-left:5px; color:#228b22;}
        .inline-input, .inline-select {width:90%; padding:3px;}
    </style>
</head>
<body>
<div class="form-container">
    <h2>Thêm sản phẩm</h2>
    <?php if ($message) echo "<div style='color:green;'>$message</div>"; ?>

   <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="add" value="1">
    <input type="text" name="ten" placeholder="Tên sản phẩm" required>
    <input type="number" name="gia" placeholder="Giá bán" required>
    <input type="number" name="gia_khuyen_mai" placeholder="Giá khuyến mãi (nếu có)">
    <input type="number" name="gia_von" placeholder="Giá vốn" required>
    <input type="number" name="so_luong" placeholder="Số lượng" required>
    <input type="text" name="loai_hoa" placeholder="Loại hoa">
    <input type="text" name="mau_sac" placeholder="Màu sắc">
    <input type="number" name="so_canh" placeholder="Số cánh">
    <input type="text" name="xuat_xu" placeholder="Xuất xứ">

    <!-- Tách từng trường ra từng dòng -->
    <select name="gioi_tinh" required>
        <option value="">Chọn giới tính</option>
        <option value="Nam">Nam</option>
        <option value="Nữ">Nữ</option>
    </select>
    <select name="dip" required>
        <option value="">Chọn dịp</option>
        <option value="Sinh nhật">Sinh nhật</option>
        <option value="Valentine">Valentine</option>
        <option value="8/3">8/3</option>
        <option value="20/10">20/10</option>
        <option value="Tốt nghiệp">Tốt nghiệp</option>
        <option value="Khác">Khác</option>
    </select>
    <textarea name="mota" placeholder="Mô tả chi tiết"></textarea>
    <input type="file" name="anh" accept="image/*">
    <input type="text" name="link_anh" placeholder="Hoặc dán link ảnh (http...)">
    <button type="submit">Thêm sản phẩm</button>
</form>
</div>

<h2 style="margin-top:30px;">Danh sách sản phẩm</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Tên</th>
        <th>Giá bán</th>
        <th>Giá KM</th>
        <th>Giá vốn</th>
        <th>Số lượng</th>
        <th>Loại hoa</th>
        <th>Màu sắc</th>
        <th>Số cánh</th>
        <th>Xuất xứ</th>
        <th>Giới tính</th>
        <th>Dịp</th>
        <th>Mô tả</th>
        <th>Thao tác</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr data-id="<?php echo $row['id']; ?>">
        <td><?php echo $row['id']; ?></td>
        <td>
            <?php
            if ($row['anh']) {
                if (strpos($row['anh'], 'http://') === 0 || strpos($row['anh'], 'https://') === 0) {
                    $imgSrc = $row['anh'];
                } else {
                    $imgSrc = 'uploads/' . $row['anh'];
                }
                echo '<img src="' . htmlspecialchars($imgSrc) . '" alt="">';
            }
            ?>
        </td>
        <?php 
        $fields = ['ten','gia','gia_khuyen_mai','gia_von','so_luong','loai_hoa','mau_sac','so_canh','xuat_xu','gioi_tinh','dip','mota'];
        foreach ($fields as $f): ?>
        <td data-field="<?php echo $f; ?>">
            <span class="text"><?php echo htmlspecialchars($row[$f]); ?></span>
            <span class="edit-btn">✏️</span>
        </td>
        <?php endforeach; ?>
        <td class="actions">
            <a href="rest_admin.php?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Xóa sản phẩm này?');">🗑️ Xóa</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<script>
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("edit-btn")) {
        const td = e.target.closest("td");
        const span = td.querySelector(".text");
        const oldValue = span.innerText;
        const field = td.dataset.field;
        const tr = td.closest("tr");
        const id = tr.dataset.id;

        let input;
        if(field === "gioi_tinh") {
            input = document.createElement("select");
            input.className = "inline-select";
            ["Nam","Nữ"].forEach(v=>{
                const opt = document.createElement("option");
                opt.value = v;
                opt.text = v;
                if(v === oldValue) opt.selected = true;
                input.appendChild(opt);
            });
        } else if(field === "dip") {
            input = document.createElement("select");
            input.className = "inline-select";
            ["Sinh nhật","Valentine","8/3","20/10","Tốt nghiệp","Khác"].forEach(v=>{
                const opt = document.createElement("option");
                opt.value = v;
                opt.text = v;
                if(v === oldValue) opt.selected = true;
                input.appendChild(opt);
            });
        } else {
            input = document.createElement("input");
            input.type = "text";
            input.value = oldValue;
            input.className = "inline-input";
        }

        td.innerHTML = "";
        td.appendChild(input);
        input.focus();

        input.addEventListener("change", function() {
            const newValue = this.value;
            fetch("update_field.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: `id=${id}&field=${field}&value=${encodeURIComponent(newValue)}`
            })
            .then(res => res.text())
            .then(()=>{
                td.innerHTML = `<span class="text">${newValue}</span><span class="edit-btn">✏️</span>`;
            });
        });

        input.addEventListener("blur", function() {
            td.innerHTML = `<span class="text">${oldValue}</span><span class="edit-btn">✏️</span>`;
        });
    }
    
});
</script>
<a href="admin.php" class="btn-back">Quay lại</a>
</body>
</html>