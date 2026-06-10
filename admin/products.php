<?php
require_once '../config/database.php';
check_admin();

// Handle Add Product
if(isset($_POST['add_product'])) {
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $price = sanitize_input($_POST['price']);
    $image = "";

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/img/";
        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $file_name = "prod_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $file_name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);
    $stmt->execute();
    header("Location: products.php?success=added");
    exit();
}

// Handle Update Product
if(isset($_POST['update_product'])) {
    $id = (int)$_POST['product_id'];
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $price = sanitize_input($_POST['price']);
    
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Upload new image
        $target_dir = "../assets/img/";
        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $file_name = "prod_" . time() . "." . $file_ext;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $file_name)) {
            // Delete old image
            $res = $conn->query("SELECT image FROM products WHERE id = $id");
            $row = $res->fetch_assoc();
            if($row['image'] && file_exists("../assets/img/" . $row['image'])) {
                unlink("../assets/img/" . $row['image']);
            }
            // Update with new image
            $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
            $stmt->bind_param("ssdsi", $name, $description, $price, $file_name, $id);
        }
    } else {
        // Update without changing image
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=? WHERE id=?");
        $stmt->bind_param("ssdi", $name, $description, $price, $id);
    }
    
    $stmt->execute();
    header("Location: products.php?success=updated");
    exit();
}

// Handle Delete Product
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Get image name first to delete from folder
    $res = $conn->query("SELECT image FROM products WHERE id = $id");
    $row = $res->fetch_assoc();
    if($row['image'] && file_exists("../assets/img/" . $row['image'])) {
        unlink("../assets/img/" . $row['image']);
    }
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: products.php?success=deleted");
    exit();
}

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
    <h2 style="margin: 0; color: #333; font-weight: 600;">Manage Products</h2>
    <button onclick="document.getElementById('addProductForm').style.display='block'; document.getElementById('editProductForm').style.display='none';" class="btn" style="background: var(--admin-primary); color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.3);">
        <i class="fas fa-plus-circle"></i> Add New Product
    </button>
</div>

<!-- Add Product Form -->
<div id="addProductForm" style="display:none; background: var(--white); padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 40px;">
    <h3 style="margin-bottom: 25px; color: var(--primary-color); display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-plus-circle"></i> Add New Product
    </h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 20px;">
            <div class="input-group">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444;">Product Name</label>
                <input type="text" name="name" placeholder="Contoh: Ikan Teri Makassar" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;" required>
            </div>
            <div class="input-group">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444;">Price (Rp)</label>
                <input type="number" name="price" placeholder="Contoh: 50000" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;" required>
            </div>
        </div>
        <div class="input-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444;">Description</label>
            <textarea name="description" rows="4" placeholder="Jelaskan detail produk di sini..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; font-family: inherit; resize: vertical;" required></textarea>
        </div>
        <div class="input-group" style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444;">Product Image</label>
            <input type="file" name="image" accept="image/png" style="width: 100%; padding: 10px; background: #f9f9f9; border: 1px dashed #ccc; border-radius: 8px;" required>
            <small style="color: #888; display: block; margin-top: 5px;">Format : Hanya PNG.</small>
        </div>
        <div style="display: flex; gap: 15px; border-top: 1px solid #eee; padding-top: 20px;">
            <button type="submit" name="add_product" class="btn btn-primary" style="padding: 12px 30px; font-weight: 600;">
                <i class="fas fa-save"></i> Save Product
            </button>
            <button type="button" onclick="document.getElementById('addProductForm').style.display='none'" class="btn" style="background: #f1f1f1; color: #666; padding: 12px 30px; border-radius: 8px; font-weight: 600;">
                Cancel
            </button>
        </div>
    </form>
</div>

<!-- Edit Product Form -->
<div id="editProductForm" style="display:none; background: var(--white); padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 40px;">
    <h3 style="margin-bottom: 25px; color: var(--admin-primary); display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-edit"></i> Edit Product
    </h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" id="edit_id">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 20px;">
            <div class="input-group">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444;">Product Name</label>
                <input type="text" name="name" id="edit_name" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;" required>
            </div>
            <div class="input-group">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444;">Price (Rp)</label>
                <input type="number" name="price" id="edit_price" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;" required>
            </div>
        </div>
        <div class="input-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444;">Description</label>
            <textarea name="description" id="edit_description" rows="4" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; font-family: inherit; resize: vertical;" required></textarea>
        </div>
        <div class="input-group" style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #444;">Product Image (Biarkan kosong jika tidak ingin ganti)</label>
            <input type="file" name="image" accept="image/png" style="width: 100%; padding: 10px; background: #f9f9f9; border: 1px dashed #ccc; border-radius: 8px;">
            <small style="color: #888; display: block; margin-top: 5px;">Format : Hanya PNG.</small>
        </div>
        <div style="display: flex; gap: 15px; border-top: 1px solid #eee; padding-top: 20px;">
            <button type="submit" name="update_product" class="btn btn-primary" style="background: var(--admin-primary); padding: 12px 30px; font-weight: 600;">
                <i class="fas fa-check"></i> Update Product
            </button>
            <button type="button" onclick="document.getElementById('editProductForm').style.display='none'" class="btn" style="background: #f1f1f1; color: #666; padding: 12px 30px; border-radius: 8px; font-weight: 600;">
                Cancel
            </button>
        </div>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
            if($res->num_rows > 0):
                while($row = $res->fetch_assoc()):
            ?>
            <tr>
                <td style="width: 80px;">
                    <img src="../assets/img/<?php echo $row['image']; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                </td>
                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                <td><small><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></small></td>
                <td style="white-space: nowrap; width: 100px;">
                    <div style="display: flex; gap: 8px;">
                        <button onclick='openEditModal(<?php echo json_encode($row); ?>)' class="btn-sm btn-primary" style="background: var(--admin-primary); cursor: pointer; border: none;"><i class="fas fa-edit"></i></button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px;">Belum ada produk. Klik "Add New Product" untuk menambah.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function openEditModal(product) {
    document.getElementById('addProductForm').style.display = 'none';
    document.getElementById('editProductForm').style.display = 'block';
    
    document.getElementById('edit_id').value = product.id;
    document.getElementById('edit_name').value = product.name;
    document.getElementById('edit_price').value = product.price;
    document.getElementById('edit_description').value = product.description;
    
    // Scroll to form
    document.getElementById('editProductForm').scrollIntoView({ behavior: 'smooth' });
}
</script>

<?php require_once 'includes/footer.php'; ?>
