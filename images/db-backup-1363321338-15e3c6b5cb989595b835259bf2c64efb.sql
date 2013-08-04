DROP TABLE IF EXISTS ad_fieldsattach;

CREATE TABLE `ad_fieldsattach` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `title` varchar(200) NOT NULL,  `extras` text NOT NULL,  `showtitle` tinyint(1) NOT NULL,  `positionarticle` tinyint(1) DEFAULT '0',  `type` varchar(20) NOT NULL,  `groupid` int(11) DEFAULT NULL,  `articlesid` varchar(255) DEFAULT NULL,  `language` varchar(20) NOT NULL,  `visible` tinyint(1) NOT NULL,  `ordering` int(11) NOT NULL,  `published` tinyint(1) NOT NULL,  `required` tinyint(1) DEFAULT NULL,  `searchable` tinyint(1) DEFAULT NULL,  `params` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO ad_fieldsattach VALUES("1","PAD","1|1/\n/2|2/\n/3|3/\n/4|4","1","0","select","1","","*","1","0","1","0","1","{\"field_size\":\"2\",\"field_maxlenght\":\"\",\"field_defaultvalue\":\"\",\"field_selectable\":\"\",\"field_selectable2\":\"\",\"field_width\":\"\",\"field_height\":\"\",\"field_filter\":\"\",\"galleryimage2\":\"1\",\"galleryimage3\":\"1\",\"gallerydescription\":\"1\",\"field_textarea\":\"\",\"field_defaultvaluetextarea\":\"\"}");
INSERT INTO ad_fieldsattach VALUES("2","Nakshatra Lord","Sun|sun/\n/Moon|moon/\n/Mars|mars/\n/Mercury|mercury/\n/Jupiter|jupiter/\n/Venus|venus/\n/Saturn|saturn/\n/Rahu|rahu/\n/Ketu|ketu","1","0","select","1","","*","1","0","1","0","1","{\"field_size\":\"\",\"field_maxlenght\":\"\",\"field_defaultvalue\":\"\",\"field_selectable\":\"\",\"field_selectable2\":\"\",\"field_width\":\"\",\"field_height\":\"\",\"field_filter\":\"\",\"galleryimage2\":\"1\",\"galleryimage3\":\"1\",\"gallerydescription\":\"1\",\"field_textarea\":\"\",\"field_defaultvaluetextarea\":\"\"}");
INSERT INTO ad_fieldsattach VALUES("3","Sub Lord","Sun|sun/\n/Moon|moon/\n/Mars|mars/\n/Mercury|mercury/\n/Jupiter|jupiter/\n/Venus|venus/\n/Saturn|saturn/\n/Rahu|rahu/\n/Ketu|ketu/\n/Ketu|ketu","1","1","select","1","","*","1","0","1","0","0","{\"field_size\":\"\",\"field_maxlenght\":\"\",\"field_defaultvalue\":\"\",\"field_selectable\":\"\",\"field_selectable2\":\"\",\"field_width\":\"\",\"field_height\":\"\",\"field_filter\":\"\",\"galleryimage2\":\"1\",\"galleryimage3\":\"1\",\"gallerydescription\":\"1\",\"field_textarea\":\"\",\"field_defaultvaluetextarea\":\"\"}");



DROP TABLE IF EXISTS ad_fieldsattach_categories_values;

CREATE TABLE `ad_fieldsattach_categories_values` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `catid` int(11) NOT NULL,  `fieldsid` int(11) NOT NULL,  `value` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS ad_fieldsattach_groups;

CREATE TABLE `ad_fieldsattach_groups` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `title` varchar(255) NOT NULL,  `note` varchar(150) DEFAULT NULL,  `description` text,  `position` varchar(255) DEFAULT NULL,  `group_for` int(1) DEFAULT NULL,  `showtitle` tinyint(1) NOT NULL,  `catid` varchar(100) NOT NULL,  `articlesid` varchar(255) DEFAULT NULL,  `recursive` tinyint(1) NOT NULL,  `language` varchar(7) NOT NULL,  `ordering` int(11) NOT NULL,  `published` tinyint(1) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO ad_fieldsattach_groups VALUES("1","Nakshatra_Fields","nakshatra_fields","","0","1","1","12","","1","*","0","1");



DROP TABLE IF EXISTS  ad_fieldsattach_images;

CREATE TABLE `ad_fieldsattach_images` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `articleid` int(11) NOT NULL,  `fieldsattachid` int(11) NOT NULL,  `catid` int(11) DEFAULT NULL,  `title` varchar(255) NOT NULL,  `image1` varchar(255) NOT NULL,  `image2` varchar(255) NOT NULL,  `image3` varchar(255) NOT NULL,  `description` text NOT NULL,  `ordering` int(11) NOT NULL,  `published` tinyint(1) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS  ad_fieldsattach_values;

CREATE TABLE `ad_fieldsattach_values` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `articleid` int(11) NOT NULL,  `fieldsid` int(11) NOT NULL,  `value` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;




