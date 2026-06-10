<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);">Tentang Kita</h1>
        <p>Mengenal lebih dekat Celebes Dried Fish</p>
    </div>
</div>

<section style="padding: 60px 0;">
    <div class="container" style="display: flex; flex-wrap: wrap; gap: 40px; align-items: center; justify-content: flex-start; max-width: 95%; margin-left: 3%; margin-right: auto;">
        <div style="flex: 1.3; min-width: 300px;">
            <!-- Image Slider -->
            <div class="about-slider" style="position: relative; height: 500px; border-radius: 15px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                <div id="slides-container" style="display: flex; height: 100%; transition: transform 0.5s ease-in-out;">
                    <div style="min-width: 100%; height: 100%;"><img src="assets/img/tentang1.jpg" style="width: 100%; height: 100%; object-fit: cover;"></div>
                    <div style="min-width: 100%; height: 100%;"><img src="assets/img/tentang2.jpg" style="width: 100%; height: 100%; object-fit: cover;"></div>
                    <div style="min-width: 100%; height: 100%;"><img src="assets/img/tentang3.jpg" style="width: 100%; height: 100%; object-fit: cover;"></div>
                    <div style="min-width: 100%; height: 100%;"><img src="assets/img/tentang4.jpg" style="width: 100%; height: 100%; object-fit: cover;"></div>
                    <div style="min-width: 100%; height: 100%;"><img src="assets/img/tentang5.jpg" style="width: 100%; height: 100%; object-fit: cover;"></div>
                </div>
                
                <!-- Navigation Buttons -->
                <button onclick="moveSlide(-1)" style="position: absolute; top: 50%; left: 15px; transform: translateY(-50%); background: rgba(255,255,255,0.7); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; transition: 0.3s; z-index: 10;"><i class="fas fa-chevron-left"></i></button>
                <button onclick="moveSlide(1)" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); background: rgba(255,255,255,0.7); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; transition: 0.3s; z-index: 10;"><i class="fas fa-chevron-right"></i></button>
                
                <!-- Indicators -->
                <div style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px;">
                    <div class="dot" style="width: 10px; height: 10px; background: rgba(255,255,255,0.5); border-radius: 50%;"></div>
                    <div class="dot" style="width: 10px; height: 10px; background: rgba(255,255,255,0.5); border-radius: 50%;"></div>
                    <div class="dot" style="width: 10px; height: 10px; background: rgba(255,255,255,0.5); border-radius: 50%;"></div>
                    <div class="dot" style="width: 10px; height: 10px; background: rgba(255,255,255,0.5); border-radius: 50%;"></div>
                    <div class="dot" style="width: 10px; height: 10px; background: rgba(255,255,255,0.5); border-radius: 50%;"></div>
                </div>
            </div>
            
            <script>
                let currentSlide = 0;
                const totalSlides = 5;
                const container = document.getElementById('slides-container');
                const dots = document.querySelectorAll('.dot');

                function updateDots() {
                    dots.forEach((dot, index) => {
                        dot.style.background = index === currentSlide ? '#fff' : 'rgba(255,255,255,0.5)';
                        dot.style.width = index === currentSlide ? '25px' : '10px';
                        dot.style.borderRadius = index === currentSlide ? '5px' : '50%';
                        dot.style.transition = '0.3s';
                    });
                }

                function moveSlide(direction) {
                    currentSlide += direction;
                    if (currentSlide >= totalSlides) currentSlide = 0;
                    if (currentSlide < 0) currentSlide = totalSlides - 1;
                    
                    container.style.transform = `translateX(-${currentSlide * 100}%)`;
                    updateDots();
                }

                // Auto slide every 5 seconds
                setInterval(() => moveSlide(1), 5000);
                updateDots();
            </script>
        </div>
        <div style="flex: 1; min-width: 300px;">
            <h2 style="color: var(--primary-color); margin-bottom: 20px;">Kisah Kami</h2>
            <p style="margin-bottom: 15px;">Celebes Dried Fish berawal dari semangat untuk melestarikan tradisi pengolahan ikan kering masyarakat pesisir Sulawesi yang terkenal dengan kualitasnya. Kami berkomitmen untuk menghadirkan produk laut terbaik yang tidak hanya lezat, tetapi juga higienis dan aman untuk dikonsumsi keluarga Anda.</p>
            <p style="margin-bottom: 15px;">Semua produk kami diproses dengan standar ketat, mulai dari pemilihan ikan segar, proses penggaraman yang pas, hingga penjemuran di bawah sinar matahari alami tanpa menggunakan bahan kimia atau pengawet buatan.</p>
            
            <h3 style="color: var(--primary-color); margin: 30px 0 15px;">Visi & Misi</h3>
            <ul style="list-style-type: disc; padding-left: 20px; margin-bottom: 20px;">
                <li style="margin-bottom: 10px;">Menjadi penyedia ikan kering premium terdepan di Indonesia.</li>
                <li style="margin-bottom: 10px;">Memberdayakan nelayan lokal dengan membeli hasil tangkapan dengan harga yang adil.</li>
                <li style="margin-bottom: 10px;">Menjaga kualitas dan kebersihan setiap produk.</li>
            </ul>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
