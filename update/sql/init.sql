-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `roles` (`id`, `title`, `parent`) VALUES
  (1,	'registrován',	NULL),
  (2,	'Prodejce',	1),
  (3,	'administrator',	2);

DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `content` text COLLATE utf8_czech_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `map_url` text COLLATE utf8_czech_ci,
  `order` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `sites` (`id`, `title`, `content`, `url`, `default`, `active`, `map_url`, `order`) VALUES
  (1,	'O společnosti',	'<p>Věnujete se profesion&aacute;lně&nbsp;v&yacute;robě n&aacute;bytku? Nebo jen doma potřebujete sestavit skř&iacute;ň? Ať tak nebo tak, v&nbsp;obou př&iacute;padech budete potřebovat kvalitn&iacute;&nbsp;n&aacute;bytkov&eacute; kov&aacute;n&iacute;. Nab&iacute;z&iacute; ho společnost <strong>KENY Export-Import</strong><strong>&nbsp;</strong><strong>s.r.o.</strong></p>',	'o-spolecnosti',	1,	1,	'',	0),
  (2,	'Kde nás najdete?',	'',	'kde-nas-najdete',	1,	1,	'https://www.google.com/maps/d/embed?mid=zVoCvwZiaDzw.kgJFvSlH0hFA',	1),
  (3,	'Kontakty',	'<h1 style=\"color: #ff0000;\"><span style=\"color: #000000;\"><strong>KENY Export - Import s.r.o.</strong></span></h1>\r\n<p>&nbsp;</p>\r\n<p><span class=\"example2\" style=\"color: #000000;\"><strong>Adresa: &nbsp;</strong></span>Are&aacute;l pek&aacute;ren SEMAG (vjezd z Jaro&scaron;ovy ulice)</p>\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Lidick&aacute; 886/43c</p>\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;736 01 &nbsp;Hav&iacute;řov - &Scaron;umbark</p>\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Tel./Fax: 596 883 223</p>\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;E-mail: <a href=\"mailto:keny.sro@email.cz\">keny.sro@email.cz</a></p>\r\n<p><strong>IČO: &nbsp; &nbsp; &nbsp; &nbsp;</strong>29457181</p>\r\n<p><strong>DIČ: &nbsp; &nbsp; &nbsp; &nbsp;</strong>CZ29457181</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Prodejn&iacute; doba:&nbsp;</strong>PO až P&Aacute; &nbsp; &nbsp; 8:00 - 15:30</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Jednatel: &nbsp;</strong>Soňa Szmekov&aacute;</p>\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Tel.: 737 241 187</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Přemysl Himlar</p>\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Tel.: 777 715 545</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>',	'kontakty',	1,	1,	'',	2);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `public` tinyint(4) NOT NULL DEFAULT '1',
  `role_id` int(11) NOT NULL DEFAULT '1',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `users` (`id`, `nickname`, `password`, `public`, `role_id`, `email`, `name`, `surname`) VALUES
  (1,	'admin',	'$2y$10$V9HcKBgX5SgFyYZiLX9v2.6b46hbjKV9pQ86gjJ/sxeelE2gxwM1i',	1,	3,	'm.himlar@gmail.com',	'',	'');

-- 2015-10-15 15:18:38
