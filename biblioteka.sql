CREATE TABLE `ksiazki` (
  `id` mediumint(8) unsigned NOT NULL,
  `tytul` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `autor` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `jezyk` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `rok` year(4) DEFAULT NULL,
  `miejsce` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `wydawnictwo` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `wydanie` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `ISBN` decimal(13,0) unsigned DEFAULT NULL,
  `ISSN` decimal(13,0) unsigned DEFAULT NULL,
  `regal` char(5) COLLATE utf8_polish_ci DEFAULT NULL,
  `polka` decimal(2,0) unsigned DEFAULT NULL,
  `rzad` decimal(2,0) unsigned DEFAULT NULL,
  `wycofana` enum('0','1') CHARACTER SET ascii NOT NULL DEFAULT '0',
  `powod` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ISBN` (`ISBN`),
  KEY `wycofana` (`wycofana`),
  KEY `ISSN` (`ISSN`),
  FULLTEXT KEY `tytul` (`tytul`),
  FULLTEXT KEY `autor` (`autor`),
  FULLTEXT KEY `wydawnictwo` (`wydawnictwo`)
);

CREATE TABLE `pozycz` (
  `id` mediumint(8) unsigned NOT NULL,
  `kto` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `od` int(11) NOT NULL,
  PRIMARY KEY (`id`,`od`)
);

CREATE TABLE `pozycz_historia` (
  `id` mediumint(8) unsigned NOT NULL,
  `kto` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `od` int(11) NOT NULL,
  `do` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`od`),
  KEY `do` (`do`)
);
