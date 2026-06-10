<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// If session is not started, start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = 0;
$total_kg = 0;
$cart_items = [];

if (!empty($cart)) {
    // Get product details for items in cart
    $ids = implode(',', array_keys($cart));
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id IN ($ids)");
    $stmt->execute();
    $res = $stmt->get_result();
    
    while ($row = $res->fetch_assoc()) {
        $product_id = $row['id'];
        $quantity = $cart[$product_id];
        $subtotal = $row['price'] * $quantity;
        
        $row['quantity'] = $quantity;
        $row['subtotal'] = $subtotal;
        $cart_items[] = $row;
        
        $total_price += $subtotal;
        $total_kg += $quantity;
    }
}
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Keranjang Belanja</h1>
        <p>Periksa kembali daftar pesanan Anda sebelum checkout</p>
    </div>
</div>

<section style="padding: 60px 0; min-height: 50vh;">
    <div class="container">
        
        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg'] == 'added'): ?>
                <div class="alert alert-success">Produk berhasil ditambahkan ke keranjang!</div>
            <?php elseif($_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success">Keranjang berhasil diperbarui!</div>
            <?php elseif($_GET['msg'] == 'removed'): ?>
                <div class="alert alert-success">Produk dihapus dari keranjang!</div>
            <?php elseif($_GET['msg'] == 'cleared'): ?>
                <div class="alert alert-success">Keranjang telah dikosongkan.</div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(empty($cart_items)): ?>
            <div style="text-align: center; padding: 50px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 15px;">Keranjang Belanja Kosong</h3>
                <p style="color: #666; margin-bottom: 20px;">Anda belum menambahkan produk apa pun ke dalam keranjang.</p>
                <a href="product.php" class="btn btn-primary">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: 1fr 350px; gap: 30px;">
                <!-- Cart Items -->
                <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
                        <h3 style="margin: 0;">Detail Produk</h3>
                        <a href="cart_action.php?action=clear" class="btn-sm btn-danger" onclick="return confirm('Kosongkan keranjang?');" style="background: transparent; color: var(--danger-color); text-decoration: underline; font-size: 0.9rem;"><i class="fas fa-trash-alt"></i> Kosongkan</a>
                    </div>
                    
                    <?php foreach($cart_items as $item): ?>
                        <div style="display: flex; gap: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                            <img src="assets/img/<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                            <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div style="display: flex; justify-content: space-between;">
                                    <h4 style="margin: 0; color: var(--primary-color); font-size: 1.1rem;"><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <a href="cart_action.php?action=remove&id=<?php echo $item['id']; ?>" style="color: var(--danger-color);"><i class="fas fa-times"></i></a>
                                </div>
                                <div style="color: var(--text-light); font-size: 0.9rem;">
                                    Harga: Rp <?php echo number_format($item['price'], 0, ',', '.'); ?> / kg
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                    <form action="cart_action.php?action=update" method="POST" style="display: flex; align-items: center; gap: 10px;">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <div style="display: flex; align-items: center; border: 1px solid #ddd; border-radius: 5px; overflow: hidden;">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 60px; padding: 8px; border: none; text-align: center; border-right: 1px solid #ddd;" onchange="this.form.submit()">
                                            <span style="padding: 0 10px; background: #f9f9f9; color: #666; font-size: 0.9rem;">Kg</span>
                                        </div>

                                    </form>
                                    <strong style="font-size: 1.1rem;">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Order Summary -->
                <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 30px; height: fit-content; position: sticky; top: 100px;">
                    <h3 style="margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 15px;">Ringkasan Pesanan</h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; color: var(--text-light);">
                        <span>Total Berat:</span>
                        <strong style="color: var(--text-dark);"><?php echo $total_kg; ?> Kg</strong>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 25px; color: var(--text-light);">
                        <span>Subtotal Produk:</span>
                        <strong style="color: var(--text-dark);">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></strong>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-top: 1px dashed #ddd; padding-top: 20px;">
                        <span style="font-weight: 600; font-size: 1.1rem;">Total Tagihan:</span>
                        <strong style="color: var(--primary-color); font-size: 1.5rem;">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></strong>
                    </div>
                    
                    <a href="checkout.php" class="btn btn-primary btn-block" style="padding: 15px; font-size: 1.1rem; font-weight: 600; text-align: center;">
                        Checkout Pesanan <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    </a>
                    
                    <div style="text-align: center; margin-top: 15px;">
                        <a href="product.php" style="color: var(--text-light); text-decoration: underline; font-size: 0.9rem;">
                            <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
            
            <style>
                @media (max-width: 768px) {
                    .container > div {
                        grid-template-columns: 1fr !important;
                    }
                }
            </style>
        <?php endif; ?>
        
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
