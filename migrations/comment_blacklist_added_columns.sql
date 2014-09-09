ALTER TABLE `default_comment_blacklists` ADD `author_id` INT NOT NULL AFTER `id` ,
ADD `blocked_user_id` INT NOT NULL AFTER `author_id` ,
ADD `blocked_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `blocked_user_id` ,
ADD INDEX ( `author_id` , `blocked_user_id` ) ;



ALTER TABLE `default_comment_blacklists` ADD `status` ENUM( 'block', 'unblock' ) NOT NULL DEFAULT 'unblock';

ALTER TABLE `default_comment_blacklists` CHANGE `website` `website` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
CHANGE `email` `email` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';