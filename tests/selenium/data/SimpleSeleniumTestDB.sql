-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: test_wiki
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.7

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
  KEY `ar_revid` (`ar_rev_id`)
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
INSERT INTO `mw_externallinks` VALUES (1,'http://meta.wikimedia.org/wiki/Help:Contents','http://org.wikimedia.meta./wiki/Help:Contents'),(1,'http://www.mediawiki.org/wiki/Manual:Configuration_settings','http://org.mediawiki.www./wiki/Manual:Configuration_settings'),(1,'http://www.mediawiki.org/wiki/Manual:FAQ','http://org.mediawiki.www./wiki/Manual:FAQ'),(1,'https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce','https://org.wikimedia.lists./mailman/listinfo/mediawiki-announce');
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
INSERT INTO `mw_interwiki` VALUES ('acronym','http://www.acronymfinder.com/af-query.asp?String=exact&Acronym=$1','','',0,0),('advogato','http://www.advogato.org/$1','','',0,0),('annotationwiki','http://www.seedwiki.com/page.cfm?wikiid=368&doc=$1','','',0,0),('arxiv','http://www.arxiv.org/abs/$1','','',0,0),('c2find','http://c2.com/cgi/wiki?FindPage&value=$1','','',0,0),('cache','http://www.google.com/search?q=cache:$1','','',0,0),('commons','http://commons.wikimedia.org/wiki/$1','','',0,0),('corpknowpedia','http://corpknowpedia.org/wiki/index.php/$1','','',0,0),('dictionary','http://www.dict.org/bin/Dict?Database=*&Form=Dict1&Strategy=*&Query=$1','','',0,0),('disinfopedia','http://www.disinfopedia.org/wiki.phtml?title=$1','','',0,0),('docbook','http://wiki.docbook.org/topic/$1','','',0,0),('doi','http://dx.doi.org/$1','','',0,0),('drumcorpswiki','http://www.drumcorpswiki.com/index.php/$1','','',0,0),('dwjwiki','http://www.suberic.net/cgi-bin/dwj/wiki.cgi?$1','','',0,0),('elibre','http://enciclopedia.us.es/index.php/$1','','',0,0),('emacswiki','http://www.emacswiki.org/cgi-bin/wiki.pl?$1','','',0,0),('foldoc','http://foldoc.org/?$1','','',0,0),('foxwiki','http://fox.wikis.com/wc.dll?Wiki~$1','','',0,0),('freebsdman','http://www.FreeBSD.org/cgi/man.cgi?apropos=1&query=$1','','',0,0),('gej','http://www.esperanto.de/cgi-bin/aktivikio/wiki.pl?$1','','',0,0),('gentoo-wiki','http://gentoo-wiki.com/$1','','',0,0),('google','http://www.google.com/search?q=$1','','',0,0),('googlegroups','http://groups.google.com/groups?q=$1','','',0,0),('hammondwiki','http://www.dairiki.org/HammondWiki/$1','','',0,0),('hewikisource','http://he.wikisource.org/wiki/$1','','',1,0),('hrwiki','http://www.hrwiki.org/index.php/$1','','',0,0),('imdb','http://us.imdb.com/Title?$1','','',0,0),('jargonfile','http://sunir.org/apps/meta.pl?wiki=JargonFile&redirect=$1','','',0,0),('jspwiki','http://www.jspwiki.org/wiki/$1','','',0,0),('keiki','http://kei.ki/en/$1','','',0,0),('kmwiki','http://kmwiki.wikispaces.com/$1','','',0,0),('linuxwiki','http://linuxwiki.de/$1','','',0,0),('lojban','http://www.lojban.org/tiki/tiki-index.php?page=$1','','',0,0),('lqwiki','http://wiki.linuxquestions.org/wiki/$1','','',0,0),('lugkr','http://lug-kr.sourceforge.net/cgi-bin/lugwiki.pl?$1','','',0,0),('mathsongswiki','http://SeedWiki.com/page.cfm?wikiid=237&doc=$1','','',0,0),('meatball','http://www.usemod.com/cgi-bin/mb.pl?$1','','',0,0),('mediawikiwiki','http://www.mediawiki.org/wiki/$1','','',0,0),('mediazilla','https://bugzilla.wikimedia.org/$1','','',1,0),('memoryalpha','http://www.memory-alpha.org/en/index.php/$1','','',0,0),('metawiki','http://sunir.org/apps/meta.pl?$1','','',0,0),('metawikimedia','http://meta.wikimedia.org/wiki/$1','','',0,0),('moinmoin','http://purl.net/wiki/moin/$1','','',0,0),('mozillawiki','http://wiki.mozilla.org/index.php/$1','','',0,0),('mw','http://www.mediawiki.org/wiki/$1','','',0,0),('oeis','http://www.research.att.com/cgi-bin/access.cgi/as/njas/sequences/eisA.cgi?Anum=$1','','',0,0),('openfacts','http://openfacts.berlios.de/index.phtml?title=$1','','',0,0),('openwiki','http://openwiki.com/?$1','','',0,0),('pmeg','http://www.bertilow.com/pmeg/$1.php','','',0,0),('ppr','http://c2.com/cgi/wiki?$1','','',0,0),('pythoninfo','http://wiki.python.org/moin/$1','','',0,0),('rfc','http://www.rfc-editor.org/rfc/rfc$1.txt','','',0,0),('s23wiki','http://is-root.de/wiki/index.php/$1','','',0,0),('seattlewiki','http://seattle.wikia.com/wiki/$1','','',0,0),('seattlewireless','http://seattlewireless.net/?$1','','',0,0),('senseislibrary','http://senseis.xmp.net/?$1','','',0,0),('sourceforge','http://sourceforge.net/$1','','',0,0),('squeak','http://wiki.squeak.org/squeak/$1','','',0,0),('susning','http://www.susning.nu/$1','','',0,0),('svgwiki','http://wiki.svg.org/$1','','',0,0),('tavi','http://tavi.sourceforge.net/$1','','',0,0),('tejo','http://www.tejo.org/vikio/$1','','',0,0),('theopedia','http://www.theopedia.com/$1','','',0,0),('tmbw','http://www.tmbw.net/wiki/$1','','',0,0),('tmnet','http://www.technomanifestos.net/?$1','','',0,0),('tmwiki','http://www.EasyTopicMaps.com/?page=$1','','',0,0),('twiki','http://twiki.org/cgi-bin/view/$1','','',0,0),('uea','http://www.tejo.org/uea/$1','','',0,0),('unreal','http://wiki.beyondunreal.com/wiki/$1','','',0,0),('usemod','http://www.usemod.com/cgi-bin/wiki.pl?$1','','',0,0),('vinismo','http://vinismo.com/en/$1','','',0,0),('webseitzwiki','http://webseitz.fluxent.com/wiki/$1','','',0,0),('why','http://clublet.com/c/c/why?$1','','',0,0),('wiki','http://c2.com/cgi/wiki?$1','','',0,0),('wikia','http://www.wikia.com/wiki/$1','','',0,0),('wikibooks','http://en.wikibooks.org/wiki/$1','','',1,0),('wikicities','http://www.wikia.com/wiki/$1','','',0,0),('wikif1','http://www.wikif1.org/$1','','',0,0),('wikihow','http://www.wikihow.com/$1','','',0,0),('wikimedia','http://wikimediafoundation.org/wiki/$1','','',0,0),('wikinews','http://en.wikinews.org/wiki/$1','','',1,0),('wikinfo','http://www.wikinfo.org/index.php/$1','','',0,0),('wikipedia','http://en.wikipedia.org/wiki/$1','','',1,0),('wikiquote','http://en.wikiquote.org/wiki/$1','','',1,0),('wikisource','http://wikisource.org/wiki/$1','','',1,0),('wikispecies','http://species.wikimedia.org/wiki/$1','','',1,0),('wikitravel','http://wikitravel.org/en/$1','','',0,0),('wikiversity','http://en.wikiversity.org/wiki/$1','','',1,0),('wikt','http://en.wiktionary.org/wiki/$1','','',1,0),('wiktionary','http://en.wiktionary.org/wiki/$1','','',1,0),('wlug','http://www.wlug.org.nz/$1','','',0,0),('zwiki','http://zwiki.org/$1','','',0,0),('zzz wiki','http://wiki.zzz.ee/index.php/$1','','',0,0);
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
-- Dumping data for table `mw_l10n_cache`
--

