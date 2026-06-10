<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Produk Kami</h1>
        <p>Pilihan ikan kering kualitas premium</p>
    </div>
</div>

<section style="padding: 60px 0 0;">
    <div class="container">
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
            

        </div>
        
        <div style="text-align: center; margin-top: 60px; margin-bottom: 100px;">
            <p style="margin-bottom: 20px;">Ingin memesan dalam jumlah besar atau untuk tanggal tertentu?</p>
            <a href="pre-order.php" class="btn" style="background: linear-gradient(135deg, var(--primary-color), #d35400); color: white; padding: 15px 40px; font-size: 1.1rem; border-radius: 50px; box-shadow: 0 10px 20px rgba(163, 78, 0, 0.2); transition: var(--transition); font-weight: 600; display: inline-block;">
                <i class="fas fa-calendar-check" style="margin-right: 10px;"></i> Lakukan Pre-Order Sekarang
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
