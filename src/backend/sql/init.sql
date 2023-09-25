CREATE TABLE `news` (
    `id` bigint unsigned PRIMARY KEY AUTO_INCREMENT,
    `owner_id` bigint unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` varchar(500) NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8mb4_general_ci';