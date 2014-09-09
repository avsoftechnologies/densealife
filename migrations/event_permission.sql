-- posting and approval permission on event and interest. 
ALTER TABLE `default_events` ADD `post_permission` ENUM( 'CREATER', 'FOLLOWER' ) NOT NULL DEFAULT 'CREATER' COMMENT 'FOLLOWERS =>Only Followers can post, CREATOR => only creater can post' AFTER `id` ,
ADD `post_approval` ENUM( 'YES', 'NO' ) NOT NULL DEFAULT 'NO' COMMENT 'if post is required to be reviewed by creater' AFTER `post_permission` ;

ALTER TABLE `default_events` CHANGE `post_permission` `comment_permission` ENUM( 'CREATER', 'FOLLOWER' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CREATER' COMMENT 'FOLLOWERS =>Only Followers can post, CREATOR => only creater can post',
CHANGE `post_approval` `comment_approval` ENUM( 'YES', 'NO' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NO' COMMENT 'if post is required to be reviewed by creater';

ALTER TABLE `default_events` CHANGE `comment_permission` `comment_permission` ENUM( 'CREATER', 'FOLLOWER' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CREATER' COMMENT 'FOLLOWERS =>Only Followers can post, CREATOR => only creater can post' AFTER `youtube_videos` ;

ALTER TABLE `default_events` CHANGE `comment_approval` `comment_approval` ENUM( 'YES', 'NO' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NO' COMMENT 'if post is required to be reviewed by creater' AFTER `comment_permission` ;
