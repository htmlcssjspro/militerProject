-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 04 2021 г., 09:19
-- Версия сервера: 5.7.27-30
-- Версия PHP: 7.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `u1036142_militer`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin`
--

CREATE TABLE `admin` (
  `id` int(1) UNSIGNED NOT NULL,
  `admin_uuid` varchar(36) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(32) NOT NULL,
  `admin_status` varchar(16) NOT NULL,
  `status` varchar(16) NOT NULL,
  `last_visit` date NOT NULL,
  `register_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin`
--

INSERT INTO `admin` (`id`, `admin_uuid`, `email`, `password`, `name`, `admin_status`, `status`, `last_visit`, `register_date`) VALUES
(1, '4fc4042f-4dfc-4f5c-bfb8-5bbf215d0bfa', 'militer@htmlcssjs.pro', '$2y$10$AzXPf7Frp0y/7r.JGQDJ6eEnPxrQJNL4OQQM4K4j1UgJMPxmc7jaO', 'Militer', 'superadmin', 'active', '2021-06-04', '0000-00-00');

-- --------------------------------------------------------

--
-- Структура таблицы `admin_layouts`
--

CREATE TABLE `admin_layouts` (
  `id` int(1) UNSIGNED NOT NULL,
  `layout` varchar(32) NOT NULL,
  `head` varchar(32) NOT NULL,
  `header` varchar(32) NOT NULL,
  `footer` varchar(32) NOT NULL,
  `aside` varchar(32) NOT NULL,
  `css` varchar(32) NOT NULL,
  `js` varchar(32) NOT NULL,
  `current` tinyint(1) NOT NULL,
  `default` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin_layouts`
--

