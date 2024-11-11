-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2024 at 03:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
('admin02', 'Nimal Perera', 'nimal123', '2002-06-06'),
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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_id`, `email`, `quantity`, `price`) VALUES
(86, 3, 'ramitha123@gmail.com', 1, 487.00);

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

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `description`, `created_date`) VALUES
(5, 'Oils', 'csdvsd svsv sd', '2024-10-25'),
(6, 'Oil Pumps', 'cv agjsvc jha', '2024-10-25');

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
(73, 'Nishantha Fernando', 'Panadura', '0771234567', 'nishanthafernando@gmail.com', '$2y$10$qjzXUe9yWejd5FeWVGdi3OygqvhFHuR9qM2cBEpA7lruajMdpQMU6'),
(74, 'Samantha Fernando', 'Panadura', '0710592699', 'ramitha123@gmail.com', '$2y$10$R4swRtSxdn1L4W4fnL8MnecfFA58LTMDBEMYu8omhJPQi7Dl8rehG'),
(76, 'himaz', 'Hambanthota', '119', 'himaz@gmail.com', '$2y$10$WHBWwEmobU.G5CPYDRZ7/ewXtDgKfKAbuSSWKTEAR2TKjfAeEQdhK'),
(77, 'Pissu Kanna', 'Kalutara', '123', 'test1@gmail.com', '$2y$10$kyoeNk0CPSpb8xoBh6pRKOJKWtzfHcmfiqZLwJqGpECQ17WKTzl9u'),
(78, 'test2', 'Kalutara', '159', 'test2@gmail.com', '$2y$10$kF/36W1NBJUju76LMd6dbOhEOv36oyx6Su/oynbeVLO.lL1kZUJCO'),
(79, 'test3', 'Kalutara', '357', 'test3@gmail.com', '$2y$10$hvJK81pfwLN/82a8amK5AO3xVGTfvUeqWDCgYyZ5EadkP4RF4DyzW'),
(80, 'test4', 'Kalutara', '456', 'test4@gmail.com', '$2y$10$5QzgQXE99gk4hM0kXEIczeCZ9Ya21WQi7x1XiMTHTuUoA2m8M0mDm'),
(81, 'test5', 'Kalutara', '852', 'test5@gmail.com', '$2y$10$9kXDCpyUmV1SguFWpHtMN.0GFbd3owHlqxgWnQ91YjDAYwLee4jkm'),
(82, 'Ramitha Heshan', '1/8A, Siriniwasa Mawatha, Kalutara North.', '0778121761', 'ramithacampus1@gmail.com', '$2y$10$5U4gpozalbWxggkLcr6aruy1h5DavYWp7Zg6Av0WWkISPrczFyhQi');

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

--
-- Dumping data for table `mech_cart`
--

INSERT INTO `mech_cart` (`id`, `product_id`, `email`, `quantity`, `price`, `total_price`) VALUES
(4, 12, 'ramitha123@gmail.com', 1, 750, 750),
(5, 26, 'ramitha123@gmail.com', 1, 4300, 4300);

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
(2, 'MLC-6729bfcb91adc', '2025-11-05', 'ramitha123@gmail.com');

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

--
-- Dumping data for table `mech_orders`
--

INSERT INTO `mech_orders` (`id`, `reference_number`, `product_id`, `quantity`, `purchase_date`, `item_total`, `total_price`, `discount`, `seller_income`, `commission`, `email`, `status`, `payment_status`, `paid_date`) VALUES
(3, 'MORD-672cf3ded15292.28134719', 11, 2, '2024-11-07', 700.00, 2200.00, 0.00, 679.00, 21.00, 'nishanthafernando@gmail.com', 'Pending', 'paid', '2024-11-08'),
(4, 'MORD-672cf3ded15292.28134719', 12, 2, '2024-11-07', 1500.00, 2200.00, 0.00, 1455.00, 45.00, 'nishanthafernando@gmail.com', 'Pending', 'paid', '2024-11-08');

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

--
-- Dumping data for table `mech_ratings`
--

INSERT INTO `mech_ratings` (`id`, `product_id`, `user_email`, `rating`, `feedback`, `rating_date`) VALUES
(1, 12, 'nishanthafernando@gmail.com', 4, 'mech', '2024-11-09 21:49:00'),
(2, 12, 'nishanthafernando@gmail.com', 4, 'mech', '2024-11-09 21:51:59');

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
(73, 'ORD-672cec08af9b34.61203515', 25, 2, '2024-11-07', 7200.00, 8439.00, 261.00, 6984.00, 216.00, 'ramithacampus@gmail.com', 'Pending', 'paid', '2024-11-08'),
(74, 'ORD-672cec08af9b34.61203515', 12, 2, '2024-11-07', 1500.00, 8439.00, 261.00, 1455.00, 45.00, 'ramithacampus@gmail.com', 'Pending', 'paid', '2024-11-08'),
(75, 'ORD-672f810ec2f233.62589168', 33, 1, '2024-11-09', 2435.00, 2361.95, 73.05, 2361.95, 73.05, 'ramithacampus@gmail.com', 'Pending', 'paid', '2024-11-11'),
(76, 'ORD-672f91492b7fd4.35552152', 12, 1, '2024-11-09', 750.00, 727.50, 22.50, 727.50, 22.50, 'ramitha123@gmail.com', 'Pending', 'paid', '2024-11-11'),
(77, 'ORD-6730b8ecaf52c0.87720267', 11, 1, '2024-11-10', 350.00, 1067.00, 33.00, 339.50, 10.50, 'ramithacampus@gmail.com', 'Pending', 'paid', '2024-11-11'),
(78, 'ORD-6730b8ecaf52c0.87720267', 12, 1, '2024-11-10', 750.00, 1067.00, 33.00, 727.50, 22.50, 'ramithacampus@gmail.com', 'Pending', 'paid', '2024-11-11'),
(79, 'ORD-6731af6f8ddd98.28552808', 12, 1, '2024-11-11', 750.00, 3089.45, 95.55, 727.50, 22.50, 'ramitha123@gmail.com', 'Pending', 'paid', '2024-11-11'),
(80, 'ORD-6731af6f8ddd98.28552808', 33, 1, '2024-11-11', 2435.00, 3089.45, 95.55, 2361.95, 73.05, 'ramitha123@gmail.com', 'Completed', 'paid', '2024-11-11');

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
(3, 17, NULL, 'engine oil', '../../uploads/pngwing.com.png', 48, 487.00),
(11, 17, NULL, 'tests', '../../uploads/pngwing.com.png', 9, 350.00),
(12, 39, NULL, 'tests', '../../uploads/pngwing.com.png', 19, 750.00),
(22, 45, 5, 'DURABLEND 5W30', '../../uploads/xpro-thumb-1698981892.png.pagespeed.ic.RkKs3KiiZz.png', 9, 3235.00),
(23, 45, 5, 'VALVOLINE SYNPOWER 5W30', '../../uploads/xpro-thumb-1692179944.png.pagespeed.ic.AhI2q4y2Cs.png', 3, 6500.00),
(24, 45, NULL, 'VALVOLINE SYNPOWER 0W20', '../../uploads/xpro-thumb-1692252593.png.pagespeed.ic.BEcmQ8fM-D.png', 6, 13000.00),
(25, 45, NULL, 'VALVOLINE PREMIUM PROTECTION 20W50', '../../uploads/xpro-thumb-1692183247.png.pagespeed.ic.Lw-fPBJAU1.png', 4, 3600.00),
(26, 45, NULL, 'VALVOLINE PREMIUM PROTECTION 10W30', '../../uploads/xpro-thumb-1692180391.png.pagespeed.ic.hEm32-Ly-A.png', 5, 4300.00),
(27, 46, NULL, 'Wagon R Oil Pump', '../../uploads/suzuki-wagon-r-genuine-oil-pump-22575353.jpg', 7, 15000.00),
(31, 45, 6, 'Wagon R Oil Pump', '../../uploads/suzuki-wagon-r-genuine-oil-pump-22575353.jpg', 43, 700.00),
(32, 47, NULL, 'saefaEWF', '../../uploads/dfhe8il-908ca101-83b9-4d22-8e17-4ccd76c40a79.png', 42, 44.00),
(33, 46, NULL, 'hi hi hi product', '../../uploads/dcccw75-0e37f4a6-6071-4fb0-ad04-66913ff3d13b.png', 7, 2435.00);

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
(9, 12, 'ramithacampus@gmail.com', 5, 'ramcampus', '2024-11-09 22:02:38'),
(12, 12, 'ramitha123@gmail.com', 4, 'ram123', '2024-11-09 22:20:34');

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
(17, 'ramithacampus@gmail.com', 'Sumanadisi Motors', 'sumanadisi@gmail.com', '0778121761', 'De krester Road, Colombo 4. (Inside ICBT Campus)', 'Bambalapitiya'),
(39, 'ramithacampus@gmail.com', 'Sumanadisi Motors', 'ramithacampus@gmail.com', '0778121762', '1/8A, Siriniwasa Mawatha, Kalutara North.', 'Kalutara'),
(42, 'nimal@gmail.com', 'bla bla bla', 'shop@gmail.com', '1190', 'Kandy', 'kalutara'),
(43, 'ramithacampus@gmail.com', 'Sumanadisi Motors', 'ramithacampus@gmail.com', '119', '1/8A, Siriniwasa Mawatha, Kalutara North.', 'Nugegoda'),
(45, 'freddy@gmail.com', 'Freddy Motors', 'freddymotorskatunayaka@gmail.com', '0123456780', 'Katunayaka', 'Katunayaka'),
(46, 'freddy@gmail.com', 'Freddy Motors', 'freddymotorscolombo@gmail.com', '0123456789', 'Colombo', 'Colombo'),
(47, 'ramitha123@gmail.com', 'shop name', 'abhithavishvajith@gmail.com', '0710592699', '484/1, Nanda Mawatha, Kurana, Katunayaka.', 'gedara branch');

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
('Samantha Fernando', '0710592699', 'abhithavishvajith@gmail.com', '$2y$10$u1iHK/0wYcxEi8hA6KEyxuHe9rN7He3gGKaZE/OUe0EM7jITwmPZq'),
('Freddy Fonseka', '0778121761', 'freddy@gmail.com', '$2y$10$xLFKPDTpdhw9Xavd3UFG3uk4i5FBgWyIB4QIbOyuS9bxbXTMRiExO'),
('Nimal Perera', '0778121761', 'nimal@gmail.com', '$2y$10$Qs.9Hrfi6jipAOSvGb7YouxGsVRP2E4TbLYzttdNCj.DZIRVv0NZ6'),
('Samantha Fernando', '0710592699', 'ramitha123@gmail.com', '$2y$10$9P3arkcuXhhTQdSmXt9b/.2OgTbeIjG2xdu0yBYI0NqY5NLmldGUC'),
('Kamal', '0778121761', 'ramitha2002@gmail.com', '$2y$10$ERDveiZ5x4zLOb5/wxxQ0umPX/gHp7dUlAUB0EiwmT/tj.StUF0vy'),
('Ramitha Heshan', '0778121761', 'ramithacampus1@gmail.com', '$2y$10$AkxiM8YkQHfJfXjejK.KyOknr2TfsFBqJFO8xlsM5sC7Dd/79rjG6'),
('Ramitha Heshan', '0778121762', 'ramithacampus@gmail.com', '$2y$10$aQdE4wOiTcAzlQ7vXIAEEONc8qJVjsdEejJZoWbXvR/PtjGEf2Qsi'),
('Sathin', '0778121761', 'sathin@gmail.com', '$2y$10$3GQYKvOwCtrPpUsNVVie6O6SXZibA98EiSPmDFCxRFRrDeyKxtYpe');

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
(11, 'XY5678', 'Honda Civic', 2021, 'Gasoline', '2.0L 4-cylinder', '215/55R16', 'nimalperera@gmail.com', 'Nimal Perera', 779713127),
(23, 'AB1234', 'test', 2024, 'test', 'test', 'test', 'tharusha@gmail.com', 'Tharusha Hirushan', 754819232),
(25, 'AB1234', 'Toyota Prius', 2019, 'Hybrid', '1.8L 4-cylinder', '215/55R16', 'ramithacampus@gmail.com', 'Ramitha Heshan', 778121761),
(27, 'XY5678', 'Honda Civic', 2012, 'Hybrid', '2.0L 4-cylinder', '195/65R15', 'ramithacampus@gmail.com', 'Ramitha Heshan', 778121761);

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
(75, 'ramithacampus@gmail.com', 25, 73, 'Toyota Prius', 2019, '778121761', '6.5982803, 79.953939', 'aul machang', '2024-11-07 11:48:37', 'Pending', 'Panadura'),
(83, 'ramithacampus@gmail.com', 25, NULL, 'Toyota Prius', 2019, '778121761', '6.5982831, 79.953941', 'test8', '2024-11-09 17:41:05', 'Pending', 'Kalutara');

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
(73, 'ramithacampus@gmail.com', 14, 73, '6.5982777, 79.9539403', 'Engine Overheating', '2024-11-01 11:20:08', 'Done', 'Panadura'),
(76, 'ramithacampus@gmail.com', 25, 77, '6.5982777, 79.9539452', 'test1', '2024-11-09 17:50:51', 'Done', 'Kalutara'),
(77, 'ramithacampus@gmail.com', 25, 78, '6.5982777, 79.9539452', 'test2', '2024-11-09 17:51:50', 'Done', 'Kalutara'),
(78, 'ramithacampus@gmail.com', 25, 79, '6.5982777, 79.9539452', 'test3', '2024-11-09 17:52:15', 'Done', 'Kalutara'),
(79, 'ramithacampus@gmail.com', 25, 80, '6.5982831, 79.953941', 'test4', '2024-11-09 17:52:45', 'Done', 'Kalutara'),
(80, 'ramithacampus@gmail.com', 25, 81, '6.5982831, 79.953941', 'test5', '2024-11-09 17:53:14', 'Done', 'Kalutara'),
(81, 'ramithacampus@gmail.com', 25, 81, '6.5982831, 79.953941', 'test6', '2024-11-09 18:10:41', 'Done', 'Kalutara'),
(82, 'ramithacampus@gmail.com', 25, 77, '6.5982831, 79.953941', 'test7', '2024-11-11 07:48:09', 'Done', 'Kalutara');

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
('Samantha Fernando', 'abhithavishvajith@gmail.com', '$2y$10$qHPhkxGa2w9wE10kjTa4..Y9AnDh.svHqYYsCtNf0m7JUDFdciate', 710592699, NULL, 'katunayaka'),
('Nimal Perera', 'nimalperera@gmail.com', '$2y$10$IKjpDOvPEgOr15pdVT8ARe9/Dq2lkbGs6aJa599AADle.CHA29RDy', 779713127, NULL, 'Kandy'),
('Samantha Fernando', 'ramitha123@gmail.com', '$2y$10$hWJVcl2ayc7P7AsRGEBDQOSIY/.AkmkyRpkAlxxmVaBo8al6fV5bG', 710592699, NULL, 'katunayaka'),
('Ramitha Heshan', 'ramithacampus@gmail.com', '$2y$10$2nJeMTkoP.bKhFF89zen1uOAmn3lyE5U.k05DQ31GU80Lb/aPG8XS', 778121761, 'test', 'Kalutara'),
('test', 'test@gmail.com', '$2y$10$a3Yk7/hlhjY.zCpMrWdIbOL6acBKo4o9VcKWe9VVIOSbtbqIW/ho.', 778121761, NULL, 'Kalutara'),
('Tharusha Hirushan', 'tharusha@gmail.com', '$2y$10$6A0pZNk3UrhQuGf2veDSCelADPf1OmOgMOiiEeJNUpQgAmGFas52u', 754819232, NULL, 'Waligama');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loyalty_card`
--
ALTER TABLE `loyalty_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mechanic`
--
ALTER TABLE `mechanic`
  MODIFY `userID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `mech_cart`
--
ALTER TABLE `mech_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `vehicleissues`
--
ALTER TABLE `vehicleissues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `vehicleissuesdone`
--
ALTER TABLE `vehicleissuesdone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

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
