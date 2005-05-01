-- SQL to create the initial tables for the Wikipedia database.
-- This is read and executed by the install script; you should
-- never have to run it by itself.
--
-- Indexes should be defined here; please import the rest from indexes.sql.

CREATE TABLE /*$wgDBprefix*/user (
  user_id int(5) unsigned NOT NULL auto_increment,
  user_name varchar(255) binary NOT NULL default '',
  user_real_name varchar(255) binary NOT NULL default '',
  user_password tinyblob NOT NULL default '',
  user_newpassword tinyblob NOT NULL default '',
  user_email tinytext NOT NULL default '',
  user_emailauthenticationtimestamp varchar(14) binary NOT NULL default '0',
  user_options blob NOT NULL default '',
  user_touched char(14) binary NOT NULL default '',
  user_token char(32) binary NOT NULL default '',
  user_email_authenticated CHAR(14) BINARY,
  user_email_token CHAR(32) BINARY,
  user_email_token_expires CHAR(14) BINARY,

  PRIMARY KEY user_id (user_id),
  INDEX user_name (user_name(10)),
  INDEX (user_email_token)
);

-- TODO: de-blob this; it should be a property table
CREATE TABLE /*$wgDBprefix*/user_rights (
  ur_user int(5) unsigned NOT NULL,
  ur_rights tinyblob NOT NULL default '',
  UNIQUE KEY ur_user (ur_user)
);

-- The following table is no longer needed with Enotif >= 2.00
-- Entries for newtalk on user_talk page are handled like in the watchlist table
-- CREATE TABLE /*$wgDBprefix*/user_newtalk (
--  user_id int(5) NOT NULL default '0',
--  user_ip varchar(40) NOT NULL default '',
--  INDEX user_id (user_id),
--  INDEX user_ip (user_ip)
-- );

CREATE TABLE /*$wgDBprefix*/page (
  -- Identifiers:
  page_id int(8) unsigned NOT NULL auto_increment,
  page_namespace tinyint NOT NULL,
  page_title varchar(255) binary NOT NULL,
  
  -- Mutable information
  page_restrictions tinyblob NOT NULL default '',
  page_counter bigint(20) unsigned NOT NULL default '0',
  page_is_redirect tinyint(1) unsigned NOT NULL default '0',
  page_is_new tinyint(1) unsigned NOT NULL default '0',
  page_random real unsigned NOT NULL,
  page_touched char(14) binary NOT NULL default '',
  
  -- Handy key to revision.rev_id of the current revision
  page_latest int(8) unsigned NOT NULL,
  page_len int(8) unsigned NOT NULL,

  PRIMARY KEY page_id (page_id),
  UNIQUE INDEX name_title (page_namespace,page_title),
  
  -- Special-purpose indexes
  INDEX (page_random),
  INDEX (page_len)
);

CREATE TABLE /*$wgDBprefix*/revision (
  rev_id int(8) unsigned NOT NULL auto_increment,
  rev_page int(8) unsigned NOT NULL,
  rev_text_id int(8) unsigned NOT NULL,
  rev_comment tinyblob NOT NULL default '',
  rev_user int(5) unsigned NOT NULL default '0',
  rev_user_text varchar(255) binary NOT NULL default '',
  rev_timestamp char(14) binary NOT NULL default '',
  rev_minor_edit tinyint(1) unsigned NOT NULL default '0',
  rev_deleted tinyint(1) unsigned NOT NULL default '0',
  
  PRIMARY KEY rev_page_id (rev_page, rev_id),
  UNIQUE INDEX rev_id (rev_id),
  INDEX rev_timestamp (rev_timestamp),
  INDEX page_timestamp (rev_page,rev_timestamp),
  INDEX user_timestamp (rev_user,rev_timestamp),
  INDEX usertext_timestamp (rev_user_text,rev_timestamp)
);


--
-- Holds text of individual page revisions.
--
CREATE TABLE /*$wgDBprefix*/text (
  old_id int(8) unsigned NOT NULL auto_increment,
  old_text mediumtext NOT NULL default '',
  old_flags tinyblob NOT NULL default '',
  
  PRIMARY KEY old_id (old_id)
);

CREATE TABLE /*$wgDBprefix*/archive (
  ar_namespace tinyint(2) unsigned NOT NULL default '0',
  ar_title varchar(255) binary NOT NULL default '',
  ar_text mediumtext NOT NULL default '',
  ar_comment tinyblob NOT NULL default '',
  ar_user int(5) unsigned NOT NULL default '0',
  ar_user_text varchar(255) binary NOT NULL,
  ar_timestamp char(14) binary NOT NULL default '',
  ar_minor_edit tinyint(1) NOT NULL default '0',
  ar_flags tinyblob NOT NULL default '',
  ar_rev_id int(8) unsigned,
  ar_text_id int(8) unsigned,
  
  KEY name_title_timestamp (ar_namespace,ar_title,ar_timestamp)
);

