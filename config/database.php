<?php
session_start();

// Database credentials
$host = getenv('DB_HOST') ?: "127.0.0.1";
$username = getenv('DB_USERNAME') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$database = getenv('DB_DATABASE') ?: "celebes_dried_fish";
$port = (int) (getenv('DB_PORT') ?: 3306);
$socket = getenv('DB_SOCKET') ?: null;

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port, $socket);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Global functions
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

function check_login() {
    if(!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Silakan login terlebih dahulu untuk melanjutkan.';
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("Location: $redirect");
        exit();
    }
}

function check_admin() {
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../index.php");
        exit();
    }
}
?>
