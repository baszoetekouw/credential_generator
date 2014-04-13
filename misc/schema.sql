DROP TABLE IF EXISTS `credentials`;
CREATE TABLE `credentials` (
  `id`          int unsigned NOT NULL AUTO_INCREMENT,
  `passphrase`  varchar(255) NOT NULL,
  `refcode`     char(6)      NOT NULL,
  `timestamp`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip`          varchar(45)  NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `refcode_UNIQUE` (`refcode`)
) DEFAULT CHARSET=utf8;

