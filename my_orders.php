<?php
require_once 'config/database.php';
check_login();

$user_id = $_SESSION['user_id'];

// Fetch regular orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();

// Fetch pre-orders
$stmt_pre = $conn->prepare("SELECT * FROM pre_orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt_pre->bind_param("i", $user_id);
$stmt_pre->execute();
$pre_orders_result = $stmt_pre->get_result();

require_once 'includes/header.php';
require_once 'includes/navbar.php';

function getStatusBadge($status) {
    switch($status) {
        case 'pending': 
            return '<span style="background-color: #6c757d; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase;">Pending</span>';
        case 'processed': 
            return '<span style="background-color: #17a2b8; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase;">Diproses</span>';
        case 'delivery': 
            return '<span style="background-color: #fd7e14; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase;">Delivery</span>';
        case 'completed': 
            return '<span style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; text-transform: uppercase;">Selesai</span>';
        default: 
            return $status;
    }
}
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Pesanan Saya</h1>
        <p>Pantau status pesanan dan pre-order Anda di sini</p>
    </div>
</div>

<section style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container" style="max-width: 1000px;">
        
        <h2 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 2px solid var(--primary-light); padding-bottom: 10px;">Riwayat Order</h2>
        <div style="background: var(--white); border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 40px;">
            <?php if($orders_result->num_rows > 0): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background-color: var(--primary-color); color: white;">
                                <th style="padding: 15px;">ID Pesanan</th>
                                <th style="padding: 15px;">Tanggal</th>
                                <th style="padding: 15px;">Pembayaran</th>
                                <th style="padding: 15px;">Jumlah (Kg)</th>
                                <th style="padding: 15px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $orders_result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px; font-weight: bold;">#ORD-<?php echo $row['id']; ?></td>
                                <td style="padding: 15px;"><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></td>
                                <td style="padding: 15px;"><?php echo htmlspecialchars($row['payment']); ?></td>
                                <td style="padding: 15px;"><?php echo number_format($row['amount'], 0, ',', '.'); ?>kg</td>
                                <td style="padding: 15px;"><?php echo getStatusBadge($row['status']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="padding: 30px; text-align: center; color: #666;">
                    <i class="fas fa-box-open" style="font-size: 3em; margin-bottom: 15px; color: #ccc;"></i>
                    <p>Anda belum memiliki pesanan reguler.</p>
                    <a href="order.php" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">Buat Pesanan</a>
                </div>
            <?php endif; ?>
        </div>

        <h2 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 2px solid var(--primary-light); padding-bottom: 10px;">Riwayat Pre-Order</h2>
        <div style="background: var(--white); border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden;">
            <?php if($pre_orders_result->num_rows > 0): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background-color: var(--primary-color); color: white;">
                                <th style="padding: 15px;">ID Pesanan</th>
                                <th style="padding: 15px;">Tanggal</th>
                                <th style="padding: 15px;">Dikirim</th>
                                <th style="padding: 15px;">Jumlah (Kg)</th>
                                <th style="padding: 15px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $pre_orders_result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px; font-weight: bold;">#PRE-<?php echo $row['id']; ?></td>
                                <td style="padding: 15px;"><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></td>
                                <td style="padding: 15px; color: var(--primary-color); font-weight: bold;"><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                                <td style="padding: 15px;"><?php echo number_format($row['amount'], 0, ',', '.'); ?>kg</td>
                                <td style="padding: 15px;"><?php echo getStatusBadge($row['status']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="padding: 30px; text-align: center; color: #666;">
                    <i class="fas fa-calendar-alt" style="font-size: 3em; margin-bottom: 15px; color: #ccc;"></i>
                    <p>Anda belum memiliki riwayat pre-order.</p>
                    <a href="pre-order.php" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">Buat Pre-Order</a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
