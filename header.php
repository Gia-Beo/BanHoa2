<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<style>
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding-left: 18px;
    padding-right: 18px;
    box-sizing: border-box;
}

.user-menu {
    display: flex;
    gap: 18px;
    list-style: none;
    align-items: center;
    margin: 0;
    padding: 0;
}
.user-menu li {
    list-style: none;
    position: relative;
}
.user-menu li a {
    color: #d13c8a;
    font-size: 1rem;
    text-decoration: none;
    background: none;
    padding: 5px 10px;
    border-radius: 6px;
    transition: background 0.2s, color 0.2s;
}
.user-menu li a:hover {
    background: #e68ab7;
    color: #fff;
}

/* Dropdown user */
.user-dropdown {
    position: relative;
    display: inline-block;
}
.user-dropdown .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 6px;
    vertical-align: middle;
}
.user-dropdown .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    min-width: 180px;
    z-index: 999;
}
.user-dropdown .dropdown-menu a {
    display: block;
    padding: 10px;
    color: #333;
    text-decoration: none;
}
.user-dropdown .dropdown-menu a:hover {
    background: #f5f5f5;
}
.user-dropdown:hover .dropdown-menu {
    display: block;
}

/* NAV */
nav {
    background: rgba(242, 231, 231, 0.95);
    box-shadow: 0 2px 10px rgba(220, 120, 187, 0.12);
    position: sticky;
    top: 0;
    z-index: 100;
    margin-bottom: 12px;
}
.main-menu {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 24px;
    list-style: none;
    padding: 10px 0;
    margin: 0;
}
.main-menu > li {
    position: relative;
    list-style: none;
}
.main-menu a {
    display: inline-block;
    padding: 8px 14px;
    color: #d13c8a;
    background: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 1.07rem;
    text-decoration: none;
    transition: color 0.2s, background 0.2s;
}
.main-menu a:hover, .main-menu .active {
    color: #fff;
    background: #e68ab7;
}
</style>

<div class="container header-flex">
  <div class="logo">
    <h1>🌿 Shop Hoa</h1>
    <p>Vườn Hoa  </p>
  </div>
  <ul class="user-menu">
    <?php if (isset($_SESSION['khach_hang'])): ?>
  <?php 
    $avatar_file = !empty($_SESSION['khach_hang']['avatar']) 
                    ? "uploads/" . $_SESSION['khach_hang']['avatar'] 
                    : "uploads/avatar-default.png";
  ?>
  <li class="user-dropdown">
    <a href="#">
      <img src="<?= htmlspecialchars($avatar_file) ?>" alt="Avatar" class="avatar">
      <?= htmlspecialchars($_SESSION['khach_hang']['ten']) ?>
    </a>
    <div class="dropdown-menu">
      <a href="profile.php"><i class="fa fa-id-card"></i> Thông tin cá nhân</a>
      <a href="lich-su.php"><i class="fa fa-history"></i> Lịch sử mua hàng</a>
      <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
        <a href="admin.php"><i class="fa fa-cogs"></i> Quản lý admin</a>
      <?php endif; ?>
      <a href="dang-xuat.php"><i class="fa fa-sign-out"></i> Đăng xuất</a>
    </div>
  </li>
<?php else: ?>
  <li><a href="dang-nhap.php">Đăng nhập</a></li>
  <li><a href="dang-ki.php">Đăng ký</a></li>
<?php endif; ?>

    <li><a href="gio-hang.php" class="cart-btn"><i class="fas fa-shopping-cart"></i></a></li>
  </ul>
</div>

<nav>
  <ul class="container main-menu">
    <li><a href="index.php">Trang Chủ</a></li>
    <li><a href="gioi-thieu.php">Giới Thiệu</a></li>
    <li><a href="cho-thue-cay-canh.php">Thuê Hoa</a></li>
    <li><a href="san-pham.php">Sản Phẩm</a></li>
    <li><a href="tu-van-cay-canh.php">Tư Vấn Hoa</a></li>
    <li><a href="#">Liên Hệ</a></li>
    <li>
      <form action="tim-kiem.php" method="get" style="display:inline;">
        <input type="text" name="tu_khoa" placeholder="Tìm kiếm..." id="search-input">
      </form>
    </li>
  </ul>
</nav>