LOCK TABLES `mw_l10n_cache` WRITE;
/*!40000 ALTER TABLE `mw_l10n_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `mw_l10n_cache` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_logging`
--

LOCK TABLES `mw_logging` WRITE;
/*!40000 ALTER TABLE `mw_logging` DISABLE KEYS */;
INSERT INTO `mw_logging` VALUES (1,'patrol','patrol','20110110173131',1,'WikiSysop',0,'TestResources',2,'','2\n0\n1',0);
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
INSERT INTO `mw_module_deps` VALUES ('ext.vector.collapsibleNav','vector','[\"\\/home\\/pdhanda\\/deployment\\/extensions\\/Vector\\/modules\\/.\\/images\\/portal-break.png\",\"\\/home\\/pdhanda\\/deployment\\/extensions\\/Vector\\/modules\\/.\\/images\\/open.png\",\"\\/home\\/pdhanda\\/deployment\\/extensions\\/Vector\\/modules\\/.\\/images\\/closed-ltr.png\"]'),('jquery.wikiEditor','vector','[\"\\/home\\/pdhanda\\/deployment\\/extensions\\/WikiEditor\\/modules\\/.\\/images\\/toolbar\\/loading.gif\"]'),('jquery.wikiEditor.toolbar','vector','{\"0\":\"\\/home\\/pdhanda\\/deployment\\/extensions\\/WikiEditor\\/modules\\/.\\/images\\/toolbar\\/base.png\",\"1\":\"\\/home\\/pdhanda\\/deployment\\/extensions\\/WikiEditor\\/modules\\/.\\/images\\/toolbar\\/loading.gif\",\"2\":\"\\/home\\/pdhanda\\/deployment\\/extensions\\/WikiEditor\\/modules\\/.\\/images\\/toolbar\\/button-sprite.png\",\"3\":\"\\/home\\/pdhanda\\/deployment\\/extensions\\/WikiEditor\\/modules\\/.\\/images\\/toolbar\\/arrow-right.png\",\"4\":\"\\/home\\/pdhanda\\/deployment\\/extensions\\/WikiEditor\\/modules\\/.\\/images\\/toolbar\\/arrow-left.png\",\"5\":\"\\/home\\/pdhanda\\/deployment\\/extensions\\/WikiEditor\\/modules\\/.\\/images\\/toolbar\\/arrow-down.png\",\"7\":\"\\/home\\/pdhanda\\/deployment\\/extensions\\/WikiEditor\\/modules\\/.\\/images\\/toolbar\\/loading-small.gif\"}'),('mediawiki.legacy.shared','vector','[\"\\/home\\/pdhanda\\/deployment\\/skins\\/common\\/images\\/feed-icon.png\",\"\\/home\\/pdhanda\\/deployment\\/skins\\/common\\/images\\/remove.png\",\"\\/home\\/pdhanda\\/deployment\\/skins\\/common\\/images\\/add.png\",\"\\/home\\/pdhanda\\/deployment\\/skins\\/common\\/images\\/ajax-loader.gif\",\"\\/home\\/pdhanda\\/deployment\\/skins\\/common\\/images\\/spinner.gif\",\"\\/home\\/pdhanda\\/deployment\\/skins\\/common\\/images\\/help-question.gif\",\"\\/home\\/pdhanda\\/deployment\\/skins\\/common\\/images\\/help-question-hover.gif\"]'),('skins.vector','vector','{\"0\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/page-base.png\",\"1\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/border.png\",\"2\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/page-fade.png\",\"4\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/tab-break.png\",\"5\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/tab-normal-fade.png\",\"6\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/tab-current-fade.png\",\"8\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/arrow-down-icon.png\",\"11\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/search-fade.png\",\"12\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/portal-break.png\",\"14\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/preferences-break.png\",\"16\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/preferences-fade.png\",\"17\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/preferences-base.png\",\"18\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/bullet-icon.png\",\"19\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/external-link-ltr-icon.png\",\"20\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/lock-icon.png\",\"21\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/mail-icon.png\",\"22\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/news-icon.png\",\"23\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/file-icon.png\",\"24\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/talk-icon.png\",\"25\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/audio-icon.png\",\"26\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/video-icon.png\",\"27\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/document-icon.png\",\"28\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/user-icon.png\",\"29\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/watch-icons.png\",\"30\":\"\\/home\\/pdhanda\\/deployment\\/skins\\/vector\\/images\\/watch-icon-loading.gif\"}');
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
INSERT INTO `mw_msg_resource` VALUES ('ext.vector.collapsibleNav','en','{\"vector-collapsiblenav-more\":\"More languages\"}','20110108005000'),('ext.vector.collapsibleTabs','en','{}','20110108005000'),('ext.vector.simpleSearch','en','{\"vector-simplesearch-search\":\"Search\",\"vector-simplesearch-containing\":\"containing...\"}','20110108005000'),('ext.wikiEditor','en','{}','20110110172914'),('ext.wikiEditor.toolbar','en','{\"wikieditor-toolbar-loading\":\"Loading...\",\"wikieditor-toolbar-tool-bold\":\"Bold\",\"wikieditor-toolbar-tool-bold-example\":\"Bold text\",\"wikieditor-toolbar-tool-italic\":\"Italic\",\"wikieditor-toolbar-tool-italic-example\":\"Italic text\",\"wikieditor-toolbar-tool-ilink\":\"Internal link\",\"wikieditor-toolbar-tool-ilink-example\":\"Link title\",\"wikieditor-toolbar-tool-xlink\":\"External link (remember http:\\/\\/ prefix)\",\"wikieditor-toolbar-tool-xlink-example\":\"http:\\/\\/www.example.com link title\",\"wikieditor-toolbar-tool-link\":\"Link\",\"wikieditor-toolbar-tool-link-title\":\"Insert link\",\"wikieditor-toolbar-tool-link-int\":\"To a wiki page\",\"wikieditor-toolbar-tool-link-int-target\":\"Target page or URL:\",\"wikieditor-toolbar-tool-link-int-target-tooltip\":\"Page title or URL\",\"wikieditor-toolbar-tool-link-int-text\":\"Text to display:\",\"wikieditor-toolbar-tool-link-int-text-tooltip\":\"Text to be displayed\",\"wikieditor-toolbar-tool-link-ext\":\"To an external web page\",\"wikieditor-toolbar-tool-link-ext-target\":\"Link URL:\",\"wikieditor-toolbar-tool-link-ext-text\":\"Link text:\",\"wikieditor-toolbar-tool-link-insert\":\"Insert link\",\"wikieditor-toolbar-tool-link-cancel\":\"Cancel\",\"wikieditor-toolbar-tool-link-int-target-status-exists\":\"Page exists\",\"wikieditor-toolbar-tool-link-int-target-status-notexists\":\"Page does not exist\",\"wikieditor-toolbar-tool-link-int-target-status-invalid\":\"Invalid title\",\"wikieditor-toolbar-tool-link-int-target-status-external\":\"External link\",\"wikieditor-toolbar-tool-link-int-target-status-loading\":\"Checking page existence...\",\"wikieditor-toolbar-tool-link-int-invalid\":\"The title you specified is invalid.\",\"wikieditor-toolbar-tool-link-lookslikeinternal\":\"The URL you specified looks like it was intended as a link to another wiki page.\\nDo you want to make it an internal link?\",\"wikieditor-toolbar-tool-link-lookslikeinternal-int\":\"Internal link\",\"wikieditor-toolbar-tool-link-lookslikeinternal-ext\":\"External link\",\"wikieditor-toolbar-tool-link-empty\":\"You did not enter anything to link to.\",\"wikieditor-toolbar-tool-file\":\"Embedded file\",\"wikieditor-toolbar-tool-file-pre\":\"$1{{ns:file}}:\",\"wikieditor-toolbar-tool-file-example\":\"Example.jpg\",\"wikieditor-toolbar-tool-reference\":\"Reference\",\"wikieditor-toolbar-tool-reference-title\":\"Insert reference\",\"wikieditor-toolbar-tool-reference-cancel\":\"Cancel\",\"wikieditor-toolbar-tool-reference-text\":\"Reference text\",\"wikieditor-toolbar-tool-reference-insert\":\"Insert\",\"wikieditor-toolbar-tool-reference-example\":\"Insert footnote text here\",\"wikieditor-toolbar-tool-signature\":\"Signature and timestamp\",\"wikieditor-toolbar-section-advanced\":\"Advanced\",\"wikieditor-toolbar-tool-heading\":\"Heading\",\"wikieditor-toolbar-tool-heading-1\":\"Level 1\",\"wikieditor-toolbar-tool-heading-2\":\"Level 2\",\"wikieditor-toolbar-tool-heading-3\":\"Level 3\",\"wikieditor-toolbar-tool-heading-4\":\"Level 4\",\"wikieditor-toolbar-tool-heading-5\":\"Level 5\",\"wikieditor-toolbar-tool-heading-example\":\"Heading text\",\"wikieditor-toolbar-group-format\":\"Format\",\"wikieditor-toolbar-tool-ulist\":\"Bulleted list\",\"wikieditor-toolbar-tool-ulist-example\":\"Bulleted list item\",\"wikieditor-toolbar-tool-olist\":\"Numbered list\",\"wikieditor-toolbar-tool-olist-example\":\"Numbered list item\",\"wikieditor-toolbar-tool-indent\":\"Indentation\",\"wikieditor-toolbar-tool-indent-example\":\"Indented line\",\"wikieditor-toolbar-tool-nowiki\":\"No wiki formatting\",\"wikieditor-toolbar-tool-nowiki-example\":\"Insert non-formatted text here\",\"wikieditor-toolbar-tool-redirect\":\"Redirect\",\"wikieditor-toolbar-tool-redirect-example\":\"Target page name\",\"wikieditor-toolbar-tool-big\":\"Big\",\"wikieditor-toolbar-tool-big-example\":\"Big text\",\"wikieditor-toolbar-tool-small\":\"Small\",\"wikieditor-toolbar-tool-small-example\":\"Small text\",\"wikieditor-toolbar-tool-superscript\":\"Superscript\",\"wikieditor-toolbar-tool-superscript-example\":\"Superscript text\",\"wikieditor-toolbar-tool-subscript\":\"Subscript\",\"wikieditor-toolbar-tool-subscript-example\":\"Subscript text\",\"wikieditor-toolbar-group-insert\":\"Insert\",\"wikieditor-toolbar-tool-gallery\":\"Picture gallery\",\"wikieditor-toolbar-tool-gallery-example\":\"{{ns:file}}:Example.jpg|Caption1\\n{{ns:file}}:Example.jpg|Caption2\",\"wikieditor-toolbar-tool-newline\":\"New line\",\"wikieditor-toolbar-tool-table\":\"Table\",\"wikieditor-toolbar-tool-table-example-old\":\"-\\n! header 1\\n! header 2\\n! header 3\\n|-\\n| row 1, cell 1\\n| row 1, cell 2\\n| row 1, cell 3\\n|-\\n| row 2, cell 1\\n| row 2, cell 2\\n| row 2, cell 3\",\"wikieditor-toolbar-tool-table-example-cell-text\":\"Cell text\",\"wikieditor-toolbar-tool-table-example\":\"Example\",\"wikieditor-toolbar-tool-table-example-header\":\"Header text\",\"wikieditor-toolbar-tool-table-title\":\"Insert table\",\"wikieditor-toolbar-tool-table-dimensions-rows\":\"Rows\",\"wikieditor-toolbar-tool-table-dimensions-columns\":\"Columns\",\"wikieditor-toolbar-tool-table-dimensions-header\":\"Add header row\",\"wikieditor-toolbar-tool-table-wikitable\":\"Style with borders\",\"wikieditor-toolbar-tool-table-sortable\":\"Make table sortable\",\"wikieditor-toolbar-tool-table-insert\":\"Insert\",\"wikieditor-toolbar-tool-table-cancel\":\"Cancel\",\"wikieditor-toolbar-tool-table-example-text\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec purus diam. Sed aliquam imperdiet nunc quis lacinia. Donec rutrum consectetur placerat. Sed volutpat neque non purus faucibus id ultricies enim euismod.\",\"wikieditor-toolbar-tool-table-toomany\":\"Inserting a table with more than $1 cells is not possible with this dialog.\",\"wikieditor-toolbar-tool-table-invalidnumber\":\"You have not entered a valid number of rows or columns.\",\"wikieditor-toolbar-tool-table-zero\":\"You cannot insert a table with zero rows or columns.\",\"wikieditor-toolbar-tool-replace\":\"Search and replace\",\"wikieditor-toolbar-tool-replace-title\":\"Search and replace\",\"wikieditor-toolbar-tool-replace-search\":\"Search for:\",\"wikieditor-toolbar-tool-replace-replace\":\"Replace with:\",\"wikieditor-toolbar-tool-replace-case\":\"Match case\",\"wikieditor-toolbar-tool-replace-regex\":\"Treat search string as a regular expression\",\"wikieditor-toolbar-tool-replace-button-findnext\":\"Find next\",\"wikieditor-toolbar-tool-replace-button-replacenext\":\"Replace next\",\"wikieditor-toolbar-tool-replace-button-replaceall\":\"Replace all\",\"wikieditor-toolbar-tool-replace-close\":\"Close\",\"wikieditor-toolbar-tool-replace-nomatch\":\"Your search did not match anything.\",\"wikieditor-toolbar-tool-replace-success\":\"$1 replacement(s) made.\",\"wikieditor-toolbar-tool-replace-emptysearch\":\"You did not enter anything to search for.\",\"wikieditor-toolbar-tool-replace-invalidregex\":\"The regular expression you entered is invalid: $1\",\"wikieditor-toolbar-section-characters\":\"Special characters\",\"wikieditor-toolbar-characters-page-latin\":\"Latin\",\"wikieditor-toolbar-characters-page-latinextended\":\"Latin extended\",\"wikieditor-toolbar-characters-page-ipa\":\"IPA\",\"wikieditor-toolbar-characters-page-symbols\":\"Symbols\",\"wikieditor-toolbar-characters-page-greek\":\"Greek\",\"wikieditor-toolbar-characters-page-cyrillic\":\"Cyrillic\",\"wikieditor-toolbar-characters-page-arabic\":\"Arabic\",\"wikieditor-toolbar-characters-page-persian\":\"Persian\",\"wikieditor-toolbar-characters-page-hebrew\":\"Hebrew\",\"wikieditor-toolbar-characters-page-bangla\":\"Bangla\",\"wikieditor-toolbar-characters-page-telugu\":\"Telugu\",\"wikieditor-toolbar-characters-page-sinhala\":\"Sinhala\",\"wikieditor-toolbar-characters-page-gujarati\":\"Gujarati\",\"wikieditor-toolbar-characters-page-thai\":\"Thai\",\"wikieditor-toolbar-characters-page-lao\":\"Lao\",\"wikieditor-toolbar-characters-page-khmer\":\"Khmer\",\"wikieditor-toolbar-section-help\":\"Help\",\"wikieditor-toolbar-help-heading-description\":\"Description\",\"wikieditor-toolbar-help-heading-syntax\":\"What you type\",\"wikieditor-toolbar-help-heading-result\":\"What you get\",\"wikieditor-toolbar-help-page-format\":\"Formatting\",\"wikieditor-toolbar-help-page-link\":\"Links\",\"wikieditor-toolbar-help-page-heading\":\"Headings\",\"wikieditor-toolbar-help-page-list\":\"Lists\",\"wikieditor-toolbar-help-page-file\":\"Files\",\"wikieditor-toolbar-help-page-reference\":\"References\",\"wikieditor-toolbar-help-page-discussion\":\"Discussion\",\"wikieditor-toolbar-help-content-bold-description\":\"Bold\",\"wikieditor-toolbar-help-content-bold-syntax\":\"\'\'\'Bold text\'\'\'\",\"wikieditor-toolbar-help-content-bold-result\":\"<strong>Bold text<\\/strong>\",\"wikieditor-toolbar-help-content-italic-description\":\"Italic\",\"wikieditor-toolbar-help-content-italic-syntax\":\"\'\'Italic text\'\'\",\"wikieditor-toolbar-help-content-italic-result\":\"<em>Italic text<\\/em>\",\"wikieditor-toolbar-help-content-bolditalic-description\":\"Bold &amp; italic\",\"wikieditor-toolbar-help-content-bolditalic-syntax\":\"\'\'\'\'\'Bold &amp; italic text\'\'\'\'\'\",\"wikieditor-toolbar-help-content-bolditalic-result\":\"<strong><em>Bold &amp; italic text<\\/em><\\/strong>\",\"wikieditor-toolbar-help-content-ilink-description\":\"Internal link\",\"wikieditor-toolbar-help-content-ilink-syntax\":\"[[Page title|Link label]]<br \\/>[[Page title]]\",\"wikieditor-toolbar-help-content-ilink-result\":\"<a href=\'#\'>Link label<\\/a><br \\/><a href=\'#\'>Page title<\\/a>\",\"wikieditor-toolbar-help-content-xlink-description\":\"External link\",\"wikieditor-toolbar-help-content-xlink-syntax\":\"[http:\\/\\/www.example.org Link label]<br \\/>[http:\\/\\/www.example.org]<br \\/>http:\\/\\/www.example.org\",\"wikieditor-toolbar-help-content-xlink-result\":\"<a href=\'#\' class=\'external\'>Link label<\\/a><br \\/><a href=\'#\' class=\'external autonumber\'>[1]<\\/a><br \\/><a href=\'#\' class=\'external\'>http:\\/\\/www.example.org<\\/a>\",\"wikieditor-toolbar-help-content-heading1-description\":\"&lt;wikieditor-toolbar-help-content-heading1-description&gt;\",\"wikieditor-toolbar-help-content-heading1-syntax\":\"&lt;wikieditor-toolbar-help-content-heading1-syntax&gt;\",\"wikieditor-toolbar-help-content-heading1-result\":\"&lt;wikieditor-toolbar-help-content-heading1-result&gt;\",\"wikieditor-toolbar-help-content-heading2-description\":\"2nd level heading\",\"wikieditor-toolbar-help-content-heading2-syntax\":\"== Heading text ==\",\"wikieditor-toolbar-help-content-heading2-result\":\"<h2>Heading text<\\/h2>\",\"wikieditor-toolbar-help-content-heading3-description\":\"3rd level heading\",\"wikieditor-toolbar-help-content-heading3-syntax\":\"=== Heading text ===\",\"wikieditor-toolbar-help-content-heading3-result\":\"<h3>Heading text<\\/h3>\",\"wikieditor-toolbar-help-content-heading4-description\":\"4th level heading\",\"wikieditor-toolbar-help-content-heading4-syntax\":\"==== Heading text ====\",\"wikieditor-toolbar-help-content-heading4-result\":\"<h4>Heading text<\\/h4>\",\"wikieditor-toolbar-help-content-heading5-description\":\"5th level heading\",\"wikieditor-toolbar-help-content-heading5-syntax\":\"===== Heading text =====\",\"wikieditor-toolbar-help-content-heading5-result\":\"<h5>Heading text<\\/h5>\",\"wikieditor-toolbar-help-content-ulist-description\":\"Bulleted list\",\"wikieditor-toolbar-help-content-ulist-syntax\":\"* List item<br \\/>* List item\",\"wikieditor-toolbar-help-content-ulist-result\":\"<ul><li>List item<\\/li><li>List item<\\/li><\\/ul>\",\"wikieditor-toolbar-help-content-olist-description\":\"Numbered list\",\"wikieditor-toolbar-help-content-olist-syntax\":\"# List item<br \\/># List item\",\"wikieditor-toolbar-help-content-olist-result\":\"<ol><li>List item<\\/li><li>List item<\\/li><\\/ol>\",\"wikieditor-toolbar-help-content-file-description\":\"Embedded file\",\"wikieditor-toolbar-help-content-file-syntax\":\"[[{{ns:file}}:Example.png|thumb|Caption text]]\",\"wikieditor-toolbar-help-content-file-result\":\"<div style=\'width:104px;\' class=\'thumbinner\'><a title=\'Caption text\' class=\'image\' href=\'#\'><img height=\'50\' width=\'100\' border=\'0\' class=\'thumbimage\' src=\'extensions\\/UsabilityInitiative\\/images\\/wikiEditor\\/toolbar\\/example-image.png\' alt=\'\'\\/><\\/a><div class=\'thumbcaption\'><div class=\'magnify\'><a title=\'Enlarge\' class=\'internal\' href=\'#\'><img height=\'11\' width=\'15\' alt=\'\' src=\'$1\\/common\\/images\\/magnify-clip.png\'\\/><\\/a><\\/div>Caption text<\\/div><\\/div>\",\"wikieditor-toolbar-help-content-reference-description\":\"Reference\",\"wikieditor-toolbar-help-content-reference-syntax\":\"Page text.&lt;ref name=\\\"test\\\"&gt;[http:\\/\\/www.example.org Link text], additional text.&lt;\\/ref&gt;\",\"wikieditor-toolbar-help-content-reference-result\":\"Page text.<sup><a href=\'#\'>[1]<\\/a><\\/sup>\",\"wikieditor-toolbar-help-content-rereference-description\":\"Additional use of same reference\",\"wikieditor-toolbar-help-content-rereference-syntax\":\"&lt;ref name=\\\"test\\\" \\/&gt;\",\"wikieditor-toolbar-help-content-rereference-result\":\"Page text.<sup><a href=\'#\'>[1]<\\/a><\\/sup>\",\"wikieditor-toolbar-help-content-showreferences-description\":\"Display references\",\"wikieditor-toolbar-help-content-showreferences-syntax\":\"&lt;references \\/&gt;\",\"wikieditor-toolbar-help-content-showreferences-result\":\"<ol class=\'references\'><li id=\'cite_note-test-0\'><b><a title=\'\' href=\'#\'>^<\\/a><\\/b> <a rel=\'nofollow\' title=\'http:\\/\\/www.example.org\' class=\'external text\' href=\'#\'>Link text<\\/a>, additional text.<\\/li><\\/ol>\",\"wikieditor-toolbar-help-content-signaturetimestamp-description\":\"Signature with timestamp\",\"wikieditor-toolbar-help-content-signaturetimestamp-syntax\":\"~~~~\",\"wikieditor-toolbar-help-content-signaturetimestamp-result\":\"<a href=\'#\' title=\'{{#special:mypage}}\'>Username<\\/a> (<a href=\'#\' title=\'{{#special:mytalk}}\'>talk<\\/a>) 15:54, 10 June 2009 (UTC)\",\"wikieditor-toolbar-help-content-signature-description\":\"Signature\",\"wikieditor-toolbar-help-content-signature-syntax\":\"~~~\",\"wikieditor-toolbar-help-content-signature-result\":\"<a href=\'#\' title=\'{{#special:mypage}}\'>Username<\\/a> (<a href=\'#\' title=\'{{#special:mytalk}}\'>talk<\\/a>)\",\"wikieditor-toolbar-help-content-indent-description\":\"Indent\",\"wikieditor-toolbar-help-content-indent-syntax\":\"Normal text<br \\/>:Indented text<br \\/>::Indented text\",\"wikieditor-toolbar-help-content-indent-result\":\"Normal text<dl><dd>Indented text<dl><dd>Indented text<\\/dd><\\/dl><\\/dd><\\/dl>\"}','20110110172914'),('jquery.async','en','{}','20110110172915'),('jquery.autoEllipsis','en','{}','20110110172915'),('jquery.checkboxShiftClick','en','{}','20110110172915'),('jquery.client','en','{}','20110110172915'),('jquery.collapsibleTabs','en','{}','20110110172915'),('jquery.cookie','en','{}','20110110172915'),('jquery.delayedBind','en','{}','20110110172915'),('jquery.highlightText','en','{}','20110110172915'),('jquery.makeCollapsible','en','{\"collapsible-expand\":\"Expand\",\"collapsible-collapse\":\"Collapse\"}','20110110172915'),('jquery.placeholder','en','{}','20110110172915'),('jquery.suggestions','en','{}','20110110172915'),('jquery.tabIndex','en','{}','20110110172915'),('jquery.textSelection','en','{}','20110110172915'),('jquery.wikiEditor','en','{\"wikieditor-wikitext-tab\":\"Wikitext\",\"wikieditor-loading\":\"Loading\"}','20110110172914'),('jquery.wikiEditor.toolbar','en','{}','20110110172914'),('mediawiki.action.watch.ajax','en','{}','20110110172915'),('mediawiki.language','en','{}','20110110172915'),('mediawiki.legacy.ajax','en','{\"watch\":\"Watch\",\"unwatch\":\"Unwatch\",\"watching\":\"Watching...\",\"unwatching\":\"Unwatching...\",\"tooltip-ca-watch\":\"Add this page to your watchlist\",\"tooltip-ca-unwatch\":\"Remove this page from your watchlist\"}','20110110172915'),('mediawiki.legacy.edit','en','{}','20110110172915'),('mediawiki.legacy.wikibits','en','{\"showtoc\":\"show\",\"hidetoc\":\"hide\"}','20110110172915'),('mediawiki.util','en','{}','20110110172915');
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
INSERT INTO `mw_msg_resource_links` VALUES ('jquery.makeCollapsible','collapsible-collapse'),('jquery.makeCollapsible','collapsible-expand'),('mediawiki.legacy.wikibits','hidetoc'),('mediawiki.legacy.wikibits','showtoc'),('mediawiki.legacy.ajax','tooltip-ca-unwatch'),('mediawiki.legacy.ajax','tooltip-ca-watch'),('mediawiki.legacy.ajax','unwatch'),('mediawiki.legacy.ajax','unwatching'),('ext.vector.collapsibleNav','vector-collapsiblenav-more'),('ext.vector.simpleSearch','vector-simplesearch-containing'),('ext.vector.simpleSearch','vector-simplesearch-search'),('mediawiki.legacy.ajax','watch'),('mediawiki.legacy.ajax','watching'),('jquery.wikiEditor','wikieditor-loading'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-arabic'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-bangla'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-cyrillic'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-greek'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-gujarati'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-hebrew'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-ipa'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-khmer'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-lao'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-latin'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-latinextended'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-persian'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-sinhala'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-symbols'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-telugu'),('ext.wikiEditor.toolbar','wikieditor-toolbar-characters-page-thai'),('ext.wikiEditor.toolbar','wikieditor-toolbar-group-format'),('ext.wikiEditor.toolbar','wikieditor-toolbar-group-insert'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-bold-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-bold-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-bold-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-bolditalic-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-bolditalic-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-bolditalic-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-file-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-file-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-file-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading1-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading1-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading1-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading2-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading2-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading2-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading3-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading3-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading3-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading4-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading4-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading4-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading5-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading5-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-heading5-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-ilink-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-ilink-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-ilink-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-indent-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-indent-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-indent-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-italic-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-italic-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-italic-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-olist-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-olist-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-olist-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-reference-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-reference-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-reference-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-rereference-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-rereference-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-rereference-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-showreferences-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-showreferences-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-showreferences-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-signature-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-signature-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-signature-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-signaturetimestamp-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-signaturetimestamp-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-signaturetimestamp-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-ulist-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-ulist-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-ulist-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-xlink-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-xlink-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-content-xlink-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-heading-description'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-heading-result'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-heading-syntax'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-page-discussion'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-page-file'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-page-format'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-page-heading'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-page-link'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-page-list'),('ext.wikiEditor.toolbar','wikieditor-toolbar-help-page-reference'),('ext.wikiEditor.toolbar','wikieditor-toolbar-loading'),('ext.wikiEditor.toolbar','wikieditor-toolbar-section-advanced'),('ext.wikiEditor.toolbar','wikieditor-toolbar-section-characters'),('ext.wikiEditor.toolbar','wikieditor-toolbar-section-help'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-big'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-big-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-bold'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-bold-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-file'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-file-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-file-pre'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-gallery'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-gallery-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-heading'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-heading-1'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-heading-2'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-heading-3'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-heading-4'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-heading-5'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-heading-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-ilink'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-ilink-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-indent'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-indent-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-italic'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-italic-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-cancel'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-empty'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-ext'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-ext-target'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-ext-text'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-insert'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-invalid'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-target'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-target-status-exists'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-target-status-external'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-target-status-invalid'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-target-status-loading'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-target-status-notexists'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-target-tooltip'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-text'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-int-text-tooltip'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-lookslikeinternal'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-lookslikeinternal-ext'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-lookslikeinternal-int'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-link-title'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-newline'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-nowiki'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-nowiki-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-olist'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-olist-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-redirect'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-redirect-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-reference'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-reference-cancel'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-reference-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-reference-insert'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-reference-text'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-reference-title'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-button-findnext'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-button-replaceall'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-button-replacenext'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-case'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-close'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-emptysearch'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-invalidregex'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-nomatch'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-regex'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-replace'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-search'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-success'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-replace-title'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-signature'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-small'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-small-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-subscript'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-subscript-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-superscript'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-superscript-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-cancel'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-dimensions-columns'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-dimensions-header'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-dimensions-rows'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-example-cell-text'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-example-header'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-example-old'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-example-text'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-insert'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-invalidnumber'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-sortable'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-title'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-toomany'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-wikitable'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-table-zero'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-ulist'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-ulist-example'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-xlink'),('ext.wikiEditor.toolbar','wikieditor-toolbar-tool-xlink-example'),('jquery.wikiEditor','wikieditor-wikitext-tab');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_page`
--

LOCK TABLES `mw_page` WRITE;
/*!40000 ALTER TABLE `mw_page` DISABLE KEYS */;
INSERT INTO `mw_page` VALUES (1,0,'Main_Page','',3,0,1,0.045389076294,'20110107184113',1,438),(2,0,'TestResources','',0,0,1,0.227355086893,'20110110173217',2,57);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_recentchanges`
--

