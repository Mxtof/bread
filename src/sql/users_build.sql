USE `bread`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`      VARCHAR(64)  NOT NULL UNIQUE,
    `email`     VARCHAR(64)  NOT NULL UNIQUE,
    `password`  VARCHAR(512) NOT NULL,
    `created`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `modified`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `group`     ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    `status`    ENUM('pending', 'enabled', 'disabled') NOT NULL DEFAULT 'pending',

    PRIMARY KEY (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='contains user entities';