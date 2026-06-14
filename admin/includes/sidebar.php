<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
    <div class="sidebar-header" style="padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <img src="../assets/img/logo.png" alt="Logo" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(255,255,255,0.3);">
            <h2 style="margin:0; font-size: 1.1rem; color: #fff; font-weight: 600;">Celebes Admin</h2>
        </div>
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="users.php" class="<?php echo $current_page == 'users.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="orders.php" class="<?php echo $current_page == 'orders.php' ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="pre_orders.php" class="<?php echo $current_page == 'pre_orders.php' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> Pre-Orders</a></li>
        <li><a href="products.php" class="<?php echo $current_page == 'products.php' ? 'active' : ''; ?>"><i class="fas fa-box"></i> Products</a></li>
        <li><a href="../index.php"><i class="fas fa-globe"></i> View Site</a></li>
        <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<main class="main-content">
    <div class="topbar">
        <div style="display: flex; align-items: center; gap: 15px;">
            <!-- Hamburger logic for mobile -->
            <button id="adminSidebarToggle" style="background: none; border: none; font-size: 1.5rem; color: var(--admin-primary); cursor: pointer; display: none;" class="mobile-toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <h3 style="margin:0; font-weight:500;">Dashboard</h3>
        </div>
        <div class="topbar-right" style="margin-right: 30px;">
            <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        </div>
    </div>
    
    <div class="content-body">