LOCK TABLES `mw_recentchanges` WRITE;
/*!40000 ALTER TABLE `mw_recentchanges` DISABLE KEYS */;
INSERT INTO `mw_recentchanges` VALUES (1,'20110107184113','20110107184113',0,'MediaWiki Default',0,'Main_Page','',0,0,1,1,1,0,1,0,'',0,'::1',0,438,0,0,NULL,'',''),(2,'20110110173131','20110110173131',1,'WikiSysop',0,'TestResources','Created page with \"Test the the SimpleSelenium database was loaded correctly\"',0,0,1,2,2,0,1,0,'',1,'::1',0,57,0,0,NULL,'','');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_revision`
--

LOCK TABLES `mw_revision` WRITE;
/*!40000 ALTER TABLE `mw_revision` DISABLE KEYS */;
INSERT INTO `mw_revision` VALUES (1,1,1,'',0,'MediaWiki Default','20110107184113',0,0,438,0),(2,2,2,'Created page with \"Test the the SimpleSelenium database was loaded correctly\"',1,'WikiSysop','20110110173131',0,0,57,0);
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
INSERT INTO `mw_searchindex` VALUES (1,'main page','  mediawiki hasu800 been successfully installed.  consult theu800 user user\'su800 guide foru800 information onu800 using theu800 wiki software. getting started getting started getting started configuration settings list mediawiki faqu800 mediawiki release mailing list '),(2,'testresources',' test theu800 theu800 simpleselenium database wasu800 loaded correctly ');
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
INSERT INTO `mw_site_stats` VALUES (1,3,2,1,2,1,-1,-1,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 MAX_ROWS=10000000 AVG_ROW_LENGTH=10240;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mw_text`
--

