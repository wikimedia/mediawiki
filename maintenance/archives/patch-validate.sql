-- For article validation

CREATE TABLE `validate` (
  `val_user` int(11) NOT NULL default '0',
  `val_title` varchar(255) binary NOT NULL default '',
  `val_timestamp` varchar(14) binary NOT NULL default '',
  `val_type` int(10) unsigned NOT NULL default '0',
  `val_value` int(11) default '0',
  KEY `val_user` (`val_user`,`val_title`,`val_timestamp`)
) TYPE=MyISAM;

