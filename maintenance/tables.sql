-- SQL to create the initial tables for the Wikipedia database.
-- This is read and executed by the install script; you should
-- never have to run it by itself.
--
-- Indexes should be defined here; please import the rest from indexes.sql.

CREATE TABLE user (
  user_id int(5) unsigned NOT NULL auto_increment,
  user_name varchar(255) binary NOT NULL default '',
  user_rights tinyblob NOT NULL default '',
  user_password tinyblob NOT NULL default '',
  user_newpassword tinyblob NOT NULL default '',
  user_email tinytext NOT NULL default '',
  user_options blob NOT NULL default '',  
  user_touched char(14) binary NOT NULL default '',
  UNIQUE KEY user_id (user_id)
) PACK_KEYS=1;
	
CREATE TABLE user_newtalk (
  user_id int(5) NOT NULL default '0',
  user_ip varchar(40) NOT NULL default ''
);

CREATE TABLE cur (
  cur_id int(8) unsigned NOT NULL auto_increment,
  cur_namespace tinyint(2) unsigned NOT NULL default '0',
  cur_title varchar(255) binary NOT NULL default '',
  cur_text mediumtext NOT NULL default '',
  cur_comment tinyblob NOT NULL default '',
  cur_user int(5) unsigned NOT NULL default '0',
  cur_user_text varchar(255) binary NOT NULL default '',
  cur_timestamp char(14) binary NOT NULL default '',
  cur_restrictions tinyblob NOT NULL default '',
  cur_counter bigint(20) unsigned NOT NULL default '0',
  cur_is_redirect tinyint(1) unsigned NOT NULL default '0',
  cur_minor_edit tinyint(1) unsigned NOT NULL default '0',
  cur_is_new tinyint(1) unsigned NOT NULL default '0',
  cur_random real unsigned NOT NULL,
  cur_touched char(14) binary NOT NULL default '',
  inverse_timestamp char(14) binary NOT NULL default '',
  UNIQUE KEY cur_id (cur_id)
) PACK_KEYS=1;

CREATE TABLE old (
  old_id int(8) unsigned NOT NULL auto_increment,
  old_namespace tinyint(2) unsigned NOT NULL default '0',
  old_title varchar(255) binary NOT NULL default '',
  old_text mediumtext NOT NULL default '',
  old_comment tinyblob NOT NULL default '',
  old_user int(5) unsigned NOT NULL default '0',
  old_user_text varchar(255) binary NOT NULL,
  old_timestamp char(14) binary NOT NULL default '',
  old_minor_edit tinyint(1) NOT NULL default '0',
  old_flags tinyblob NOT NULL default '',
  inverse_timestamp char(14) binary NOT NULL default '',
  UNIQUE KEY old_id (old_id)
) PACK_KEYS=1;

CREATE TABLE archive (
  ar_namespace tinyint(2) unsigned NOT NULL default '0',
  ar_title varchar(255) binary NOT NULL default '',
  ar_text mediumtext NOT NULL default '',
  ar_comment tinyblob NOT NULL default '',
  ar_user int(5) unsigned NOT NULL default '0',
  ar_user_text varchar(255) binary NOT NULL,
  ar_timestamp char(14) binary NOT NULL default '',
  ar_minor_edit tinyint(1) NOT NULL default '0',
  ar_flags tinyblob NOT NULL default ''
) PACK_KEYS=1;

--
-- Track links that do exist
-- l_from and l_to key to cur_id
--
CREATE TABLE links (
  l_from int(8) unsigned NOT NULL default '0',
  l_to int(8) unsigned NOT NULL default '0',
  UNIQUE KEY l_from(l_from,l_to),
  KEY (l_to)
);

--
-- Track links to pages that don't yet exist.
-- bl_from keys to cur_id
-- bl_to is a text link (namespace:title)
--
CREATE TABLE brokenlinks (
  bl_from int(8) unsigned NOT NULL default '0',
  bl_to varchar(255) binary NOT NULL default '',
  UNIQUE KEY bl_from(bl_from,bl_to),
  KEY (bl_to)
);

--
-- Track links to images *used inline*
-- il_from keys to cur_id, il_to keys to image_name.
-- We don't distinguish live from broken links.
--
CREATE TABLE imagelinks (
  il_from int(8) unsigned NOT NULL default '0',
  il_to varchar(255) binary NOT NULL default '',
  UNIQUE KEY il_from(il_from,il_to),
  KEY (il_to)
);

