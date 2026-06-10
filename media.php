<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Media & Kontak</h1>
        <p>Hubungi kami atau ikuti update terbaru di media sosial</p>
    </div>
</div>

<section style="padding: 60px 0;">
    <div class="container">
        <div style="display: flex; flex-wrap: wrap; gap: 40px;">
            
            <!-- Contact Info -->
            <div style="flex: 1; min-width: 300px;">
                <h2 style="color: var(--primary-color); margin-bottom: 20px;">Informasi Kontak</h2>
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary-color); font-size: 1.5rem; margin-right: 15px; margin-top: 5px;"></i>
                        <div>
                            <h4 style="margin-bottom: 5px;">Alamat Produksi</h4>
                            <p style="color: var(--text-light);">Jl. Poros Barombong, Jl. Ujung Bulo,<br>Lembang Parang, Kec Barombong,<br>Kabupaten Gowa, Sulawesi Selatan 90225</p>
                        </div>
                    </li>
                    <li style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                        <i class="fas fa-phone-alt" style="color: var(--primary-color); font-size: 1.5rem; margin-right: 15px; margin-top: 5px;"></i>
                        <div>
                            <h4 style="margin-bottom: 5px;">Telepon / WhatsApp</h4>
                            <p style="color: var(--text-light);">+62 853 9784 6292</p>
                        </div>
                    </li>
                    <li style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                        <i class="fas fa-envelope" style="color: var(--primary-color); font-size: 1.5rem; margin-right: 15px; margin-top: 5px;"></i>
                        <div>
                            <h4 style="margin-bottom: 5px;">Email</h4>
                            <p style="color: var(--text-light);">admin@celebesdriedfish.com</p>
                        </div>
                    </li>
                </ul>

                <h2 style="color: var(--primary-color); margin: 40px 0 20px;">Sosial Media</h2>
                <div style="display: flex; gap: 15px;">
                    <a href="https://www.facebook.com/share/1EvG175B21/" target="_blank" style="width: 50px; height: 50px; background: #3b5998; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: var(--transition);"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/celebesdriedfish?igsh=cm91aDZ4bHcyeGE1" target="_blank" style="width: 50px; height: 50px; background: #E1306C; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: var(--transition);"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@celebesdriedfish?_t=ZS-8x2YNGl2pAw&_r=1" target="_blank" style="width: 50px; height: 50px; background: #010101; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: var(--transition);"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Contact Form -->
            <div style="flex: 1; min-width: 300px; background: var(--white); padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 20px; color: var(--text-dark);">Kirim Pesan</h3>
                <form id="contactForm">
                    <div class="input-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="subject">Subjek</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="input-group">
                        <label for="message">Pesan</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="sendToWhatsApp()">Kirim pesan via WhatsApp</button>
                </form>
            </div>
            
        </div>
    </div>
</section>

<script>
function sendToWhatsApp() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;

    if (!name || !email || !subject || !message) {
        alert('Mohon lengkapi semua data terlebih dahulu!');
        return;
    }

    const adminPhone = "6285397846292";
    const waText = "Halo Celebes Dried Fish!\n\n" +
                 "*Nama*: " + name + "\n" +
                 "*Email*: " + email + "\n" +
                 "*Subjek*: " + subject + "\n" +
                 "*Pesan*: " + message;
    
    const waUrl = "https://wa.me/" + adminPhone + "?text=" + encodeURIComponent(waText);
    
    window.open(waUrl, '_blank');
}
</script>

<?php require_once 'includes/footer.php'; ?>
