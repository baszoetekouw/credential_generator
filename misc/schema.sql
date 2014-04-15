DROP TABLE IF EXISTS `credentials`;
CREATE TABLE `credentials` (
  `id`              int unsigned NOT NULL AUTO_INCREMENT,
  `passphrase`      varchar(255) NOT NULL,
  `refcode`         char(6)      NOT NULL,
  `generation_date` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `generation_ip`   varchar(45)  NOT NULL,
  `viewed_by`       varchar(255) DEFAULT NULL,
  `view_date`       timestamp    NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `refcode_UNIQUE` (`refcode`),
  KEY `generation_date_IDX` (`generation_date`),
  KEY `viewed_by_IDX` (`viewed_by`(255)),
  KEY `view_date_IDX` (`view_date`)
) DEFAULT CHARSET=utf8;

