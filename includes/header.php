<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celebes Dried Fish</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=1.10">
</head>
<body>

<?php if(isset($_SESSION['error'])): ?>
    <div id="global-alert" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000; background: #fff; color: #d63031; padding: 15px 25px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 15px; min-width: 300px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem;"></i>
        <p style="margin: 0; font-size: 1rem; font-weight: 500;"><?php echo $_SESSION['error']; ?></p>
    </div>
    <script>
        setTimeout(function() {
            const alert = document.getElementById('global-alert');
            if(alert) {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translate(-50%, -60%)';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['success'])): ?>
    <div id="global-alert-success" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000; background: #fff; color: #27ae60; padding: 15px 25px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 15px; min-width: 300px;">
        <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
        <p style="margin: 0; font-size: 1rem; font-weight: 500;"><?php echo $_SESSION['success']; ?></p>
    </div>
    <script>
        setTimeout(function() {
            const alert = document.getElementById('global-alert-success');
            if(alert) {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translate(-50%, -60%)';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
