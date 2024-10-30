CREATE TABLE `woo_manufacturer_brand` (
  `intmanufactureid` int(20) NOT NULL AUTO_INCREMENT,
  `varname` varchar(200) NOT NULL,
  `varimagepath` varchar(255) NOT NULL,
  `txtdesc` text NOT NULL,
  `varheading` varchar(200) NOT NULL,
  `dtdateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dtlastmodified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`intmanufactureid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;




CREATE TABLE `woo_manufacturer_brand_item` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


