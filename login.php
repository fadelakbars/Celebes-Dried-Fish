<?php
require_once 'config/database.php';

// If user is already logged in, redirect
if(isset($_SESSION['user_id'])) {
    if($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

// Handle Login
if(isset($_POST['login'])) {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if($user['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Password salah!";
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan!";
    }
    $stmt->close();
    // Redirect back to page to show error (you might want to handle this better in production)
    header("Location: index.php?error=login");
    exit();
}

// Handle Registration
if(isset($_POST['register'])) {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if($check->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Email sudah terdaftar!";
        header("Location: index.php?error=register");
        exit();
    }
    $check->close();
    
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    
    if($stmt->execute()) {
        $_SESSION['success'] = "Registrasi berhasil, silakan login!";
        header("Location: index.php?success=register");
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat registrasi.";
        header("Location: index.php?error=register");
    }
    $stmt->close();
    exit();
}

// If accessed directly without POST
header("Location: index.php");
exit();
?>
