
CREATE TABLE IF NOT EXISTS `auto_approvals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `approval_type` enum('comment') NOT NULL DEFAULT 'comment',
  `status` enum('on','off') NOT NULL DEFAULT 'on',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

