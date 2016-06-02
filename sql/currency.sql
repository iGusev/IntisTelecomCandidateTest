CREATE TABLE `currency` (
  `symbol` varchar(3) NOT NULL DEFAULT '',
  `rate` int(11) NOT NULL,
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;