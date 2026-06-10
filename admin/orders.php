<?php
require_once '../config/database.php';
check_admin();

// Handle Status Change
if(isset($_POST['update_status'])) {
    $id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $id");
    header("Location: orders.php");
    exit();
}

// Handle Delete
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM orders WHERE id = $id");
    header("Location: orders.php");
    exit();
}

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<h2 style="margin-bottom: 20px;">Manage Orders</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Info</th>
                <th>Order Details</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
            while($row = $orders->fetch_assoc()):
            ?>
            <tr>
                <td>#ORD-<?php echo $row['id']; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                    <small><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></small><br>
                    <small><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></small>
                </td>
                <td>
                    <strong>Items:</strong><br>
                    <ul style="margin: 5px 0; padding-left: 15px; font-size: 0.85rem; color: #555;">
                    <?php
                    $order_id = $row['id'];
                    $items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id");
                    while($item = $items->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($item['name']) . " (" . $item['quantity'] . "kg)</li>";
                    }
                    ?>
                    </ul>
                    <small>Address: <?php echo htmlspecialchars($row['address']); ?></small><br>
                    <?php if(!empty($row['note'])): ?><small>Note: <?php echo htmlspecialchars($row['note']); ?></small><br><?php endif; ?>
                    <strong style="color: var(--primary-color);">Total: Rp <?php echo number_format($row['total_price'], 0, ',', '.'); ?></strong> (<?php echo htmlspecialchars($row['amount']); ?>kg)
                </td>
                <td>
                    <?php echo htmlspecialchars($row['payment']); ?>
                    <?php if(!empty($row['payment_proof'])): ?>
                        <br>
                        <a href="../assets/uploads/proofs/<?php echo $row['payment_proof']; ?>" target="_blank" style="color: var(--primary-color); font-size: 0.8rem; font-weight: bold;">
                            <i class="fas fa-image"></i> Lihat Bukti
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <form action="" method="POST" style="display: flex; gap: 5px;">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="status" style="padding: 3px; font-size: 0.8rem;">
                            <option value="pending" <?php echo $row['status']=='pending'?'selected':''; ?>>Pending</option>
                            <option value="processed" <?php echo $row['status']=='processed'?'selected':''; ?>>Processed</option>
                            <option value="delivery" <?php echo $row['status']=='delivery'?'selected':''; ?>>Delivery</option>
                            <option value="completed" <?php echo $row['status']=='completed'?'selected':''; ?>>Completed</option>
                        </select>
                        <button type="submit" name="update_status" class="btn-sm btn-primary"><i class="fas fa-save"></i></button>
                    </form>
                </td>
                <td>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-sm btn-danger" onclick="return confirm('Delete this order?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
