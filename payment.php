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

// Midtrans configuration
$clientKey = 'Mid-client-EJuj_YZGbMxrWQ0E';
$serverKey = 'Mid-server-TityNbLzJ7khZKHxi3EjA7Ee';
// Menggunakan sandbox endpoint untuk simulasi
$isProduction = false; 
$snapUrl = $isProduction ? "https://app.midtrans.com/snap/v1/transactions" : "https://app.sandbox.midtrans.com/snap/v1/transactions";

$midtrans_order_id = "CDF-" . strtoupper($type) . "-" . $order['id'] . "-" . time();
$gross_amount = $order['total_price'];

// If total_price is 0 for some reason, provide a fallback
if ($gross_amount <= 0) {
    $gross_amount = 10000;
}

$params = array(
    'transaction_details' => array(
        'order_id' => $midtrans_order_id,
        'gross_amount' => $gross_amount,
    ),
    'customer_details' => array(
        'first_name' => $order['name'],
        'email' => $order['email'],
        'phone' => $order['phone'],
    ),
);

$snapToken = "";

// Request Snap Token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $snapUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode($serverKey . ':')
));
$result = curl_exec($ch);
curl_close($ch);

$resultObj = json_decode($result);
if (isset($resultObj->token)) {
    $snapToken = $resultObj->token;
} else {
    $error_msg = "Gagal mendapatkan token pembayaran dari Midtrans. " . ($resultObj->error_messages[0] ?? 'Periksa kunci API.');
}

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Selesaikan Pembayaran</h1>
        <p>Silakan selesaikan pembayaran untuk memproses pesanan Anda</p>
    </div>
</div>

<section style="padding: 60px 0; min-height: 50vh;">
    <div class="container" style="max-width: 600px; text-align: center;">
        <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 40px;">
            <?php if(isset($error_msg)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_msg); ?></div>
                <div style="margin-top: 15px; text-align: left; background: #f8f9fa; padding: 15px; border-radius: 5px; font-size: 0.9rem;">
                    <strong>Midtrans API Response:</strong><br>
                    <?php echo htmlspecialchars($result); ?>
                </div>
                <a href="index.php" class="btn btn-primary" style="margin-top: 20px;">Kembali ke Beranda</a>
            <?php else: ?>
                <h3 style="margin-bottom: 20px;">Total Tagihan: Rp <?php echo number_format($gross_amount, 0, ',', '.'); ?></h3>
                <p style="margin-bottom: 30px; color: #666;">Klik tombol di bawah ini untuk memilih metode pembayaran melalui Midtrans.</p>
                <button id="pay-button" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem; font-weight: bold; width: 100%;">Bayar Sekarang</button>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if(!isset($error_msg) && !empty($snapToken)): ?>
<script src="<?php echo $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js'; ?>" data-client-key="<?php echo $clientKey; ?>"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('<?php echo $snapToken; ?>', {
            onSuccess: function(result){
                window.location.href = 'payment_success.php?type=<?php echo urlencode($type); ?>&id=<?php echo $id; ?>';
            },
            onPending: function(result){
                alert("Pembayaran tertunda. Silakan selesaikan pembayaran Anda!");
            },
            onError: function(result){
                alert("Pembayaran gagal. Silakan coba lagi.");
            },
            onClose: function(){
                // Popup closed before finishing payment
            }
        });
    };
    
    // Automatically trigger payment popup on load
    setTimeout(function() {
        document.getElementById('pay-button').click();
    }, 1000);
</script>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
