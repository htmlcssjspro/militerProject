-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 20 2021 г., 09:46
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
-- База данных: `u1036142_mika`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin`
--

CREATE TABLE `admin` (
  `id` int(1) UNSIGNED NOT NULL,
  `login` varchar(16) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin`
--

INSERT INTO `admin` (`id`, `login`, `password`) VALUES
(1, 'admin', '$2y$10$tTv5DgbNA4j38/yuPg58h.NWEEnsSU1CQXwY706rUwvmT0QllI9Fm');

-- --------------------------------------------------------

--
-- Структура таблицы `sitemap`
--

CREATE TABLE `sitemap` (
  `id` int(2) UNSIGNED NOT NULL,
  `page_id` varchar(32) NOT NULL,
  `page_url` varchar(255) NOT NULL,
  `controller` tinytext NOT NULL,
  `action` tinytext NOT NULL,
  `method` enum('get','post','put','patch','delete') NOT NULL,
  `label` tinytext NOT NULL,
  `title` tinytext NOT NULL,
  `description` tinytext NOT NULL,
  `h1` tinytext NOT NULL,
  `header_nav` tinyint(1) NOT NULL DEFAULT '0',
  `header_order` int(1) NOT NULL,
  `robots` tinyint(1) NOT NULL DEFAULT '1',
  `admin` tinyint(1) NOT NULL DEFAULT '1',
  `admin_aside` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sitemap`
--

INSERT INTO `sitemap` (`id`, `page_id`, `page_url`, `controller`, `action`, `method`, `label`, `title`, `description`, `h1`, `header_nav`, `header_order`, `robots`, `admin`, `admin_aside`) VALUES
(514, 'admin_api_login', '/admin/api/login', 'AdminApiController', 'login', 'post', 'Admin-api-login', '', '', '', 0, 0, 0, 0, 0),
(515, 'admin_api_preferences', '/admin/api/preferences', 'AdminApiController', 'preferences', 'post', 'Admin-api-preferences', '', '', '', 0, 0, 0, 0, 0),
(412, 'admin_blocks', '/admin/blocks', 'AdminController', 'blocks', 'get', 'Блоки', 'adminBlocks', 'adminBlocks', '', 0, 0, 0, 0, 1),
(410, 'admin_home_page', '/admin', 'AdminController', 'index', 'get', 'Панель администратора', 'admin', 'admin', '', 0, 0, 0, 0, 0),
(405, 'admin_login_page', '/admin/login', 'AdminController', 'loginPage', 'get', 'AdminLoginPage', 'login', 'login', '', 0, 0, 0, 0, 0),
(411, 'admin_pages', '/admin/pages', 'AdminController', 'pages', 'get', 'Страницы', 'adminPages', 'adminPages', 'AdminPages', 0, 0, 0, 0, 1),
(480, 'admin_preferences', '/admin/preferences', 'AdminController', 'preferences', 'get', 'Настройки', 'preferences', 'preferences', '', 0, 0, 0, 0, 1),
(415, 'admin_users_list', '/admin/users-list', 'AdminController', 'usersList', 'get', 'Список пользователей', 'adminUsersList', 'adminUsersList', '', 0, 0, 0, 0, 1),
(215, 'api_access_restore', '/api/access-restore', 'ApiController', 'accessRestore', 'post', 'Api-access-restore', '', '', '', 0, 0, 0, 0, 0),
(214, 'api_access_restore_request', '/api/access-restore-request', 'ApiController', 'accessRestoreRequest', 'post', 'Api-access-restore-request', '', '', '', 0, 0, 0, 0, 0),
(299, 'api_documentation', '/api/documentation', 'ApiController', 'documentation', 'get', 'Api-documentation', 'Api-documentation', 'Api-documentation', '', 0, 0, 0, 0, 0),
(211, 'api_login', '/api/login', 'ApiController', 'login', 'post', 'Api-login', '', '', '', 0, 0, 0, 0, 0),
(212, 'api_logout', '/api/logout', 'ApiController', 'logout', 'post', 'Api-logout', '', '', '', 0, 0, 0, 0, 0),
(210, 'api_register', '/api/register', 'ApiController', 'register', 'post', 'Api-register', '', '', '', 0, 0, 0, 0, 0),
(110, 'home', '/', 'MainController', 'index', 'get', 'Главная', 'Главная', 'Главная', '', 0, 0, 1, 1, 0),
(111, 'page1', '/page1', 'MainController', 'page', 'get', 'Page1', 'Page1', 'Page1', '', 1, 1, 1, 1, 0),
(112, 'page2', '/page2', 'MainController', 'page2', 'get', 'Page2', 'Page2', 'Page2', '', 1, 2, 1, 1, 0),
(113, 'page3', '/page3', 'MainController', 'page3', 'get', 'Page3', 'Page3', 'Page3', '', 1, 3, 1, 1, 0),
(114, 'page4', '/page4', 'MainController', 'page4', 'get', 'Page4', 'Page4', 'Page4', '', 1, 4, 1, 1, 0),
(115, 'page5', '/page5', 'MainController', 'page5', 'get', 'Page5', 'Page5', 'Page5', '', 1, 5, 1, 1, 0),
(118, 'user', '/user', 'MainController', 'user', 'get', 'Личный кабинет', 'Личный кабинет', 'Личный кабинет', '', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_uuid` varchar(36) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `restore_password` varchar(255) NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'user',
  `balance` decimal(6,2) NOT NULL DEFAULT '0.00',
  `phone` varchar(20) NOT NULL DEFAULT 'не указан',
  `about` text NOT NULL,
  `last_visit` date NOT NULL,
  `register_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `user_uuid`, `username`, `name`, `email`, `password`, `restore_password`, `status`, `balance`, `phone`, `about`, `last_visit`, `register_date`) VALUES
(10, '4fc4042f-4dfc-4f5c-bfb8-5bbf215d0bfa', 'aaaaa2', 'aaaa', 'htmlcssjs.pro@gmail.com', '$2y$10$GWgAZp1xUwAuFUeWvf7C4Os9li2CC2VQdJtRuXrK9aLmwk9jwpH8e', '$2y$10$uo.CUNWJxNbARmaokaZsr./3gyFX7OEE3oMD/BszwBt9LFMeEEiR2', 'organiza', '0.00', '+79149705050', '', '2021-04-19', '2020-09-24');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sitemap`
--
ALTER TABLE `sitemap`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `page_url` (`page_url`);

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
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `sitemap`
--
ALTER TABLE `sitemap`
  MODIFY `id` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=516;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
