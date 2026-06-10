<?php
require_once 'config/database.php';

try {
    $conn->query("ALTER TABLE orders ADD COLUMN total_price INT NOT NULL DEFAULT 0 AFTER amount");
} catch (Exception $e) {}

try {
    $conn->query("ALTER TABLE pre_orders ADD COLUMN total_price INT NOT NULL DEFAULT 0 AFTER amount");
} catch (Exception $e) {}

// Create order_items table
$sql = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql) === TRUE) {
    echo "Database migrated successfully.\n";
} else {
    echo "Error migrating database: " . $conn->error . "\n";
}

// Create user_addresses table
$sql_addresses = "CREATE TABLE IF NOT EXISTS user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    label VARCHAR(100) NOT NULL,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql_addresses) === TRUE) {
    echo "user_addresses table created successfully.\n";
} else {
    echo "Error creating user_addresses table: " . $conn->error . "\n";
}
?>
