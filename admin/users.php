<?php
require_once '../config/database.php';
check_admin();

// Handle Delete User
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $id AND role != 'admin'");
    header("Location: users.php");
    exit();
}

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<h2 style="margin-bottom: 20px;">Manage Users</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
            while($row = $users->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
                <td><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></td>
                <td>
                    <?php if($row['role'] !== 'admin'): ?>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
