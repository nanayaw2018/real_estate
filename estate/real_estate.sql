-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2018 at 05:16 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `real_estate`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `brand` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `items` text NOT NULL,
  `expire_date` datetime NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `items`, `expire_date`, `paid`) VALUES
(5, '[{"id":"2","size":"small","quantity":"3"},{"id":"3","size":"medium","quantity":"4"},{"id":"4","size":"medium","quantity":"25"}]', '2018-02-04 18:05:40', 0),
(6, '[{"id":"3","size":"small","quantity":5}]', '2018-03-12 19:25:36', 0),
(7, '[{"id":"6","size":"small","quantity":"12"}]', '2018-04-09 21:39:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `list_price` decimal(10,2) NOT NULL,
  `brand` int(11) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `featured` int(11) NOT NULL DEFAULT '0',
  `sizes` text NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `theme_id` tinyint(4) NOT NULL,
  `theme_name` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`theme_id`, `theme_name`, `status`) VALUES
(1, 'superhero', 0),
(2, 'solar', 1),
(3, 'slate', 0),
(4, 'sandstone', 0),
(5, 'darkly', 0),
(6, 'cyborg', 0),
(7, 'readable', 0),
(8, 'lumen', 0),
(9, 'paper', 0),
(10, 'default', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(175) NOT NULL,
  `password` varchar(255) NOT NULL,
  `join_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL,
  `permissions` varchar(255) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `join_date`, `last_login`, `permissions`, `deleted`) VALUES
(1, 'Blankson Richmond', 'blanksonrichmondm@yahoo.com', '$2y$10$veTH0tMt7iTvBRSm9ui1QOIzvU0dJ84SBsYzJ6zTh.h2wsGHh0NmK', '2017-12-06 07:02:51', '2018-03-23 17:06:00', 'admin,editor', 0),
(2, 'Belinda Afari', 'belindafari@gmail.com', '$2y$10$ts90l1rerQh.IX0dVoXavupmcYecfkHYBxhS.kDiwHzvV8pZ01Y0.', '2017-12-07 07:27:02', '2017-12-19 00:02:00', 'editor', 1),
(3, 'Emmanuel Pamfo', 'emmanuelpamfo@gmail.com', '$2y$10$kKlvJt2EoOZC28j943Qf.en9IzkHQy24jiPmZhZpvnDwVaoZBZcTG', '2017-12-07 08:24:06', '2017-12-07 17:43:00', 'admin,editor', 1),
(4, 'Nakie Agebettor-Darko', 'nakieagbettor-darko@gmail.com', '$2y$10$vOHV5zJbJRGVpzWY61DqWuO0ElUxQnyaCqawsqxsV7L2mnfGZwmIq', '2017-12-07 08:31:35', '2017-12-07 18:00:00', 'admin,editor', 1),
(5, 'Kojo Efo-Mawugbe', 'kojomawugbe@gmail.com', '$2y$10$e7WLMwfIAJwNXuw9lkzI3Ot7NlLH/BxKiMRkd/YN/TopSZgI2dPN6', '2017-12-07 09:23:14', '2017-12-09 21:22:00', 'editor', 1),
(6, 'Steves Adjei', 'steveadjei-adjetey@gmail.com', '$2y$10$MsugMNGDCZkvNf0CNSmKWeuW4zX/jR5cy3neEsZh6DNpwAAhV4W7y', '2017-12-07 09:27:27', '2017-12-07 02:56:00', 'admin,editor', 1),
(7, 'Mfononbong Ayankunde', 'mfononbong@gmail.com', '$2y$10$LfwDk8OuoongwJ8i2UZ7CuBKoKbP65AzOWV86nj8Wcto3YchoyvA6', '2017-12-07 17:06:10', '0000-00-00 00:00:00', 'editor', 1),
(8, 'Anthony Obi', 'anthonyobi@gmail.com', '$2y$10$ZpN7Xy6WRKyWl2FESKOm6OqXs0nUFEj.0LWC1NG6g0siC8d/rHeHu', '2017-12-07 17:15:48', '0000-00-00 00:00:00', 'admin,editor', 1),
(9, 'Frank Braye', 'frankbraye@gmail.com', '$2y$10$6NWvT6083DemP162kMME4uAvmFHRWT97WcVALc2i.2XIiZqBQSxym', '2017-12-07 17:22:09', '0000-00-00 00:00:00', 'admin,editor', 1),
(10, 'Israel Chukukwelu', 'chukes@gmail.com', '$2y$10$UnkG5.HkKFjSpK5wXJMsL.pPwzetT2obLFFb/EW7zZEQILzeffR..', '2017-12-07 17:26:48', '0000-00-00 00:00:00', 'admin,editor', 1),
(11, 'Abigail Brazy', 'abigailbrazy@gmail.com', '$2y$10$4S2j.6NCEFyaYEVBQxRl8OUGdW1BaRVvU4AcRjnf0q.UXAyQXGQ1.', '2017-12-07 17:28:11', '0000-00-00 00:00:00', 'editor', 1),
(12, 'Isaac Yeboah', 'isaacyeboah@gmail.com', '$2y$10$p8jCZltNdzsWLKNj.nseieZqXkaY0IiWUozdVkvoB.AZF18pzfCVm', '2017-12-07 17:30:57', '0000-00-00 00:00:00', 'admin,editor', 1),
(14, 'Nicholas Darko', 'nichodarko@gmail.com', '$2y$10$UtkydkFAH7AB8hYwiG7vYuJvxtH.SZjWn9/0azgGQ71pVCbQFvc4.', '2017-12-07 17:39:14', '0000-00-00 00:00:00', 'editor', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`theme_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `theme_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