--
-- Track links that do exist
-- l_from and l_to key to cur_id
--
CREATE TABLE /*$wgDBprefix*/links (
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
CREATE TABLE /*$wgDBprefix*/brokenlinks (
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
CREATE TABLE /*$wgDBprefix*/imagelinks (
  il_from int(8) unsigned NOT NULL default '0',
  il_to varchar(255) binary NOT NULL default '',
  UNIQUE KEY il_from(il_from,il_to),
  KEY (il_to)
);

--
-- Track category inclusions *used inline*
-- cl_from keys to cur_id, cl_to keys to cur_title of the category page.
-- cl_sortkey is the title of the linking page or an optional override
-- cl_timestamp marks when the link was last added
--
CREATE TABLE /*$wgDBprefix*/categorylinks (
  cl_from int(8) unsigned NOT NULL default '0',
  cl_to varchar(255) binary NOT NULL default '',
  cl_sortkey varchar(255) binary NOT NULL default '',
  cl_timestamp timestamp NOT NULL,
  UNIQUE KEY cl_from(cl_from,cl_to),
  KEY cl_sortkey(cl_to,cl_sortkey(128)),
  KEY cl_timestamp(cl_to,cl_timestamp)
);

--
-- Stores (possibly gzipped) serialized objects with
-- cache arrays to reduce database load slurping up
-- from links and brokenlinks.
--
CREATE TABLE /*$wgDBprefix*/linkscc (
  lcc_pageid INT UNSIGNED NOT NULL UNIQUE KEY,
  lcc_cacheobj MEDIUMBLOB NOT NULL
);

CREATE TABLE /*$wgDBprefix*/site_stats (
  ss_row_id int(8) unsigned NOT NULL,
  ss_total_views bigint(20) unsigned default '0',
  ss_total_edits bigint(20) unsigned default '0',
  ss_good_articles bigint(20) unsigned default '0',
  UNIQUE KEY ss_row_id (ss_row_id)
);

CREATE TABLE /*$wgDBprefix*/hitcounter (
  hc_id INTEGER UNSIGNED NOT NULL
) TYPE=HEAP MAX_ROWS=25000;

CREATE TABLE /*$wgDBprefix*/ipblocks (
  ipb_id int(8) NOT NULL auto_increment,
  ipb_address varchar(40) binary NOT NULL default '',
  ipb_user int(8) unsigned NOT NULL default '0',
  ipb_by int(8) unsigned NOT NULL default '0',
  ipb_reason tinyblob NOT NULL default '',
  ipb_timestamp char(14) binary NOT NULL default '',
  ipb_auto tinyint(1) NOT NULL default '0',
  ipb_expiry char(14) binary NOT NULL default '',

  PRIMARY KEY ipb_id (ipb_id),
  INDEX ipb_address (ipb_address),
  INDEX ipb_user (ipb_user)
);

CREATE TABLE /*$wgDBprefix*/image (
  img_name varchar(255) binary NOT NULL default '',
  img_size int(8) unsigned NOT NULL default '0',
  img_width int(5)  NOT NULL default '0',
  img_height int(5)  NOT NULL default '0',
  img_metadata mediumblob NOT NULL,
  img_bits int(3)  NOT NULL default '0',
  img_type int(3)  NOT NULL default '0',
  img_description tinyblob NOT NULL default '',
  img_user int(5) unsigned NOT NULL default '0',
  img_user_text varchar(255) binary NOT NULL default '',
  img_timestamp char(14) binary NOT NULL default '',
  
  PRIMARY KEY img_name (img_name),
  INDEX img_size (img_size),
  INDEX img_timestamp (img_timestamp)
);

CREATE TABLE /*$wgDBprefix*/oldimage (
  oi_name varchar(255) binary NOT NULL default '',
  oi_archive_name varchar(255) binary NOT NULL default '',
  oi_size int(8) unsigned NOT NULL default 0,
  oi_width int(5) NOT NULL default 0,
  oi_height int(5) NOT NULL default 0,
  oi_bits int(3) NOT NULL default 0,
  oi_type int(3) NOT NULL default 0,
  oi_description tinyblob NOT NULL default '',
  oi_user int(5) unsigned NOT NULL default '0',
  oi_user_text varchar(255) binary NOT NULL default '',
  oi_timestamp char(14) binary NOT NULL default '',

  INDEX oi_name (oi_name(10))
);

CREATE TABLE /*$wgDBprefix*/recentchanges (
  rc_id int(8) NOT NULL auto_increment,
  rc_timestamp varchar(14) binary NOT NULL default '',
  rc_cur_time varchar(14) binary NOT NULL default '',
  rc_user int(10) unsigned NOT NULL default '0',
  rc_user_text varchar(255) binary NOT NULL default '',
  rc_namespace tinyint(3) NOT NULL default '0',
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
  rc_moved_to_title varchar(255) binary NOT NULL default '',
  rc_patrolled tinyint(3) unsigned NOT NULL default '0',
  rc_ip char(15) NOT NULL default '',
  
  PRIMARY KEY rc_id (rc_id),
  INDEX rc_timestamp (rc_timestamp),
  INDEX rc_namespace_title (rc_namespace, rc_title),
  INDEX rc_cur_id (rc_cur_id),
  INDEX new_name_timestamp(rc_new,rc_namespace,rc_timestamp),
  INDEX rc_ip (rc_ip)
);

CREATE TABLE /*$wgDBprefix*/watchlist (
  wl_user int(5) unsigned NOT NULL,
  wl_namespace tinyint(2) unsigned NOT NULL default '0',
  wl_title varchar(255) binary NOT NULL default '',
  wl_notificationtimestamp varchar(14) binary NOT NULL default '0',
  UNIQUE KEY (wl_user, wl_namespace, wl_title),
  KEY namespace_title (wl_namespace,wl_title)
);

CREATE TABLE /*$wgDBprefix*/math (
  math_inputhash varchar(16) NOT NULL,
  math_outputhash varchar(16) NOT NULL,
  math_html_conservativeness tinyint(1) NOT NULL,
  math_html text,
  math_mathml text,
  UNIQUE KEY math_inputhash (math_inputhash)
);


-- Table searchindex must be MyISAM for fulltext support

CREATE TABLE /*$wgDBprefix*/searchindex (
  si_page int(8) unsigned NOT NULL,
  si_title varchar(255) NOT NULL default '',
  si_text mediumtext NOT NULL default '',
  UNIQUE KEY (si_page),
  FULLTEXT si_title (si_title),
  FULLTEXT si_text (si_text)

) TYPE=MyISAM;

CREATE TABLE /*$wgDBprefix*/interwiki (
  iw_prefix char(32) NOT NULL,
  iw_url char(127) NOT NULL,
  iw_local BOOL NOT NULL,
  UNIQUE KEY iw_prefix (iw_prefix)
);

-- Used for caching expensive grouped queries
CREATE TABLE /*$wgDBprefix*/querycache (
  qc_type char(32) NOT NULL,
  qc_value int(5) unsigned NOT NULL default '0',
  qc_namespace tinyint(2) unsigned NOT NULL default '0',
  qc_title char(255) binary NOT NULL default '',
  KEY (qc_type,qc_value)
);

-- For a few generic cache operations if not using Memcached
CREATE TABLE /*$wgDBprefix*/objectcache (
  keyname char(255) binary not null default '',
  value mediumblob,
  exptime datetime,
  unique key (keyname),
  key (exptime)
);

-- For storing revision text
CREATE TABLE /*$wgDBprefix*/blobs (
  blob_index char(255) binary NOT NULL default '',
  blob_data longblob NOT NULL default '',
  UNIQUE key blob_index (blob_index)
);

-- For article validation

CREATE TABLE /*$wgDBprefix*/validate (
  `val_user` int(11) NOT NULL default '0',
  `val_page` int(11) unsigned NOT NULL default '0',
  `val_revision` int(11) unsigned NOT NULL default '0',
  `val_type` int(11) unsigned NOT NULL default '0',
  `val_value` int(11) default '0',
  `val_comment` varchar(255) NOT NULL default '',
  KEY `val_user` (`val_user`,`val_revision`)
) TYPE=MyISAM;

CREATE TABLE /*$wgDBprefix*/logging (
  -- Symbolic keys for the general log type and the action type
  -- within the log. The output format will be controlled by the
  -- action field, but only the type controls categorization.
  log_type char(10) NOT NULL default '',
  log_action char(10) NOT NULL default '',
  
  -- Timestamp. Duh.
  log_timestamp char(14) NOT NULL default '19700101000000',
  
  -- The user who performed this action; key to user_id
  log_user int unsigned NOT NULL default 0,
  
  -- Key to the page affected. Where a user is the target,
  -- this will point to the user page.
  log_namespace tinyint unsigned NOT NULL default 0,
  log_title varchar(255) binary NOT NULL default '',
  
  -- Freeform text. Interpreted as edit history comments.
  log_comment varchar(255) NOT NULL default '',
  
  -- LF separated list of miscellaneous parameters
  log_params blob NOT NULL default '',

  KEY type_time (log_type, log_timestamp),
  KEY user_time (log_user, log_timestamp),
  KEY page_time (log_namespace, log_title, log_timestamp)
);





-- Hold group name and description
CREATE TABLE /*$wgDBprefix*/`group` (
  group_id int(5) unsigned NOT NULL auto_increment,
  group_name varchar(50) NOT NULL default '',
  group_description varchar(255) NOT NULL default '',
  group_rights tinyblob,
  PRIMARY KEY  (group_id)
);

-- Relation table between user and groups
CREATE TABLE /*$wgDBprefix*/user_groups (
	ug_user int(5) unsigned NOT NULL default '0',
	ug_group int(5) unsigned NOT NULL default '0',
	PRIMARY KEY  (ug_user,ug_group)
);
