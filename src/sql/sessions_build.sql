USE `bread`;

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
    `id`         VARCHAR(255),
    `user_id`    INT UNSIGNED NOT NULL,
    `login_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    CONSTRAINT `user_ref`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='contains user sessions';