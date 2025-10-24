<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thuê Hoa / Cây Cảnh | WEBCAYCANH</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <header>
    <?php include 'header.php'; ?>
  </header>
  
<section class="gioi-thieu-section">
  <div class="anh1.webp">
    <img src="img/anh2.jpg" alt="Chậu cây trang trí">
  </div>
  <div class="gioi-thieu-text">
    <h2>Chào mừng đến với <span>Dịch Vụ Cho Thuê Hoa </span></h2>
    <ul>
          <li>Hoa tươi và cây cảnh được chăm sóc kỹ lưỡng</li>
          <li>Cho thuê linh hoạt: theo giờ, theo ngày</li>
          <li>Bảo dưỡng, thay mới miễn phí nếu cần</li>
          <li>Trang trí theo yêu cầu, giao nhận tận nơi</li>
          <li>Giá thuê linh hoạt và cạnh tranh</li>
        </ul>
  </div>
</section>

  

      

      <div class="rental-contact" style="margin-top:32px;">
        <h3>Liên hệ thuê cây/hoa</h3>
        <ul>
          <li>Hotline: <a href="tel:0919214748" class="sdt">0919214748</a></li>
          <li>Email: <a href="mailto:webcaycanh@gmail.com">webcaycanh@gmail.com</a></li>
          <li>Hoặc điền thông tin bên dưới:</li>
        </ul>

        <form action="xu-ly-thue.php" method="post" class="contact-form">
          <input type="text" name="ho_ten" placeholder="Họ tên *" required>
          <input type="tel" name="so_dien_thoai" placeholder="Số điện thoại *" required>
          <input type="text" name="ten_san_pham" placeholder="Tên cây/hoa muốn thuê *" required>
          <input type="text" name="thoi_gian" placeholder="Thời gian thuê (VD: 3 giờ, 2 ngày, 1 tháng)" required>

          <label for="hinh_thuc_thue" style="margin-top: 8px;">Hình thức thuê:</label>
          <select name="hinh_thuc_thue" id="hinh_thuc_thue" required>
            <option value="">-- Chọn hình thức --</option>
            <option value="theo_gio">Theo giờ</option>
            <option value="theo_ngay">Theo ngày</option>
            <option value="theo_thang">Theo tháng</option>
          </select>

          <textarea name="ghi_chu" placeholder="Ghi chú thêm (nếu có)..."></textarea>
          <button type="submit">Gửi yêu cầu thuê</button>
        </form>
      </div>
    </section>
  </main>

  <?php include 'footer.php'; ?>
</body>
</html>
