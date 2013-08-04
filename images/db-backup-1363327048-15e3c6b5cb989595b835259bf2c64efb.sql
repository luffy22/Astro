DROP TABLE IF EXISTS ad_fieldsattach;

CREATE TABLE `ad_fieldsattach` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `title` varchar(200) NOT NULL,  `extras` text NOT NULL,  `showtitle` tinyint(1) NOT NULL,  `positionarticle` tinyint(1) DEFAULT '0',  `type` varchar(20) NOT NULL,  `groupid` int(11) DEFAULT NULL,  `articlesid` varchar(255) DEFAULT NULL,  `language` varchar(20) NOT NULL,  `visible` tinyint(1) NOT NULL,  `ordering` int(11) NOT NULL,  `published` tinyint(1) NOT NULL,  `required` tinyint(1) DEFAULT NULL,  `searchable` tinyint(1) DEFAULT NULL,  `params` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO ad_fieldsattach VALUES("1","Direction","East|east/\n/West|west/\n/North|north/\n/South|south","1","0","select","1","","*","1","1","1","0","1","{\"field_size\":\"\",\"field_maxlenght\":\"\",\"field_defaultvalue\":\"\",\"field_selectable\":\"\",\"field_selectable2\":\"\",\"field_width\":\"\",\"field_height\":\"\",\"field_filter\":\"\",\"galleryimage2\":\"1\",\"galleryimage3\":\"1\",\"gallerydescription\":\"1\",\"field_textarea\":\"\",\"field_defaultvaluetextarea\":\"\"}");
INSERT INTO ad_fieldsattach VALUES("2","Nakshatra Lord","Sun|sun/\n/Moon|moon/\n/Mars|mars/\n/Mercury|mercury/\n/Jupiter|jupiter/\n/Venus|venus/\n/Saturn|saturn/\n/Rahu|rahu/\n/Ketu|ketu","1","0","select","1","","*","1","0","1","0","1","{\"field_size\":\"\",\"field_maxlenght\":\"\",\"field_defaultvalue\":\"\",\"field_selectable\":\"\",\"field_selectable2\":\"\",\"field_width\":\"\",\"field_height\":\"\",\"field_filter\":\"\",\"galleryimage2\":\"1\",\"galleryimage3\":\"1\",\"gallerydescription\":\"1\",\"field_textarea\":\"\",\"field_defaultvaluetextarea\":\"\"}");
INSERT INTO ad_fieldsattach VALUES("3","Aim","Dharma|dharma/\n/Artha|Artha/\n/Kama|kama/\n/Moksha|moksha","1","1","select","1","","*","1","2","1","0","0","{\"field_size\":\"\",\"field_maxlenght\":\"\",\"field_defaultvalue\":\"\",\"field_selectable\":\"\",\"field_selectable2\":\"\",\"field_width\":\"\",\"field_height\":\"\",\"field_filter\":\"\",\"galleryimage2\":\"1\",\"galleryimage3\":\"1\",\"gallerydescription\":\"1\",\"field_textarea\":\"\",\"field_defaultvaluetextarea\":\"\"}");
INSERT INTO ad_fieldsattach VALUES("4","Power","","1","1","input","1","","*","1","3","1","0","1","{\"field_size\":\"\",\"field_maxlenght\":\"\",\"field_defaultvalue\":\"\",\"field_selectable\":\"\",\"field_selectable2\":\"\",\"field_width\":\"\",\"field_height\":\"\",\"field_filter\":\"\",\"galleryimage2\":\"1\",\"galleryimage3\":\"1\",\"gallerydescription\":\"1\",\"field_textarea\":\"\",\"field_defaultvaluetextarea\":\"\"}");



DROP TABLE IF EXISTS ad_fieldsattach_categories_values;

CREATE TABLE `ad_fieldsattach_categories_values` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `catid` int(11) NOT NULL,  `fieldsid` int(11) NOT NULL,  `value` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS ad_fieldsattach_groups;

CREATE TABLE `ad_fieldsattach_groups` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `title` varchar(255) NOT NULL,  `note` varchar(150) DEFAULT NULL,  `description` text,  `position` varchar(255) DEFAULT NULL,  `group_for` int(1) DEFAULT NULL,  `showtitle` tinyint(1) NOT NULL,  `catid` varchar(100) NOT NULL,  `articlesid` varchar(255) DEFAULT NULL,  `recursive` tinyint(1) NOT NULL,  `language` varchar(7) NOT NULL,  `ordering` int(11) NOT NULL,  `published` tinyint(1) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO ad_fieldsattach_groups VALUES("1","Nakshatra_Fields","nakshatra_fields","","0","0","1","12","","1","*","0","1");



DROP TABLE IF EXISTS  ad_fieldsattach_images;

