CREATE TABLE IF NOT EXISTS `#__joocomments_ratings` (
  `comment_id` integer unsigned NOT NULL,
  `user_id` integer unsigned NOT NULL,
  `vote` integer NOT NULL,
  `ip` varchar(100) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  INDEX `idx_comment_id` (`comment_id`),
  INDEX `idx_user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `#__joocomments` add column `voting` int(11) NOT NULL DEFAULT '0';