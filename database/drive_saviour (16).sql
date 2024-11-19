-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Nov 19, 2024 at 12:46 PM
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
  `description` varchar(250) DEFAULT NULL,
  `created_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `description`, `created_date`) VALUES
(9, 'Oil Pumps', '', '2024-11-18'),
(10, 'Oils', '', '2024-11-18'),
(12, 'Automotive Accessories', '', '2024-11-18'),
(13, 'Lubricant Spare Parts', '', '2024-11-18'),
(14, 'Suspension Components', '', '2024-11-18'),
(15, 'Braking System Components', '', '2024-11-18'),
(16, 'Spoilers', NULL, '2024-11-18'),
(17, 'Electronics Accessories', '', '2024-11-18'),
(18, 'Headlights', '', '2024-11-18'),
(19, 'Air Filter', '', '2024-11-18'),
(20, 'Oil Filter', '', '2024-11-18');

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
(83, 'Nishantha Fernando', 'Bambalapitiya', '0777152679', 'nishanthafernando@gmail.com', '$2y$10$/E51jygS6uZmcVuTac3etusqHG1ph54O08DrQbEQOrVvFg83uXPEO'),
(84, 'test1', 'Kalutara', '0775016186', 'test@gmail.com', '$2y$10$lcWJ1Hdbs621HUVFDCZ31.SGyLrAEVWE1Zzoow/ZEVfAx.EtWn4/e'),
(85, 'test2', 'Panadura', '0775017187', 'test2@gmail.com', '$2y$10$n588WTonb5ncYRy8fR39PeTzKpEHTJhsZznr6EkrSOPMamAkVBs7W');

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

--
-- Dumping data for table `mech_loyalty_card`
--

INSERT INTO `mech_loyalty_card` (`id`, `card_no`, `expire_date`, `email`) VALUES
(4, '9856816773908767', '2025-11-19', 'nishanthafernando@gmail.com');

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
(86, 'ORD-673b3e8a080849.74979986', 41, 1, '2024-11-18', 13100.00, 29100.00, 0.00, 12707.00, 393.00, 'ramithacampus@gmail.com', 'Completed', 'Pending', NULL),
(87, 'ORD-673b3e8a080849.74979986', 35, 1, '2024-11-18', 14500.00, 29100.00, 0.00, 14065.00, 435.00, 'ramithacampus@gmail.com', 'Completed', 'Pending', NULL),
(88, 'ORD-673b3e8a080849.74979986', 44, 1, '2024-11-18', 1500.00, 29100.00, 0.00, 1455.00, 45.00, 'ramithacampus@gmail.com', 'Completed', 'Pending', NULL),
(89, 'ORD-673c11b7e59791.18796763', 37, 11, '2024-11-19', 88000.00, 97500.00, 0.00, 85360.00, 2640.00, 'ramithacampus@gmail.com', 'Pending', 'Pending', NULL),
(90, 'ORD-673c11b7e59791.18796763', 48, 1, '2024-11-19', 9500.00, 97500.00, 0.00, 9215.00, 285.00, 'ramithacampus@gmail.com', 'Pending', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `paid_mechanics`
--

CREATE TABLE `paid_mechanics` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `purchase_date` date NOT NULL,
  `expire_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(34, 48, 9, 'Wagon R Oil Pump', '../../uploads/suzuki-wagon-r-genuine-oil-pump-22575353.jpg', 18, 18000.00),
