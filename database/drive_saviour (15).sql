-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Nov 12, 2024 at 10:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drive_saviour`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `email` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `dob` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `name`, `password`, `dob`) VALUES
('freddy@gmail.com', 'Freddy Fonnseka', 'admin01', '2002-12-23');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `created_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_card`
--

CREATE TABLE `loyalty_card` (
  `id` int(11) NOT NULL,
  `card_no` varchar(150) NOT NULL,
  `expire_date` date NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loyalty_card`
--

INSERT INTO `loyalty_card` (`id`, `card_no`, `expire_date`, `email`) VALUES
(10, '1809496974655751', '2025-11-12', 'ramithacampus@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `mechanic`
--

CREATE TABLE `mechanic` (
  `userID` int(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mechanic`
--

INSERT INTO `mechanic` (`userID`, `name`, `address`, `phone`, `email`, `password`) VALUES
(83, 'Nishantha Fernando', 'Panadura', '0777152678', 'nishanthafernando@gmail.com', '$2y$10$/E51jygS6uZmcVuTac3etusqHG1ph54O08DrQbEQOrVvFg83uXPEO');

-- --------------------------------------------------------

--
-- Table structure for table `mech_cart`
--

CREATE TABLE `mech_cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `total_price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mech_loyalty_card`
--

CREATE TABLE `mech_loyalty_card` (
  `id` int(11) NOT NULL,
  `card_no` varchar(150) NOT NULL,
  `expire_date` date NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mech_orders`
--

CREATE TABLE `mech_orders` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(150) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `item_total` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `seller_income` decimal(10,2) NOT NULL,
  `commission` decimal(10,2) NOT NULL,
  `email` varchar(150) NOT NULL,
  `status` enum('Pending','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `paid_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mech_ratings`
--

CREATE TABLE `mech_ratings` (
  `id` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `rating_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `product_id` int(150) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `item_total` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `seller_income` decimal(10,2) NOT NULL,
  `commission` decimal(10,2) NOT NULL,
  `email` varchar(150) NOT NULL,
  `status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending',
  `payment_status` varchar(255) DEFAULT 'Pending',
  `paid_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `reference_number`, `product_id`, `quantity`, `purchase_date`, `item_total`, `total_price`, `discount`, `seller_income`, `commission`, `email`, `status`, `payment_status`, `paid_date`) VALUES
(79, 'ORD-6732f5ea16e401.70268856', 34, 1, '2024-11-12', 18000.00, 17460.00, 540.00, 17460.00, 540.00, 'ramithacampus@gmail.com', 'Completed', 'paid', '2024-11-12'),
(80, 'ORD-6732f7c235a449.75708135', 34, 1, '2024-11-12', 18000.00, 32500.00, 0.00, 17460.00, 540.00, 'niklesha@gmail.com', 'Completed', 'paid', '2024-11-12'),
(81, 'ORD-6732f7c235a449.75708135', 35, 1, '2024-11-12', 14500.00, 32500.00, 0.00, 14065.00, 435.00, 'niklesha@gmail.com', 'Completed', 'paid', '2024-11-12');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(255) NOT NULL,
  `order_id` int(11) NOT NULL,
  `paid_date` date DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `quantity_available` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `shop_id`, `cat_id`, `product_name`, `image_url`, `quantity_available`, `price`) VALUES
(34, 48, NULL, 'Wagon R Oil Pump', '../../uploads/suzuki-wagon-r-genuine-oil-pump-22575353.jpg', 18, 18000.00),
(35, 48, NULL, 'ALTO SUMP', '../../uploads/oil-sump-suzuki-alto-iii-10-vvt-11511m65l10-000-p0-6248p.jpg', 9, 14500.00),
(36, 49, NULL, 'ALTO SUMP', '../../uploads/oil-sump-suzuki-alto-iii-10-vvt-11511m65l10-000-p0-6248p.jpg', 15, 14500.00),
(37, 49, NULL, 'Valvoline Synpower 5W30', '../../uploads/xpro-thumb-1692179944.png.pagespeed.ic.AhI2q4y2Cs.png', 30, 8000.00);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `feedback` text DEFAULT NULL,
  `rating_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `product_id`, `user_email`, `rating`, `feedback`, `rating_date`) VALUES
(14, 34, 'ramithacampus@gmail.com', 4, 'Good Product', '2024-11-12 12:00:33'),
(15, 34, 'niklesha@gmail.com', 3, 'Nice one', '2024-11-12 12:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `ownerEmail` varchar(110) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `branch` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `ownerEmail`, `shop_name`, `email`, `number`, `address`, `branch`) VALUES
(48, 'freddy@gmail.com', 'Freddy Motors', 'freddyneg@gmail.com', '0112345678', '50, Poruthota Rd, Negombo', 'Negombo'),
(49, 'freddy@gmail.com', 'Freddy Motors', 'freddyc4@gmail.com', '0113456789', '150, Milagiriya Rd, Colombo 4.', 'Bambalapitiya');

-- --------------------------------------------------------

--
-- Table structure for table `shop_owner`
--

CREATE TABLE `shop_owner` (
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_owner`
--

INSERT INTO `shop_owner` (`name`, `phone`, `email`, `password`) VALUES
('Freddy Fonseka', '0777152675', 'freddy@gmail.com', '$2y$10$7LhVg52tGRqqPEjP7ywla.138hfy8vmCrZwROrQI7zxxhzBoBx27y');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `v_id` int(11) NOT NULL,
  `number_plate` varchar(50) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `fuel_type` varchar(75) NOT NULL,
  `engine_type` varchar(75) NOT NULL,
  `tire_size` varchar(75) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contact` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`v_id`, `number_plate`, `model`, `year`, `fuel_type`, `engine_type`, `tire_size`, `email`, `name`, `contact`) VALUES
(28, 'AB1234', 'Toyota Prius', 2019, 'Hybrid', '1.8L 4-cylinder', '215/55R16', 'ramithacampus@gmail.com', 'Ramitha Heshan', 778121761);

-- --------------------------------------------------------

--
-- Table structure for table `vehicleissues`
--

CREATE TABLE `vehicleissues` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `v_id` int(11) NOT NULL,
  `mech_id` int(11) DEFAULT NULL,
  `vehicle_model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `vehicle_issue` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicleissuesdone`
--

CREATE TABLE `vehicleissuesdone` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `v_id` int(11) NOT NULL,
  `mech_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `vehicle_issue` text NOT NULL,
  `job_done_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicleissuesdone`
--

INSERT INTO `vehicleissuesdone` (`id`, `email`, `v_id`, `mech_id`, `location`, `vehicle_issue`, `job_done_at`, `status`, `city`) VALUES
(87, 'ramithacampus@gmail.com', 28, 83, '6.5982842, 79.9539367', 'Engine Overheating', '2024-11-12 07:01:56', 'Done', 'Panadura');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_owner`
--

CREATE TABLE `vehicle_owner` (
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` int(11) NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `city` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_owner`
--

INSERT INTO `vehicle_owner` (`name`, `email`, `password`, `phone`, `address`, `city`) VALUES
('Niklesha Perera', 'niklesha@gmail.com', '$2y$10$vJYP1Y2ig.RG5k6ntDgce.fUsRgIusxUcKqjwq8blTnuAA/kQybI.', 721594786, NULL, 'Kalutara'),
('Ramitha Heshan', 'ramithacampus@gmail.com', '$2y$10$w5/eYLOv3UT668KnXIt3HOxCbmEvnfMYBYNVq1BTCCCGFWDF/MNvC', 778121761, NULL, 'Kalutara');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyalty_card`
--
ALTER TABLE `loyalty_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `mechanic`
--
ALTER TABLE `mechanic`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `mech_cart`
--
ALTER TABLE `mech_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `mech_loyalty_card`
--
ALTER TABLE `mech_loyalty_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `mech_orders`
--
ALTER TABLE `mech_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `mech_ratings`
--
ALTER TABLE `mech_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownerEmail` (`ownerEmail`);

--
-- Indexes for table `shop_owner`
--
ALTER TABLE `shop_owner`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`v_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `vehicleissues`
--
ALTER TABLE `vehicleissues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `mech_id` (`mech_id`),
  ADD KEY `v_id` (`v_id`);

--
-- Indexes for table `vehicleissuesdone`
--
ALTER TABLE `vehicleissuesdone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `mech_id` (`mech_id`),
  ADD KEY `v_id` (`v_id`);

--
-- Indexes for table `vehicle_owner`
--
ALTER TABLE `vehicle_owner`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loyalty_card`
--
ALTER TABLE `loyalty_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mechanic`
--
ALTER TABLE `mechanic`
  MODIFY `userID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `mech_cart`
--
ALTER TABLE `mech_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mech_loyalty_card`
--
ALTER TABLE `mech_loyalty_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mech_orders`
--
ALTER TABLE `mech_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mech_ratings`
--
ALTER TABLE `mech_ratings`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `vehicleissues`
--
ALTER TABLE `vehicleissues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `vehicleissuesdone`
--
ALTER TABLE `vehicleissuesdone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`email`) REFERENCES `vehicle_owner` (`email`);

--
-- Constraints for table `loyalty_card`
--
ALTER TABLE `loyalty_card`
  ADD CONSTRAINT `loyalty_card_ibfk_1` FOREIGN KEY (`email`) REFERENCES `vehicle_owner` (`email`);

--
-- Constraints for table `mech_cart`
--
ALTER TABLE `mech_cart`
  ADD CONSTRAINT `mech_cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `mech_cart_ibfk_2` FOREIGN KEY (`email`) REFERENCES `mechanic` (`email`);

--
-- Constraints for table `mech_loyalty_card`
--
ALTER TABLE `mech_loyalty_card`
  ADD CONSTRAINT `mech_loyalty_card_ibfk_1` FOREIGN KEY (`email`) REFERENCES `mechanic` (`email`);

--
-- Constraints for table `mech_orders`
--
ALTER TABLE `mech_orders`
  ADD CONSTRAINT `mech_orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `mech_orders_ibfk_2` FOREIGN KEY (`email`) REFERENCES `mechanic` (`email`);

--
-- Constraints for table `mech_ratings`
--
ALTER TABLE `mech_ratings`
  ADD CONSTRAINT `mech_ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `mech_ratings_ibfk_2` FOREIGN KEY (`user_email`) REFERENCES `mechanic` (`email`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`email`) REFERENCES `vehicle_owner` (`email`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_email`) REFERENCES `vehicle_owner` (`email`);

--
-- Constraints for table `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `ownerEmail` FOREIGN KEY (`ownerEmail`) REFERENCES `shop_owner` (`email`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`email`) REFERENCES `vehicle_owner` (`email`);

--
-- Constraints for table `vehicleissues`
--
ALTER TABLE `vehicleissues`
  ADD CONSTRAINT `email` FOREIGN KEY (`email`) REFERENCES `vehicle_owner` (`email`),
  ADD CONSTRAINT `v_id` FOREIGN KEY (`v_id`) REFERENCES `vehicle` (`v_id`),
  ADD CONSTRAINT `vehicleissues_ibfk_1` FOREIGN KEY (`mech_id`) REFERENCES `mechanic` (`userID`);

--
-- Constraints for table `vehicleissuesdone`
--
ALTER TABLE `vehicleissuesdone`
  ADD CONSTRAINT `vehicleissuesdone_ibfk_1` FOREIGN KEY (`email`) REFERENCES `vehicle_owner` (`email`),
  ADD CONSTRAINT `vehicleissuesdone_ibfk_2` FOREIGN KEY (`mech_id`) REFERENCES `mechanic` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
