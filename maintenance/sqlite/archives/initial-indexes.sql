-- Correct for the total lack of indexes in the MW 1.13 SQLite schema
--
-- Unique indexes need to be handled with INSERT SELECT since just running
-- the CREATE INDEX statement will fail if there are duplicate values.
--
-- Ignore duplicates, several tables will have them (e.g. T18966) but in
-- most cases it's harmless to discard them.

--------------------------------------------------------------------------------
-- Drop temporary tables from aborted runs
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS /*_*/user_tmp;
DROP TABLE IF EXISTS /*_*/user_groups_tmp;
DROP TABLE IF EXISTS /*_*/page_tmp;
DROP TABLE IF EXISTS /*_*/revision_tmp;
DROP TABLE IF EXISTS /*_*/pagelinks_tmp;
DROP TABLE IF EXISTS /*_*/templatelinks_tmp;
DROP TABLE IF EXISTS /*_*/imagelinks_tmp;
DROP TABLE IF EXISTS /*_*/categorylinks_tmp;
DROP TABLE IF EXISTS /*_*/category_tmp;
DROP TABLE IF EXISTS /*_*/langlinks_tmp;
DROP TABLE IF EXISTS /*_*/site_stats_tmp;
DROP TABLE IF EXISTS /*_*/ipblocks_tmp;
DROP TABLE IF EXISTS /*_*/watchlist_tmp;
DROP TABLE IF EXISTS /*_*/math_tmp;
DROP TABLE IF EXISTS /*_*/interwiki_tmp;
DROP TABLE IF EXISTS /*_*/page_restrictions_tmp;
DROP TABLE IF EXISTS /*_*/protected_titles_tmp;
DROP TABLE IF EXISTS /*_*/page_props_tmp;
DROP TABLE IF EXISTS /*_*/archive_tmp;
DROP TABLE IF EXISTS /*_*/externallinks_tmp;

--------------------------------------------------------------------------------
-- Create new tables
--------------------------------------------------------------------------------

CREATE TABLE /*_*/user_tmp (
  user_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_name varchar(255) binary NOT NULL default '',
  user_real_name varchar(255) binary NOT NULL default '',
  user_password tinyblob NOT NULL,
  user_newpassword tinyblob NOT NULL,
  user_newpass_time binary(14),
  user_email tinytext NOT NULL,
  user_options blob NOT NULL,
  user_touched binary(14) NOT NULL default '',
  user_token binary(32) NOT NULL default '',
  user_email_authenticated binary(14),
  user_email_token binary(32),
  user_email_token_expires binary(14),
  user_registration binary(14),
  user_editcount int
);
CREATE UNIQUE INDEX /*i*/user_name ON /*_*/user_tmp (user_name);
CREATE INDEX /*i*/user_email_token ON /*_*/user_tmp (user_email_token);