(35, 48, 13, 'ALTO SUMP', '../../uploads/images.jpeg', 8, 14500.00),
(36, 49, 13, 'ALTO SUMP', '../../uploads/oil-sump-suzuki-alto-iii-10-vvt-11511m65l10-000-p0-6248p.jpg', 15, 14500.00),
(37, 49, 10, 'Valvoline Synpower 5W30', '../../uploads/xpro-thumb-1692179944.png.pagespeed.ic.AhI2q4y2Cs.png', 19, 8000.00),
(41, 51, 10, 'Shell Rotella', '../../uploads/Shell-Rotella-5w40-600x600.png', 19, 13100.00),
(42, 51, 12, 'Steering wheel cover', '../../uploads/71-3u2EG4rL._AC_SX466_.jpg', 5, 750.00),
(43, 52, 12, 'Seat cover', '../../uploads/81QonsMl7tL._AC_SX466_.jpg', 10, 17000.00),
(44, 52, 12, 'Floor mats', '../../uploads/71uIoWQhMAL._AC_SX466_.jpg', 4, 1500.00),
(45, 51, 18, 'Kahn Headlight', '../../uploads/69089-rtc4615-k-halogen-headlamp-kit-contains-2-units.jpg', 20, 20000.00),
(46, 51, 18, '5 x 7 Square LED Headlight', '../../uploads/d49aaa21c5b502dc1c4d86c568328547.jpg_720x720q80.jpg', 10, 15000.00),
(47, 48, 19, 'Air Filter - CHAMP PH2876', '../../uploads/png-transparent-oil-filter-air-filter-car-filtration-synthetic-oil-car-logo-quality-oil.png', 15, 5050.00),
(48, 48, 14, 'Shock Absorber 12 inch', '../../uploads/Shock-absorbers.jpg', 29, 9500.00),
(49, 49, 18, 'Iron Walls 5 x 7 Square LED Headlight', '../../uploads/d49aaa21c5b502dc1c4d86c568328547.jpg_720x720q80.jpg', 20, 18000.00),
(50, 49, 15, 'Brembo Brake Caliper - 2pcs', '../../uploads/break.jpeg', 25, 3220.00),
(51, 53, 19, 'Air Filter Japan', '../../uploads/png-transparent-oil-filter-air-filter-car-filtration-synthetic-oil-car-logo-quality-oil.png', 5, 5000.00),
(53, 53, 13, 'ALTO SUMP', '../../uploads/images.jpeg', 10, 18000.00);

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
(17, 41, 'ramithacampus@gmail.com', 4, 'good product', '2024-11-18 19:32:27');

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
(49, 'freddy@gmail.com', 'Freddy Motors', 'freddyc4@gmail.com', '0113456789', '150, Milagiriya Rd, Colombo 4.', 'Bambalapitiya'),
(51, 'sarath@gmail.com', 'Sarath Motors', 'sarathmotorskalutara@gmail.com', '0112345678', '8A, Siriniwasa Mawatha, Kalutara North.', 'Kalutara'),
(52, 'sarath@gmail.com', 'Sarath Motors', 'sarathmotorsnagoda@gmail.com', '0112457896', 'No 5, Flower rd, Nagoda', 'Nagoda'),
(53, 'freddy@gmail.com', 'ABC', 'ABC@gmail.com', '0775016186', '1/8A, Siriniwasa Mawatha, Kalutara North.', 'Bambalapitiya'),
(54, 'freddy@gmail.com', 'ABC', 'ramithacampus@gmail.com', '0778121761', '1/8A, Siriniwasa Mawatha, Kalutara North.', 'Kohuwala'),
(55, 'freddy@gmail.com', 'ABC', 'ramithacampus@gmail.com', '0778121761', '1/8A, Siriniwasa Mawatha, Kalutara North.', 'Kohuwala');

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
('Freddy Fonseka', '0777152675', 'freddy@gmail.com', '$2y$10$7LhVg52tGRqqPEjP7ywla.138hfy8vmCrZwROrQI7zxxhzBoBx27y'),
('Sarath Perera', '0748125777', 'sarath@gmail.com', '$2y$10$C/nuiNaTsAK956kqUnM5luYHk.0eqldFR4ckyI/kNMaZcqOW3bOzu');

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

--
-- Dumping data for table `vehicleissues`
--

INSERT INTO `vehicleissues` (`id`, `email`, `v_id`, `mech_id`, `vehicle_model`, `year`, `mobile_number`, `location`, `vehicle_issue`, `created_at`, `status`, `city`) VALUES
(93, 'ramithacampus@gmail.com', 28, NULL, 'Toyota Prius', 2019, '778121762', '6.8856609, 79.8603439', 'test', '2024-11-19 04:35:28', 'Pending', 'Bambalapitiya');

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
(90, 'ramithacampus@gmail.com', 28, 83, '6.893888, 79.854722', 'Engine Overheat', '2024-11-19 04:38:00', 'Done', 'Bambalapitiya');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_owner`
--

CREATE TABLE `vehicle_owner` (
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` int(11) NOT NULL,
  `city` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_owner`
--

INSERT INTO `vehicle_owner` (`name`, `email`, `password`, `phone`, `city`) VALUES
('Osura Chandula', 'osura@gmail.com', '$2y$10$Ng4dwPorO2/l0nShaQdB2u34lPeEXtFSvld7IBzG0dGMws1NoC1gG', 751218047, 'Minuwangoda'),
('Prashid Dilshan', 'prashid@gmail.com', '$2y$10$OaFhyb2zkY.tK0yvribAWu.IyJoFzViGdSk9Orhy4r2zjY6xBfzp2', 725478952, 'Monaragala'),
('Ramitha Heshan', 'ramithacampus@gmail.com', '$2y$10$w5/eYLOv3UT668KnXIt3HOxCbmEvnfMYBYNVq1BTCCCGFWDF/MNvC', 778121762, 'Kalutara');

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
-- Indexes for table `paid_mechanics`
--
ALTER TABLE `paid_mechanics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `loyalty_card`
--
ALTER TABLE `loyalty_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mechanic`
--
ALTER TABLE `mechanic`
  MODIFY `userID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `mech_cart`
--
ALTER TABLE `mech_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mech_loyalty_card`
--
ALTER TABLE `mech_loyalty_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `paid_mechanics`
--
ALTER TABLE `paid_mechanics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `vehicleissues`
--
ALTER TABLE `vehicleissues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `vehicleissuesdone`
--
ALTER TABLE `vehicleissuesdone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

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
-- Constraints for table `paid_mechanics`
--
ALTER TABLE `paid_mechanics`
  ADD CONSTRAINT `paid_mechanics_ibfk_1` FOREIGN KEY (`email`) REFERENCES `mechanic` (`email`);

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
