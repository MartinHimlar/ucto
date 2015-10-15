-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '' + 00 :00'';
SET foreign_key_checks = 0;
SET sql_mode = '' NO_AUTO_VALUE_ON_ZERO'';

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id`     INT(11)               NOT NULL AUTO_INCREMENT,
  `title`  VARCHAR(255)
           COLLATE utf8_czech_ci NOT NULL,
  `parent` INT(11)                        DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `roles` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_czech_ci;

INSERT INTO `roles` (`id`, `title`, `parent`) VALUES
  (1, ''registrován'', NULL),
  (2, ''Prodejce'', 1),
  (3, ''administrator'', 2);

DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id`      INT(11)               NOT NULL AUTO_INCREMENT,
  `title`   VARCHAR(255)
            COLLATE utf8_czech_ci NOT NULL,
  `content` TEXT
            COLLATE utf8_czech_ci NOT NULL,
  `url`     VARCHAR(255)
            CHARACTER SET utf8
            COLLATE utf8_bin      NOT NULL,
  `default` TINYINT(1)            NOT NULL DEFAULT ''0'',
  `active`  TINYINT(1)            NOT NULL DEFAULT ''0'',
  `map_url` TEXT
            COLLATE utf8_czech_ci,
  `order`   INT(2)                NOT NULL DEFAULT ''0'',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_czech_ci;

INSERT INTO `sites` (`id`, `title`, `content`, `url`, `default`, `active`, `map_url`, `order`) VALUES
  (1, ''O společnosti'', '' < h1 > Dobr & yacute;
den, </h1>\n< DIV >\n<p>v&iacute;
t&aacute;
me V&aacute;
s na str&aacute;
nk&aacute;
ch&nbsp;
&uacute;
četnictv&iacute;
&nbsp;
<strong>Zdeňka Himlarov&aacute;
</strong>, zab&yacute;
vaj&iacute;
c&iacute;
se daňovou a &uacute;
četnickou problematikou.M&aacute;
te li z&aacute;
jem o některou z na&scaron;
ich služeb, či BY jste jen chtěli vědět, co v&scaron;
e děl&aacute;
me, kontaktujte n&aacute;
s pomoc&iacute;
sekce kontakty.</p>\n<p>Děkujeme za projeven&yacute;
z&aacute;
jem a přejeme V&aacute;
m hezk&yacute;
den, </p>\n<p>Zdeňka Himlarov&aacute;
</p>\n</ DIV >'', ''o-spolecnosti'', 1, 0, '''', 0),
(2, ''Kde nás najdete?'', '''', ''kde-nas-najdete'', 1, 0, '''', 1),
(3, ''Kontakty'', ''<h1>Kontakty</h1>\n< DIV >\n<p><strong>Zdeňka Himlarov&aacute;
</strong><br />Stonavsk&aacute;
902/4<br />Horn&iacute;
Such&aacute;<br />73535<br /><br />IČ:46601546<br />DIČ:CZ 5659031620<br /><br />email:himlarova@gmail.com<br />tel:596 410 439<br />mob:+420 774 715 203</p>\n</ DIV >\n<p>&nbsp;
</p>\n<p>&nbsp;
</p>\n<p>&nbsp;
</p>\n<p>&nbsp;
</p>'', ''kontakty'', 1, 0, '''', 2);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`       INT(11)               NOT NULL AUTO_INCREMENT,
  `nickname` VARCHAR(255)
             COLLATE utf8_czech_ci NOT NULL,
  `password` VARCHAR(255)
             COLLATE utf8_czech_ci NOT NULL,
  `public`   TINYINT(4)            NOT NULL DEFAULT ''1'',
  `role_id`  INT(11)               NOT NULL DEFAULT ''1'',
  `email`    VARCHAR(255)
             CHARACTER SET utf8
             COLLATE utf8_bin      NOT NULL,
  `name`     VARCHAR(255)
             COLLATE utf8_czech_ci NOT NULL,
  `surname`  VARCHAR(255)
             COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_czech_ci;

INSERT INTO `users` (`id`, `nickname`, `password`, `public`, `role_id`, `email`, `name`, `surname`) VALUES
  (1, ''admin'', ''$2y$10$V9HcKBgX5SgFyYZiLX9v2.6b46hbjKV9pQ86gjJ / sxeelE2gxwM1i'', 1, 3, ''m.himlar@gmail.com'', '''',
   ''''),
  (3, ''USER'', ''$2y$10$wrN9tjM.ODjLCNZIZWz23uaTSTFycwkYRKUHan9jaxqtaazZkXRzy'', 1, 2, ''himlarova@gmail.com'',
   ''Zdeňka'', ''Himlarová'');

-- 2015-10-15 16:41:23
