--
-- Tracks people currently editing an article
-- Enabled with $wgAjaxShowEditors = true;
--

CREATE TABLE /*$wgDBprefix*/editings (
  `editings_page` int(8) NOT NULL,
  `editings_user` varchar(255) NOT NULL,
  `editings_started` char(14) NOT NULL,
  `editings_touched` char(14) NOT NULL,
  PRIMARY KEY  (`editings_page`,`editings_user`),
  KEY `editings_page` (`editings_page`)
  KEY `editings_page_started` (`editings_page`,`editings_user`,`editings_started`)
) TYPE=InnoDB;
