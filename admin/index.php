<?php
require_once '../config/database.php';
check_admin();

// Get Stats
$users_count = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'user'")->fetch_assoc()['c'];
$orders_count = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
$pre_orders_count = $conn->query("SELECT COUNT(*) as c FROM pre_orders")->fetch_assoc()['c'];

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<h2 style="margin-bottom: 20px;">Overview</h2>

<div class="stat-cards">
    <div class="card">
        <div class="card-info">
            <h3><?php echo $users_count; ?></h3>
            <p>Total Users</p>
        </div>
        <div class="card-icon bg-primary">
            <i class="fas fa-users"></i>
        </div>
    </div>
    
    <div class="card">
        <div class="card-info">
            <h3><?php echo $orders_count; ?></h3>
            <p>Total Orders</p>
        </div>
        <div class="card-icon bg-success">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <div class="card">
        <div class="card-info">
            <h3><?php echo $pre_orders_count; ?></h3>
            <p>Total Pre-Orders</p>
        </div>
        <div class="card-icon bg-warning">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </div>
</div>

<div class="table-container">
    <h3 style="margin-bottom: 15px;">Recent Orders</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Amount (Kg)</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recent_orders = $conn->query("SELECT id, name, amount, created_at, status FROM orders ORDER BY created_at DESC LIMIT 5");
            while($row = $recent_orders->fetch_assoc()):
            ?>
            <tr>
                <td>#ORD-<?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['amount']); ?>kg</td>
                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                <td><span class="badge badge-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
