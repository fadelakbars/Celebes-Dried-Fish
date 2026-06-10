<?php
require_once 'config/database.php';
check_login();

// Fetch user addresses
$user_id = $_SESSION['user_id'];
$addr_stmt = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
$addr_stmt->bind_param("i", $user_id);
$addr_stmt->execute();
$addr_res = $addr_stmt->get_result();
$addresses = [];
$default_address = null;
while ($row = $addr_res->fetch_assoc()) {
    $addresses[] = $row;
    if ($row['is_default']) {
        $default_address = $row;
    }
}
$addr_stmt->close();

// Fetch user email
$user_email = '';
$user_stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_res = $user_stmt->get_result();
if ($user_row = $user_res->fetch_assoc()) {
    $user_email = $user_row['email'];
}
$user_stmt->close();

// Handle Order Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_order'])) {
    $user_id = $_SESSION['user_id'];
    $name = sanitize_input($_POST['name']);
    $phone = sanitize_input($_POST['phone']);
    $email = sanitize_input($_POST['email']);
    $address = sanitize_input($_POST['address']);
    $product_id = sanitize_input($_POST['product_id']);
    $amount = sanitize_input($_POST['amount']);
    $payment = 'Payment Gateway';
    $note = sanitize_input($_POST['note']);

    $payment_proof = NULL;
    $can_proceed = true;

    if ($can_proceed) {
        // Save address if requested and not empty
        if (isset($_POST['save_address']) && $_POST['save_address'] == '1') {
            $label = 'Alamat Baru';
            $is_def = empty($addresses) ? 1 : 0;
            $save_stmt = $conn->prepare("INSERT INTO user_addresses (user_id, label, receiver_name, receiver_phone, address, is_default) VALUES (?, ?, ?, ?, ?, ?)");
            $save_stmt->bind_param("issssi", $user_id, $label, $name, $phone, $address, $is_def);
            $save_stmt->execute();
            $save_stmt->close();
        }

        $conn->begin_transaction();
        
        try {
            // Get product price and name
            $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows == 0) {
                throw new Exception("Produk tidak ditemukan.");
            }
            $product = $res->fetch_assoc();
            $total_price = $product['price'] * $amount;

            $stmt = $conn->prepare("INSERT INTO orders (user_id, name, phone, email, address, amount, total_price, payment, payment_proof, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssiisss", $user_id, $name, $phone, $email, $address, $amount, $total_price, $payment, $payment_proof, $note);
            $stmt->execute();
            
            $order_id = $conn->insert_id;
            
            // Insert into order_items table
            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt_items->bind_param("iiii", $order_id, $product_id, $amount, $product['price']);
            $stmt_items->execute();
            
            $conn->commit();

            $success_msg = "Pesanan berhasil dibuat! Anda akan dialihkan ke halaman pembayaran.";
            
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'payment.php?type=order&id=$order_id';
                }, 1500);
            </script>";
            
        } catch (Exception $e) {
            $conn->rollback();
            $error_msg = "Terjadi kesalahan. Gagal membuat pesanan. Error: " . $e->getMessage();
        }
    }
}

// Fetch products for dropdown
$products_query = $conn->query("SELECT id, name, price FROM products ORDER BY name ASC");
$products = [];
while ($row = $products_query->fetch_assoc()) {
    $products[] = $row;
}


require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 25px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color); margin: 0 0 5px 0;">Form Pemesanan</h1>
        <p>Lengkapi data di bawah ini untuk melakukan pemesanan</p>
    </div>
</div>

