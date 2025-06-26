CREATE TABLE IF NOT EXISTS `countries` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS  `regions` (
    `id` int NOT NULL AUTO_INCREMENT,
    `country_id` int DEFAULT NULL,
    `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `country_id_fk` (`country_id`),
    CONSTRAINT `country_id_fk` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cities` (
    `id` int NOT NULL AUTO_INCREMENT,
    `region_id` int DEFAULT NULL,
    `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `lat` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `lng` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `region_id_fk` (`region_id`),
    CONSTRAINT `region_id_fk` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `countries` SET `id` = 1, `name` = 'Беларусь';

INSERT INTO `regions` SET `id` = 1, `country_id` = 1, `name` = 'Брестская обл.';
INSERT INTO `regions` SET `id` = 2, `country_id` = 1, `name` = 'Витебская обл.';
INSERT INTO `regions` SET `id` = 3, `country_id` = 1, `name` = 'Гомельская обл.';
INSERT INTO `regions` SET `id` = 4, `country_id` = 1, `name` = 'Гродненская обл.';
INSERT INTO `regions` SET `id` = 5, `country_id` = 1, `name` = 'Минская обл.';
INSERT INTO `regions` SET `id` = 6, `country_id` = 1, `name` = 'Могилевская обл.';


