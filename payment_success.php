<?php
require_once 'config/database.php';
check_login();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = isset($_GET['type']) ? sanitize_input($_GET['type']) : 'order';

if ($id <= 0) {
    header("Location: index.php");
    exit();
}

$table = ($type == 'pre_order') ? 'pre_orders' : 'orders';
$stmt = $conn->prepare("SELECT * FROM $table WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$order = $res->fetch_assoc();

// Update status to 'pending' as requested, keeping payment info
$update_stmt = $conn->prepare("UPDATE $table SET status = 'pending', payment = 'Midtrans' WHERE id = ?");
$update_stmt->bind_param("i", $id);
$update_stmt->execute();
$update_stmt->close();

$name = $order['name'];
$phone = $order['phone'];
$address = $order['address'];
$note = $order['note'];
$total_price = $order['total_price'];

// Construct WhatsApp message
$admin_wa = "6281234567890"; // Ganti dengan nomor WA admin
$wa_text = "Halo Celebes Dried Fish! Saya ingin konfirmasi pesanan (TELAH DIBAYAR VIA MIDTRANS):\n\n"
         . "*Nama*: $name\n"
         . "*No HP*: $phone\n"
         . "*Alamat*: $address\n"
         . "*Catatan*: $note\n\n";

if ($type == 'order') {
    $wa_text .= "*DETAIL PESANAN:*\n";
    // Fetch items
    $items_stmt = $conn->prepare("SELECT products.name, order_items.quantity, order_items.price FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = ?");
    $items_stmt->bind_param("i", $id);
    $items_stmt->execute();
    $items_res = $items_stmt->get_result();
    
    if ($items_res->num_rows > 0) {
        while ($item = $items_res->fetch_assoc()) {
            $subtotal = $item['quantity'] * $item['price'];
            $wa_text .= "- {$item['name']} ({$item['quantity']} kg) - Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }
    } else {
        // Fallback if no items
        $wa_text .= "- {$order['amount']} kg\n";
    }
} else {
    $wa_text .= "*Pre-Order (Jumlah)*: {$order['amount']} kg\n";
    $wa_text .= "*Tgl Kirim*: {$order['date']}\n";
}

$wa_text .= "\n*Total Tagihan*: Rp " . number_format($total_price, 0, ',', '.') . "\n"
          . "*Status Pembayaran*: LUNAS\n\n"
          . "Mohon segera diproses. Terima kasih.";

$wa_url = "https://wa.me/" . $admin_wa . "?text=" . urlencode($wa_text);

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Pembayaran Berhasil!</h1>
        <p>Terima kasih, pembayaran Anda telah kami terima.</p>
    </div>
</div>

<section style="padding: 60px 0; min-height: 50vh;">
    <div class="container" style="max-width: 600px; text-align: center;">
        <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 40px;">
            <i class="fas fa-check-circle" style="font-size: 5rem; color: #2ecc71; margin-bottom: 20px;"></i>
            <h2 style="margin-bottom: 15px; color: #2ecc71;">Transaksi Berhasil</h2>
            <p style="margin-bottom: 30px; color: #666;">Terimakasih telah melakukan pembelian, pesanan anda telah kami simpan dan akan segera kami proses.</p>
            
            <div style="margin-top: 20px;">
                <a href="index.php" style="color: #666; text-decoration: underline;">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</section>

<script>
    setTimeout(function() {
        window.open('<?php echo $wa_url; ?>', '_blank');
        window.location.href = 'index.php';
    }, 10000);
</script>

<?php require_once 'includes/footer.php'; ?>