CREATE TABLE /*_*/user_groups_tmp (
  ug_user int unsigned NOT NULL default 0,
  ug_group varbinary(16) NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/ug_user_group ON /*_*/user_groups_tmp (ug_user,ug_group);
CREATE INDEX /*i*/ug_group ON /*_*/user_groups_tmp (ug_group);

CREATE TABLE /*_*/page_tmp (
  page_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  page_namespace int NOT NULL,
  page_title varchar(255) binary NOT NULL,
  page_restrictions tinyblob NOT NULL,
  page_is_redirect tinyint unsigned NOT NULL default 0,
  page_is_new tinyint unsigned NOT NULL default 0,
  page_random real unsigned NOT NULL,
  page_touched binary(14) NOT NULL default '',
  page_latest int unsigned NOT NULL,
  page_len int unsigned NOT NULL
);

CREATE UNIQUE INDEX /*i*/name_title ON /*_*/page_tmp (page_namespace,page_title);
CREATE INDEX /*i*/page_random ON /*_*/page_tmp (page_random);
CREATE INDEX /*i*/page_len ON /*_*/page_tmp (page_len);


CREATE TABLE /*_*/revision_tmp (
  rev_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rev_page int unsigned NOT NULL,
  rev_text_id int unsigned NOT NULL,
  rev_comment tinyblob NOT NULL,
  rev_user int unsigned NOT NULL default 0,
  rev_user_text varchar(255) binary NOT NULL default '',
  rev_timestamp binary(14) NOT NULL default '',
  rev_minor_edit tinyint unsigned NOT NULL default 0,
  rev_deleted tinyint unsigned NOT NULL default 0,
  rev_len int unsigned,
  rev_parent_id int unsigned default NULL
);
CREATE UNIQUE INDEX /*i*/rev_page_id ON /*_*/revision_tmp (rev_page, rev_id);
CREATE INDEX /*i*/rev_timestamp ON /*_*/revision_tmp (rev_timestamp);
CREATE INDEX /*i*/page_timestamp ON /*_*/revision_tmp (rev_page,rev_timestamp);
CREATE INDEX /*i*/user_timestamp ON /*_*/revision_tmp (rev_user,rev_timestamp);
CREATE INDEX /*i*/usertext_timestamp ON /*_*/revision_tmp (rev_user_text,rev_timestamp);

CREATE TABLE /*_*/pagelinks_tmp (
  pl_from int unsigned NOT NULL default 0,
  pl_namespace int NOT NULL default 0,
  pl_title varchar(255) binary NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/pl_from ON /*_*/pagelinks_tmp (pl_from,pl_namespace,pl_title);
CREATE INDEX /*i*/pl_namespace_title ON /*_*/pagelinks_tmp (pl_namespace,pl_title,pl_from);


CREATE TABLE /*_*/templatelinks_tmp (
  tl_from int unsigned NOT NULL default 0,
  tl_namespace int NOT NULL default 0,
  tl_title varchar(255) binary NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/tl_from ON /*_*/templatelinks_tmp (tl_from,tl_namespace,tl_title);
CREATE INDEX /*i*/tl_namespace_title ON /*_*/templatelinks_tmp (tl_namespace,tl_title,tl_from);


CREATE TABLE /*_*/imagelinks_tmp (
  il_from int unsigned NOT NULL default 0,
  il_to varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/il_from ON /*_*/imagelinks_tmp (il_from,il_to);
CREATE INDEX /*i*/il_to ON /*_*/imagelinks_tmp (il_to,il_from);


CREATE TABLE /*_*/categorylinks_tmp (
  cl_from int unsigned NOT NULL default 0,
  cl_to varchar(255) binary NOT NULL default '',
  cl_sortkey varchar(70) binary NOT NULL default '',
  cl_timestamp timestamp NOT NULL
);
CREATE UNIQUE INDEX /*i*/cl_from ON /*_*/categorylinks_tmp (cl_from,cl_to);
CREATE INDEX /*i*/cl_sortkey ON /*_*/categorylinks_tmp (cl_to,cl_sortkey,cl_from);
CREATE INDEX /*i*/cl_timestamp ON /*_*/categorylinks_tmp (cl_to,cl_timestamp);


CREATE TABLE /*_*/category_tmp (
  cat_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  cat_title varchar(255) binary NOT NULL,
  cat_pages int signed NOT NULL default 0,
  cat_subcats int signed NOT NULL default 0,
  cat_files int signed NOT NULL default 0,
  cat_hidden tinyint unsigned NOT NULL default 0
);
CREATE UNIQUE INDEX /*i*/cat_title ON /*_*/category_tmp (cat_title);
CREATE INDEX /*i*/cat_pages ON /*_*/category_tmp (cat_pages);

CREATE TABLE /*_*/langlinks_tmp (
  ll_from int unsigned NOT NULL default 0,
  ll_lang varbinary(20) NOT NULL default '',
  ll_title varchar(255) binary NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/ll_from ON /*_*/langlinks_tmp (ll_from, ll_lang);
CREATE INDEX /*i*/ll_lang_title ON /*_*/langlinks_tmp (ll_lang, ll_title);


CREATE TABLE /*_*/site_stats_tmp (
  ss_row_id int unsigned NOT NULL,
  ss_total_edits bigint unsigned default 0,
  ss_good_articles bigint unsigned default 0,
  ss_total_pages bigint default '-1',
  ss_users bigint default '-1',
  ss_active_users bigint default '-1',
  ss_admins int default '-1',
  ss_images int default 0
);
CREATE UNIQUE INDEX /*i*/ss_row_id ON /*_*/site_stats_tmp (ss_row_id);


CREATE TABLE /*_*/ipblocks_tmp (
  ipb_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ipb_address tinyblob NOT NULL,
  ipb_user int unsigned NOT NULL default 0,
  ipb_by int unsigned NOT NULL default 0,
  ipb_by_text varchar(255) binary NOT NULL default '',
  ipb_reason tinyblob NOT NULL,
  ipb_timestamp binary(14) NOT NULL default '',
  ipb_auto bool NOT NULL default 0,

  -- If set to 1, block applies only to logged-out users
  ipb_anon_only bool NOT NULL default 0,
  ipb_create_account bool NOT NULL default 1,
  ipb_enable_autoblock bool NOT NULL default '1',
  ipb_expiry varbinary(14) NOT NULL default '',
  ipb_range_start tinyblob NOT NULL,
  ipb_range_end tinyblob NOT NULL,
  ipb_deleted bool NOT NULL default 0,
  ipb_block_email bool NOT NULL default 0,
  ipb_allow_usertalk bool NOT NULL default 0
);
CREATE UNIQUE INDEX /*i*/ipb_address ON /*_*/ipblocks_tmp (ipb_address(255), ipb_user, ipb_auto, ipb_anon_only);
CREATE INDEX /*i*/ipb_user ON /*_*/ipblocks_tmp (ipb_user);
CREATE INDEX /*i*/ipb_range ON /*_*/ipblocks_tmp (ipb_range_start(8), ipb_range_end(8));
CREATE INDEX /*i*/ipb_timestamp ON /*_*/ipblocks_tmp (ipb_timestamp);
CREATE INDEX /*i*/ipb_expiry ON /*_*/ipblocks_tmp (ipb_expiry);


CREATE TABLE /*_*/watchlist_tmp (
  wl_user int unsigned NOT NULL,
  wl_namespace int NOT NULL default 0,
  wl_title varchar(255) binary NOT NULL default '',
  wl_notificationtimestamp varbinary(14)
);

CREATE UNIQUE INDEX /*i*/wl_user_namespace_title ON /*_*/watchlist_tmp (wl_user, wl_namespace, wl_title);
CREATE INDEX /*i*/namespace_title ON /*_*/watchlist_tmp (wl_namespace, wl_title);


CREATE TABLE /*_*/math_tmp (
  math_inputhash varbinary(16) NOT NULL,
  math_outputhash varbinary(16) NOT NULL,
  math_html_conservativeness tinyint NOT NULL,
  math_html text,
  math_mathml text
);

CREATE UNIQUE INDEX /*i*/math_inputhash ON /*_*/math_tmp (math_inputhash);


CREATE TABLE /*_*/interwiki_tmp (
  iw_prefix varchar(32) NOT NULL,
  iw_url blob NOT NULL,
  iw_local bool NOT NULL,
  iw_trans tinyint NOT NULL default 0
);

CREATE UNIQUE INDEX /*i*/iw_prefix ON /*_*/interwiki_tmp (iw_prefix);


CREATE TABLE /*_*/page_restrictions_tmp (
  pr_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  pr_page int NOT NULL,
  pr_type varbinary(60) NOT NULL,
  pr_level varbinary(60) NOT NULL,
  pr_cascade tinyint NOT NULL,
  pr_user int NULL,
  pr_expiry varbinary(14) NULL
);

CREATE UNIQUE INDEX /*i*/pr_pagetype ON /*_*/page_restrictions_tmp (pr_page,pr_type);
CREATE UNIQUE INDEX /*i*/pr_typelevel ON /*_*/page_restrictions_tmp (pr_type,pr_level);
CREATE UNIQUE INDEX /*i*/pr_level ON /*_*/page_restrictions_tmp (pr_level);
CREATE UNIQUE INDEX /*i*/pr_cascade ON /*_*/page_restrictions_tmp (pr_cascade);

CREATE TABLE /*_*/protected_titles_tmp (
  pt_namespace int NOT NULL,
  pt_title varchar(255) binary NOT NULL,
  pt_user int unsigned NOT NULL,
  pt_reason tinyblob,
  pt_timestamp binary(14) NOT NULL,
  pt_expiry varbinary(14) NOT NULL default '',
  pt_create_perm varbinary(60) NOT NULL
);
CREATE UNIQUE INDEX /*i*/pt_namespace_title ON /*_*/protected_titles_tmp (pt_namespace,pt_title);
CREATE INDEX /*i*/pt_timestamp ON /*_*/protected_titles_tmp (pt_timestamp);

CREATE TABLE /*_*/page_props_tmp (
  pp_page int NOT NULL,
  pp_propname varbinary(60) NOT NULL,
  pp_value blob NOT NULL
);
CREATE UNIQUE INDEX /*i*/pp_page_propname ON /*_*/page_props_tmp (pp_page,pp_propname);

--
-- Holding area for deleted articles, which may be viewed
-- or restored by admins through the Special:Undelete interface.
-- The fields generally correspond to the page, revision, and text
-- fields, with several caveats.
-- Cannot reasonably create views on this table, due to the presence of TEXT
-- columns.
CREATE TABLE /*$wgDBprefix*/archive_tmp (
   ar_id NOT NULL PRIMARY KEY clustered IDENTITY,
   ar_namespace SMALLINT NOT NULL DEFAULT 0,
   ar_title NVARCHAR(255) NOT NULL DEFAULT '',
   ar_text NVARCHAR(MAX) NOT NULL,
   ar_comment NVARCHAR(255) NOT NULL,
   ar_user INT NULL REFERENCES /*$wgDBprefix*/[user](user_id) ON DELETE SET NULL,
   ar_user_text NVARCHAR(255) NOT NULL,
   ar_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   ar_minor_edit BIT NOT NULL DEFAULT 0,
   ar_flags NVARCHAR(255) NOT NULL,
   ar_rev_id INT,
   ar_text_id INT,
   ar_deleted BIT NOT NULL DEFAULT 0,
   ar_len INT DEFAULT NULL,
   ar_page_id INT NULL,
   ar_parent_id INT NULL
);
CREATE INDEX /*$wgDBprefix*/ar_name_title_timestamp ON /*$wgDBprefix*/archive_tmp(ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*$wgDBprefix*/ar_usertext_timestamp ON /*$wgDBprefix*/archive_tmp(ar_user_text,ar_timestamp);
CREATE INDEX /*$wgDBprefix*/ar_user_text    ON /*$wgDBprefix*/archive_tmp(ar_user_text);

--
-- Track links to external URLs
-- IE >= 4 supports no more than 2083 characters in a URL
CREATE TABLE /*$wgDBprefix*/externallinks_tmp (
   el_id INT NOT NULL PRIMARY KEY clustered IDENTITY,
   el_from INT NOT NULL DEFAULT '0',
   el_to VARCHAR(2083) NOT NULL,
   el_index VARCHAR(896) NOT NULL,
);
-- Maximum key length ON SQL Server is 900 bytes
CREATE INDEX /*$wgDBprefix*/externallinks_index   ON /*$wgDBprefix*/externallinks_tmp(el_index);

--------------------------------------------------------------------------------
-- Populate the new tables using INSERT SELECT
--------------------------------------------------------------------------------

INSERT OR IGNORE INTO /*_*/user_tmp SELECT * FROM /*_*/user;
INSERT OR IGNORE INTO /*_*/user_groups_tmp SELECT * FROM /*_*/user_groups;
INSERT OR IGNORE INTO /*_*/page_tmp SELECT * FROM /*_*/page;
INSERT OR IGNORE INTO /*_*/revision_tmp SELECT * FROM /*_*/revision;
INSERT OR IGNORE INTO /*_*/pagelinks_tmp SELECT * FROM /*_*/pagelinks;
INSERT OR IGNORE INTO /*_*/templatelinks_tmp SELECT * FROM /*_*/templatelinks;
INSERT OR IGNORE INTO /*_*/imagelinks_tmp SELECT * FROM /*_*/imagelinks;
INSERT OR IGNORE INTO /*_*/categorylinks_tmp SELECT * FROM /*_*/categorylinks;
INSERT OR IGNORE INTO /*_*/category_tmp SELECT * FROM /*_*/category;
INSERT OR IGNORE INTO /*_*/langlinks_tmp SELECT * FROM /*_*/langlinks;
INSERT OR IGNORE INTO /*_*/site_stats_tmp SELECT * FROM /*_*/site_stats;
INSERT OR IGNORE INTO /*_*/ipblocks_tmp SELECT * FROM /*_*/ipblocks;
INSERT OR IGNORE INTO /*_*/watchlist_tmp SELECT * FROM /*_*/watchlist;
INSERT OR IGNORE INTO /*_*/math_tmp SELECT * FROM /*_*/math;
INSERT OR IGNORE INTO /*_*/interwiki_tmp SELECT * FROM /*_*/interwiki;
INSERT OR IGNORE INTO /*_*/page_restrictions_tmp SELECT * FROM /*_*/page_restrictions;
INSERT OR IGNORE INTO /*_*/protected_titles_tmp SELECT * FROM /*_*/protected_titles;
INSERT OR IGNORE INTO /*_*/page_props_tmp SELECT * FROM /*_*/page_props;
INSERT OR IGNORE INTO /*_*/archive_tmp SELECT * FROM /*_*/archive;
INSERT OR IGNORE INTO /*_*/externallinks_tmp SELECT * FROM /*_*/externallinks;

--------------------------------------------------------------------------------
-- Do the table renames
--------------------------------------------------------------------------------

DROP TABLE /*_*/user;
ALTER TABLE /*_*/user_tmp RENAME TO /*_*/user;
DROP TABLE /*_*/user_groups;
ALTER TABLE /*_*/user_groups_tmp RENAME TO /*_*/user_groups;
DROP TABLE /*_*/page;
ALTER TABLE /*_*/page_tmp RENAME TO /*_*/page;
DROP TABLE /*_*/revision;
ALTER TABLE /*_*/revision_tmp RENAME TO /*_*/revision;
DROP TABLE /*_*/pagelinks;
ALTER TABLE /*_*/pagelinks_tmp RENAME TO /*_*/pagelinks;
DROP TABLE /*_*/templatelinks;
ALTER TABLE /*_*/templatelinks_tmp RENAME TO /*_*/templatelinks;
DROP TABLE /*_*/imagelinks;
ALTER TABLE /*_*/imagelinks_tmp RENAME TO /*_*/imagelinks;
DROP TABLE /*_*/categorylinks;
ALTER TABLE /*_*/categorylinks_tmp RENAME TO /*_*/categorylinks;
DROP TABLE /*_*/category;
ALTER TABLE /*_*/category_tmp RENAME TO /*_*/category;
DROP TABLE /*_*/langlinks;
ALTER TABLE /*_*/langlinks_tmp RENAME TO /*_*/langlinks;
DROP TABLE /*_*/site_stats;
ALTER TABLE /*_*/site_stats_tmp RENAME TO /*_*/site_stats;
DROP TABLE /*_*/ipblocks;
ALTER TABLE /*_*/ipblocks_tmp RENAME TO /*_*/ipblocks;
DROP TABLE /*_*/watchlist;
ALTER TABLE /*_*/watchlist_tmp RENAME TO /*_*/watchlist;
DROP TABLE /*_*/math;
ALTER TABLE /*_*/math_tmp RENAME TO /*_*/math;
DROP TABLE /*_*/interwiki;
ALTER TABLE /*_*/interwiki_tmp RENAME TO /*_*/interwiki;
DROP TABLE /*_*/page_restrictions;
ALTER TABLE /*_*/page_restrictions_tmp RENAME TO /*_*/page_restrictions;
DROP TABLE /*_*/protected_titles;
ALTER TABLE /*_*/protected_titles_tmp RENAME TO /*_*/protected_titles;
DROP TABLE /*_*/page_props;
ALTER TABLE /*_*/page_props_tmp RENAME TO /*_*/page_props;
DROP TABLE /*_*/archive;
ALTER TABLE /*_*/archive_tmp RENAME TO /*_*/archive;
DROP TABLE /*_*/externalllinks;
ALTER TABLE /*_*/externallinks_tmp RENAME TO /*_*/externallinks;

--------------------------------------------------------------------------------
-- Drop and create tables with unique indexes but no valuable data
--------------------------------------------------------------------------------


DROP TABLE IF EXISTS /*_*/searchindex;
CREATE TABLE /*_*/searchindex (
  si_page int unsigned NOT NULL,
  si_title varchar(255) NOT NULL default '',
  si_text mediumtext NOT NULL
);
CREATE UNIQUE INDEX /*i*/si_page ON /*_*/searchindex (si_page);
CREATE INDEX /*i*/si_title ON /*_*/searchindex (si_title);
CREATE INDEX /*i*/si_text ON /*_*/searchindex (si_text);

DROP TABLE IF EXISTS /*_*/querycache_info;
CREATE TABLE /*_*/querycache_info (
  qci_type varbinary(32) NOT NULL default '',
  qci_timestamp binary(14) NOT NULL default '19700101000000'
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/qci_type ON /*_*/querycache_info (qci_type);

--------------------------------------------------------------------------------
-- Empty some cache tables to make the update faster
--------------------------------------------------------------------------------

DELETE FROM /*_*/querycache;
DELETE FROM /*_*/objectcache;
DELETE FROM /*_*/querycachetwo;

--------------------------------------------------------------------------------
-- Add indexes to tables with no unique indexes
--------------------------------------------------------------------------------

CREATE INDEX /*i*/un_user_id ON /*_*/user_newtalk (user_id);
CREATE INDEX /*i*/un_user_ip ON /*_*/user_newtalk (user_ip);
CREATE INDEX /*i*/name_title_timestamp ON /*_*/archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*i*/ar_usertext_timestamp ON /*_*/archive (ar_user_text,ar_timestamp);
CREATE INDEX /*i*/el_from ON /*_*/externallinks (el_from, el_to(40));
CREATE INDEX /*i*/el_to ON /*_*/externallinks (el_to(60), el_from);
CREATE INDEX /*i*/el_index ON /*_*/externallinks (el_index(60));
CREATE INDEX /*i*/img_usertext_timestamp ON /*_*/image (img_user_text,img_timestamp);
CREATE INDEX /*i*/img_size ON /*_*/image (img_size);
CREATE INDEX /*i*/img_timestamp ON /*_*/image (img_timestamp);
CREATE INDEX /*i*/img_sha1 ON /*_*/image (img_sha1);
CREATE INDEX /*i*/oi_usertext_timestamp ON /*_*/oldimage (oi_user_text,oi_timestamp);
CREATE INDEX /*i*/oi_name_timestamp ON /*_*/oldimage (oi_name,oi_timestamp);
CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name,oi_archive_name(14));
CREATE INDEX /*i*/oi_sha1 ON /*_*/oldimage (oi_sha1);
CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
CREATE INDEX /*i*/fa_group_key ON /*_*/filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
CREATE INDEX /*i*/fa_user_timestamp ON /*_*/filearchive (fa_user_text,fa_timestamp);
CREATE INDEX /*i*/rc_timestamp ON /*_*/recentchanges (rc_timestamp);
CREATE INDEX /*i*/rc_namespace_title_timestamp ON /*_*/recentchanges (rc_namespace, rc_title, rc_timestamp);
CREATE INDEX /*i*/rc_cur_id ON /*_*/recentchanges (rc_cur_id);
CREATE INDEX /*i*/new_name_timestamp ON /*_*/recentchanges (rc_new,rc_namespace,rc_timestamp);
CREATE INDEX /*i*/rc_ip ON /*_*/recentchanges (rc_ip);
CREATE INDEX /*i*/rc_ns_usertext ON /*_*/recentchanges (rc_namespace, rc_user_text);
CREATE INDEX /*i*/rc_user_text ON /*_*/recentchanges (rc_user_text, rc_timestamp);
CREATE INDEX /*i*/qc_type_value ON /*_*/querycache (qc_type,qc_value);
CREATE INDEX /*i*/oc_exptime ON /*_*/objectcache (exptime);
CREATE INDEX /*i*/type_time ON /*_*/logging (log_type, log_timestamp);
CREATE INDEX /*i*/user_time ON /*_*/logging (log_user, log_timestamp);
CREATE INDEX /*i*/page_time ON /*_*/logging (log_namespace, log_title, log_timestamp);
CREATE INDEX /*i*/times ON /*_*/logging (log_timestamp);
CREATE INDEX /*i*/job_cmd_namespace_title ON /*_*/job (job_cmd, job_namespace, job_title);
CREATE INDEX /*i*/rd_ns_title ON /*_*/redirect (rd_namespace,rd_title,rd_from);
CREATE INDEX /*i*/qcc_type ON /*_*/querycachetwo (qcc_type,qcc_value);
CREATE INDEX /*i*/qcc_title ON /*_*/querycachetwo (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX /*i*/qcc_titletwo ON /*_*/querycachetwo (qcc_type,qcc_namespacetwo,qcc_titletwo);

INSERT INTO /*_*/updatelog (ul_key) VALUES ('initial_indexes');