LOCK TABLES `mw_text` WRITE;
/*!40000 ALTER TABLE `mw_text` DISABLE KEYS */;
INSERT INTO `mw_text` VALUES (1,'\'\'\'MediaWiki has been successfully installed.\'\'\'\n\nConsult the [http://meta.wikimedia.org/wiki/Help:Contents User\'s Guide] for information on using the wiki software.\n\n== Getting started ==\n* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]\n* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]\n* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]','utf-8'),(2,'Test the the SimpleSelenium database was loaded correctly','utf-8');
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
  `tc_time` binary(14) DEFAULT NULL,
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
INSERT INTO `mw_updatelog` VALUES ('cl_fields_update',NULL),('convert transcache field',NULL),('mime_minor_length',NULL),('populate category',NULL),('populate rev_len',NULL),('populate rev_parent_id',NULL),('updatelist-1.18alpha-1294425799','a:128:{i:0;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:6:\"ipb_id\";i:3;s:18:\"patch-ipblocks.sql\";}i:1;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:10:\"ipb_expiry\";i:3;s:20:\"patch-ipb_expiry.sql\";}i:2;a:1:{i:0;s:17:\"doInterwikiUpdate\";}i:3;a:1:{i:0;s:13:\"doIndexUpdate\";}i:4;a:3:{i:0;s:8:\"addTable\";i:1;s:10:\"hitcounter\";i:2;s:20:\"patch-hitcounter.sql\";}i:5;a:4:{i:0;s:8:\"addField\";i:1;s:13:\"recentchanges\";i:2;s:7:\"rc_type\";i:3;s:17:\"patch-rc_type.sql\";}i:6;a:4:{i:0;s:8:\"addField\";i:1;s:4:\"user\";i:2;s:14:\"user_real_name\";i:3;s:23:\"patch-user-realname.sql\";}i:7;a:3:{i:0;s:8:\"addTable\";i:1;s:10:\"querycache\";i:2;s:20:\"patch-querycache.sql\";}i:8;a:3:{i:0;s:8:\"addTable\";i:1;s:11:\"objectcache\";i:2;s:21:\"patch-objectcache.sql\";}i:9;a:3:{i:0;s:8:\"addTable\";i:1;s:13:\"categorylinks\";i:2;s:23:\"patch-categorylinks.sql\";}i:10;a:1:{i:0;s:16:\"doOldLinksUpdate\";}i:11;a:1:{i:0;s:22:\"doFixAncientImagelinks\";}i:12;a:4:{i:0;s:8:\"addField\";i:1;s:13:\"recentchanges\";i:2;s:5:\"rc_ip\";i:3;s:15:\"patch-rc_ip.sql\";}i:13;a:4:{i:0;s:8:\"addIndex\";i:1;s:5:\"image\";i:2;s:7:\"PRIMARY\";i:3;s:28:\"patch-image_name_primary.sql\";}i:14;a:4:{i:0;s:8:\"addField\";i:1;s:13:\"recentchanges\";i:2;s:5:\"rc_id\";i:3;s:15:\"patch-rc_id.sql\";}i:15;a:4:{i:0;s:8:\"addField\";i:1;s:13:\"recentchanges\";i:2;s:12:\"rc_patrolled\";i:3;s:19:\"patch-rc-patrol.sql\";}i:16;a:3:{i:0;s:8:\"addTable\";i:1;s:7:\"logging\";i:2;s:17:\"patch-logging.sql\";}i:17;a:4:{i:0;s:8:\"addField\";i:1;s:4:\"user\";i:2;s:10:\"user_token\";i:3;s:20:\"patch-user_token.sql\";}i:18;a:4:{i:0;s:8:\"addField\";i:1;s:9:\"watchlist\";i:2;s:24:\"wl_notificationtimestamp\";i:3;s:28:\"patch-email-notification.sql\";}i:19;a:1:{i:0;s:17:\"doWatchlistUpdate\";}i:20;a:4:{i:0;s:9:\"dropField\";i:1;s:4:\"user\";i:2;s:33:\"user_emailauthenticationtimestamp\";i:3;s:30:\"patch-email-authentication.sql\";}i:21;a:1:{i:0;s:21:\"doSchemaRestructuring\";}i:22;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"logging\";i:2;s:10:\"log_params\";i:3;s:20:\"patch-log_params.sql\";}i:23;a:4:{i:0;s:8:\"checkBin\";i:1;s:7:\"logging\";i:2;s:9:\"log_title\";i:3;s:23:\"patch-logging-title.sql\";}i:24;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"archive\";i:2;s:9:\"ar_rev_id\";i:3;s:24:\"patch-archive-rev_id.sql\";}i:25;a:4:{i:0;s:8:\"addField\";i:1;s:4:\"page\";i:2;s:8:\"page_len\";i:3;s:18:\"patch-page_len.sql\";}i:26;a:4:{i:0;s:9:\"dropField\";i:1;s:8:\"revision\";i:2;s:17:\"inverse_timestamp\";i:3;s:27:\"patch-inverse_timestamp.sql\";}i:27;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"revision\";i:2;s:11:\"rev_text_id\";i:3;s:21:\"patch-rev_text_id.sql\";}i:28;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"revision\";i:2;s:11:\"rev_deleted\";i:3;s:21:\"patch-rev_deleted.sql\";}i:29;a:4:{i:0;s:8:\"addField\";i:1;s:5:\"image\";i:2;s:9:\"img_width\";i:3;s:19:\"patch-img_width.sql\";}i:30;a:4:{i:0;s:8:\"addField\";i:1;s:5:\"image\";i:2;s:12:\"img_metadata\";i:3;s:22:\"patch-img_metadata.sql\";}i:31;a:4:{i:0;s:8:\"addField\";i:1;s:4:\"user\";i:2;s:16:\"user_email_token\";i:3;s:26:\"patch-user_email_token.sql\";}i:32;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"archive\";i:2;s:10:\"ar_text_id\";i:3;s:25:\"patch-archive-text_id.sql\";}i:33;a:1:{i:0;s:15:\"doNamespaceSize\";}i:34;a:4:{i:0;s:8:\"addField\";i:1;s:5:\"image\";i:2;s:14:\"img_media_type\";i:3;s:24:\"patch-img_media_type.sql\";}i:35;a:1:{i:0;s:17:\"doPagelinksUpdate\";}i:36;a:4:{i:0;s:9:\"dropField\";i:1;s:5:\"image\";i:2;s:8:\"img_type\";i:3;s:23:\"patch-drop_img_type.sql\";}i:37;a:1:{i:0;s:18:\"doUserUniqueUpdate\";}i:38;a:1:{i:0;s:18:\"doUserGroupsUpdate\";}i:39;a:4:{i:0;s:8:\"addField\";i:1;s:10:\"site_stats\";i:2;s:14:\"ss_total_pages\";i:3;s:27:\"patch-ss_total_articles.sql\";}i:40;a:3:{i:0;s:8:\"addTable\";i:1;s:12:\"user_newtalk\";i:2;s:22:\"patch-usernewtalk2.sql\";}i:41;a:3:{i:0;s:8:\"addTable\";i:1;s:10:\"transcache\";i:2;s:20:\"patch-transcache.sql\";}i:42;a:4:{i:0;s:8:\"addField\";i:1;s:9:\"interwiki\";i:2;s:8:\"iw_trans\";i:3;s:25:\"patch-interwiki-trans.sql\";}i:43;a:3:{i:0;s:8:\"addTable\";i:1;s:10:\"trackbacks\";i:2;s:20:\"patch-trackbacks.sql\";}i:44;a:1:{i:0;s:15:\"doWatchlistNull\";}i:45;a:4:{i:0;s:8:\"addIndex\";i:1;s:7:\"logging\";i:2;s:5:\"times\";i:3;s:29:\"patch-logging-times-index.sql\";}i:46;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:15:\"ipb_range_start\";i:3;s:25:\"patch-ipb_range_start.sql\";}i:47;a:1:{i:0;s:18:\"doPageRandomUpdate\";}i:48;a:4:{i:0;s:8:\"addField\";i:1;s:4:\"user\";i:2;s:17:\"user_registration\";i:3;s:27:\"patch-user_registration.sql\";}i:49;a:1:{i:0;s:21:\"doTemplatelinksUpdate\";}i:50;a:3:{i:0;s:8:\"addTable\";i:1;s:13:\"externallinks\";i:2;s:23:\"patch-externallinks.sql\";}i:51;a:3:{i:0;s:8:\"addTable\";i:1;s:3:\"job\";i:2;s:13:\"patch-job.sql\";}i:52;a:4:{i:0;s:8:\"addField\";i:1;s:10:\"site_stats\";i:2;s:9:\"ss_images\";i:3;s:19:\"patch-ss_images.sql\";}i:53;a:3:{i:0;s:8:\"addTable\";i:1;s:9:\"langlinks\";i:2;s:19:\"patch-langlinks.sql\";}i:54;a:3:{i:0;s:8:\"addTable\";i:1;s:15:\"querycache_info\";i:2;s:24:\"patch-querycacheinfo.sql\";}i:55;a:3:{i:0;s:8:\"addTable\";i:1;s:11:\"filearchive\";i:2;s:21:\"patch-filearchive.sql\";}i:56;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:13:\"ipb_anon_only\";i:3;s:23:\"patch-ipb_anon_only.sql\";}i:57;a:4:{i:0;s:8:\"addIndex\";i:1;s:13:\"recentchanges\";i:2;s:14:\"rc_ns_usertext\";i:3;s:31:\"patch-recentchanges-utindex.sql\";}i:58;a:4:{i:0;s:8:\"addIndex\";i:1;s:13:\"recentchanges\";i:2;s:12:\"rc_user_text\";i:3;s:28:\"patch-rc_user_text-index.sql\";}i:59;a:4:{i:0;s:8:\"addField\";i:1;s:4:\"user\";i:2;s:17:\"user_newpass_time\";i:3;s:27:\"patch-user_newpass_time.sql\";}i:60;a:3:{i:0;s:8:\"addTable\";i:1;s:8:\"redirect\";i:2;s:18:\"patch-redirect.sql\";}i:61;a:3:{i:0;s:8:\"addTable\";i:1;s:13:\"querycachetwo\";i:2;s:23:\"patch-querycachetwo.sql\";}i:62;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:20:\"ipb_enable_autoblock\";i:3;s:32:\"patch-ipb_optional_autoblock.sql\";}i:63;a:1:{i:0;s:26:\"doBacklinkingIndicesUpdate\";}i:64;a:4:{i:0;s:8:\"addField\";i:1;s:13:\"recentchanges\";i:2;s:10:\"rc_old_len\";i:3;s:16:\"patch-rc_len.sql\";}i:65;a:4:{i:0;s:8:\"addField\";i:1;s:4:\"user\";i:2;s:14:\"user_editcount\";i:3;s:24:\"patch-user_editcount.sql\";}i:66;a:1:{i:0;s:20:\"doRestrictionsUpdate\";}i:67;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"logging\";i:2;s:6:\"log_id\";i:3;s:16:\"patch-log_id.sql\";}i:68;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"revision\";i:2;s:13:\"rev_parent_id\";i:3;s:23:\"patch-rev_parent_id.sql\";}i:69;a:4:{i:0;s:8:\"addField\";i:1;s:17:\"page_restrictions\";i:2;s:5:\"pr_id\";i:3;s:35:\"patch-page_restrictions_sortkey.sql\";}i:70;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"revision\";i:2;s:7:\"rev_len\";i:3;s:17:\"patch-rev_len.sql\";}i:71;a:4:{i:0;s:8:\"addField\";i:1;s:13:\"recentchanges\";i:2;s:10:\"rc_deleted\";i:3;s:20:\"patch-rc_deleted.sql\";}i:72;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"logging\";i:2;s:11:\"log_deleted\";i:3;s:21:\"patch-log_deleted.sql\";}i:73;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"archive\";i:2;s:10:\"ar_deleted\";i:3;s:20:\"patch-ar_deleted.sql\";}i:74;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:11:\"ipb_deleted\";i:3;s:21:\"patch-ipb_deleted.sql\";}i:75;a:4:{i:0;s:8:\"addField\";i:1;s:11:\"filearchive\";i:2;s:10:\"fa_deleted\";i:3;s:20:\"patch-fa_deleted.sql\";}i:76;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"archive\";i:2;s:6:\"ar_len\";i:3;s:16:\"patch-ar_len.sql\";}i:77;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:15:\"ipb_block_email\";i:3;s:22:\"patch-ipb_emailban.sql\";}i:78;a:1:{i:0;s:28:\"doCategorylinksIndicesUpdate\";}i:79;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"oldimage\";i:2;s:11:\"oi_metadata\";i:3;s:21:\"patch-oi_metadata.sql\";}i:80;a:4:{i:0;s:8:\"addIndex\";i:1;s:7:\"archive\";i:2;s:18:\"usertext_timestamp\";i:3;s:28:\"patch-archive-user-index.sql\";}i:81;a:4:{i:0;s:8:\"addIndex\";i:1;s:5:\"image\";i:2;s:22:\"img_usertext_timestamp\";i:3;s:26:\"patch-image-user-index.sql\";}i:82;a:4:{i:0;s:8:\"addIndex\";i:1;s:8:\"oldimage\";i:2;s:21:\"oi_usertext_timestamp\";i:3;s:29:\"patch-oldimage-user-index.sql\";}i:83;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"archive\";i:2;s:10:\"ar_page_id\";i:3;s:25:\"patch-archive-page_id.sql\";}i:84;a:4:{i:0;s:8:\"addField\";i:1;s:5:\"image\";i:2;s:8:\"img_sha1\";i:3;s:18:\"patch-img_sha1.sql\";}i:85;a:3:{i:0;s:8:\"addTable\";i:1;s:16:\"protected_titles\";i:2;s:26:\"patch-protected_titles.sql\";}i:86;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:11:\"ipb_by_text\";i:3;s:21:\"patch-ipb_by_text.sql\";}i:87;a:3:{i:0;s:8:\"addTable\";i:1;s:10:\"page_props\";i:2;s:20:\"patch-page_props.sql\";}i:88;a:3:{i:0;s:8:\"addTable\";i:1;s:9:\"updatelog\";i:2;s:19:\"patch-updatelog.sql\";}i:89;a:3:{i:0;s:8:\"addTable\";i:1;s:8:\"category\";i:2;s:18:\"patch-category.sql\";}i:90;a:1:{i:0;s:20:\"doCategoryPopulation\";}i:91;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"archive\";i:2;s:12:\"ar_parent_id\";i:3;s:22:\"patch-ar_parent_id.sql\";}i:92;a:4:{i:0;s:8:\"addField\";i:1;s:12:\"user_newtalk\";i:2;s:19:\"user_last_timestamp\";i:3;s:29:\"patch-user_last_timestamp.sql\";}i:93;a:1:{i:0;s:18:\"doPopulateParentId\";}i:94;a:4:{i:0;s:8:\"checkBin\";i:1;s:16:\"protected_titles\";i:2;s:8:\"pt_title\";i:3;s:27:\"patch-pt_title-encoding.sql\";}i:95;a:1:{i:0;s:28:\"doMaybeProfilingMemoryUpdate\";}i:96;a:1:{i:0;s:26:\"doFilearchiveIndicesUpdate\";}i:97;a:4:{i:0;s:8:\"addField\";i:1;s:10:\"site_stats\";i:2;s:15:\"ss_active_users\";i:3;s:25:\"patch-ss_active_users.sql\";}i:98;a:1:{i:0;s:17:\"doActiveUsersInit\";}i:99;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"ipblocks\";i:2;s:18:\"ipb_allow_usertalk\";i:3;s:28:\"patch-ipb_allow_usertalk.sql\";}i:100;a:1:{i:0;s:14:\"doUniquePlTlIl\";}i:101;a:3:{i:0;s:8:\"addTable\";i:1;s:10:\"change_tag\";i:2;s:20:\"patch-change_tag.sql\";}i:102;a:3:{i:0;s:8:\"addTable\";i:1;s:11:\"tag_summary\";i:2;s:20:\"patch-change_tag.sql\";}i:103;a:3:{i:0;s:8:\"addTable\";i:1;s:9:\"valid_tag\";i:2;s:20:\"patch-change_tag.sql\";}i:104;a:3:{i:0;s:8:\"addTable\";i:1;s:15:\"user_properties\";i:2;s:25:\"patch-user_properties.sql\";}i:105;a:3:{i:0;s:8:\"addTable\";i:1;s:10:\"log_search\";i:2;s:20:\"patch-log_search.sql\";}i:106;a:1:{i:0;s:21:\"doLogSearchPopulation\";}i:107;a:4:{i:0;s:8:\"addField\";i:1;s:7:\"logging\";i:2;s:13:\"log_user_text\";i:3;s:23:\"patch-log_user_text.sql\";}i:108;a:3:{i:0;s:8:\"addTable\";i:1;s:10:\"l10n_cache\";i:2;s:20:\"patch-l10n_cache.sql\";}i:109;a:3:{i:0;s:8:\"addTable\";i:1;s:13:\"external_user\";i:2;s:23:\"patch-external_user.sql\";}i:110;a:4:{i:0;s:8:\"addIndex\";i:1;s:10:\"log_search\";i:2;s:12:\"ls_field_val\";i:3;s:33:\"patch-log_search-rename-index.sql\";}i:111;a:4:{i:0;s:8:\"addIndex\";i:1;s:10:\"change_tag\";i:2;s:17:\"change_tag_rc_tag\";i:3;s:28:\"patch-change_tag-indexes.sql\";}i:112;a:4:{i:0;s:8:\"addField\";i:1;s:8:\"redirect\";i:2;s:12:\"rd_interwiki\";i:3;s:22:\"patch-rd_interwiki.sql\";}i:113;a:1:{i:0;s:23:\"doUpdateTranscacheField\";}i:114;a:1:{i:0;s:14:\"renameEuWikiId\";}i:115;a:1:{i:0;s:22:\"doUpdateMimeMinorField\";}i:116;a:1:{i:0;s:16:\"doPopulateRevLen\";}i:117;a:3:{i:0;s:8:\"addTable\";i:1;s:7:\"iwlinks\";i:2;s:17:\"patch-iwlinks.sql\";}i:118;a:4:{i:0;s:8:\"addIndex\";i:1;s:7:\"iwlinks\";i:2;s:21:\"iwl_prefix_title_from\";i:3;s:27:\"patch-rename-iwl_prefix.sql\";}i:119;a:4:{i:0;s:8:\"addField\";i:1;s:9:\"updatelog\";i:2;s:8:\"ul_value\";i:3;s:18:\"patch-ul_value.sql\";}i:120;a:4:{i:0;s:8:\"addField\";i:1;s:9:\"interwiki\";i:2;s:6:\"iw_api\";i:3;s:27:\"patch-iw_api_and_wikiid.sql\";}i:121;a:4:{i:0;s:9:\"dropIndex\";i:1;s:7:\"iwlinks\";i:2;s:10:\"iwl_prefix\";i:3;s:25:\"patch-kill-iwl_prefix.sql\";}i:122;a:4:{i:0;s:9:\"dropIndex\";i:1;s:7:\"iwlinks\";i:2;s:21:\"iwl_prefix_from_title\";i:3;s:22:\"patch-kill-iwl_pft.sql\";}i:123;a:4:{i:0;s:8:\"addField\";i:1;s:13:\"categorylinks\";i:2;s:12:\"cl_collation\";i:3;s:40:\"patch-categorylinks-better-collation.sql\";}i:124;a:1:{i:0;s:16:\"doClFieldsUpdate\";}i:125;a:1:{i:0;s:17:\"doCollationUpdate\";}i:126;a:3:{i:0;s:8:\"addTable\";i:1;s:12:\"msg_resource\";i:2;s:22:\"patch-msg_resource.sql\";}i:127;a:3:{i:0;s:8:\"addTable\";i:1;s:11:\"module_deps\";i:2;s:21:\"patch-module_deps.sql\";}}');
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
INSERT INTO `mw_user` VALUES (1,'WikiSysop','',':B:9c595470:df2c1237ae75896744457e7dfbeb7f90','',NULL,'','','20110110173136','5e3b582786fa8150118cfa78f18de0c5',NULL,'\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',NULL,'20110107184113',1);
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
INSERT INTO `mw_user_groups` VALUES (1,'bureaucrat'),(1,'sysop');
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

-- Dump completed on 2011-01-10  9:34:34
