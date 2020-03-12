CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `groups` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Staff', '2019-12-16 10:46:56', '2020-01-08 09:39:58'),
(3, 'Year 7', '2019-12-16 10:48:02', '2019-12-16 10:48:02'),
(4, 'Year 8', '2019-12-16 10:48:11', '2019-12-16 10:48:11'),
(5, 'Year 9', '2019-12-16 10:48:17', '2019-12-16 10:48:17'),
(6, 'Year 10', '2019-12-16 10:48:23', '2019-12-16 10:48:23'),
(7, 'Year 11', '2019-12-16 10:48:34', '2019-12-16 10:48:34'),
(8, 'Year 12', '2019-12-16 10:48:39', '2019-12-16 10:48:39'),
(9, 'Year 13', '2019-12-16 10:48:45', '2019-12-16 10:48:45');

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(15, 'App\\User', 1),
(16, 'App\\User', 38),
(19, 'App\\User', 32),
(19, 'App\\User', 40),
(19, 'App\\User', 41),
(19, 'App\\User', 42),
(19, 'App\\User', 43);


CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(17, 'USERS-view-users', 'web', '2019-12-12 10:16:33', '2020-01-23 09:27:37'),
(18, 'USERS-delete-users', 'web', '2019-12-12 10:16:33', '2019-12-14 13:49:08'),
(19, 'USERS-create-users', 'web', '2019-12-12 10:16:33', '2019-12-14 13:49:14'),
(20, 'USERS-edit-users', 'web', '2019-12-12 10:16:33', '2019-12-14 13:49:20'),
(23, 'RUNS-create-runs', 'web', '2019-12-16 13:30:05', '2019-12-18 15:35:45'),
(24, 'RUNS-view-runs', 'web', '2019-12-18 15:28:56', '2019-12-18 15:28:56'),
(25, 'RUNS-edit-runs', 'web', '2019-12-18 15:29:11', '2019-12-18 15:29:11'),
(27, 'USERS-change-privacy', 'web', '2020-01-23 08:59:35', '2020-01-23 08:59:35');


CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(15, 'Super Admin', 'web', '2019-12-13 13:38:46', '2019-12-13 13:38:46'),
(16, 'PLT Student', 'web', '2019-12-13 13:53:40', '2019-12-13 13:56:42'),
(19, 'Student', 'web', '2019-12-16 09:37:38', '2019-12-16 09:37:38');


CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(17, 16),
(19, 16),
(23, 16),
(24, 16),
(24, 19),
(27, 16),
(27, 19);

CREATE TABLE `seasons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `seasons` (`id`, `name`, `created_at`, `updated_at`, `active`) VALUES
(1, '2019/2020', '2020-01-06 06:00:00', '2020-01-06 06:00:00', 1);

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` int(10) UNSIGNED DEFAULT NULL,
  `private` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `users` (`id`, `email`, `email_verified_at`, `password`, `api_token`, `remember_token`, `created_at`, `updated_at`, `group_id`, `lastname`, `firstname`, `gender`, `private`) VALUES
(1, 'n.wootton@thestreetlyacademy.co.uk', NULL, '$2y$10$9mkw8G54xlci6/KQyqZYJO1mdX0pGT8Ay7fNWLM2bja1gslDU.wn.', 'PmqQRZ2MM6SXTXhe5eNMpKqcpI61Ivz7qI2n19LMhs9L0xdbyb1bwlr4TM1C', NULL, '2019-12-11 12:22:25', '2020-01-23 09:29:08', 1, 'Wootton', 'Nicholas', 0, 0),
(32, 'j.badg@badger.com', NULL, '$2y$10$9mkw8G54xlci6/KQyqZYJO1mdX0pGT8Ay7fNWLM2bja1gslDU.wn.', NULL, NULL, '2019-12-16 09:34:49', '2020-01-13 13:05:43', 3, 'Badger', 'Jeff', 1, 0),
(43, 'nicholas.d.wootton@gmail.com', NULL, '$2y$10$1lvcdOlU78Vgh6hDWCB3I.2MdEIGQFW7/FatPrkCbP6t5mvmktgLG', 'Qbih9ekqkelaFzr58BSnJ2SBtxUYPDSWTPjCgM03MYrdDbJ6OwKbTPQnGwdc', NULL, '2020-01-23 09:29:55', '2020-01-23 09:37:02', 3, 'Wootton', 'Nicholas', 1, 1);


ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);


ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`);

ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

ALTER TABLE `seasons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;