<section style="padding: 60px 0;">
    <div class="container" style="max-width: 800px;">
        
        <?php if(isset($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <?php if(isset($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <div style="background: var(--white); padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
            <!-- Address Selector Card -->
            <?php if (!empty($addresses)): ?>
                <div id="selected-address-card" style="background: #fffcf8; padding: 20px; border-radius: 8px; border: 1.5px solid var(--primary-color); margin-bottom: 25px; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px dashed var(--primary-light); padding-bottom: 8px;">
                        <span style="font-weight: 600; color: var(--primary-color); display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-map-marker-alt"></i> Alamat Pengiriman (<span id="active-label"><?php echo htmlspecialchars($default_address ? $default_address['label'] : $addresses[0]['label']); ?></span>)
                        </span>
                        <button type="button" class="btn btn-outline" style="padding: 4px 12px; font-size: 0.8rem; border-radius: 4px;" onclick="openAddressSelectModal()">Pilih Alamat Lain</button>
                    </div>
                    <div id="active-address-details">
                        <strong style="font-size: 1.05rem;" id="active-receiver-name"><?php echo htmlspecialchars($default_address ? $default_address['receiver_name'] : $addresses[0]['receiver_name']); ?></strong> 
                        <span style="color: var(--text-light);" id="active-receiver-phone">| <?php echo htmlspecialchars($default_address ? $default_address['receiver_phone'] : $addresses[0]['receiver_phone']); ?></span>
                        <p style="margin-top: 8px; color: var(--text-light); font-size: 0.95rem; line-height: 1.5; white-space: pre-line;" id="active-address-text"><?php echo htmlspecialchars($default_address ? $default_address['address'] : $addresses[0]['address']); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div style="background: #fafafa; padding: 15px; border-radius: 8px; border: 1px dashed var(--gray-border); margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; gap: 15px; flex-wrap: wrap;">
                    <span style="font-size: 0.9rem; color: var(--text-light);"><i class="fas fa-info-circle"></i> Anda belum memiliki alamat tersimpan.</span>
                    <a href="addresses.php" target="_blank" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.8rem;"><i class="fas fa-plus"></i> Kelola Alamat</a>
                </div>
            <?php endif; ?>

            <?php
            $init_name = $default_address ? $default_address['receiver_name'] : ($addresses[0]['receiver_name'] ?? $_SESSION['user_name']);
            $init_phone = $default_address ? $default_address['receiver_phone'] : ($addresses[0]['receiver_phone'] ?? '');
            $init_address = $default_address ? $default_address['address'] : ($addresses[0]['address'] ?? '');
            ?>

            <form action="order.php" method="POST" enctype="multipart/form-data">
                <?php if (!empty($addresses)): ?>
                    <!-- Hidden inputs for name, phone, and address to be sent to server -->
                    <input type="hidden" id="name" name="name" value="<?php echo htmlspecialchars($init_name); ?>">
                    <input type="hidden" id="phone" name="phone" value="<?php echo htmlspecialchars($init_phone); ?>">
                    <input type="hidden" id="address" name="address" value="<?php echo htmlspecialchars($init_address); ?>">
                <?php else: ?>
                    <div class="form-grid">
                        <div class="input-group" style="margin-bottom: 0;">
                            <label for="name">Nama Penerima</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($init_name); ?>" required>
                        </div>
                        <div class="input-group" style="margin-bottom: 0;">
                            <label for="phone">Nomor HP / WhatsApp</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($init_phone); ?>" required>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="input-group" style="margin-top: 20px;">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
                </div>

                <?php if (empty($addresses)): ?>
                    <div class="input-group">
                        <label for="address">Alamat Pengiriman Lengkap</label>
                        <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($init_address); ?></textarea>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 15px; margin-bottom: 15px;">
                        <input type="checkbox" id="save_address" name="save_address" value="1" style="width: auto; cursor: pointer;">
                        <label for="save_address" style="margin: 0; cursor: pointer; font-size: 0.9rem; font-weight: normal;">Simpan alamat ini untuk berikutnya</label>
                    </div>
                <?php endif; ?>

                <?php 
                $selected_product = isset($_GET['product']) ? $_GET['product'] : '';
                $product_id_value = '';
                $product_name_display = '';
                if (!empty($products)) {
                    $product_id_value = $products[0]['id'];
                    $product_name_display = $products[0]['name'] . ' - Rp ' . number_format($products[0]['price'], 0, ',', '.');
                }
                foreach ($products as $p) {
                    if (strtolower($selected_product) == strtolower($p['name'])) {
                        $product_id_value = $p['id'];
                        $product_name_display = $p['name'] . ' - Rp ' . number_format($p['price'], 0, ',', '.');
                        break;
                    }
                }
                ?>
                <input type="hidden" name="product_id" value="<?php echo $product_id_value; ?>">
                
                <div class="form-grid">
                    <div class="input-group" style="margin-top: 20px;">
                        <label>Produk Terpilih</label>
                        <input type="text" value="<?php echo htmlspecialchars($product_name_display); ?>" readonly style="background-color: #f5f5f5; border: 1px solid #ddd; cursor: not-allowed; color: #666;">
                    </div>
                    <div class="input-group" style="margin-top: 20px;">
                        <label for="amount">Jumlah / Berat (Kilogram)</label>
                        <input type="number" id="amount" name="amount" min="1" placeholder="Min. 1kg" required>
                    </div>
                </div>

                <div class="input-group" style="margin-top: 20px;">
                    <label for="note">Catatan Tambahan (Opsional)</label>
                    <textarea id="note" name="note" rows="2" placeholder="Detail petunjuk arah, preferensi produk, dll."></textarea>
                </div>

                <button type="submit" name="submit_order" class="btn btn-primary btn-block" style="margin-top: 20px;">Lanjut Pembayaran</button>
            </form>
        </div>
    </div>
</section>


<!-- Address Selection Modal -->
<?php if (!empty($addresses)): ?>
<div id="addressSelectModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 600px; padding: 25px;">
        <span class="close-modal" onclick="closeAddressSelectModal()">&times;</span>
        <h3 style="color: var(--primary-color); margin-bottom: 20px; border-bottom: 2px solid var(--primary-light); padding-bottom: 10px;">Pilih Alamat Pengiriman</h3>
        
        <div style="max-height: 400px; overflow-y: auto; display: grid; gap: 15px; padding-right: 5px;">
            <?php foreach ($addresses as $addr): ?>
                <div class="address-select-card" style="border: 1px solid var(--gray-border); border-radius: 8px; padding: 15px; cursor: pointer; transition: var(--transition); position: relative;" onclick="selectAddress(<?php echo htmlspecialchars(json_encode($addr)); ?>)">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <strong style="color: var(--text-dark);"><?php echo htmlspecialchars($addr['receiver_name']); ?></strong>
                        <span style="color: var(--text-light); font-size: 0.9rem;">| <?php echo htmlspecialchars($addr['receiver_phone']); ?></span>
                        <span style="background: #e8f4fd; color: #1e88e5; padding: 2px 6px; border-radius: 3px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;"><?php echo htmlspecialchars($addr['label']); ?></span>
                        <?php if ($addr['is_default']): ?>
                            <span style="background: var(--primary-light); color: var(--primary-color); padding: 2px 6px; border-radius: 3px; font-size: 0.7rem; font-weight: 600;">Utama</span>
                        <?php endif; ?>
                    </div>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.9rem; line-height: 1.4; white-space: pre-line;"><?php echo htmlspecialchars($addr['address']); ?></p>
                    
                    <div style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--primary-color); font-size: 1.2rem; display: none;" class="check-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
            <a href="addresses.php" target="_blank" class="btn btn-outline" style="padding: 8px 20px; font-size: 0.9rem;"><i class="fas fa-cog"></i> Kelola Alamat</a>
            <button type="button" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem; margin-left: 10px;" onclick="closeAddressSelectModal()">Tutup</button>
        </div>
    </div>
</div>

<style>
    .address-select-card:hover {
        border-color: var(--primary-color);
        background-color: #fffcf8;
    }
    .address-select-card.active {
        border-color: var(--primary-color);
        background-color: #fffcf8;
    }
    .address-select-card.active .check-icon {
        display: block !important;
    }
</style>

<script>
    const addressModal = document.getElementById('addressSelectModal');
    const savedAddressesList = <?php echo json_encode($addresses); ?>;
    let selectedAddressId = <?php echo $default_address ? $default_address['id'] : ($addresses[0]['id'] ?? 0); ?>;
    
    function openAddressSelectModal() {
        addressModal.style.display = 'block';
        // Mark active card
        const cards = document.querySelectorAll('.address-select-card');
        cards.forEach((card, index) => {
            const addr = savedAddressesList[index];
            if (addr.id === selectedAddressId) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }
    
    function closeAddressSelectModal() {
        addressModal.style.display = 'none';
    }
    
    function selectAddress(addr) {
        selectedAddressId = addr.id;
        
        // Update Form inputs
        document.getElementById('name').value = addr.receiver_name;
        document.getElementById('phone').value = addr.receiver_phone;
        document.getElementById('address').value = addr.address;
        
        // Update Display card in the page
        document.getElementById('active-label').innerText = addr.label;
        document.getElementById('active-receiver-name').innerText = addr.receiver_name;
        document.getElementById('active-receiver-phone').innerText = "| " + addr.receiver_phone;
        document.getElementById('active-address-text').innerText = addr.address;
        
        closeAddressSelectModal();
    }
    
    // Close modal on outside click
    window.addEventListener('click', function(event) {
        if (event.target == addressModal) {
            closeAddressSelectModal();
        }
    });
</script>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
