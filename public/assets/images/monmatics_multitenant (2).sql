-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2024 at 12:34 PM
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
-- Database: `monmatics_multitenant`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('EzUnjA5K3NhvxaznZWceBNN3XKvlcTopohZnouTg', 1, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', 'YToxNzp7czo2OiJfdG9rZW4iO3M6NDA6IkVFeG54bHhPN2xQcmJkOVl5b2NTUHlpb0puU2QwdVZPeHE1Z0NudXAiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjU5OiJodHRwOi8vbG9jYWxob3N0Ojc4ODAvbW9ubWF0aWNzLXByby9tb25tYXRpY3MtcHJvL2Rpc3BsYXkvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiY29tcGFueUlkIjtzOjM2OiJmNGZkNjgyYy1kYzJkLTQyYmItOTE5Yy1jNDIwOTQ2M2UwZmYiO3M6NDoiY29kZSI7czozOiJTQVIiO3M6Njoic3ltYm9sIjtzOjM6IlNBUiI7czoxNDoibXVsdGlfY3VycmVuY3kiO2k6MTtzOjY6InVzZXJJZCI7aToxO3M6MjE6InZvdWNoZXJBcHByb3ZhbF9maXJzdCI7YjoxO3M6MjI6InZvdWNoZXJBcHByb3ZhbF9zZWNvbmQiO2I6MDtzOjEwOiJkYXRlRm9ybWF0IjtzOjEwOiJtbS9kZC9ZWVlZIjtzOjU6Im1vbnRoIjtzOjQ6IjIwMjQiO3M6ODoiZGJfZW1haWwiO3M6Mjc6InplZXNoYW5qYXZlZDI3NjY1QGdtYWlsLmNvbSI7czo3OiJkYl9uYW1lIjtzOjIxOiJtb25tYXRpY3NfZGV2ZWxvcG1lbnQiO3M6MTE6ImRiX3VzZXJuYW1lIjtzOjQ6InJvb3QiO3M6MTE6ImRiX3Bhc3N3b3JkIjtzOjA6IiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1706708400),
('RapwN7voouCwKTM0iRd4uPsnxgDGb7xevh7UXr60', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'YToxMjp7czo2OiJfdG9rZW4iO3M6NDA6Ik9UNWdZSzdWUVN6allOeHo4Nnc1U1k1QUY0RUlqc3R5bHFnYTRYc3ciO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjcwOiJodHRwOi8vbG9jYWxob3N0Ojc4ODAvbW9ubWF0aWNzLXByby9tb25tYXRpY3MtcHJvL2F1dGhlbnRpY2F0aW9uL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJjb21wYW55SWQiO3M6MzY6ImY0ZmQ2ODJjLWRjMmQtNDJiYi05MTljLWM0MjA5NDYzZTBmZiI7czo0OiJjb2RlIjtzOjM6IlNBUiI7czo2OiJzeW1ib2wiO3M6MzoiU0FSIjtzOjE0OiJtdWx0aV9jdXJyZW5jeSI7aToxO3M6NjoidXNlcklkIjtpOjE7czoyMToidm91Y2hlckFwcHJvdmFsX2ZpcnN0IjtiOjE7czoyMjoidm91Y2hlckFwcHJvdmFsX3NlY29uZCI7YjowO3M6MTA6ImRhdGVGb3JtYXQiO3M6MTA6Im1tL2RkL1lZWVkiO3M6NToibW9udGgiO3M6NDoiMjAyNCI7fQ==', 1706771534);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `db_name` varchar(255) DEFAULT NULL,
  `db_username` varchar(255) DEFAULT NULL,
  `db_password` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `db_name`, `db_username`, `db_password`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2010, 'zeeshanjaved276651@gmail.com', 'monmatics_development', 'root', '', NULL, 'admin', NULL, '2023-07-04 02:04:55', '2023-07-04 02:04:55'),
(20001, 'hi@monmatics.com', 'monmatics_development', 'root', '', NULL, 'admin', NULL, '2023-07-04 02:04:55', '2023-07-04 02:04:55'),
(20002, 'hi@mnc.com', 'monmatics_ramzanfarm', 'root', '', NULL, 'admin', NULL, '2023-07-04 02:05:12', '2023-07-04 02:05:12'),
(20003, 'abidakram33@gmail.com', 'monmatics_me', 'root', '', NULL, 'admin', NULL, '2023-07-04 02:05:28', '2023-07-04 02:05:28'),
(20005, 'rizwan@solutionswave.com', 'solutionswave_ofdesk', 'root', '', NULL, 'admin', NULL, '2023-07-04 02:05:28', '2023-07-04 02:05:28'),
(20007, 'naseem@chohan.net', 'monmatics_ramzanfarm', 'root', '', NULL, '', NULL, NULL, NULL),
(20009, 'vicore@monmatics.com', 'monmatics_development', 'root', '', NULL, 'admin', NULL, '2023-07-04 02:04:55', '2023-07-04 02:04:55'),
(20018, 'hussain@solutionswave.com', 'solutionswave_ofdesk', 'root', '', NULL, 'admin', NULL, '2023-07-04 02:05:28', '2023-07-04 02:05:28'),
(20020, 'weryj@mailinator.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20021, 'mokuk@mailinator.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20023, 'kahigum@mailinator.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20024, 'zeeshanjaved27665@gmail.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20025, 'zeeshanjaved@gmail.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20026, 'zeeshanjaved32427665@gmail.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20027, 'admin@gmail.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20028, 'demo@gmail.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20029, 'check@gmail.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL),
(20030, 'zeeshan@gmail.com', 'monmatics_development', 'root', '', NULL, '', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20031;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
