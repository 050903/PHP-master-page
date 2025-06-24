-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 24, 2025 lúc 07:00 PM
-- Phiên bản máy phục vụ: 8.0.40
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shopsua`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) NOT NULL,
  `discount_percentage` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `short_description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `original_price`, `discount_price`, `discount_percentage`, `image_url`, `short_description`, `created_at`, `updated_at`) VALUES
(1, 'Sữa hạt Đa dưỡng chất & Sữa hạt Bữa ăn thay thế 30g Forganic [Combo 1 gói trải nghiệm]', 150000.00, 129000.00, 14, 'images/1.png', 'Combo 1 gói trải nghiệm', '2025-06-24 16:33:00', '2025-06-24 16:57:15'),
(2, 'Sữa hạt đa dưỡng chất Forganic 400g tặng ly sứ cao cấp [Có Quà]', 450000.00, 410000.00, 9, 'images/2.png', 'tặng ly sứ cao cấp [Có Quà]', '2025-06-24 16:33:00', '2025-06-24 16:57:15'),
(3, 'Sữa hạt đa dưỡng chất Forganic 400g Tặng Sữa bữa ăn thay thế 400g [Mua 1 Tặng 1]', 1249000.00, 789000.00, 37, 'images/3.png', 'Mua 1 Tặng 1', '2025-06-24 16:33:00', '2025-06-24 16:57:15'),
(4, 'Sữa Nan InfiniPro A2 số 3 800g [Combo 6 Lon][Có Quà] cho bé 2-6 tuổi', 3599000.00, 3480000.00, 6, 'images/4.png', 'Combo 6 Lon [Có Quà]', '2025-06-24 16:33:00', '2025-06-24 16:57:15'),
(5, 'Sữa Nan InfiniPro A2 số 3 800g cho bé 2-6 tuổi. [Combo 2 Lon][Có Quà] Tặng Xe điều khiển', 1239000.00, 1160000.00, 6, 'images/5.png', 'Combo 2 Lon [Có Quà] Tặng Xe điều khiển', '2025-06-24 16:33:00', '2025-06-24 16:57:15');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