--
-- Stores (possibly gzipped) serialized objects with
-- cache arrays to reduce database load slurping up
-- from links and brokenlinks.
--
CREATE TABLE linkscc (
  lcc_pageid INT UNSIGNED NOT NULL UNIQUE KEY,
  lcc_cacheobj MEDIUMBLOB NOT NULL
);

CREATE TABLE site_stats (
  ss_row_id int(8) unsigned NOT NULL,
  ss_total_views bigint(20) unsigned default '0',
  ss_total_edits bigint(20) unsigned default '0',
  ss_good_articles bigint(20) unsigned default '0',
  UNIQUE KEY ss_row_id (ss_row_id)
);

CREATE TABLE hitcounter (
  hc_id INTEGER UNSIGNED NOT NULL
) TYPE=HEAP MAX_ROWS=25000;

CREATE TABLE ipblocks (
  ipb_id int(8) NOT NULL auto_increment,
  ipb_address varchar(40) binary NOT NULL default '',
  ipb_user int(8) unsigned NOT NULL default '0',
  ipb_by int(8) unsigned NOT NULL default '0',
  ipb_reason tinyblob NOT NULL default '',
  ipb_timestamp char(14) binary NOT NULL default '',
  ipb_auto tinyint(1) NOT NULL default '0',
  ipb_expiry char(14) binary NOT NULL default '',
  UNIQUE KEY ipb_id (ipb_id)
) PACK_KEYS=1;

CREATE TABLE image (
  img_name varchar(255) binary NOT NULL default '',
  img_size int(8) unsigned NOT NULL default '0',
  img_description tinyblob NOT NULL default '',
  img_user int(5) unsigned NOT NULL default '0',
  img_user_text varchar(255) binary NOT NULL default '',
  img_timestamp char(14) binary NOT NULL default ''
) PACK_KEYS=1;

CREATE TABLE oldimage (
  oi_name varchar(255) binary NOT NULL default '',
  oi_archive_name varchar(255) binary NOT NULL default '',
  oi_size int(8) unsigned NOT NULL default 0,
  oi_description tinyblob NOT NULL default '',
  oi_user int(5) unsigned NOT NULL default '0',
  oi_user_text varchar(255) binary NOT NULL default '',
  oi_timestamp char(14) binary NOT NULL default ''
) PACK_KEYS=1;

CREATE TABLE recentchanges (
  rc_timestamp varchar(14) binary NOT NULL default '',
  rc_cur_time varchar(14) binary NOT NULL default '',
  rc_user int(10) unsigned NOT NULL default '0',
  rc_user_text varchar(255) binary NOT NULL default '',
  rc_namespace tinyint(3) unsigned NOT NULL default '0',
  rc_title varchar(255) binary NOT NULL default '',
  rc_comment varchar(255) binary NOT NULL default '',
  rc_minor tinyint(3) unsigned NOT NULL default '0',
  rc_bot tinyint(3) unsigned NOT NULL default '0',
  rc_new tinyint(3) unsigned NOT NULL default '0',
  rc_cur_id int(10) unsigned NOT NULL default '0',
  rc_this_oldid int(10) unsigned NOT NULL default '0',
  rc_last_oldid int(10) unsigned NOT NULL default '0',
  rc_type tinyint(3) unsigned NOT NULL default '0',
  rc_moved_to_ns tinyint(3) unsigned NOT NULL default '0',
  rc_moved_to_title varchar(255) binary NOT NULL default ''
) PACK_KEYS=1;

CREATE TABLE watchlist (
  wl_user int(5) unsigned NOT NULL,
  wl_namespace tinyint(2) unsigned NOT NULL default '0',
  wl_title varchar(255) binary NOT NULL default '',
  UNIQUE KEY (wl_user, wl_namespace, wl_title)
) PACK_KEYS=1;

CREATE TABLE math (
  math_inputhash varchar(16) NOT NULL,
  math_outputhash varchar(16) NOT NULL,
  math_html_conservativeness tinyint(1) NOT NULL,
  math_html text,
  math_mathml text,
  UNIQUE KEY math_inputhash (math_inputhash)
);


-- Table searchindex must be MyISAM for fulltext support

CREATE TABLE searchindex (
  si_page int(8) unsigned NOT NULL,
  si_title varchar(255) NOT NULL default '',
  si_text mediumtext NOT NULL default '',
  UNIQUE KEY (si_page)
) PACK_KEYS=1;

CREATE TABLE interwiki (
  iw_prefix char(32) NOT NULL,
  iw_url char(127) NOT NULL,
  iw_local BOOL NOT NULL,
  UNIQUE KEY iw_prefix (iw_prefix)
);

