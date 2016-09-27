CREATE TABLE IF NOT EXISTS `PREFIX_invoicefox` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoicefox_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `doc_num` varchar(50) NOT NULL,
  `tax_id` varchar(50) NOT NULL,
  `is_finalize` int(1) NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;