<?php
require_once 'config/database.php';

// Ensure session is started (already done in config/database.php if check_login is called, but database.php might not start session for guests. Let's make sure session is started in database.php. Yes, session_start() is usually in database.php or header.php)

// Check if session is not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'add') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($product_id > 0) {
        // Check if product exists in database
        $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
            }
            // Set success message in session if needed, but we can just redirect
            header("Location: cart.php?msg=added");
            exit();
        }
    }
    header("Location: product.php?error=invalid");
    exit();
} 
elseif ($action == 'update') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($product_id > 0 && isset($_SESSION['cart'][$product_id])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header("Location: cart.php?msg=updated");
    exit();
} 
elseif ($action == 'remove') {
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($product_id > 0 && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: cart.php?msg=removed");
    exit();
}
elseif ($action == 'clear') {
    $_SESSION['cart'] = [];
    header("Location: cart.php?msg=cleared");
    exit();
}

// Fallback
header("Location: cart.php");
exit();
?>
