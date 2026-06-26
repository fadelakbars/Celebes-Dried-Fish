<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Kebijakan Privasi</h1>
        <p>Bagaimana kami mengelola dan melindungi data pribadi Anda</p>
    </div>
</div>

<section style="padding: 60px 0;">
    <div class="container" style="max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
        <h2 style="color: var(--primary-color); margin-bottom: 20px;">1. Informasi yang Kami Kumpulkan</h2>
        <p style="margin-bottom: 15px; line-height: 1.6;">Kami mengumpulkan informasi penting untuk memproses transaksi pesanan Anda di Celebes Dried Fish. Informasi ini mencakup:</p>
        <ul style="list-style-type: disc; padding-left: 20px; margin-bottom: 25px; line-height: 1.6;">
            <li style="margin-bottom: 8px;">Nama lengkap penerima pesanan.</li>
            <li style="margin-bottom: 8px;">Alamat pengiriman lengkap (termasuk koordinat peta jika Anda menggunakan fitur peta).</li>
            <li style="margin-bottom: 8px;">Nomor telepon / WhatsApp.</li>
            <li style="margin-bottom: 8px;">Alamat email akun.</li>
        </ul>

        <h2 style="color: var(--primary-color); margin-bottom: 20px;">2. Penggunaan Informasi Anda</h2>
        <p style="margin-bottom: 15px; line-height: 1.6;">Data yang kami kumpulkan digunakan secara eksklusif untuk tujuan berikut:</p>
        <ul style="list-style-type: disc; padding-left: 20px; margin-bottom: 25px; line-height: 1.6;">
            <li style="margin-bottom: 8px;">Memproses pesanan dan melakukan pengiriman produk.</li>
            <li style="margin-bottom: 8px;">Menghubungi Anda terkait status pesanan atau konfirmasi pembayaran.</li>
            <li style="margin-bottom: 8px;">Memfasilitasi transaksi pembayaran yang aman melalui Payment Gateway Midtrans.</li>
            <li style="margin-bottom: 8px;">Meningkatkan pengalaman berbelanja dan keamanan akun Anda di website kami.</li>
        </ul>

        <h2 style="color: var(--primary-color); margin-bottom: 20px;">3. Cookie dan Teknologi Pelacak</h2>
        <p style="margin-bottom: 15px; line-height: 1.6;">Website kami hanya menggunakan cookie sesi fungsional (`PHPSESSID`) yang mutlak diperlukan agar fitur keranjang belanja, proses login, dan checkout berjalan dengan baik. Cookie ini tidak mengumpulkan data pribadi di luar aktivitas berbelanja Anda dan akan otomatis kedaluwarsa setelah Anda menutup browser.</p>

        <h2 style="color: var(--primary-color); margin-bottom: 20px;">4. Penyimpanan dan Keamanan Data</h2>
        <p style="margin-bottom: 15px; line-height: 1.6;">Kami berkomitmen penuh untuk menjaga keamanan informasi pribadi Anda. Kami menerapkan enkripsi dan berbagai langkah perlindungan teknis di server kami untuk mencegah akses yang tidak sah atau kebocoran data pribadi Anda.</p>

        <h2 style="color: var(--primary-color); margin-bottom: 20px;">5. Hubungi Kami</h2>
        <p style="margin-bottom: 15px; line-height: 1.6;">Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami melalui:</p>
        <ul style="list-style-type: none; padding-left: 0; margin-bottom: 25px; line-height: 1.6;">
            <li style="margin-bottom: 8px;"><i class="fas fa-envelope" style="color: var(--primary-color); margin-right: 10px;"></i> celebesdriedfish@gmail.com</li>
            <li style="margin-bottom: 8px;"><i class="fas fa-phone" style="color: var(--primary-color); margin-right: 10px;"></i> +62 853 9784 6292</li>
        </ul>
        
        <div style="border-top: 1px solid #eee; padding-top: 20px; text-align: center; margin-top: 40px; color: #888; font-size: 0.9rem;">
            Terakhir diperbarui: <?php echo date('d F Y'); ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
