-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2018 at 12:45 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE IF NOT EXISTS `brand` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `brand_name` varchar(20) NOT NULL,
  `brand_status` enum('active','inactive') NOT NULL,
  PRIMARY KEY (`brand_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `cat_id`, `brand_name`, `brand_status`) VALUES
(1, 2, 'jio', 'active'),
(2, 3, 'intex', 'active'),
(3, 2, 'HP', 'active'),
(4, 1, 'myntra', 'active'),
(5, 3, 'syska', 'active'),
(6, 1, 'jbong', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(20) NOT NULL,
  `cat_status` enum('active','inactive') NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `cat_status`) VALUES
(1, 'man fashion', 'active'),
(2, 'computers', 'active'),
(3, 'electronics', 'active'),
(4, 'blubs', 'inactive'),
(5, 'programming books', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_order`
--

CREATE TABLE IF NOT EXISTS `inventory_order` (
  `iny_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `iny_order_total` double(10,2) NOT NULL,
  `iny_order_date` date NOT NULL,
  `iny_order_name` varchar(100) NOT NULL,
  `iny_order_address` text NOT NULL,
  `payment_status` enum('cash','credit') NOT NULL,
  `iny_order_status` enum('active','inactive') NOT NULL,
  `iny_order_create_date` date NOT NULL,
  PRIMARY KEY (`iny_order_id`),
  KEY `usr_id` (`usr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `inventory_order`
--

INSERT INTO `inventory_order` (`iny_order_id`, `usr_id`, `iny_order_total`, `iny_order_date`, `iny_order_name`, `iny_order_address`, `payment_status`, `iny_order_status`, `iny_order_create_date`) VALUES
(1, 1, 43390.00, '2018-06-14', 'sumit', 'flipkaert loot', 'cash', 'active', '2018-06-22'),
(2, 1, 2160.00, '2018-06-22', 'shubham pal', 'buy for fun', 'cash', 'active', '2018-06-23'),
(3, 2, 22000.00, '2018-06-20', 'vedansh', 'gift from my side', 'credit', 'active', '2018-06-23'),
(4, 3, 22525.00, '2018-06-25', 'amit', 'order by amit', 'cash', 'active', '2018-06-24'),
(5, 5, 21660.00, '2018-06-20', 'dhiman', '#delhi , 06', 'credit', 'active', '2018-06-26');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_order_product`
--

CREATE TABLE IF NOT EXISTS `inventory_order_product` (
  `iny_order_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `iny_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `tax` double(10,2) NOT NULL,
  PRIMARY KEY (`iny_order_product_id`),
  KEY `product_id` (`product_id`),
  KEY `iny_order_id` (`iny_order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `inventory_order_product`
--

INSERT INTO `inventory_order_product` (`iny_order_product_id`, `iny_order_id`, `product_id`, `quantity`, `price`, `tax`) VALUES
(1, 1, 5, 1, 20000.00, 5.00),
(2, 1, 2, 1, 20000.00, 10.00),
(3, 1, 1, 1, 300.00, 30.00),
(9, 2, 1, 2, 300.00, 30.00),
(10, 2, 4, 2, 500.00, 5.00),
(11, 2, 3, 1, 300.00, 10.00),
(13, 3, 2, 1, 20000.00, 10.00),
(14, 4, 2, 1, 20000.00, 10.00),
(15, 4, 4, 1, 500.00, 5.00),
(16, 5, 5, 1, 20000.00, 5.00),
(17, 5, 3, 2, 300.00, 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `prd_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `prd_name` varchar(100) NOT NULL,
  `prd_description` text NOT NULL,
  `prd_quantity` int(11) NOT NULL,
  `prd_unit` varchar(100) NOT NULL,
  `prd_base_price` double(10,2) NOT NULL,
  `prd_tax` decimal(4,2) NOT NULL,
  `prd_minimum_ord` double(10,2) NOT NULL,
  `prd_enter_by` int(11) NOT NULL,
  `prd_status` enum('active','inactive') NOT NULL,
  `prd_date` date NOT NULL,
  PRIMARY KEY (`prd_id`),
  KEY `cat_id` (`cat_id`),
  KEY `brand_id` (`brand_id`),
  KEY `prd_enter_by` (`prd_enter_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prd_id`, `cat_id`, `brand_id`, `prd_name`, `prd_description`, `prd_quantity`, `prd_unit`, `prd_base_price`, `prd_tax`, `prd_minimum_ord`, `prd_enter_by`, `prd_status`, `prd_date`) VALUES
(1, 2, 2, 'keyboard', 'multimedia keyboard', 10, 'nos', 300.00, '30.00', 1.00, 2, 'active', '2018-06-06'),
(2, 2, 3, '15 inches laptop', '15 inches laptop', 500, 'nos', 20000.00, '10.00', 20000.00, 1, 'active', '2018-06-13'),
(3, 1, 4, 'printed T-Shirt', 'printed T-Shirt with logos and desigh', 100, 'nos', 300.00, '10.00', 300.00, 1, 'active', '2018-06-16'),
(4, 2, 3, 'pendrive 16gb', '16gb pendrive ', 500, 'nos', 500.00, '5.00', 500.00, 1, 'active', '2018-06-16'),
(5, 3, 5, '15 inches smart TV', 'Smart TV', 500, 'nos', 20000.00, '5.00', 20000.00, 1, 'active', '2018-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE IF NOT EXISTS `user_details` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_email` varchar(100) NOT NULL,
  `usr_password` varchar(100) NOT NULL,
  `usr_name` varchar(100) NOT NULL,
  `usr_type` enum('master','user') NOT NULL,
  `usr_status` enum('active','inactive') NOT NULL,
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`usr_id`, `usr_email`, `usr_password`, `usr_name`, `usr_type`, `usr_status`) VALUES
(1, 'ashish@gmail.com', 'ashish', 'Ashish', 'master', 'active'),
(2, 'sumit@gmail.com', 'sumit', 'sumit dhiman', 'user', 'active'),
(3, 'amit@gmail.com', 'amit', 'amit kumar', 'user', 'active'),
(4, 'ashu@gmail.com', 'ashu', 'Ashu', 'user', 'inactive'),
(5, 'dhiman@gmail.com', 'dhiman', 'Dhiman', 'user', 'active'),
(6, 'shivani@gmail.com', 'shivani', 'shivani', 'user', 'inactive'),
(8, 'dhyan@gmail.com', 'dhyan', 'dhyan', 'user', 'active'),
(9, 'bheem@gmail.com', 'bheem', 'bheem', 'user', 'active'),
(14, 'vishal@gmail.com', 'vishal', 'vishal', 'user', 'active'),
(15, 'varsha@gmail.com', 'varsha', 'varsha', 'user', 'active');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brand`
--
ALTER TABLE `brand`
  ADD CONSTRAINT `brand_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`);

--
-- Constraints for table `inventory_order`
--
ALTER TABLE `inventory_order`
  ADD CONSTRAINT `inventory_order_ibfk_1` FOREIGN KEY (`usr_id`) REFERENCES `user_details` (`usr_id`);

--
-- Constraints for table `inventory_order_product`
--
ALTER TABLE `inventory_order_product`
  ADD CONSTRAINT `inventory_order_product_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`prd_id`),
  ADD CONSTRAINT `inventory_order_product_ibfk_2` FOREIGN KEY (`iny_order_id`) REFERENCES `inventory_order` (`iny_order_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`),
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`prd_enter_by`) REFERENCES `user_details` (`usr_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
