-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2026 at 11:31 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `celebes_dried_fish`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `amount` int(11) NOT NULL,
  `total_price` int(11) NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `payment` varchar(50) NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` enum('pending','processed','delivery','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `email`, `address`, `amount`, `total_price`, `note`, `payment`, `payment_proof`, `status`, `created_at`) VALUES
(31, 1, 'Ruslan', '082498294982', 'ruslankk41@gmail.com', 'Jalan Sultan Alauddin, Mangasa, Tamalate, Makassar, Sulawesi Selatan, Sulawesi, 92221, Indonesia', 2, 50000, '', 'Midtrans', NULL, 'completed', '2026-06-07 17:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(43, 31, 1, 1, 20000),
(44, 31, 2, 1, 30000);

-- --------------------------------------------------------

--
-- Table structure for table `pre_orders`
--

CREATE TABLE `pre_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `amount` int(11) NOT NULL,
  `total_price` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `note` text DEFAULT NULL,
  `payment` varchar(50) NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` enum('pending','processed','delivery','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pre_orders`
--

INSERT INTO `pre_orders` (`id`, `user_id`, `name`, `phone`, `email`, `address`, `amount`, `total_price`, `date`, `note`, `payment`, `payment_proof`, `status`, `created_at`) VALUES
(5, 1, 'Ruslan', '082498294982', 'ruslankk41@gmail.com', 'Jalan Sultan Alauddin, Mangasa, Tamalate, Makassar, Sulawesi Selatan, Sulawesi, 92221, Indonesia', 1, 40000, '2026-06-25', 'Produk: Medium Dried Sardines\n', 'Payment Gateway', NULL, 'pending', '2026-06-07 17:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'Dried Anchovies', 'Ikan teri premium pilihan, dikeringkan secara alami tanpa bahan pengawet. Gurih dan bergizi tinggi.', 20000.00, 'product-anchovies.png', '2026-05-01 17:54:21'),
(2, 'Small Dried Sardines', 'Ikan sardin ukuran kecil berkualitas tinggi. Memiliki tekstur renyah saat digoreng dan rasa otentik.', 30000.00, 'product-small-sardines.png', '2026-05-01 17:54:21'),
(3, 'Medium Dried Sardines', 'Ikan sardin ukuran sedang yang diolah secara higienis. Kaya protein dan cocok untuk berbagai hidangan.', 40000.00, 'product-medium-sardines.png', '2026-05-01 17:54:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Ruslan', 'ruslankk41@gmail.com', '$2y$10$GmL.sxRx3tL1Smu/e/MVguaGM8HvhMOcB9JQGfDZ6xTkdPnqH79ki', 'admin', '2026-04-30 10:39:57'),
(4, 'user1', 'user1@gmail.com', '$2y$10$VcBgJw/M3FYZaRd99ErxF.oatwihkR2EKHGJq7/WfY/jlZofxO28u', 'user', '2026-04-30 10:47:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `receiver_phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `label`, `receiver_name`, `receiver_phone`, `address`, `is_default`, `created_at`) VALUES
(3, 4, 'Rumah', 'Ruslan', '082498294982', 'Jalan Poros Sungguminasa - Takalar, Panciro, Bajeng, Gowa, Sulawesi Selatan, Sulawesi, 92123, Indonesia', 1, '2026-06-07 06:51:51'),
(4, 4, 'Kantor', 'Akbar', '082498294982', 'Jalan Letjen Hertasning, Bonto Cinde, Tidung, Rappocini, Makassar, Sulawesi Selatan, Sulawesi, 90221, Indonesia', 0, '2026-06-07 06:54:38'),
(5, 1, 'Rumah', 'Ruslan', '082498294982', 'Jalan Abdullah Daeng Sirua Lorong 7B, Karampuang, Panakkukang, Makassar, Sulawesi Selatan, Sulawesi, 90135, Indonesia', 1, '2026-06-07 15:22:42'),
(6, 1, 'Kos', 'Kadir', '082498294982', 'Jalan Poros Sungguminasa - Takalar, Panciro, Bajeng, Gowa, Sulawesi Selatan, Sulawesi, 92123, Indonesia', 0, '2026-06-07 15:23:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `pre_orders`
--
ALTER TABLE `pre_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `pre_orders`
--
ALTER TABLE `pre_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_orders`
--
ALTER TABLE `pre_orders`
  ADD CONSTRAINT `pre_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
