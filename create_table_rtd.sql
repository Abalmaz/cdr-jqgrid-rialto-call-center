CREATE TABLE `rtd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skype_date` datetime DEFAULT NULL,
  `modify_date` datetime NOT NULL,
  `Date_contact` datetime DEFAULT NULL,
  `NOTE` varchar(250) NOT NULL,
  `clients_id` int(11) NOT NULL,
  `skype_name` varchar(100) DEFAULT NULL,
  `avg_income` varchar(20) DEFAULT NULL,
  `field_activity` varchar(250) DEFAULT NULL,
  `kind_activity` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_id` (`clients_id`),
  CONSTRAINT `clients_id` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;