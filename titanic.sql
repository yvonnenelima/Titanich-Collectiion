-- phpMyAdmin SQL Dump - Corrected Version
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Host: 127.0.0.1
-- Generation Time: August 28, 2025 at 10:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Charset Settings
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `titanic_collection`

-- ------------------------------
-- Table: admin (DROP IF EXISTS to avoid conflicts)
-- ------------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `UserName` VARCHAR(100) DEFAULT NULL,
  `Password` VARCHAR(100) DEFAULT NULL,
  `updationDate` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Insert data into admin
INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', 'revilo23', NULL);

-- ------------------------------
-- Table: users (New table for login system)
-- ------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample users with hashed passwords
INSERT INTO `users` (`full_name`, `email`, `phone`, `password`, `address`, `is_active`) VALUES
('Demo User', 'demo@titanichstore.com', '+254123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Demo Street, Nairobi, Kenya', 1),
('John Doe', 'john@example.com', '+254987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Main Street, Mombasa, Kenya', 1),
('Jane Smith', 'jane@example.com', '+254111222333', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Oak Avenue, Kisumu, Kenya', 1),
('Admin User', 'admin@titanichstore.com', '+254444555666', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '321 Admin Plaza, Nairobi, Kenya', 1);

-- ------------------------------
-- Table: tblusers (Existing table - keeping for compatibility)
-- ------------------------------
DROP TABLE IF EXISTS `tblusers`;
CREATE TABLE `tblusers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `FullName` VARCHAR(100) DEFAULT NULL,
  `MobileNumber` CHAR(10) DEFAULT NULL,
  `EmailId` VARCHAR(70) DEFAULT NULL,
  `Password` VARCHAR(100) DEFAULT NULL,
  `isAdmin` TINYINT(1) DEFAULT 0,
  `is_verified` TINYINT(1) DEFAULT 0,
  `email_token` VARCHAR(255) DEFAULT NULL,
  `reset_token` VARCHAR(255) DEFAULT NULL,
  `reset_expires` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Insert data into tblusers
INSERT INTO `tblusers` (`FullName`, `EmailId`, `Password`, `isAdmin`, `is_verified`, `email_token`, `reset_token`, `reset_expires`) VALUES
('Admin User', 'admin@titanic.com', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 1, 0, NULL, NULL, NULL),
('Jane Doe', 'jane@example.com', '$2y$10$yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy', 0, 0, NULL, NULL, NULL),
('John Smith', 'john@example.com', '$2y$10$zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', 0, 0, NULL, NULL, NULL);

-- ------------------------------
-- Table: tblshoes (NEW - Main shoes management table for admin)
-- ------------------------------
DROP TABLE IF EXISTS `tblshoes`;
CREATE TABLE `tblshoes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ShoeName` VARCHAR(255) NOT NULL,
  `ShoeDescription` TEXT DEFAULT NULL,
  `Brand` VARCHAR(100) DEFAULT NULL,
  `Category` ENUM('Sneakers', 'Sports', 'Casual', 'Formal', 'Sandals', 'Boots') DEFAULT 'Sneakers',
  `Size` VARCHAR(50) DEFAULT NULL,
  `Color` VARCHAR(50) DEFAULT NULL,
  `Gender` ENUM('Men', 'Women', 'Unisex', 'Kids') DEFAULT 'Unisex',
  `Material` VARCHAR(100) DEFAULT NULL,
  `OriginalPrice` DECIMAL(10,2) DEFAULT NULL,
  `SalePrice` DECIMAL(10,2) NOT NULL,
  `Stock` INT(11) DEFAULT 0,
  `MainImage` VARCHAR(255) DEFAULT NULL,
  `AdditionalImages` TEXT DEFAULT NULL,
  `IsActive` TINYINT(1) DEFAULT 1,
  `IsFeatured` TINYINT(1) DEFAULT 0,
  `Tags` VARCHAR(500) DEFAULT NULL,
  `CreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CreatedBy` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Brand` (`Brand`),
  KEY `Category` (`Category`),
  KEY `IsActive` (`IsActive`),
  KEY `IsFeatured` (`IsFeatured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample shoes data
INSERT INTO `tblshoes` (`ShoeName`, `ShoeDescription`, `Brand`, `Category`, `Size`, `Color`, `Gender`, `Material`, `OriginalPrice`, `SalePrice`, `Stock`, `MainImage`, `IsActive`, `IsFeatured`, `Tags`) VALUES
('Air Max 270', 'Comfortable running shoes with air cushioning technology', 'Nike', 'Sports', '8-12', 'Black/White', 'Unisex', 'Synthetic/Mesh', 15000.00, 12000.00, 25, 'airmax270.jpg', 1, 1, 'running,comfort,nike,sports'),
('Stan Smith Classic', 'Iconic white leather sneakers', 'Adidas', 'Casual', '7-11', 'White/Green', 'Unisex', 'Leather', 8000.00, 6500.00, 30, 'stansmith.jpg', 1, 1, 'classic,leather,adidas,casual'),
('Chuck Taylor All Star', 'Classic canvas high-top sneakers', 'Converse', 'Casual', '6-13', 'Red', 'Unisex', 'Canvas', 5000.00, 4200.00, 20, 'converse_red.jpg', 1, 0, 'canvas,converse,classic,high-top'),
('Puma Suede Classic', 'Retro style suede sneakers', 'Puma', 'Casual', '7-12', 'Blue', 'Unisex', 'Suede', 7000.00, 5500.00, 15, 'puma_suede.jpg', 1, 0, 'suede,puma,retro,casual'),
('New Balance 574', 'Comfortable everyday sneakers', 'New Balance', 'Sneakers', '8-11', 'Gray', 'Unisex', 'Synthetic', 9000.00, 7500.00, 18, 'nb574.jpg', 1, 1, 'comfort,new balance,everyday'),
('Birkenstock Arizona', 'Comfortable cork footbed sandals', 'Birkenstock', 'Sandals', '6-12', 'Brown', 'Unisex', 'Leather/Cork', 12000.00, 10000.00, 12, 'birkenstock.jpg', 1, 0, 'comfort,sandals,cork,birkenstock');

-- ------------------------------
-- Table: products (Updated to work with tblshoes)
-- ------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `product_type` VARCHAR(255) DEFAULT 'Uncategorized',
  `image` VARCHAR(255),
  `old_price` DECIMAL(10, 2),
  `new_price` DECIMAL(10, 2) NOT NULL,
  `stock` INT DEFAULT 0,
  `date_added` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `shoe_id` INT(11) DEFAULT NULL,
  KEY `shoe_id` (`shoe_id`),
  CONSTRAINT `products_ibfk_shoe` FOREIGN KEY (`shoe_id`) REFERENCES `tblshoes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert products linked to tblshoes
INSERT INTO `products` (`name`, `description`, `product_type`, `image`, `old_price`, `new_price`, `stock`, `shoe_id`) VALUES
('Air Max 270', 'Comfortable running shoes with air cushioning technology', 'Nike', 'airmax270.jpg', 15000.00, 12000.00, 25, 1),
('Stan Smith Classic', 'Iconic white leather sneakers', 'Adidas', 'stansmith.jpg', 8000.00, 6500.00, 30, 2),
('Chuck Taylor All Star', 'Classic canvas high-top sneakers', 'Converse', 'converse_red.jpg', 5000.00, 4200.00, 20, 3),
('Puma Suede Classic', 'Retro style suede sneakers', 'Puma', 'puma_suede.jpg', 7000.00, 5500.00, 15, 4),
('New Balance 574', 'Comfortable everyday sneakers', 'New Balance', 'nb574.jpg', 9000.00, 7500.00, 18, 5),
('Birkenstock Arizona', 'Comfortable cork footbed sandals', 'Sandals', 'birkenstock.jpg', 12000.00, 10000.00, 12, 6);

-- ------------------------------
-- Table: wishlist (Updated to reference users table)
-- ------------------------------
DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `product_id` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------
-- Table: cart (Updated to reference users table)
-- ------------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `product_id` INT(11) DEFAULT NULL,
  `quantity` INT(11) DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------
-- Table: orders (Updated to reference users table)
-- ------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `total` DECIMAL(10,2) DEFAULT NULL,
  `status` ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
  `shipping_address` TEXT DEFAULT NULL,
  `phone_number` VARCHAR(20) DEFAULT NULL,
  `order_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------
-- Table: order_items
-- ------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) DEFAULT NULL,
  `product_id` INT(11) DEFAULT NULL,
  `quantity` INT(11) DEFAULT NULL,
  `price` DECIMAL(10,2) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------
-- Table: shoe_categories (For better category management)
-- ------------------------------
DROP TABLE IF EXISTS `shoe_categories`;
CREATE TABLE `shoe_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` VARCHAR(100) NOT NULL,
  `CategoryDescription` TEXT DEFAULT NULL,
  `IsActive` TINYINT(1) DEFAULT 1,
  `CreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `CategoryName` (`CategoryName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default categories
INSERT INTO `shoe_categories` (`CategoryName`, `CategoryDescription`) VALUES
('Sneakers', 'Casual and street-style sneakers'),
('Sports', 'Athletic and performance shoes'),
('Casual', 'Everyday comfortable shoes'),
('Formal', 'Dress shoes and formal footwear'),
('Sandals', 'Open-toe casual footwear'),
('Boots', 'High-ankle protective footwear');

-- ------------------------------
-- Table: shoe_brands (For better brand management)
-- ------------------------------
DROP TABLE IF EXISTS `shoe_brands`;
CREATE TABLE `shoe_brands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `BrandName` VARCHAR(100) NOT NULL,
  `BrandDescription` TEXT DEFAULT NULL,
  `BrandLogo` VARCHAR(255) DEFAULT NULL,
  `IsActive` TINYINT(1) DEFAULT 1,
  `CreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `BrandName` (`BrandName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default brands
INSERT INTO `shoe_brands` (`BrandName`, `BrandDescription`) VALUES
('Nike', 'Leading athletic footwear and apparel brand'),
('Adidas', 'German multinational corporation that designs and manufactures shoes'),
('Converse', 'American shoe company known for Chuck Taylor All-Stars'),
('Puma', 'German multinational corporation that designs athletic shoes'),
('New Balance', 'American footwear manufacturer'),
('Birkenstock', 'German shoe manufacturer known for comfortable sandals'),
('Vans', 'American manufacturer of skateboarding shoes'),
('Reebok', 'American-inspired global brand with deep fitness roots');

COMMIT;

-- Restore previous charset settings
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;