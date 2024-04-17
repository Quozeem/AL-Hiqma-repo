-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2024 at 10:59 PM
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
-- Database: `books`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `authors` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `number_of_pages` int(11) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `release_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `isbn`, `authors`, `country`, `number_of_pages`, `publisher`, `release_date`, `created_at`, `updated_at`) VALUES
(1, 'Ademola', '9780743273565', 'F. Scott Fitzgerald', 'United States', 180, 'Ademola', '1925-04-10', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(2, 'Quozeem', '9780060935467', 'Harper Lee', 'United States', 336, 'Quozeem', '1960-07-11', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(3, '1984', '9780451524935', 'George Orwell', 'United Kingdom', 328, 'Signet Classic', '1949-06-08', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(4, 'Pride and Prejudice', '9780486284736', 'Jane Austen', 'United Kingdom', 352, 'Dover Publications', '1813-01-28', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(5, 'The Catcher in the Rye', '9780316769488', 'J.D. Salinger', 'United States', 224, 'Little, Brown and Company', '1951-07-16', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(6, 'The Lord of the Rings', '9780261102385', 'J.R.R. Tolkien', 'United Kingdom', 1178, 'HarperCollins', '1954-07-29', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(7, 'Harry Potter and the Sorcerer\'s Stone', '9780590353403', 'J.K. Rowling', 'United Kingdom', 309, 'Scholastic', '1997-06-26', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(8, 'Moby-Dick', '9781853260087', 'Herman Melville', 'United States', 720, 'Wordsworth Editions', '1851-10-18', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(9, 'The Hobbit', '9780345339683', 'J.R.R. Tolkien', 'United Kingdom', 322, 'Ballantine Books', '1937-09-21', '2024-04-17 20:53:16', '2024-04-17 20:53:16'),
(10, 'Alice\'s Adventures in Wonderland', '9781503275921', 'Lewis Carroll', 'United Kingdom', 92, 'CreateSpace Independent Publishing Platform', '1865-11-26', '2024-04-17 20:53:16', '2024-04-17 20:53:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
