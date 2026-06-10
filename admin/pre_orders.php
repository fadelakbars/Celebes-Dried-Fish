<?php
require_once '../config/database.php';
check_admin();

// Handle Status Change
if(isset($_POST['update_status'])) {
    $id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE pre_orders SET status = '$status' WHERE id = $id");
    header("Location: pre_orders.php");
    exit();
}

// Handle Delete
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM pre_orders WHERE id = $id");
    header("Location: pre_orders.php");
    exit();
}

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<h2 style="margin-bottom: 20px;">Manage Pre-Orders</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Info</th>
                <th>Order Details</th>
                <th>Target Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $orders = $conn->query("SELECT * FROM pre_orders ORDER BY created_at DESC");
            while($row = $orders->fetch_assoc()):
            ?>
            <tr>
                <td>#PO-<?php echo $row['id']; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                    <small><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></small>
                </td>
                <td>
                    Amount: <?php echo htmlspecialchars($row['amount']); ?>kg<br>
                    Payment: <?php echo htmlspecialchars($row['payment']); ?>
                    <?php if(!empty($row['payment_proof'])): ?>
                        <br>
                        <a href="../assets/uploads/proofs/<?php echo $row['payment_proof']; ?>" target="_blank" style="color: var(--primary-color); font-size: 0.8rem; font-weight: bold;">
                            <i class="fas fa-image"></i> Lihat Bukti
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo date('d M Y', strtotime($row['date'])); ?></strong>
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
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-sm btn-danger" onclick="return confirm('Delete this pre-order?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
