-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июн 07 2020 г., 17:59
-- Версия сервера: 5.7.23
-- Версия PHP: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dorway_keyword`
--

-- --------------------------------------------------------

--
-- Структура таблицы `temp`
--

CREATE TABLE `temp` (
  `id` int(11) UNSIGNED NOT NULL,
  `flag` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `temp_address`
--

CREATE TABLE `temp_address` (
  `temp_id` int(255) UNSIGNED NOT NULL DEFAULT '0',
  `address` varchar(512) NOT NULL DEFAULT '',
  `address_city` varchar(256) NOT NULL DEFAULT '',
  `address_borough` varchar(256) NOT NULL DEFAULT '',
  `address_street` varchar(256) NOT NULL DEFAULT '',
  `postal_code` varchar(20) NOT NULL DEFAULT '',
  `latitude` varchar(20) NOT NULL DEFAULT '',
  `longitude` varchar(20) NOT NULL DEFAULT '',
  `time_zone` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `temp_internet`
--

CREATE TABLE `temp_internet` (
  `temp_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `site` varchar(256) NOT NULL DEFAULT '',
  `email` varchar(256) NOT NULL DEFAULT '',
  `email2` varchar(256) NOT NULL DEFAULT '',
  `twitter` varchar(256) NOT NULL DEFAULT '',
  `linkedin` varchar(256) NOT NULL DEFAULT '',
  `facebook` varchar(256) NOT NULL DEFAULT '',
  `instagram` varchar(256) NOT NULL DEFAULT '',
  `google_plus` varchar(256) NOT NULL DEFAULT '',
  `skype` varchar(255) NOT NULL DEFAULT '',
  `telegram` varchar(256) NOT NULL DEFAULT '',
  `site_generator` varchar(256) NOT NULL DEFAULT '',
  `site_title` varchar(256) NOT NULL DEFAULT '',
  `site_description` varchar(512) NOT NULL DEFAULT '',
  `site_keywords` varchar(512) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `temp_main`
--

CREATE TABLE `temp_main` (
  `temp_id` int(255) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(512) NOT NULL DEFAULT '',
  `type` varchar(512) NOT NULL DEFAULT '',
  `types` varchar(1024) NOT NULL DEFAULT '',
  `phone` varchar(256) NOT NULL DEFAULT '',
  `verified` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `owner_id` varchar(100) NOT NULL DEFAULT '',
  `owner_link` varchar(512) NOT NULL DEFAULT '',
  `google_id` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `temp_query`
--

CREATE TABLE `temp_query` (
  `temp_id` int(255) UNSIGNED NOT NULL DEFAULT '0',
  `query` varchar(255) NOT NULL DEFAULT '',
  `query_p1` varchar(256) NOT NULL DEFAULT '',
  `query_p2` varchar(256) NOT NULL DEFAULT '',
  `query_p3` varchar(256) NOT NULL DEFAULT '',
  `query_p4` varchar(256) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `temp_reviews_photos_hours`
--

CREATE TABLE `temp_reviews_photos_hours` (
  `temp_id` int(255) UNSIGNED NOT NULL DEFAULT '0',
  `rating` decimal(2,1) UNSIGNED NOT NULL DEFAULT '0.0',
  `reviews` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `reviews_link` varchar(512) NOT NULL DEFAULT '',
  `reviews_per_score` varchar(100) NOT NULL DEFAULT '',
  `reviews_id` varchar(50) NOT NULL DEFAULT '',
  `photos_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `photo` varchar(512) NOT NULL DEFAULT '',
  `working_hours` varchar(512) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flag` (`flag`);

--
-- Индексы таблицы `temp_address`
--
ALTER TABLE `temp_address`
  ADD PRIMARY KEY (`temp_id`),
  ADD KEY `temp_id` (`temp_id`);

--
-- Индексы таблицы `temp_internet`
--
ALTER TABLE `temp_internet`
  ADD PRIMARY KEY (`temp_id`);

--
-- Индексы таблицы `temp_main`
--
ALTER TABLE `temp_main`
  ADD PRIMARY KEY (`temp_id`),
  ADD UNIQUE KEY `google_id` (`google_id`,`temp_id`);

--
-- Индексы таблицы `temp_query`
--
ALTER TABLE `temp_query`
  ADD PRIMARY KEY (`temp_id`);

--
-- Индексы таблицы `temp_reviews_photos_hours`
--
ALTER TABLE `temp_reviews_photos_hours`
  ADD PRIMARY KEY (`temp_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `temp`
--
ALTER TABLE `temp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `temp_address`
--
ALTER TABLE `temp_address`
  ADD CONSTRAINT `temp_address_ibfk_1` FOREIGN KEY (`temp_id`) REFERENCES `temp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `temp_internet`
--
ALTER TABLE `temp_internet`
  ADD CONSTRAINT `temp_internet_ibfk_1` FOREIGN KEY (`temp_id`) REFERENCES `temp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `temp_main`
--
ALTER TABLE `temp_main`
  ADD CONSTRAINT `temp_main_ibfk_1` FOREIGN KEY (`temp_id`) REFERENCES `temp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `temp_query`
--
ALTER TABLE `temp_query`
  ADD CONSTRAINT `temp_query_ibfk_1` FOREIGN KEY (`temp_id`) REFERENCES `temp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `temp_reviews_photos_hours`
--
ALTER TABLE `temp_reviews_photos_hours`
  ADD CONSTRAINT `temp_reviews_photos_hours_ibfk_1` FOREIGN KEY (`temp_id`) REFERENCES `temp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
