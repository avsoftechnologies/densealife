-- --------------------------------------------------------

--
-- Table structure for table `default_share`
--

CREATE TABLE IF NOT EXISTS `default_shares` (
  `id` int(11) NOT NULL,
  `fk_comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shared_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_comment_id` (`fk_comment_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `default_share`
--
ALTER TABLE `default_shares`
  ADD CONSTRAINT `default_shares_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `default_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comment_id` FOREIGN KEY (`fk_comment_id`) REFERENCES `default_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `default_shares` ADD `comment` MEDIUMTEXT NULL AFTER `user_id` ;

ALTER TABLE `default_shares` ADD PRIMARY KEY ( `id` ) ;

ALTER TABLE `default_shares` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;