CREATE TABLE `ad_fieldsattach_images` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `articleid` int(11) NOT NULL,  `fieldsattachid` int(11) NOT NULL,  `catid` int(11) DEFAULT NULL,  `title` varchar(255) NOT NULL,  `image1` varchar(255) NOT NULL,  `image2` varchar(255) NOT NULL,  `image3` varchar(255) NOT NULL,  `description` text NOT NULL,  `ordering` int(11) NOT NULL,  `published` tinyint(1) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS  ad_fieldsattach_values;

CREATE TABLE `ad_fieldsattach_values` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `articleid` int(11) NOT NULL,  `fieldsid` int(11) NOT NULL,  `value` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;

INSERT INTO  ad_fieldsattach_values VALUES("1","49","2","saturn");
INSERT INTO  ad_fieldsattach_values VALUES("2","49","1","south");
INSERT INTO  ad_fieldsattach_values VALUES("3","49","3","dharma");
INSERT INTO  ad_fieldsattach_values VALUES("4","49","4","Abundance");
INSERT INTO  ad_fieldsattach_values VALUES("5","39","2","rahu");
INSERT INTO  ad_fieldsattach_values VALUES("6","39","1","west");
INSERT INTO  ad_fieldsattach_values VALUES("7","39","3","kama");
INSERT INTO  ad_fieldsattach_values VALUES("8","39","4","Achieving");
INSERT INTO  ad_fieldsattach_values VALUES("9","42","2","mercury");
INSERT INTO  ad_fieldsattach_values VALUES("10","42","1","south");
INSERT INTO  ad_fieldsattach_values VALUES("11","42","3","dharma");
INSERT INTO  ad_fieldsattach_values VALUES("12","42","4","Destroying, Obstructs Spiritual Energy");
INSERT INTO  ad_fieldsattach_values VALUES("13","34","2","ketu");
INSERT INTO  ad_fieldsattach_values VALUES("14","34","1","south");
INSERT INTO  ad_fieldsattach_values VALUES("15","34","3","dharma");
INSERT INTO  ad_fieldsattach_values VALUES("16","34","4","Healing");
INSERT INTO  ad_fieldsattach_values VALUES("17","35","2","venus");
INSERT INTO  ad_fieldsattach_values VALUES("18","35","1","west");
INSERT INTO  ad_fieldsattach_values VALUES("19","35","3","Artha");
INSERT INTO  ad_fieldsattach_values VALUES("20","35","4","Removing");
INSERT INTO  ad_fieldsattach_values VALUES("21","46","2","mars");
INSERT INTO  ad_fieldsattach_values VALUES("22","46","1","west");
INSERT INTO  ad_fieldsattach_values VALUES("23","46","3","dharma");
INSERT INTO  ad_fieldsattach_values VALUES("24","46","4","Creating, Spiritual Power");
INSERT INTO  ad_fieldsattach_values VALUES("25","55","2","mars");
INSERT INTO  ad_fieldsattach_values VALUES("26","55","1","east");
INSERT INTO  ad_fieldsattach_values VALUES("27","55","3","dharma");
INSERT INTO  ad_fieldsattach_values VALUES("28","55","4","Joining");
INSERT INTO  ad_fieldsattach_values VALUES("29","45","2","moon");
INSERT INTO  ad_fieldsattach_values VALUES("30","45","1","south");
INSERT INTO  ad_fieldsattach_values VALUES("31","45","3","moksha");
INSERT INTO  ad_fieldsattach_values VALUES("32","45","4","Gaining");
INSERT INTO  ad_fieldsattach_values VALUES("33","50","2","mercury");
INSERT INTO  ad_fieldsattach_values VALUES("34","50","1","west");
INSERT INTO  ad_fieldsattach_values VALUES("35","50","3","Artha");
INSERT INTO  ad_fieldsattach_values VALUES("36","50","4","Heroism");
INSERT INTO  ad_fieldsattach_values VALUES("37","36","2","sun");
INSERT INTO  ad_fieldsattach_values VALUES("38","36","1","north");
INSERT INTO  ad_fieldsattach_values VALUES("39","36","3","kama");
INSERT INTO  ad_fieldsattach_values VALUES("40","36","4","Burning");
INSERT INTO  ad_fieldsattach_values VALUES("41","43","2","ketu");
INSERT INTO  ad_fieldsattach_values VALUES("42","43","1","west");
INSERT INTO  ad_fieldsattach_values VALUES("43","43","3","Artha");
INSERT INTO  ad_fieldsattach_values VALUES("44","43","4","Dying, Spiritual Rebirth");
INSERT INTO  ad_fieldsattach_values VALUES("45","38","2","mars");
INSERT INTO  ad_fieldsattach_values VALUES("46","38","1","south");
INSERT INTO  ad_fieldsattach_values VALUES("47","38","3","moksha");
INSERT INTO  ad_fieldsattach_values VALUES("48","38","4","Enjoying");
INSERT INTO  ad_fieldsattach_values VALUES("49","51","2","ketu");
INSERT INTO  ad_fieldsattach_values VALUES("50","51","1","north");
INSERT INTO  ad_fieldsattach_values VALUES("51","51","3","kama");
INSERT INTO  ad_fieldsattach_values VALUES("52","51","4","Clearing");
INSERT INTO  ad_fieldsattach_values VALUES("53","40","2","jupiter");
INSERT INTO  ad_fieldsattach_values VALUES("54","40","1","north");
INSERT INTO  ad_fieldsattach_values VALUES("55","40","3","Artha");
INSERT INTO  ad_fieldsattach_values VALUES("56","40","4","Revitalising");
INSERT INTO  ad_fieldsattach_values VALUES("57","52","2","venus");
INSERT INTO  ad_fieldsattach_values VALUES("58","52","1","east");
INSERT INTO  ad_fieldsattach_values VALUES("59","52","3","moksha");
INSERT INTO  ad_fieldsattach_values VALUES("60","52","4","Invigorating");
INSERT INTO  ad_fieldsattach_values VALUES("61","57","2","jupiter");
INSERT INTO  ad_fieldsattach_values VALUES("62","57","1","west");
INSERT INTO  ad_fieldsattach_values VALUES("63","57","3","Artha");
INSERT INTO  ad_fieldsattach_values VALUES("64","57","4","Upraising");
INSERT INTO  ad_fieldsattach_values VALUES("65","44","2","venus");
INSERT INTO  ad_fieldsattach_values VALUES("66","44","1","north");
INSERT INTO  ad_fieldsattach_values VALUES("67","44","3","kama");
INSERT INTO  ad_fieldsattach_values VALUES("68","44","4","Procreating");
INSERT INTO  ad_fieldsattach_values VALUES("69","41","2","saturn");
INSERT INTO  ad_fieldsattach_values VALUES("70","41","1","east");
INSERT INTO  ad_fieldsattach_values VALUES("71","41","3","dharma");
INSERT INTO  ad_fieldsattach_values VALUES("72","41","4","sanctifying create spiritual energy");
INSERT INTO  ad_fieldsattach_values VALUES("73","59","2","mercury");
INSERT INTO  ad_fieldsattach_values VALUES("74","59","1","east");
INSERT INTO  ad_fieldsattach_values VALUES("75","59","3","moksha");
INSERT INTO  ad_fieldsattach_values VALUES("76","59","4","Nourishing");
INSERT INTO  ad_fieldsattach_values VALUES("77","37","2","moon");
INSERT INTO  ad_fieldsattach_values VALUES("78","37","1","east");
INSERT INTO  ad_fieldsattach_values VALUES("79","37","3","moksha");
INSERT INTO  ad_fieldsattach_values VALUES("80","37","4","Growing");
INSERT INTO  ad_fieldsattach_values VALUES("81","56","2","rahu");
INSERT INTO  ad_fieldsattach_values VALUES("82","56","1","south");
INSERT INTO  ad_fieldsattach_values VALUES("83","56","3","dharma");
INSERT INTO  ad_fieldsattach_values VALUES("84","56","4","Healing");
INSERT INTO  ad_fieldsattach_values VALUES("85","54","2","moon");
INSERT INTO  ad_fieldsattach_values VALUES("86","54","1","north");
INSERT INTO  ad_fieldsattach_values VALUES("87","54","3","Artha");
INSERT INTO  ad_fieldsattach_values VALUES("88","54","4","Connecting");
INSERT INTO  ad_fieldsattach_values VALUES("89","47","2","rahu");
INSERT INTO  ad_fieldsattach_values VALUES("90","47","1","north");
INSERT INTO  ad_fieldsattach_values VALUES("91","47","3","Artha");
INSERT INTO  ad_fieldsattach_values VALUES("92","47","4","Transforming");
INSERT INTO  ad_fieldsattach_values VALUES("93","53","2","sun");
INSERT INTO  ad_fieldsattach_values VALUES("94","53","1","south");
INSERT INTO  ad_fieldsattach_values VALUES("95","53","3","moksha");
INSERT INTO  ad_fieldsattach_values VALUES("96","53","4","Victory");
INSERT INTO  ad_fieldsattach_values VALUES("97","58","2","saturn");
INSERT INTO  ad_fieldsattach_values VALUES("98","58","1","north");
INSERT INTO  ad_fieldsattach_values VALUES("99","58","3","kama");
INSERT INTO  ad_fieldsattach_values VALUES("100","58","4","Stabilising");
INSERT INTO  ad_fieldsattach_values VALUES("101","60","2","sun");
INSERT INTO  ad_fieldsattach_values VALUES("102","60","1","east");
INSERT INTO  ad_fieldsattach_values VALUES("103","60","3","moksha");
INSERT INTO  ad_fieldsattach_values VALUES("104","60","4","Prospering");
INSERT INTO  ad_fieldsattach_values VALUES("105","48","2","jupiter");
INSERT INTO  ad_fieldsattach_values VALUES("106","48","1","east");
INSERT INTO  ad_fieldsattach_values VALUES("107","48","3","dharma");
INSERT INTO  ad_fieldsattach_values VALUES("108","48","4","Harvesting");



