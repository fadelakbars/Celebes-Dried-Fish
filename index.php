<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container" style="display: flex; justify-content: center; align-items: center; width: 100%;">
        <div class="hero-content" style="background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); padding: 50px; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 15px 35px rgba(0,0,0,0.2); text-align: center; max-width: 800px;">
            <h1 style="margin-bottom: 10px; text-transform: uppercase; letter-spacing: 3px; color: white; font-size: 3.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">CELEBES <br>DRIED FISH</h1>
            <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 30px;">
                <div style="height: 2px; width: 40px; background-color: var(--primary-color);"></div>
                <span style="font-weight: 600; font-size: 1.2rem; color: #fff; letter-spacing: 5px; text-transform: uppercase; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">PT Romo Aquatic Gowa</span>
                <div style="height: 2px; width: 40px; background-color: var(--primary-color);"></div>
            </div>
            <p style="color: #eee; font-size: 1.1rem; margin-bottom: 35px; line-height: 1.8;">Rasakan cita rasa otentik dari laut Sulawesi. Diproses secara higienis, tanpa bahan pengawet buatan, dan dijemur dengan sinar matahari alami.</p>
            <div class="hero-btns" style="display: flex; justify-content: center; gap: 20px;">
                <a href="product.php" class="btn btn-primary" style="padding: 12px 35px;">Lihat Produk</a>
                <a href="about.php" class="btn btn-outline" style="padding: 12px 35px; border-color: white; color: white;">Tentang Kami</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section style="padding: 60px 0; background-color: var(--gray-light);">
    <div class="container">
        <h2 class="section-title">Mengapa Memilih Kami?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; text-align: center;">
            <div style="background: var(--white); padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <i class="fas fa-fish" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 15px;">Kualitas Premium</h3>
                <p>Ikan segar pilihan yang diproses langsung dari tangkapan nelayan lokal dengan standar kualitas tinggi.</p>
            </div>
            <div style="background: var(--white); padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <i class="fas fa-sun" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 15px;">100% Alami</h3>
                <p>Dikeringkan secara alami di bawah sinar matahari dan diproses tanpa tambahan bahan kimia berbahaya.</p>
            </div>
            <div style="background: var(--white); padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <i class="fas fa-truck" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 15px;">Pengiriman Aman</h3>
                <p>Dikemas dengan aman dan higienis memastikan kualitas produk tetap terjaga sampai ke tangan Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section (Copied from service.php) -->
<section style="padding: 100px 0 60px;">
    <div class="container">
        <h2 class="section-title">Layanan Kami</h2>
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

<!-- Products Section (Copied from product.php) -->
<section style="padding: 60px 0; background-color: var(--gray-light);">
    <div class="container">
        <h2 class="section-title">Produk Kami</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
            <?php
            $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
            if($res->num_rows > 0):
                while($row = $res->fetch_assoc()):
            ?>
            <!-- Product Card -->
            <div style="border: 1px solid var(--gray-border); border-radius: 10px; overflow: hidden; transition: var(--transition); background: white;">
                <div style="height: 300px; background: #eee; overflow: hidden;">
                    <img src="assets/img/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 20px;">
                    <h3 style="margin-bottom: 10px; color: var(--primary-color);"><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 15px; min-height: 60px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;"><?php echo htmlspecialchars($row['description']); ?></p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: bold; font-size: 1.2rem; color: var(--text-dark);">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?> <small style="font-size:0.8rem; font-weight:normal;">/ kg</small></span>
                        <div style="display: flex; gap: 8px;">
                            <form action="cart_action.php?action=add" method="POST" style="margin: 0;">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn" style="padding: 8px 12px; font-size: 0.9rem; border: 1px solid var(--primary-color); background: white; color: var(--primary-color); cursor: pointer; border-radius: 5px; transition: 0.3s;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='var(--primary-color)';" title="Tambah ke Keranjang"><i class="fas fa-cart-plus"></i></button>
                            </form>
                            <a href="order.php?product=<?php echo urlencode($row['name']); ?>" class="btn btn-primary" style="padding: 8px 15px; font-size: 0.9rem;">Pesan</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else:
            ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; color: #666;">
                    <i class="fas fa-box-open" style="font-size: 4rem; margin-bottom: 20px; color: #ccc;"></i>
                    <p>Maaf, saat ini belum ada produk yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 60px; margin-bottom: 40px;">
            <p style="margin-bottom: 20px;">Ingin memesan dalam jumlah besar atau untuk tanggal tertentu?</p>
            <a href="pre-order.php" class="btn" style="background: linear-gradient(135deg, var(--primary-color), #d35400); color: white; padding: 15px 40px; font-size: 1.1rem; border-radius: 50px; box-shadow: 0 10px 20px rgba(163, 78, 0, 0.2); transition: var(--transition); font-weight: 600; display: inline-block;">
                <i class="fas fa-calendar-check" style="margin-right: 10px;"></i> Lakukan Pre-Order Sekarang
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
