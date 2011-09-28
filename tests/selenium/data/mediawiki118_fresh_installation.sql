-- MySQL dump 10.13  Distrib 5.1.41, for Win32 (ia32)
--
-- Host: localhost    Database: test_wiki
-- ------------------------------------------------------
-- Server version	5.1.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mw_archive`
--

DROP TABLE IF EXISTS `mw_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_archive` (
  `ar_namespace` int(11) NOT NULL DEFAULT '0',
  `ar_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `ar_text` mediumblob NOT NULL,
  `ar_comment` tinyblob NOT NULL,
  `ar_user` int(10) unsigned NOT NULL DEFAULT '0',
  `ar_user_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ar_timestamp` binary(14) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `ar_minor_edit` tinyint(4) NOT NULL DEFAULT '0',
  `ar_flags` tinyblob NOT NULL,
  `ar_rev_id` int(10) unsigned DEFAULT NULL,
  `ar_text_id` int(10) unsigned DEFAULT NULL,
  `ar_deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ar_len` int(10) unsigned DEFAULT NULL,
  `ar_page_id` int(10) unsigned DEFAULT NULL,
  `ar_parent_id` int(10) unsigned DEFAULT NULL,
  KEY `name_title_timestamp` (`ar_namespace`,`ar_title`,`ar_timestamp`),
  KEY `usertext_timestamp` (`ar_user_text`,`ar_timestamp`),
  KEY `ar_page_revid` (`ar_namespace`,`ar_title`,`ar_rev_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_archive`
--

LOCK TABLES `mw_archive` WRITE;
/*!40000 ALTER TABLE `mw_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_category`
--

DROP TABLE IF EXISTS `mw_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_category` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `cat_pages` int(11) NOT NULL DEFAULT '0',
  `cat_subcats` int(11) NOT NULL DEFAULT '0',
  `cat_files` int(11) NOT NULL DEFAULT '0',
  `cat_hidden` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_title` (`cat_title`),
  KEY `cat_pages` (`cat_pages`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_category`
--

LOCK TABLES `mw_category` WRITE;
/*!40000 ALTER TABLE `mw_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_categorylinks`
--

DROP TABLE IF EXISTS `mw_categorylinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_categorylinks` (
  `cl_from` int(10) unsigned NOT NULL DEFAULT '0',
  `cl_to` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `cl_sortkey` varbinary(230) NOT NULL DEFAULT '',
  `cl_sortkey_prefix` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `cl_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cl_collation` varbinary(32) NOT NULL DEFAULT '',
  `cl_type` enum('page','subcat','file') NOT NULL DEFAULT 'page',
  UNIQUE KEY `cl_from` (`cl_from`,`cl_to`),
  KEY `cl_sortkey` (`cl_to`,`cl_type`,`cl_sortkey`,`cl_from`),
  KEY `cl_timestamp` (`cl_to`,`cl_timestamp`),
  KEY `cl_collation` (`cl_collation`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_categorylinks`
--

LOCK TABLES `mw_categorylinks` WRITE;
/*!40000 ALTER TABLE `mw_categorylinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_categorylinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_change_tag`
--

DROP TABLE IF EXISTS `mw_change_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_change_tag` (
  `ct_rc_id` int(11) DEFAULT NULL,
  `ct_log_id` int(11) DEFAULT NULL,
  `ct_rev_id` int(11) DEFAULT NULL,
  `ct_tag` varchar(255) NOT NULL,
  `ct_params` blob,
  UNIQUE KEY `change_tag_rc_tag` (`ct_rc_id`,`ct_tag`),
  UNIQUE KEY `change_tag_log_tag` (`ct_log_id`,`ct_tag`),
  UNIQUE KEY `change_tag_rev_tag` (`ct_rev_id`,`ct_tag`),
  KEY `change_tag_tag_id` (`ct_tag`,`ct_rc_id`,`ct_rev_id`,`ct_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_change_tag`
--

LOCK TABLES `mw_change_tag` WRITE;
/*!40000 ALTER TABLE `mw_change_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_change_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_external_user`
--

DROP TABLE IF EXISTS `mw_external_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_external_user` (
  `eu_local_id` int(10) unsigned NOT NULL,
  `eu_external_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`eu_local_id`),
  UNIQUE KEY `eu_external_id` (`eu_external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_external_user`
--

LOCK TABLES `mw_external_user` WRITE;
/*!40000 ALTER TABLE `mw_external_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_external_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_externallinks`
--

DROP TABLE IF EXISTS `mw_externallinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_externallinks` (
  `el_from` int(10) unsigned NOT NULL DEFAULT '0',
  `el_to` blob NOT NULL,
  `el_index` blob NOT NULL,
  KEY `el_from` (`el_from`,`el_to`(40)),
  KEY `el_to` (`el_to`(60),`el_from`),
  KEY `el_index` (`el_index`(60))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_externallinks`
--

LOCK TABLES `mw_externallinks` WRITE;
/*!40000 ALTER TABLE `mw_externallinks` DISABLE KEYS */;
INSERT INTO `mw_externallinks` VALUES (1,'http://meta.wikimedia.org/wiki/Help:Contents','http://org.wikimedia.meta./wiki/Help:Contents');
INSERT INTO `mw_externallinks` VALUES (1,'http://www.mediawiki.org/wiki/Manual:Configuration_settings','http://org.mediawiki.www./wiki/Manual:Configuration_settings');
INSERT INTO `mw_externallinks` VALUES (1,'http://www.mediawiki.org/wiki/Manual:FAQ','http://org.mediawiki.www./wiki/Manual:FAQ');
INSERT INTO `mw_externallinks` VALUES (1,'https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce','https://org.wikimedia.lists./mailman/listinfo/mediawiki-announce');
/*!40000 ALTER TABLE `mw_externallinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_filearchive`
--

DROP TABLE IF EXISTS `mw_filearchive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_filearchive` (
  `fa_id` int(11) NOT NULL AUTO_INCREMENT,
  `fa_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `fa_archive_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT '',
  `fa_storage_group` varbinary(16) DEFAULT NULL,
  `fa_storage_key` varbinary(64) DEFAULT '',
  `fa_deleted_user` int(11) DEFAULT NULL,
  `fa_deleted_timestamp` binary(14) DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `fa_deleted_reason` text,
  `fa_size` int(10) unsigned DEFAULT '0',
  `fa_width` int(11) DEFAULT '0',
  `fa_height` int(11) DEFAULT '0',
  `fa_metadata` mediumblob,
  `fa_bits` int(11) DEFAULT '0',
  `fa_media_type` enum('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE') DEFAULT NULL,
  `fa_major_mime` enum('unknown','application','audio','image','text','video','message','model','multipart') DEFAULT 'unknown',
  `fa_minor_mime` varbinary(100) DEFAULT 'unknown',
  `fa_description` tinyblob,
  `fa_user` int(10) unsigned DEFAULT '0',
  `fa_user_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `fa_timestamp` binary(14) DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `fa_deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fa_id`),
  KEY `fa_name` (`fa_name`,`fa_timestamp`),
  KEY `fa_storage_group` (`fa_storage_group`,`fa_storage_key`),
  KEY `fa_deleted_timestamp` (`fa_deleted_timestamp`),
  KEY `fa_user_timestamp` (`fa_user_text`,`fa_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_filearchive`
--

LOCK TABLES `mw_filearchive` WRITE;
/*!40000 ALTER TABLE `mw_filearchive` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_filearchive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_hitcounter`
--

DROP TABLE IF EXISTS `mw_hitcounter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_hitcounter` (
  `hc_id` int(10) unsigned NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=latin1 MAX_ROWS=25000;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_hitcounter`
--

LOCK TABLES `mw_hitcounter` WRITE;
/*!40000 ALTER TABLE `mw_hitcounter` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_hitcounter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_image`
--

DROP TABLE IF EXISTS `mw_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_image` (
  `img_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `img_size` int(10) unsigned NOT NULL DEFAULT '0',
  `img_width` int(11) NOT NULL DEFAULT '0',
  `img_height` int(11) NOT NULL DEFAULT '0',
  `img_metadata` mediumblob NOT NULL,
  `img_bits` int(11) NOT NULL DEFAULT '0',
  `img_media_type` enum('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE') DEFAULT NULL,
  `img_major_mime` enum('unknown','application','audio','image','text','video','message','model','multipart') NOT NULL DEFAULT 'unknown',
  `img_minor_mime` varbinary(100) NOT NULL DEFAULT 'unknown',
  `img_description` tinyblob NOT NULL,
  `img_user` int(10) unsigned NOT NULL DEFAULT '0',
  `img_user_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `img_timestamp` varbinary(14) NOT NULL DEFAULT '',
  `img_sha1` varbinary(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`img_name`),
  KEY `img_usertext_timestamp` (`img_user_text`,`img_timestamp`),
  KEY `img_size` (`img_size`),
  KEY `img_timestamp` (`img_timestamp`),
  KEY `img_sha1` (`img_sha1`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_image`
--

LOCK TABLES `mw_image` WRITE;
/*!40000 ALTER TABLE `mw_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_imagelinks`
--

DROP TABLE IF EXISTS `mw_imagelinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_imagelinks` (
  `il_from` int(10) unsigned NOT NULL DEFAULT '0',
  `il_to` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  UNIQUE KEY `il_from` (`il_from`,`il_to`),
  UNIQUE KEY `il_to` (`il_to`,`il_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_imagelinks`
--

LOCK TABLES `mw_imagelinks` WRITE;
/*!40000 ALTER TABLE `mw_imagelinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_imagelinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_interwiki`
--

DROP TABLE IF EXISTS `mw_interwiki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_interwiki` (
  `iw_prefix` varchar(32) NOT NULL,
  `iw_url` blob NOT NULL,
  `iw_api` blob NOT NULL,
  `iw_wikiid` varchar(64) NOT NULL,
  `iw_local` tinyint(1) NOT NULL,
  `iw_trans` tinyint(4) NOT NULL DEFAULT '0',
  UNIQUE KEY `iw_prefix` (`iw_prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_interwiki`
--

LOCK TABLES `mw_interwiki` WRITE;
/*!40000 ALTER TABLE `mw_interwiki` DISABLE KEYS */;
INSERT INTO `mw_interwiki` VALUES ('acronym','http://www.acronymfinder.com/af-query.asp?String=exact&Acronym=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('advogato','http://www.advogato.org/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('annotationwiki','http://www.seedwiki.com/page.cfm?wikiid=368&doc=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('arxiv','http://www.arxiv.org/abs/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('c2find','http://c2.com/cgi/wiki?FindPage&value=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('cache','http://www.google.com/search?q=cache:$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('commons','http://commons.wikimedia.org/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('corpknowpedia','http://corpknowpedia.org/wiki/index.php/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('dictionary','http://www.dict.org/bin/Dict?Database=*&Form=Dict1&Strategy=*&Query=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('disinfopedia','http://www.disinfopedia.org/wiki.phtml?title=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('docbook','http://wiki.docbook.org/topic/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('doi','http://dx.doi.org/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('drumcorpswiki','http://www.drumcorpswiki.com/index.php/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('dwjwiki','http://www.suberic.net/cgi-bin/dwj/wiki.cgi?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('elibre','http://enciclopedia.us.es/index.php/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('emacswiki','http://www.emacswiki.org/cgi-bin/wiki.pl?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('foldoc','http://foldoc.org/?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('foxwiki','http://fox.wikis.com/wc.dll?Wiki~$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('freebsdman','http://www.FreeBSD.org/cgi/man.cgi?apropos=1&query=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('gej','http://www.esperanto.de/cgi-bin/aktivikio/wiki.pl?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('gentoo-wiki','http://gentoo-wiki.com/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('google','http://www.google.com/search?q=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('googlegroups','http://groups.google.com/groups?q=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('hammondwiki','http://www.dairiki.org/HammondWiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('hewikisource','http://he.wikisource.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('hrwiki','http://www.hrwiki.org/index.php/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('imdb','http://us.imdb.com/Title?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('jargonfile','http://sunir.org/apps/meta.pl?wiki=JargonFile&redirect=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('jspwiki','http://www.jspwiki.org/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('keiki','http://kei.ki/en/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('kmwiki','http://kmwiki.wikispaces.com/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('linuxwiki','http://linuxwiki.de/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('lojban','http://www.lojban.org/tiki/tiki-index.php?page=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('lqwiki','http://wiki.linuxquestions.org/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('lugkr','http://lug-kr.sourceforge.net/cgi-bin/lugwiki.pl?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('mathsongswiki','http://SeedWiki.com/page.cfm?wikiid=237&doc=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('meatball','http://www.usemod.com/cgi-bin/mb.pl?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('mediawikiwiki','http://www.mediawiki.org/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('mediazilla','https://bugzilla.wikimedia.org/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('memoryalpha','http://www.memory-alpha.org/en/index.php/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('metawiki','http://sunir.org/apps/meta.pl?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('metawikipedia','http://meta.wikimedia.org/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('moinmoin','http://purl.net/wiki/moin/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('mozillawiki','http://wiki.mozilla.org/index.php/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('mw','http://www.mediawiki.org/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('oeis','http://www.research.att.com/cgi-bin/access.cgi/as/njas/sequences/eisA.cgi?Anum=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('openfacts','http://openfacts.berlios.de/index.phtml?title=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('openwiki','http://openwiki.com/?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('pmeg','http://www.bertilow.com/pmeg/$1.php','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('ppr','http://c2.com/cgi/wiki?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('pythoninfo','http://wiki.python.org/moin/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('rfc','http://www.rfc-editor.org/rfc/rfc$1.txt','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('s23wiki','http://is-root.de/wiki/index.php/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('seattlewiki','http://seattle.wikia.com/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('seattlewireless','http://seattlewireless.net/?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('senseislibrary','http://senseis.xmp.net/?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('sourceforge','http://sourceforge.net/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('squeak','http://wiki.squeak.org/squeak/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('susning','http://www.susning.nu/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('svgwiki','http://wiki.svg.org/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('tavi','http://tavi.sourceforge.net/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('tejo','http://www.tejo.org/vikio/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('theopedia','http://www.theopedia.com/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('tmbw','http://www.tmbw.net/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('tmnet','http://www.technomanifestos.net/?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('tmwiki','http://www.EasyTopicMaps.com/?page=$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('twiki','http://twiki.org/cgi-bin/view/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('uea','http://www.tejo.org/uea/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('unreal','http://wiki.beyondunreal.com/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('usemod','http://www.usemod.com/cgi-bin/wiki.pl?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('vinismo','http://vinismo.com/en/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('webseitzwiki','http://webseitz.fluxent.com/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('why','http://clublet.com/c/c/why?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wiki','http://c2.com/cgi/wiki?$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wikia','http://www.wikia.com/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wikibooks','http://en.wikibooks.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wikicities','http://www.wikia.com/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wikif1','http://www.wikif1.org/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wikihow','http://www.wikihow.com/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wikimedia','http://wikimediafoundation.org/wiki/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wikinews','http://en.wikinews.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wikinfo','http://www.wikinfo.org/index.php/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wikipedia','http://en.wikipedia.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wikiquote','http://en.wikiquote.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wikisource','http://wikisource.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wikispecies','http://species.wikimedia.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wikitravel','http://wikitravel.org/en/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('wikiversity','http://en.wikiversity.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wikt','http://en.wiktionary.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wiktionary','http://en.wiktionary.org/wiki/$1','','',1,0);
INSERT INTO `mw_interwiki` VALUES ('wlug','http://www.wlug.org.nz/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('zwiki','http://zwiki.org/$1','','',0,0);
INSERT INTO `mw_interwiki` VALUES ('zzz wiki','http://wiki.zzz.ee/index.php/$1','','',0,0);
/*!40000 ALTER TABLE `mw_interwiki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_ipblocks`
--

DROP TABLE IF EXISTS `mw_ipblocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_ipblocks` (
  `ipb_id` int(11) NOT NULL AUTO_INCREMENT,
  `ipb_address` tinyblob NOT NULL,
  `ipb_user` int(10) unsigned NOT NULL DEFAULT '0',
  `ipb_by` int(10) unsigned NOT NULL DEFAULT '0',
  `ipb_by_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `ipb_reason` tinyblob NOT NULL,
  `ipb_timestamp` binary(14) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `ipb_auto` tinyint(1) NOT NULL DEFAULT '0',
  `ipb_anon_only` tinyint(1) NOT NULL DEFAULT '0',
  `ipb_create_account` tinyint(1) NOT NULL DEFAULT '1',
  `ipb_enable_autoblock` tinyint(1) NOT NULL DEFAULT '1',
  `ipb_expiry` varbinary(14) NOT NULL DEFAULT '',
  `ipb_range_start` tinyblob NOT NULL,
  `ipb_range_end` tinyblob NOT NULL,
  `ipb_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `ipb_block_email` tinyint(1) NOT NULL DEFAULT '0',
  `ipb_allow_usertalk` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ipb_id`),
  UNIQUE KEY `ipb_address` (`ipb_address`(255),`ipb_user`,`ipb_auto`,`ipb_anon_only`),
  KEY `ipb_user` (`ipb_user`),
  KEY `ipb_range` (`ipb_range_start`(8),`ipb_range_end`(8)),
  KEY `ipb_timestamp` (`ipb_timestamp`),
  KEY `ipb_expiry` (`ipb_expiry`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_ipblocks`
--

LOCK TABLES `mw_ipblocks` WRITE;
/*!40000 ALTER TABLE `mw_ipblocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_ipblocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_iwlinks`
--

DROP TABLE IF EXISTS `mw_iwlinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_iwlinks` (
  `iwl_from` int(10) unsigned NOT NULL DEFAULT '0',
  `iwl_prefix` varbinary(20) NOT NULL DEFAULT '',
  `iwl_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  UNIQUE KEY `iwl_from` (`iwl_from`,`iwl_prefix`,`iwl_title`),
  UNIQUE KEY `iwl_prefix_title_from` (`iwl_prefix`,`iwl_title`,`iwl_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_iwlinks`
--

LOCK TABLES `mw_iwlinks` WRITE;
/*!40000 ALTER TABLE `mw_iwlinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_iwlinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_job`
--

DROP TABLE IF EXISTS `mw_job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_job` (
  `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_cmd` varbinary(60) NOT NULL DEFAULT '',
  `job_namespace` int(11) NOT NULL,
  `job_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `job_params` blob NOT NULL,
  PRIMARY KEY (`job_id`),
  KEY `job_cmd` (`job_cmd`,`job_namespace`,`job_title`,`job_params`(128))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_job`
--

LOCK TABLES `mw_job` WRITE;
/*!40000 ALTER TABLE `mw_job` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_job` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_l10n_cache`
--

DROP TABLE IF EXISTS `mw_l10n_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_l10n_cache` (
  `lc_lang` varbinary(32) NOT NULL,
  `lc_key` varchar(255) NOT NULL,
  `lc_value` mediumblob NOT NULL,
  KEY `lc_lang_key` (`lc_lang`,`lc_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `mw_langlinks`
--

DROP TABLE IF EXISTS `mw_langlinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_langlinks` (
  `ll_from` int(10) unsigned NOT NULL DEFAULT '0',
  `ll_lang` varbinary(20) NOT NULL DEFAULT '',
  `ll_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  UNIQUE KEY `ll_from` (`ll_from`,`ll_lang`),
  KEY `ll_lang` (`ll_lang`,`ll_title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_langlinks`
--

LOCK TABLES `mw_langlinks` WRITE;
/*!40000 ALTER TABLE `mw_langlinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_langlinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_log_search`
--

DROP TABLE IF EXISTS `mw_log_search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_log_search` (
  `ls_field` varbinary(32) NOT NULL,
  `ls_value` varchar(255) NOT NULL,
  `ls_log_id` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `ls_field_val` (`ls_field`,`ls_value`,`ls_log_id`),
  KEY `ls_log_id` (`ls_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_log_search`
--

LOCK TABLES `mw_log_search` WRITE;
/*!40000 ALTER TABLE `mw_log_search` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_log_search` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_logging`
--

DROP TABLE IF EXISTS `mw_logging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_logging` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_type` varbinary(32) NOT NULL DEFAULT '',
  `log_action` varbinary(32) NOT NULL DEFAULT '',
  `log_timestamp` binary(14) NOT NULL DEFAULT '19700101000000',
  `log_user` int(10) unsigned NOT NULL DEFAULT '0',
  `log_user_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `log_namespace` int(11) NOT NULL DEFAULT '0',
  `log_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `log_page` int(10) unsigned DEFAULT NULL,
  `log_comment` varchar(255) NOT NULL DEFAULT '',
  `log_params` blob NOT NULL,
  `log_deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `type_time` (`log_type`,`log_timestamp`),
  KEY `user_time` (`log_user`,`log_timestamp`),
  KEY `page_time` (`log_namespace`,`log_title`,`log_timestamp`),
  KEY `times` (`log_timestamp`),
  KEY `log_user_type_time` (`log_user`,`log_type`,`log_timestamp`),
  KEY `log_page_id_time` (`log_page`,`log_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_logging`
--

LOCK TABLES `mw_logging` WRITE;
/*!40000 ALTER TABLE `mw_logging` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_logging` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_math`
--

DROP TABLE IF EXISTS `mw_math`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_math` (
  `math_inputhash` varbinary(16) NOT NULL,
  `math_outputhash` varbinary(16) NOT NULL,
  `math_html_conservativeness` tinyint(4) NOT NULL,
  `math_html` text,
  `math_mathml` text,
  UNIQUE KEY `math_inputhash` (`math_inputhash`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_math`
--

LOCK TABLES `mw_math` WRITE;
/*!40000 ALTER TABLE `mw_math` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_math` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_module_deps`
--

DROP TABLE IF EXISTS `mw_module_deps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_module_deps` (
  `md_module` varbinary(255) NOT NULL,
  `md_skin` varbinary(32) NOT NULL,
  `md_deps` mediumblob NOT NULL,
  UNIQUE KEY `md_module_skin` (`md_module`,`md_skin`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_module_deps`
--

LOCK TABLES `mw_module_deps` WRITE;
/*!40000 ALTER TABLE `mw_module_deps` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_module_deps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_msg_resource`
--

DROP TABLE IF EXISTS `mw_msg_resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_msg_resource` (
  `mr_resource` varbinary(255) NOT NULL,
  `mr_lang` varbinary(32) NOT NULL,
  `mr_blob` mediumblob NOT NULL,
  `mr_timestamp` binary(14) NOT NULL,
  UNIQUE KEY `mr_resource_lang` (`mr_resource`,`mr_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_msg_resource`
--

LOCK TABLES `mw_msg_resource` WRITE;
/*!40000 ALTER TABLE `mw_msg_resource` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_msg_resource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_msg_resource_links`
--

DROP TABLE IF EXISTS `mw_msg_resource_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_msg_resource_links` (
  `mrl_resource` varbinary(255) NOT NULL,
  `mrl_message` varbinary(255) NOT NULL,
  UNIQUE KEY `mrl_message_resource` (`mrl_message`,`mrl_resource`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_msg_resource_links`
--

LOCK TABLES `mw_msg_resource_links` WRITE;
/*!40000 ALTER TABLE `mw_msg_resource_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_msg_resource_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_objectcache`
--

DROP TABLE IF EXISTS `mw_objectcache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_objectcache` (
  `keyname` varbinary(255) NOT NULL DEFAULT '',
  `value` mediumblob,
  `exptime` datetime DEFAULT NULL,
  PRIMARY KEY (`keyname`),
  KEY `exptime` (`exptime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_objectcache`
--

LOCK TABLES `mw_objectcache` WRITE;
/*!40000 ALTER TABLE `mw_objectcache` DISABLE KEYS */;
INSERT INTO `mw_objectcache` VALUES ('test_wiki-mw_:messages:en','K�2��.�2�R\ns\r\n���S�δ2��\0','2010-12-31 13:16:31');
INSERT INTO `mw_objectcache` VALUES ('test_wiki-mw_:pcache:idhash:1-0!*!*!!en!*','�V[o�F�g~�����B��P��m)$TK�U��\0�����8$��9�a\rI�l��X>��ܹ�q�Sa,��ʕ�x��?[~ʃ|�.Z��$�8Y�\'�I�Y�K��-\04�U����J�\'&�uB)�:I������E�m�sk`�`k�Q�v��a���\rZ��t�0����+P%GE�ـ�JX;\n-s�P�@�B�b���8~�첒$��ea�υ��f��+���0[,�F�x��d�\'�z�0��BJ���=���J��c�\\��:��&B��T��\'��C��Fdÿ׆Fq����Gd����%8G�0��A�I��;		Ԙ`�7�5�LI\r��(���{�c�����g+��8Qr�&�ͦ��A)�V��ЕPT��\\UƧtn��Z�e�SfJZ(V�P�}����0��O�N	�=j�\\H��y�\\�U[h]T:���bd��u��+�j%\'�6k��f:E�;�@Yך��4���Ȁ��q���Z�º6<b�3��TU(d��,\n���Y|�e�\'�5��T�fU�8}�\"��m���/���}Uk�9o��;����|*R?�n���	3d��g1��y�\\f8gk�����w��=��:/Y7���ۋ�^<�Ō����v#���i����C�#��6\Z�.0�Ua$4�=\Z���;��4����Y=���5���:kpΐq��Ŧ4��X���C��qYߵ-��ǈ�����D�f�����:����(3t��14C��J�#����������WXT���Δy:�^�6�v�7����I�U�Ee��(�p0��ga�6Mj��Sc�,ѫ@��ޅ+R����A��xХ\'6���utǷbۛ��`j�8ؚ�G�IC<KS��5�|�krJ\ry�\\b3xP�����ua�@����$SS�`��tQ.gwW��\r@\'���w�� ��xZ(�>5{����dw�>�=J)\r�6t ��X����M���B�\n�ŖT����b>�qg����7��z�n7��vwr�-%u�-Qi�iX1��Ne���A#�v��ӧ�3��?','2010-12-31 13:16:31');
INSERT INTO `mw_objectcache` VALUES ('test_wiki-mw_:pcache:idoptions:1','E���@D��`�\"v�ƣ��Wh��,�b�!�⭙7�L+�|}�t��I�$�<���F\rpSl�4����OJN`\r\Z����ծ���)��PY��$�K����գ9�Vjp72��E���c�Wp�2��cVxu7��	����p#�r=.���[>y)Zp��','2010-12-31 13:16:31');
INSERT INTO `mw_objectcache` VALUES ('test_wiki-mw_:resourceloader:filter:minify-css:3832ee25d9c44988461f5f339b9b6a48','+�26�Rr�MM�LTH�ɩV\0�Z(��(3�(R�d\r\0','2038-01-19 03:14:07');
INSERT INTO `mw_objectcache` VALUES ('test_wiki-mw_:resourceloader:filter:minify-css:aa0df16258ad99a1d249e796b5067ed9','+�2��Rr�MM�LTH�ɩN��K-�Q.,�L�NJ,R\0��s򋬔�\r���V�\Z\0','2038-01-19 03:14:07');
INSERT INTO `mw_objectcache` VALUES ('test_wiki-mw_:resourceloader:filter:minify-js:22814eeadc9cf0a9ebcd844e14198e66','m��r�0��y����r�&Qޡמ!\n�qQ�Xq;}���$��ވ� �c!]]].o5S�\n�)Fq��L^��?�s�F�!�O�M\\�������\0���N��Ɂ���լ����:��-�j��F��{ۅ�G�\"i�� \Z�6�K����!��Y]=�F[�~竍���䶃����`��9N�Ǵ���@�K��|z�?1�A��@J#_ԁ�7\'�l�1)J�͵�).�3z�f�T�A���Hњ�[#)�BzRA�7֌��\"T�*~SW���/P���B�Ŏ;\Z�ay�6����+U��?.$�6��-uT�v@h��s�&�����Nإb�fJ�~�]6��p��/q)�>�E�1��͔A\ne�L�g\ZE�`cW�����`fJ�E�a��>��b\n�ӑd�.u�do��[�\nt��b�+���l\Z?X*��Y�(�օ;�L�Jqťɝ���d$�\"�WzG�-@b~+�#�kǞَ�Ƃ~������P)B	����q�Җ2���r�Rl����`z	�4�����ÝX�m�;�X݁t;r.�sA��R��y)�kA�\nR�JT��J�U��*�W��_ߟ�4@�vt��f���>����x���','2038-01-19 03:14:07');
INSERT INTO `mw_objectcache` VALUES ('test_wiki-mw_:resourceloader:filter:minify-js:dd9440c19c575629ac5ec90e489cf62e','+�21�R���Ԕ�����L���Ĕ�\"��ĒT�j��̒T%+���ĔJ�ZMk.%k\0','2038-01-19 03:14:07');
/*!40000 ALTER TABLE `mw_objectcache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_oldimage`
--

DROP TABLE IF EXISTS `mw_oldimage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_oldimage` (
  `oi_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `oi_archive_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `oi_size` int(10) unsigned NOT NULL DEFAULT '0',
  `oi_width` int(11) NOT NULL DEFAULT '0',
  `oi_height` int(11) NOT NULL DEFAULT '0',
  `oi_bits` int(11) NOT NULL DEFAULT '0',
  `oi_description` tinyblob NOT NULL,
  `oi_user` int(10) unsigned NOT NULL DEFAULT '0',
  `oi_user_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `oi_timestamp` binary(14) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `oi_metadata` mediumblob NOT NULL,
  `oi_media_type` enum('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE') DEFAULT NULL,
  `oi_major_mime` enum('unknown','application','audio','image','text','video','message','model','multipart') NOT NULL DEFAULT 'unknown',
  `oi_minor_mime` varbinary(100) NOT NULL DEFAULT 'unknown',
  `oi_deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `oi_sha1` varbinary(32) NOT NULL DEFAULT '',
  KEY `oi_usertext_timestamp` (`oi_user_text`,`oi_timestamp`),
  KEY `oi_name_timestamp` (`oi_name`,`oi_timestamp`),
  KEY `oi_name_archive_name` (`oi_name`,`oi_archive_name`(14)),
  KEY `oi_sha1` (`oi_sha1`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_oldimage`
--

LOCK TABLES `mw_oldimage` WRITE;
/*!40000 ALTER TABLE `mw_oldimage` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_oldimage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_page`
--

DROP TABLE IF EXISTS `mw_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_page` (
  `page_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_namespace` int(11) NOT NULL,
  `page_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `page_restrictions` tinyblob NOT NULL,
  `page_counter` bigint(20) unsigned NOT NULL DEFAULT '0',
  `page_is_redirect` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `page_is_new` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `page_random` double unsigned NOT NULL,
  `page_touched` binary(14) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `page_latest` int(10) unsigned NOT NULL,
  `page_len` int(10) unsigned NOT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `name_title` (`page_namespace`,`page_title`),
  KEY `page_random` (`page_random`),
  KEY `page_len` (`page_len`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_page`
--

LOCK TABLES `mw_page` WRITE;
/*!40000 ALTER TABLE `mw_page` DISABLE KEYS */;
INSERT INTO `mw_page` VALUES (1,0,'Main_Page','',1,0,1,0.334989576352,'20101230131547',1,438);
/*!40000 ALTER TABLE `mw_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_page_props`
--

DROP TABLE IF EXISTS `mw_page_props`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_page_props` (
  `pp_page` int(11) NOT NULL,
  `pp_propname` varbinary(60) NOT NULL,
  `pp_value` blob NOT NULL,
  UNIQUE KEY `pp_page_propname` (`pp_page`,`pp_propname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_page_props`
--

LOCK TABLES `mw_page_props` WRITE;
/*!40000 ALTER TABLE `mw_page_props` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_page_props` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_page_restrictions`
--

DROP TABLE IF EXISTS `mw_page_restrictions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_page_restrictions` (
  `pr_page` int(11) NOT NULL,
  `pr_type` varbinary(60) NOT NULL,
  `pr_level` varbinary(60) NOT NULL,
  `pr_cascade` tinyint(4) NOT NULL,
  `pr_user` int(11) DEFAULT NULL,
  `pr_expiry` varbinary(14) DEFAULT NULL,
  `pr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pr_id`),
  UNIQUE KEY `pr_pagetype` (`pr_page`,`pr_type`),
  KEY `pr_typelevel` (`pr_type`,`pr_level`),
  KEY `pr_level` (`pr_level`),
  KEY `pr_cascade` (`pr_cascade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_page_restrictions`
--

LOCK TABLES `mw_page_restrictions` WRITE;
/*!40000 ALTER TABLE `mw_page_restrictions` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_page_restrictions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_pagelinks`
--

DROP TABLE IF EXISTS `mw_pagelinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_pagelinks` (
  `pl_from` int(10) unsigned NOT NULL DEFAULT '0',
  `pl_namespace` int(11) NOT NULL DEFAULT '0',
  `pl_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  UNIQUE KEY `pl_from` (`pl_from`,`pl_namespace`,`pl_title`),
  UNIQUE KEY `pl_namespace` (`pl_namespace`,`pl_title`,`pl_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_pagelinks`
--

LOCK TABLES `mw_pagelinks` WRITE;
/*!40000 ALTER TABLE `mw_pagelinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_pagelinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_protected_titles`
--

DROP TABLE IF EXISTS `mw_protected_titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_protected_titles` (
  `pt_namespace` int(11) NOT NULL,
  `pt_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `pt_user` int(10) unsigned NOT NULL,
  `pt_reason` tinyblob,
  `pt_timestamp` binary(14) NOT NULL,
  `pt_expiry` varbinary(14) NOT NULL DEFAULT '',
  `pt_create_perm` varbinary(60) NOT NULL,
  UNIQUE KEY `pt_namespace_title` (`pt_namespace`,`pt_title`),
  KEY `pt_timestamp` (`pt_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_protected_titles`
--

LOCK TABLES `mw_protected_titles` WRITE;
/*!40000 ALTER TABLE `mw_protected_titles` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_protected_titles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_querycache`
--

DROP TABLE IF EXISTS `mw_querycache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_querycache` (
  `qc_type` varbinary(32) NOT NULL,
  `qc_value` int(10) unsigned NOT NULL DEFAULT '0',
  `qc_namespace` int(11) NOT NULL DEFAULT '0',
  `qc_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  KEY `qc_type` (`qc_type`,`qc_value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_querycache`
--

LOCK TABLES `mw_querycache` WRITE;
/*!40000 ALTER TABLE `mw_querycache` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_querycache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_querycache_info`
--

DROP TABLE IF EXISTS `mw_querycache_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_querycache_info` (
  `qci_type` varbinary(32) NOT NULL DEFAULT '',
  `qci_timestamp` binary(14) NOT NULL DEFAULT '19700101000000',
  UNIQUE KEY `qci_type` (`qci_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_querycache_info`
--

LOCK TABLES `mw_querycache_info` WRITE;
/*!40000 ALTER TABLE `mw_querycache_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_querycache_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_querycachetwo`
--

DROP TABLE IF EXISTS `mw_querycachetwo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_querycachetwo` (
  `qcc_type` varbinary(32) NOT NULL,
  `qcc_value` int(10) unsigned NOT NULL DEFAULT '0',
  `qcc_namespace` int(11) NOT NULL DEFAULT '0',
  `qcc_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `qcc_namespacetwo` int(11) NOT NULL DEFAULT '0',
  `qcc_titletwo` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  KEY `qcc_type` (`qcc_type`,`qcc_value`),
  KEY `qcc_title` (`qcc_type`,`qcc_namespace`,`qcc_title`),
  KEY `qcc_titletwo` (`qcc_type`,`qcc_namespacetwo`,`qcc_titletwo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_querycachetwo`
--

LOCK TABLES `mw_querycachetwo` WRITE;
/*!40000 ALTER TABLE `mw_querycachetwo` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_querycachetwo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_recentchanges`
--

DROP TABLE IF EXISTS `mw_recentchanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_recentchanges` (
  `rc_id` int(11) NOT NULL AUTO_INCREMENT,
  `rc_timestamp` varbinary(14) NOT NULL DEFAULT '',
  `rc_cur_time` varbinary(14) NOT NULL DEFAULT '',
  `rc_user` int(10) unsigned NOT NULL DEFAULT '0',
  `rc_user_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `rc_namespace` int(11) NOT NULL DEFAULT '0',
  `rc_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `rc_comment` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `rc_minor` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rc_bot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rc_new` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rc_cur_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rc_this_oldid` int(10) unsigned NOT NULL DEFAULT '0',
  `rc_last_oldid` int(10) unsigned NOT NULL DEFAULT '0',
  `rc_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rc_moved_to_ns` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rc_moved_to_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `rc_patrolled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rc_ip` varbinary(40) NOT NULL DEFAULT '',
  `rc_old_len` int(11) DEFAULT NULL,
  `rc_new_len` int(11) DEFAULT NULL,
  `rc_deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rc_logid` int(10) unsigned NOT NULL DEFAULT '0',
  `rc_log_type` varbinary(255) DEFAULT NULL,
  `rc_log_action` varbinary(255) DEFAULT NULL,
  `rc_params` blob,
  PRIMARY KEY (`rc_id`),
  KEY `rc_timestamp` (`rc_timestamp`),
  KEY `rc_namespace_title` (`rc_namespace`,`rc_title`),
  KEY `rc_cur_id` (`rc_cur_id`),
  KEY `new_name_timestamp` (`rc_new`,`rc_namespace`,`rc_timestamp`),
  KEY `rc_ip` (`rc_ip`),
  KEY `rc_ns_usertext` (`rc_namespace`,`rc_user_text`),
  KEY `rc_user_text` (`rc_user_text`,`rc_timestamp`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_recentchanges`
--

LOCK TABLES `mw_recentchanges` WRITE;
/*!40000 ALTER TABLE `mw_recentchanges` DISABLE KEYS */;
INSERT INTO `mw_recentchanges` VALUES (1,'20101230131547','20101230131547',0,'MediaWiki Default',0,'Main_Page','',0,0,1,1,1,0,1,0,'',0,'::1',0,438,0,0,NULL,'','');
/*!40000 ALTER TABLE `mw_recentchanges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_redirect`
--

DROP TABLE IF EXISTS `mw_redirect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_redirect` (
  `rd_from` int(10) unsigned NOT NULL DEFAULT '0',
  `rd_namespace` int(11) NOT NULL DEFAULT '0',
  `rd_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `rd_interwiki` varchar(32) DEFAULT NULL,
  `rd_fragment` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`rd_from`),
  KEY `rd_ns_title` (`rd_namespace`,`rd_title`,`rd_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_redirect`
--

LOCK TABLES `mw_redirect` WRITE;
/*!40000 ALTER TABLE `mw_redirect` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_redirect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_revision`
--

DROP TABLE IF EXISTS `mw_revision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_revision` (
  `rev_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rev_page` int(10) unsigned NOT NULL,
  `rev_text_id` int(10) unsigned NOT NULL,
  `rev_comment` tinyblob NOT NULL,
  `rev_user` int(10) unsigned NOT NULL DEFAULT '0',
  `rev_user_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `rev_timestamp` binary(14) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `rev_minor_edit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rev_deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rev_len` int(10) unsigned DEFAULT NULL,
  `rev_parent_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`rev_id`),
  UNIQUE KEY `rev_page_id` (`rev_page`,`rev_id`),
  KEY `rev_timestamp` (`rev_timestamp`),
  KEY `page_timestamp` (`rev_page`,`rev_timestamp`),
  KEY `user_timestamp` (`rev_user`,`rev_timestamp`),
  KEY `usertext_timestamp` (`rev_user_text`,`rev_timestamp`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_revision`
--

LOCK TABLES `mw_revision` WRITE;
/*!40000 ALTER TABLE `mw_revision` DISABLE KEYS */;
INSERT INTO `mw_revision` VALUES (1,1,1,'',0,'MediaWiki Default','20101230131547',0,0,438,0);
/*!40000 ALTER TABLE `mw_revision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_searchindex`
--

DROP TABLE IF EXISTS `mw_searchindex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_searchindex` (
  `si_page` int(10) unsigned NOT NULL,
  `si_title` varchar(255) NOT NULL DEFAULT '',
  `si_text` mediumtext NOT NULL,
  UNIQUE KEY `si_page` (`si_page`),
  FULLTEXT KEY `si_title` (`si_title`),
  FULLTEXT KEY `si_text` (`si_text`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_searchindex`
--

LOCK TABLES `mw_searchindex` WRITE;
/*!40000 ALTER TABLE `mw_searchindex` DISABLE KEYS */;
INSERT INTO `mw_searchindex` VALUES (1,'main page','  mediawiki hasu800 been successfully installed.  consult theu800 user user\'su800 guide foru800 information onu800 using theu800 wiki software. getting started getting started getting started configuration settings list mediawiki faqu800 mediawiki release mailing list ');
/*!40000 ALTER TABLE `mw_searchindex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_site_stats`
--

DROP TABLE IF EXISTS `mw_site_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_site_stats` (
  `ss_row_id` int(10) unsigned NOT NULL,
  `ss_total_views` bigint(20) unsigned DEFAULT '0',
  `ss_total_edits` bigint(20) unsigned DEFAULT '0',
  `ss_good_articles` bigint(20) unsigned DEFAULT '0',
  `ss_total_pages` bigint(20) DEFAULT '-1',
  `ss_users` bigint(20) DEFAULT '-1',
  `ss_active_users` bigint(20) DEFAULT '-1',
  `ss_admins` int(11) DEFAULT '-1',
  `ss_images` int(11) DEFAULT '0',
  UNIQUE KEY `ss_row_id` (`ss_row_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_site_stats`
--

LOCK TABLES `mw_site_stats` WRITE;
/*!40000 ALTER TABLE `mw_site_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_site_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_tag_summary`
--

DROP TABLE IF EXISTS `mw_tag_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_tag_summary` (
  `ts_rc_id` int(11) DEFAULT NULL,
  `ts_log_id` int(11) DEFAULT NULL,
  `ts_rev_id` int(11) DEFAULT NULL,
  `ts_tags` blob NOT NULL,
  UNIQUE KEY `tag_summary_rc_id` (`ts_rc_id`),
  UNIQUE KEY `tag_summary_log_id` (`ts_log_id`),
  UNIQUE KEY `tag_summary_rev_id` (`ts_rev_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_tag_summary`
--

LOCK TABLES `mw_tag_summary` WRITE;
/*!40000 ALTER TABLE `mw_tag_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_tag_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_templatelinks`
--

DROP TABLE IF EXISTS `mw_templatelinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_templatelinks` (
  `tl_from` int(10) unsigned NOT NULL DEFAULT '0',
  `tl_namespace` int(11) NOT NULL DEFAULT '0',
  `tl_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  UNIQUE KEY `tl_from` (`tl_from`,`tl_namespace`,`tl_title`),
  UNIQUE KEY `tl_namespace` (`tl_namespace`,`tl_title`,`tl_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_templatelinks`
--

LOCK TABLES `mw_templatelinks` WRITE;
/*!40000 ALTER TABLE `mw_templatelinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_templatelinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_text`
--

DROP TABLE IF EXISTS `mw_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_text` (
  `old_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_text` mediumblob NOT NULL,
  `old_flags` tinyblob NOT NULL,
  PRIMARY KEY (`old_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 MAX_ROWS=10000000 AVG_ROW_LENGTH=10240;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_text`
--

LOCK TABLES `mw_text` WRITE;
/*!40000 ALTER TABLE `mw_text` DISABLE KEYS */;
INSERT INTO `mw_text` VALUES (1,'\'\'\'MediaWiki has been successfully installed.\'\'\'\n\nConsult the [http://meta.wikimedia.org/wiki/Help:Contents User\'s Guide] for information on using the wiki software.\n\n== Getting started ==\n* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]\n* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]\n* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]','utf-8');
/*!40000 ALTER TABLE `mw_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_trackbacks`
--

DROP TABLE IF EXISTS `mw_trackbacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_trackbacks` (
  `tb_id` int(11) NOT NULL AUTO_INCREMENT,
  `tb_page` int(11) DEFAULT NULL,
  `tb_title` varchar(255) NOT NULL,
  `tb_url` blob NOT NULL,
  `tb_ex` text,
  `tb_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tb_id`),
  KEY `tb_page` (`tb_page`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_trackbacks`
--

LOCK TABLES `mw_trackbacks` WRITE;
/*!40000 ALTER TABLE `mw_trackbacks` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_trackbacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_transcache`
--

DROP TABLE IF EXISTS `mw_transcache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_transcache` (
  `tc_url` varbinary(255) NOT NULL,
  `tc_contents` text,
  `tc_time` binary(14) NOT NULL,
  UNIQUE KEY `tc_url_idx` (`tc_url`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_transcache`
--

LOCK TABLES `mw_transcache` WRITE;
/*!40000 ALTER TABLE `mw_transcache` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_transcache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_updatelog`
--

DROP TABLE IF EXISTS `mw_updatelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_updatelog` (
  `ul_key` varchar(255) NOT NULL,
  `ul_value` blob,
  PRIMARY KEY (`ul_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_updatelog`
--

LOCK TABLES `mw_updatelog` WRITE;
/*!40000 ALTER TABLE `mw_updatelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_updatelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_user`
--

DROP TABLE IF EXISTS `mw_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `user_real_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `user_password` tinyblob NOT NULL,
  `user_newpassword` tinyblob NOT NULL,
  `user_newpass_time` binary(14) DEFAULT NULL,
  `user_email` tinytext NOT NULL,
  `user_touched` binary(14) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `user_token` binary(32) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `user_email_authenticated` binary(14) DEFAULT NULL,
  `user_email_token` binary(32) DEFAULT NULL,
  `user_email_token_expires` binary(14) DEFAULT NULL,
  `user_registration` binary(14) DEFAULT NULL,
  `user_editcount` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `user_email_token` (`user_email_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_user`
--

LOCK TABLES `mw_user` WRITE;
/*!40000 ALTER TABLE `mw_user` DISABLE KEYS */;
INSERT INTO `mw_user` VALUES (1,'WikiSysop','',':B:b1373470:f7e87db0c9596055f39a1225b0c31508','',NULL,'','','20101230131552','de4ddde7c4eef6e3609f4287324a0a18',NULL,'\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',NULL,'20101230131547',0);
/*!40000 ALTER TABLE `mw_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_user_groups`
--

DROP TABLE IF EXISTS `mw_user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_user_groups` (
  `ug_user` int(10) unsigned NOT NULL DEFAULT '0',
  `ug_group` varbinary(16) NOT NULL DEFAULT '',
  UNIQUE KEY `ug_user_group` (`ug_user`,`ug_group`),
  KEY `ug_group` (`ug_group`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_user_groups`
--

LOCK TABLES `mw_user_groups` WRITE;
/*!40000 ALTER TABLE `mw_user_groups` DISABLE KEYS */;
INSERT INTO `mw_user_groups` VALUES (1,'bureaucrat');
INSERT INTO `mw_user_groups` VALUES (1,'sysop');
/*!40000 ALTER TABLE `mw_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_user_newtalk`
--

DROP TABLE IF EXISTS `mw_user_newtalk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_user_newtalk` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_ip` varbinary(40) NOT NULL DEFAULT '',
  `user_last_timestamp` binary(14) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  KEY `user_id` (`user_id`),
  KEY `user_ip` (`user_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_user_newtalk`
--

LOCK TABLES `mw_user_newtalk` WRITE;
/*!40000 ALTER TABLE `mw_user_newtalk` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_user_newtalk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_user_properties`
--

DROP TABLE IF EXISTS `mw_user_properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_user_properties` (
  `up_user` int(11) NOT NULL,
  `up_property` varbinary(32) NOT NULL,
  `up_value` blob,
  UNIQUE KEY `user_properties_user_property` (`up_user`,`up_property`),
  KEY `user_properties_property` (`up_property`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_user_properties`
--

LOCK TABLES `mw_user_properties` WRITE;
/*!40000 ALTER TABLE `mw_user_properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_user_properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_valid_tag`
--

DROP TABLE IF EXISTS `mw_valid_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_valid_tag` (
  `vt_tag` varchar(255) NOT NULL,
  PRIMARY KEY (`vt_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_valid_tag`
--

LOCK TABLES `mw_valid_tag` WRITE;
/*!40000 ALTER TABLE `mw_valid_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_valid_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mw_watchlist`
--

DROP TABLE IF EXISTS `mw_watchlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mw_watchlist` (
  `wl_user` int(10) unsigned NOT NULL,
  `wl_namespace` int(11) NOT NULL DEFAULT '0',
  `wl_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `wl_notificationtimestamp` varbinary(14) DEFAULT NULL,
  UNIQUE KEY `wl_user` (`wl_user`,`wl_namespace`,`wl_title`),
  KEY `namespace_title` (`wl_namespace`,`wl_title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_watchlist`
--

LOCK TABLES `mw_watchlist` WRITE;
/*!40000 ALTER TABLE `mw_watchlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_watchlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-12-31  1:20:11
