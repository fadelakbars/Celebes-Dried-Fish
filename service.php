<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Layanan Kami</h1>
        <p>Solusi kebutuhan ikan kering Anda</p>
    </div>
</div>

<section style="padding: 100px 0; min-height: 70vh;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
            
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 30px; border: 1px solid var(--gray-border); border-radius: 10px; height: 100%; box-sizing: border-box;">
                <div style="width: 80px; height: 80px; background-color: var(--primary-light); color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 20px;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 style="margin-bottom: 15px;">Penjualan Eceran</h3>
                <p style="color: var(--text-light); margin-bottom: 20px;">Kami melayani pembelian secara eceran untuk kebutuhan konsumsi rumah tangga harian Anda. Produk kami dikemas dengan praktis, higienis, dan siap untuk diolah kapan saja.</p>
                <a href="product.php" class="btn btn-primary" style="margin-top: auto;">Lihat Produk</a>
            </div>

            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 30px; border: 1px solid var(--gray-border); border-radius: 10px; height: 100%; box-sizing: border-box;">
                <div style="width: 80px; height: 80px; background-color: var(--primary-light); color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 20px;">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3 style="margin-bottom: 15px;">Grosir</h3>
                <p style="color: var(--text-light); margin-bottom: 20px;">Ingin berbisnis? Kami menawarkan harga khusus serta diskon menarik untuk setiap pembelian dalam jumlah besar (grosir) bagi Anda yang ingin menjadi mitra strategis kami.</p>
                <a href="media.php" class="btn btn-primary" style="margin-top: auto;">Hubungi Kami</a>
            </div>

            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 30px; border: 1px solid var(--gray-border); border-radius: 10px; height: 100%; box-sizing: border-box;">
                <div style="width: 80px; height: 80px; background-color: var(--primary-light); color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 20px;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 style="margin-bottom: 15px;">Pre-Order (PO)</h3>
                <p style="color: var(--text-light); margin-bottom: 20px;">Untuk acara khusus atau stok jangka panjang, kami menyediakan layanan pemesanan terjadwal (Pre-Order) untuk memastikan Anda mendapat produk yang paling segar.</p>
                <a href="pre-order.php" class="btn btn-primary" style="margin-top: auto;">Pesan Pre-Order</a>
            </div>
            
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
