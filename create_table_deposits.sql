CREATE TABLE `deposits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `type_dep` varchar(15) DEFAULT NULL,
  `amount` decimal(19,2) DEFAULT NULL,
  `Junior` varchar(50) DEFAULT NULL,
  `Senior` varchar(50) DEFAULT NULL,
  `clients_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_id_d` (`clients_id`),
  CONSTRAINT `clients_id_d` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;