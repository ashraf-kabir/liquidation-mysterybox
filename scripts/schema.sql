-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 08, 2020 at 10:30 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `code_builder_default`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_operation`
--

CREATE TABLE `admin_operation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `detail` text NOT NULL,
  `last_ip` varchar(25) NOT NULL,
  `user_agent` varchar(100) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `tag` text NOT NULL,
  `html` text NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`id`, `slug`, `subject`, `tag`, `html`, `created_at`, `updated_at`) VALUES
(1, 'reset-password', 'Reset your password', 'email,reset_token,link', 'Hi {{{email}}},<br/>You have requested to reset your password. Please click the link below to reset it.<br/><a href="{{{link}}}/{{{reset_token}}}">Link</a>. <br/>Thanks,<br/> Admin', '2020-03-08', '2020-03-08 21:11:52'),
(2, 'register', 'Register', 'email', 'Hi {{{email}}},<br/>Thanks for registering on our platform. <br/>Thanks,<br/> Admin', '2020-03-08', '2020-03-08 21:11:52'),
(3, 'confirm-password', 'Confirm your account', 'email,confirm_token,link', 'Hi {{{email}}},<br/>Please click the link below to confirm your account.<br/><a href="{{{link}}}/{{{confirm_token}}}">Link</a>Thanks,<br/> Admin', '2020-03-08', '2020-03-08 21:11:52'),
(4, 'verify', 'verify account with konfor', 'code', 'Your verification # is {{{code}}}', '2020-03-08', '2020-03-08 21:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `caption` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `url`, `caption`, `user_id`, `width`, `height`, `type`, `created_at`, `updated_at`) VALUES
(1, 'https://i.imgur.com/AzJ7DRw.png', '', 1, 581, 581, 1, '2020-03-08', '2020-03-08 21:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `member_operation`
--

CREATE TABLE `member_operation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `detail` text NOT NULL,
  `last_ip` varchar(25) NOT NULL,
  `user_agent` varchar(100) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(9377770345344, 'setting', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345345, 'role', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345346, 'referLog', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345347, 'user', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345348, 'token', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345349, 'email', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345350, 'sms', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345351, 'adminOperation', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345352, 'memberOperation', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345353, 'image', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0),
(9377770345354, 'test', '2020-03-09 01:11:52', '2020-03-09 01:11:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `refer_log`
--

CREATE TABLE `refer_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `referrer_user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'member', '2020-03-08', '2020-03-08 21:11:52'),
(2, 'admin', '2020-03-08', '2020-03-08 21:11:52'),
(3, 'system', '2020-03-08', '2020-03-08 21:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `value` text NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `key`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 0, 'Manaknight Inc', '2020-03-08', '2020-03-08 21:11:52'),
(2, 'site_logo', 0, 'https://manaknightdigital.com/assets/img/logo.png', '2020-03-08', '2020-03-08 21:11:52'),
(3, 'maintenance', 1, '0', '2020-03-08', '2020-03-08 21:11:52'),
(4, 'version', 0, '1.0.0', '2020-03-08', '2020-03-08 21:11:52'),
(5, 'copyright', 0, 'Copyright © 2019 Manaknightdigital Inc. All rights reserved.', '2020-03-08', '2020-03-08 21:11:52'),
(6, 'license_key', 4, '4097fbd4f340955de76ca555c201b185cf9d6921d977301b05cdddeae4af54f924f0508cd0f7ca66', '2020-03-08', '2020-03-08 21:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` int(11) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `tag` text NOT NULL,
  `content` text NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sms`
--

INSERT INTO `sms` (`id`, `slug`, `tag`, `content`, `created_at`, `updated_at`) VALUES
(1, 'verify', 'code', 'Your verification # is {{{code}}}', '2020-03-08', '2020-03-08 21:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `file` text NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` text NOT NULL,
  `data` text NOT NULL,
  `type` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ttl` int(11) NOT NULL,
  `issue_at` datetime DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(1) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `image` text NOT NULL,
  `image_id` int(11) NOT NULL,
  `refer` varchar(50) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `verify` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `type`, `first_name`, `last_name`, `phone`, `image`, `image_id`, `refer`, `profile_id`, `verify`, `role_id`, `stripe_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin@manaknight.com', '$2b$10$VZZIWlrCs.bL3XpmEjyO9Ox9cDTH5z0Tj7y5KTruo4bfysh4MxpG2', 'n', 'Admin', 'Admin', '12345678', 'https://i.imgur.com/AzJ7DRw.png', 1, 'admin', 0, 1, 2, '', 1, '2020-03-08', '2020-03-08 21:11:52'),
(2, 'member@manaknight.com', '$2b$10$S90Fzyu27NpV/i0A98HlaeFmqjzy2vbuWk06ZPSJdYU5qFV8BjoJO', 'n', 'Admin', 'Admin', '12345678', 'https://i.imgur.com/AzJ7DRw.png', 1, 'member', 0, 1, 1, '', 1, '2020-03-08', '2020-03-08 21:11:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_operation`
--
ALTER TABLE `admin_operation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_operation`
--
ALTER TABLE `member_operation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `refer_log`
--
ALTER TABLE `refer_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_operation`
--
ALTER TABLE `admin_operation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `member_operation`
--
ALTER TABLE `member_operation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `refer_log`
--
ALTER TABLE `refer_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;