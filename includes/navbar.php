<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo" style="display: flex; align-items: center; gap: 10px;">
            <img src="assets/img/logo.png" alt="Logo" class="logo-img" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--white);">
            <span>Celebes Dried Fish</span>
        </a>

        <ul class="nav-links">
            <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="about.php" class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About Us</a></li>
            <li><a href="product.php" class="<?php echo $current_page == 'product.php' ? 'active' : ''; ?>">Product</a></li>
            <li><a href="service.php" class="<?php echo $current_page == 'service.php' ? 'active' : ''; ?>">Service</a></li>
            <li><a href="media.php" class="<?php echo $current_page == 'media.php' ? 'active' : ''; ?>">Media</a></li>
        </ul>

        <div class="nav-actions">
            <!-- Cart Icon -->
            <?php
            $cart_count = 0;
            if(isset($_SESSION['cart'])) {
                foreach($_SESSION['cart'] as $qty) {
                    $cart_count += $qty;
                }
            }
            ?>
            <a href="cart.php" style="position: relative; margin-right: 15px; color: var(--primary-color); text-decoration: none; font-size: 1.2rem; display: flex; align-items: center;">
                <i class="fas fa-shopping-cart"></i>
                <?php if($cart_count > 0): ?>
                <span style="position: absolute; top: -10px; right: -10px; background: var(--primary-color); color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 50%; font-weight: bold; border: 2px solid var(--white);"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="user-dropdown">
                    <button class="user-btn"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Akun'); ?> <i class="fas fa-caret-down"></i></button>
                    <div class="dropdown-content">
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="admin/index.php">Dashboard</a>
                        <?php endif; ?>
                        <a href="my_orders.php">Pesanan Saya</a>
                        <a href="addresses.php">Alamat Saya</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <button class="login-btn" id="openLoginModal"><i class="fas fa-user"></i> Login</button>
            <?php endif; ?>
            <div class="hamburger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </div>
    </div>
</nav>

<!-- Login/Register Modal -->
<?php if(!isset($_SESSION['user_id'])): ?>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="form-container">
            <!-- Login Form -->
            <form id="loginForm" action="login.php" method="POST" class="auth-form active">
                <h2>Login</h2>
                <div class="input-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-block">Masuk</button>
                <p class="switch-form">Belum punya akun? <a href="#" id="showRegister">Daftar di sini</a></p>
            </form>

            <!-- Register Form -->
            <form id="registerForm" action="login.php" method="POST" class="auth-form">
                <h2>Register</h2>
                <div class="input-group">
                    <label for="reg-name">Nama Lengkap</label>
                    <input type="text" id="reg-name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary btn-block">Daftar</button>
                <p class="switch-form">Sudah punya akun? <a href="#" id="showLogin">Login di sini</a></p>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Global Notifications -->
<?php if(isset($_SESSION['success'])): ?>
<div class="alert alert-success global-alert" style="max-width: 1200px; margin: 20px auto 0; width: 90%;">
    <?php 
    echo $_SESSION['success']; 
    unset($_SESSION['success']);
    ?>
</div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
<div class="alert alert-danger global-alert" style="max-width: 1200px; margin: 20px auto 0; width: 90%;">
    <?php 
    echo $_SESSION['error']; 
    unset($_SESSION['error']);
    ?>
</div>
<?php endif; ?>

<!-- Auto-hide alerts script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.global-alert');
    if(alerts.length > 0) {
        setTimeout(function() {
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000); // Hide after 5 seconds
    }
});
</script>