INSERT INTO `admin_layouts` (`id`, `layout`, `head`, `header`, `footer`, `aside`, `css`, `js`, `current`, `default`) VALUES
(1, 'adminLayout', 'head', 'header', 'footer', 'aside', 'admin', 'admin', 1, 1),
(2, 'simpleLayout', 'head', '', '', '', 'admin', 'admin', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `admin_popups`
--

CREATE TABLE `admin_popups` (
  `id` int(1) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `file` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin_popups`
--

INSERT INTO `admin_popups` (`id`, `name`, `file`) VALUES
(1, 'accessRestore', 'accessRestore'),
(2, 'slider', 'militerslider');

-- --------------------------------------------------------

--
-- Структура таблицы `admin_sitemap`
--

CREATE TABLE `admin_sitemap` (
  `id` int(1) UNSIGNED NOT NULL,
  `page_uri` varchar(255) NOT NULL,
  `layout` varchar(32) NOT NULL,
  `main_content` varchar(32) NOT NULL,
  `page_css` varchar(32) NOT NULL,
  `page_js` varchar(32) NOT NULL,
  `label` varchar(32) NOT NULL,
  `title` varchar(81) NOT NULL,
  `description` varchar(300) NOT NULL,
  `h1` varchar(255) NOT NULL,
  `header_nav` tinyint(1) NOT NULL DEFAULT '0',
  `header_nav_order` int(1) NOT NULL,
  `footer_nav` tinyint(1) NOT NULL DEFAULT '0',
  `footer_nav_order` int(1) NOT NULL,
  `aside_nav` tinyint(1) NOT NULL DEFAULT '0',
  `aside_nav_order` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin_sitemap`
--

INSERT INTO `admin_sitemap` (`id`, `page_uri`, `layout`, `main_content`, `page_css`, `page_js`, `label`, `title`, `description`, `h1`, `header_nav`, `header_nav_order`, `footer_nav`, `footer_nav_order`, `aside_nav`, `aside_nav_order`) VALUES
(400, 'admin', 'adminLayout', 'homePage', '', '', 'Панель администратора', 'admin', 'admin', '', 0, 0, 0, 0, 0, 0),
(420, 'admin/pages', 'adminLayout', 'pages', '', '', 'Страницы', 'adminPages', 'adminPages', 'AdminPages', 0, 0, 0, 0, 1, 1),
(430, 'admin/blocks', 'adminLayout', 'blocks', '', '', 'Блоки', 'adminBlocks', 'adminBlocks', '', 0, 0, 0, 0, 1, 2),
(440, 'admin/users-list', 'adminLayout', 'usersList', '', '', 'Список пользователей', 'adminUsersList', 'adminUsersList', 'adminUsersList', 0, 0, 0, 0, 1, 3),
(450, 'admin/preferences', 'adminLayout', 'preferences', '', '', 'Настройки', 'preferences', 'preferences', 'preferences', 0, 0, 0, 0, 1, 4),
(500, 'admin/login', 'simpleLayout', 'login', 'login', '', '', 'Admin Login', 'Admin Login', 'Admin Login', 0, 0, 0, 0, 0, 0),
(510, 'admin/admin-activation', 'simpleLayout', 'adminActivation', 'adminActivation', '', '', 'Admin Activation', 'Admin Activation', 'Admin Activation', 0, 0, 0, 0, 0, 0),
(520, 'admin/api-documentation', 'simpleLayout', '', '', '', 'Api-documentation', 'Api-documentation', 'Api-documentation', '', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `main_layouts`
--

CREATE TABLE `main_layouts` (
  `id` int(1) UNSIGNED NOT NULL,
  `layout` varchar(32) NOT NULL,
  `head` varchar(32) NOT NULL,
  `header` varchar(32) NOT NULL,
  `footer` varchar(32) NOT NULL,
  `aside` varchar(32) NOT NULL,
  `css` varchar(32) NOT NULL,
  `js` varchar(32) NOT NULL,
  `current` tinyint(1) NOT NULL,
  `default` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `main_layouts`
--

INSERT INTO `main_layouts` (`id`, `layout`, `head`, `header`, `footer`, `aside`, `css`, `js`, `current`, `default`) VALUES
(1, 'multyLayout', 'head', 'multyHeader', 'multyFooter', 'multyAside', 'main', 'main', 1, 1),
(2, 'landingLayout', 'head', 'landingHeader', 'landingFooter', 'landingAside', 'main', 'main', 0, 0),
(3, 'simpleLayout', 'head', '', '', '', 'main', 'main', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `main_popups`
--

CREATE TABLE `main_popups` (
  `id` int(1) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `file` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `main_popups`
--

INSERT INTO `main_popups` (`id`, `name`, `file`) VALUES
(1, 'login', 'login'),
(2, 'register', 'register'),
(3, 'access-restore', 'accessRestore');

-- --------------------------------------------------------

--
-- Структура таблицы `main_sections`
--

CREATE TABLE `main_sections` (
  `id` int(1) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `file` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `main_sections`
--

INSERT INTO `main_sections` (`id`, `name`, `file`) VALUES
(1, 'accessRestore', 'accessRestore'),
(2, 'slider', 'militerslider');

-- --------------------------------------------------------

--
-- Структура таблицы `main_sitemap`
--

CREATE TABLE `main_sitemap` (
  `id` int(1) UNSIGNED NOT NULL,
  `page_uri` varchar(255) NOT NULL,
  `layout` varchar(32) NOT NULL,
  `main_content` varchar(32) NOT NULL,
  `page_css` varchar(32) NOT NULL,
  `page_js` varchar(32) NOT NULL,
  `label` varchar(32) NOT NULL,
  `title` varchar(81) NOT NULL,
  `description` varchar(300) NOT NULL,
  `h1` varchar(255) NOT NULL,
  `header_nav` tinyint(1) NOT NULL DEFAULT '0',
  `header_nav_order` int(1) NOT NULL,
  `footer_nav` tinyint(1) NOT NULL DEFAULT '0',
  `footer_nav_order` int(1) NOT NULL,
  `aside_nav` tinyint(1) NOT NULL DEFAULT '0',
  `aside_nav_order` int(1) NOT NULL,
  `robots` tinyint(1) NOT NULL DEFAULT '1',
  `admin` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `main_sitemap`
--

INSERT INTO `main_sitemap` (`id`, `page_uri`, `layout`, `main_content`, `page_css`, `page_js`, `label`, `title`, `description`, `h1`, `header_nav`, `header_nav_order`, `footer_nav`, `footer_nav_order`, `aside_nav`, `aside_nav_order`, `robots`, `admin`) VALUES
(110, '', 'multyLayout', 'homePage', '', '', 'Главная', 'Главная', 'Главная', '', 0, 0, 0, 0, 0, 0, 1, 1),
(111, 'page1', 'multyLayout', 'page1', '', '', 'Page1', 'Page1', 'Page1', '', 1, 1, 1, 1, 1, 1, 1, 1),
(112, 'page2', 'multyLayout', 'page2', '', '', 'Page2', 'Page2', 'Page2', '', 1, 2, 1, 2, 1, 2, 1, 1),
(113, 'page3', 'multyLayout', 'page3', '', '', 'Page3', 'Page3', 'Page3', '', 1, 3, 1, 3, 1, 3, 1, 1),
(114, 'page4', 'multyLayout', 'page4', '', '', 'Page4', 'Page4', 'Page4', '', 1, 4, 1, 4, 1, 4, 1, 1),
(115, 'page5', 'multyLayout', 'page5', '', '', 'Page5', 'Page5', 'Page5', '', 1, 5, 1, 5, 1, 5, 1, 1),
(118, 'user', 'multyLayout', 'user', '', '', 'Личный кабинет', 'Личный кабинет', 'Личный кабинет', '', 0, 0, 0, 0, 0, 0, 0, 0),
(200, 'access-restore', 'simpleLayout', 'accessRestore', '', '', 'Восстановление доступа', 'Восстановление доступа', 'Восстановление доступа', 'Восстановление доступа', 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(1) UNSIGNED NOT NULL,
  `user_uuid` varchar(36) NOT NULL,
  `username` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'user',
  `balance` decimal(6,2) NOT NULL DEFAULT '0.00',
  `phone` varchar(20) NOT NULL DEFAULT 'не указан',
  `about` text NOT NULL,
  `last_visit` date NOT NULL,
  `register_date` date NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `user_uuid`, `username`, `name`, `email`, `password`, `status`, `balance`, `phone`, `about`, `last_visit`, `register_date`, `admin`) VALUES
(1, '4fc4042f-4dfc-4f5c-bfb8-5bbf215d0bfa', 'aaaaa2', 'name', 'htmlcssjs.pro@gmail.com', '$2y$10$/Xp4pTDXKGF9zJ4vinfxK.ZcsnkHJRU/ry0HowhK6AmSrY0yG5sDe', 'user', '0.00', '+79149705050', '', '2020-11-26', '2020-09-24', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`email`),
  ADD UNIQUE KEY `admin_uuid` (`admin_uuid`);

--
-- Индексы таблицы `admin_layouts`
--
ALTER TABLE `admin_layouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `layout` (`layout`);

--
-- Индексы таблицы `admin_popups`
--
ALTER TABLE `admin_popups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `admin_sitemap`
--
ALTER TABLE `admin_sitemap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_uri` (`page_uri`) USING BTREE,
  ADD KEY `layout` (`layout`);

--
-- Индексы таблицы `main_layouts`
--
ALTER TABLE `main_layouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `layout` (`layout`);

--
-- Индексы таблицы `main_popups`
--
ALTER TABLE `main_popups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `main_sections`
--
ALTER TABLE `main_sections`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `main_sitemap`
--
ALTER TABLE `main_sitemap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_uri` (`page_uri`) USING BTREE,
  ADD KEY `layout` (`layout`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_uuid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `admin_layouts`
--
ALTER TABLE `admin_layouts`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `admin_popups`
--
ALTER TABLE `admin_popups`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `admin_sitemap`
--
ALTER TABLE `admin_sitemap`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=521;

--
-- AUTO_INCREMENT для таблицы `main_layouts`
--
ALTER TABLE `main_layouts`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `main_popups`
--
ALTER TABLE `main_popups`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `main_sections`
--
ALTER TABLE `main_sections`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `main_sitemap`
--
ALTER TABLE `main_sitemap`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `admin_sitemap`
--
ALTER TABLE `admin_sitemap`
  ADD CONSTRAINT `admin_sitemap_ibfk_1` FOREIGN KEY (`layout`) REFERENCES `admin_layouts` (`layout`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `main_sitemap`
--
ALTER TABLE `main_sitemap`
  ADD CONSTRAINT `main_sitemap_ibfk_1` FOREIGN KEY (`layout`) REFERENCES `main_layouts` (`layout`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
