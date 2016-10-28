-- Correct for the total lack of indexes in the MW 1.13 SQLite schema
--
-- Unique indexes need to be handled with INSERT SELECT since just running
-- the CREATE INDEX statement will fail if there are duplicate values.
--
-- Ignore duplicates, several tables will have them (e.g. bug 16966) but in
-- most cases it's harmless to discard them.

--------------------------------------------------------------------------------
-- Drop temporary tables from aborted runs
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS /*_*/user_tmp;
DROP TABLE IF EXISTS /*_*/user_groups_tmp;
DROP TABLE IF EXISTS /*_*/user_former_groups_tmp;
DROP TABLE IF EXISTS /*_*/user_newtalk_tmp;
DROP TABLE IF EXISTS /*_*/user_properties_tmp;
DROP TABLE IF EXISTS /*_*/bot_passwords_tmp;
DROP TABLE IF EXISTS /*_*/page_tmp;
DROP TABLE IF EXISTS /*_*/revision_tmp;
DROP TABLE IF EXISTS /*_*/text_tmp;
DROP TABLE IF EXISTS /*_*/archive_tmp;
DROP TABLE IF EXISTS /*_*/pagelinks_tmp;
DROP TABLE IF EXISTS /*_*/templatelinks_tmp;
DROP TABLE IF EXISTS /*_*/imagelinks_tmp;
DROP TABLE IF EXISTS /*_*/categorylinks_tmp;
DROP TABLE IF EXISTS /*_*/category_tmp;
DROP TABLE IF EXISTS /*_*/externallinks_tmp;
DROP TABLE IF EXISTS /*_*/langlinks_tmp;
DROP TABLE IF EXISTS /*_*/iwlinks_tmp;
DROP TABLE IF EXISTS /*_*/site_stats_tmp;
DROP TABLE IF EXISTS /*_*/ipblocks_tmp;
DROP TABLE IF EXISTS /*_*/image_tmp;
DROP TABLE IF EXISTS /*_*/oldimage_tmp;
DROP TABLE IF EXISTS /*_*/filearchive_tmp;
DROP TABLE IF EXISTS /*_*/uploadstash_tmp;
DROP TABLE IF EXISTS /*_*/recentchanges_tmp;
DROP TABLE IF EXISTS /*_*/watchlist_tmp;
DROP TABLE IF EXISTS /*_*/interwiki_tmp;
DROP TABLE IF EXISTS /*_*/querycache_tmp;
DROP TABLE IF EXISTS /*_*/objectcache_tmp;
DROP TABLE IF EXISTS /*_*/transcache_tmp;
DROP TABLE IF EXISTS /*_*/logging_tmp;
DROP TABLE IF EXISTS /*_*/log_search_tmp;
DROP TABLE IF EXISTS /*_*/job_tmp;
DROP TABLE IF EXISTS /*_*/redirect_tmp;
DROP TABLE IF EXISTS /*_*/querycachetwo_tmp;
DROP TABLE IF EXISTS /*_*/page_restrictions_tmp;
DROP TABLE IF EXISTS /*_*/protected_titles_tmp;
DROP TABLE IF EXISTS /*_*/page_props_tmp;
DROP TABLE IF EXISTS /*_*/updatelog_tmp;
DROP TABLE IF EXISTS /*_*/change_tag_tmp;
DROP TABLE IF EXISTS /*_*/tag_summary_tmp;
DROP TABLE IF EXISTS /*_*/valid_tag_tmp;
DROP TABLE IF EXISTS /*_*/l10n_cache_tmp;
DROP TABLE IF EXISTS /*_*/module_deps_tmp;
DROP TABLE IF EXISTS /*_*/sites_tmp;
DROP TABLE IF EXISTS /*_*/site_identifiers_tmp;

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
  user_editcount int,
  user_password_expires varbinary(14) DEFAULT NULL
);
CREATE UNIQUE INDEX /*i*/user_name ON /*_*/user_tmp (user_name);
CREATE INDEX /*i*/user_email_token ON /*_*/user_tmp (user_email_token);
CREATE INDEX /*i*/user_email ON /*_*/user_tmp (user_email(50));

CREATE TABLE /*_*/user_groups_tmp (
  ug_user int unsigned NOT NULL default 0,
  ug_group varbinary(255) NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/ug_user_group ON /*_*/user_groups_tmp (ug_user,ug_group);
CREATE INDEX /*i*/ug_group ON /*_*/user_groups_tmp (ug_group);


CREATE TABLE /*_*/user_former_groups_tmp (
  -- Key to user_id
  ufg_user int unsigned NOT NULL default 0,
  ufg_group varbinary(255) NOT NULL default ''
);
CREATE UNIQUE INDEX /*i*/ufg_user_group ON /*_*/user_former_groups_tmp (ufg_user,ufg_group);


CREATE TABLE /*_*/user_newtalk_tmp (
  user_id int unsigned NOT NULL default 0,
  user_ip varbinary(40) NOT NULL default '',
  user_last_timestamp varbinary(14) NULL default NULL
);
CREATE INDEX /*i*/un_user_id ON /*_*/user_newtalk_tmp (user_id);
CREATE INDEX /*i*/un_user_ip ON /*_*/user_newtalk_tmp (user_ip);


CREATE TABLE /*_*/user_properties_tmp (
  up_user int NOT NULL,
  up_property varbinary(255) NOT NULL,
  up_value blob
);

CREATE UNIQUE INDEX /*i*/user_properties_user_property ON /*_*/user_properties_tmp (up_user,up_property);
CREATE INDEX /*i*/user_properties_property ON /*_*/user_properties_tmp (up_property);

CREATE TABLE /*_*/bot_passwords_tmp (
  bp_user int NOT NULL,
  bp_app_id varbinary(32) NOT NULL,
  bp_password tinyblob NOT NULL,
  bp_token binary(32) NOT NULL default '',
  bp_restrictions blob NOT NULL,
  bp_grants blob NOT NULL,
  PRIMARY KEY ( bp_user, bp_app_id )
);

CREATE TABLE /*_*/page_tmp (
  page_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  page_namespace int NOT NULL,
  page_title varchar(255) binary NOT NULL,
  page_restrictions tinyblob NOT NULL,
  page_is_redirect tinyint unsigned NOT NULL default 0,
  page_is_new tinyint unsigned NOT NULL default 0,
  page_random real unsigned NOT NULL,
  page_touched binary(14) NOT NULL default '',
  page_links_updated varbinary(14) NULL default NULL,
  page_latest int unsigned NOT NULL,
  page_len int unsigned NOT NULL,
  page_content_model varbinary(32) DEFAULT NULL,
  page_lang varbinary(35) DEFAULT NULL
);

CREATE UNIQUE INDEX /*i*/name_title ON /*_*/page_tmp (page_namespace,page_title);
CREATE INDEX /*i*/page_random ON /*_*/page_tmp (page_random);
CREATE INDEX /*i*/page_len ON /*_*/page_tmp (page_len);
CREATE INDEX /*i*/page_redirect_namespace_len ON /*_*/page_tmp (page_is_redirect, page_namespace, page_len);


CREATE TABLE /*_*/revision_tmp (
  rev_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rev_page int unsigned NOT NULL,
  rev_text_id int unsigned NOT NULL,
  rev_comment varbinary(767) NOT NULL,
  rev_user int unsigned NOT NULL default 0,
  rev_user_text varchar(255) binary NOT NULL default '',
  rev_timestamp binary(14) NOT NULL default '',
  rev_minor_edit tinyint unsigned NOT NULL default 0,
  rev_deleted tinyint unsigned NOT NULL default 0,
  rev_len int unsigned,
  rev_parent_id int unsigned default NULL,
  rev_sha1 varbinary(32) NOT NULL default '',
  rev_content_model varbinary(32) DEFAULT NULL,
  rev_content_format varbinary(64) DEFAULT NULL
);
CREATE INDEX /*i*/rev_page_id ON /*_*/revision_tmp (rev_page, rev_id);
CREATE INDEX /*i*/rev_timestamp ON /*_*/revision_tmp (rev_timestamp);
CREATE INDEX /*i*/page_timestamp ON /*_*/revision_tmp (rev_page,rev_timestamp);
CREATE INDEX /*i*/user_timestamp ON /*_*/revision_tmp (rev_user,rev_timestamp);
CREATE INDEX /*i*/usertext_timestamp ON /*_*/revision_tmp (rev_user_text,rev_timestamp);
CREATE INDEX /*i*/page_user_timestamp ON /*_*/revision_tmp (rev_page,rev_user,rev_timestamp);

CREATE TABLE /*_*/text_tmp (
  old_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  old_text mediumblob NOT NULL,
  old_flags tinyblob NOT NULL
);

CREATE TABLE /*_*/archive_tmp (
  ar_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ar_namespace int NOT NULL default 0,
  ar_title varchar(255) binary NOT NULL default '',
  ar_text mediumblob NOT NULL,
  ar_comment varbinary(767) NOT NULL,
  ar_user int unsigned NOT NULL default 0,
  ar_user_text varchar(255) binary NOT NULL,
  ar_timestamp binary(14) NOT NULL default '',
  ar_minor_edit tinyint NOT NULL default 0,
  ar_flags tinyblob NOT NULL,
  ar_rev_id int unsigned,
  ar_text_id int unsigned,
  ar_deleted tinyint unsigned NOT NULL default 0,
  ar_len int unsigned,
  ar_page_id int unsigned,
  ar_parent_id int unsigned default NULL,
  ar_sha1 varbinary(32) NOT NULL default '',
  ar_content_model varbinary(32) DEFAULT NULL,
  ar_content_format varbinary(64) DEFAULT NULL
);

CREATE INDEX /*i*/name_title_timestamp ON /*_*/archive_tmp (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*i*/ar_usertext_timestamp ON /*_*/archive_tmp (ar_user_text,ar_timestamp);
CREATE INDEX /*i*/ar_revid ON /*_*/archive_tmp (ar_rev_id);

CREATE TABLE /*_*/pagelinks_tmp (
  pl_from int unsigned NOT NULL default 0,
  pl_from_namespace int NOT NULL default 0,
  pl_namespace int NOT NULL default 0,
  pl_title varchar(255) binary NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/pl_from ON /*_*/pagelinks_tmp (pl_from,pl_namespace,pl_title);
CREATE INDEX /*i*/pl_namespace ON /*_*/pagelinks_tmp (pl_namespace,pl_title,pl_from);
CREATE INDEX /*i*/pl_backlinks_namespace ON /*_*/pagelinks_tmp (pl_from_namespace,pl_namespace,pl_title,pl_from);


CREATE TABLE /*_*/templatelinks_tmp (
  tl_from int unsigned NOT NULL default 0,
  tl_from_namespace int NOT NULL default 0,
  tl_namespace int NOT NULL default 0,
  tl_title varchar(255) binary NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/tl_from ON /*_*/templatelinks_tmp (tl_from,tl_namespace,tl_title);
CREATE INDEX /*i*/tl_namespace ON /*_*/templatelinks_tmp (tl_namespace,tl_title,tl_from);
CREATE INDEX /*i*/tl_backlinks_namespace ON /*_*/templatelinks_tmp (tl_from_namespace,tl_namespace,tl_title,tl_from);


CREATE TABLE /*_*/imagelinks_tmp (
  il_from int unsigned NOT NULL default 0,
  il_from_namespace int NOT NULL default 0,
  il_to varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/il_from ON /*_*/imagelinks_tmp (il_from,il_to);
CREATE INDEX /*i*/il_to ON /*_*/imagelinks_tmp (il_to,il_from);
CREATE INDEX /*i*/il_backlinks_namespace ON /*_*/imagelinks_tmp (il_from_namespace,il_to,il_from);


CREATE TABLE /*_*/categorylinks_tmp (
  cl_from int unsigned NOT NULL default 0,
  cl_to varchar(255) binary NOT NULL default '',
  cl_sortkey varbinary(230) NOT NULL default '',
  cl_sortkey_prefix varchar(255) binary NOT NULL default '',
  cl_timestamp timestamp NOT NULL,
  cl_collation varbinary(32) NOT NULL default '',
  cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page'
);
CREATE UNIQUE INDEX /*i*/cl_from ON /*_*/categorylinks_tmp (cl_from,cl_to);
CREATE INDEX /*i*/cl_sortkey ON /*_*/categorylinks_tmp (cl_to,cl_type,cl_sortkey,cl_from);
CREATE INDEX /*i*/cl_timestamp ON /*_*/categorylinks_tmp (cl_to,cl_timestamp);
CREATE INDEX /*i*/cl_collation_ext ON /*_*/categorylinks_tmp (cl_collation, cl_to, cl_type, cl_from);


CREATE TABLE /*_*/category_tmp (
  cat_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  cat_title varchar(255) binary NOT NULL,
  cat_pages int signed NOT NULL default 0,
  cat_subcats int signed NOT NULL default 0,
  cat_files int signed NOT NULL default 0
);
CREATE UNIQUE INDEX /*i*/cat_title ON /*_*/category_tmp (cat_title);
CREATE INDEX /*i*/cat_pages ON /*_*/category_tmp (cat_pages);


CREATE TABLE /*_*/externallinks_tmp (
  el_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  el_from int unsigned NOT NULL default 0,
  el_to blob NOT NULL,
  el_index blob NOT NULL
);

CREATE INDEX /*i*/el_from ON /*_*/externallinks_tmp (el_from, el_to(40));
CREATE INDEX /*i*/el_to ON /*_*/externallinks_tmp (el_to(60), el_from);
CREATE INDEX /*i*/el_index ON /*_*/externallinks_tmp (el_index(60));


CREATE TABLE /*_*/langlinks_tmp (
  ll_from int unsigned NOT NULL default 0,
  ll_lang varbinary(20) NOT NULL default '',
  ll_title varchar(255) binary NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/ll_from ON /*_*/langlinks_tmp (ll_from, ll_lang);
CREATE INDEX /*i*/ll_lang ON /*_*/langlinks_tmp (ll_lang, ll_title);


CREATE TABLE /*_*/iwlinks_tmp (
  iwl_from int unsigned NOT NULL default 0,
  iwl_prefix varbinary(20) NOT NULL default '',
  iwl_title varchar(255) binary NOT NULL default ''
);

CREATE UNIQUE INDEX /*i*/iwl_from ON /*_*/iwlinks_tmp (iwl_from, iwl_prefix, iwl_title);
CREATE INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks_tmp (iwl_prefix, iwl_title, iwl_from);
CREATE INDEX /*i*/iwl_prefix_from_title ON /*_*/iwlinks_tmp (iwl_prefix, iwl_from, iwl_title);


CREATE TABLE /*_*/site_stats_tmp (
  ss_row_id int unsigned NOT NULL,
  ss_total_edits bigint unsigned default 0,
  ss_good_articles bigint unsigned default 0,
  ss_total_pages bigint default '-1',
  ss_users bigint default '-1',
  ss_active_users bigint default '-1',
  ss_images int default 0
);
CREATE UNIQUE INDEX /*i*/ss_row_id ON /*_*/site_stats_tmp (ss_row_id);


CREATE TABLE /*_*/ipblocks_tmp (
  ipb_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ipb_address tinyblob NOT NULL,
  ipb_user int unsigned NOT NULL default 0,
  ipb_by int unsigned NOT NULL default 0,
  ipb_by_text varchar(255) binary NOT NULL default '',
  ipb_reason varbinary(767) NOT NULL,
  ipb_timestamp binary(14) NOT NULL default '',
  ipb_auto bool NOT NULL default 0,
  ipb_anon_only bool NOT NULL default 0,
  ipb_create_account bool NOT NULL default 1,
  ipb_enable_autoblock bool NOT NULL default '1',
  ipb_expiry varbinary(14) NOT NULL default '',
  ipb_range_start tinyblob NOT NULL,
  ipb_range_end tinyblob NOT NULL,
  ipb_deleted bool NOT NULL default 0,
  ipb_block_email bool NOT NULL default 0,
  ipb_allow_usertalk bool NOT NULL default 0,
  ipb_parent_block_id int default NULL
);
CREATE UNIQUE INDEX /*i*/ipb_address ON /*_*/ipblocks_tmp (ipb_address(255), ipb_user, ipb_auto, ipb_anon_only);
CREATE INDEX /*i*/ipb_user ON /*_*/ipblocks_tmp (ipb_user);
CREATE INDEX /*i*/ipb_range ON /*_*/ipblocks_tmp (ipb_range_start(8), ipb_range_end(8));
CREATE INDEX /*i*/ipb_timestamp ON /*_*/ipblocks_tmp (ipb_timestamp);
CREATE INDEX /*i*/ipb_expiry ON /*_*/ipblocks_tmp (ipb_expiry);
CREATE INDEX /*i*/ipb_parent_block_id ON /*_*/ipblocks_tmp (ipb_parent_block_id);

--
-- Uploaded images and other files.
--
CREATE TABLE /*_*/image_tmp (
  img_name varchar(255) binary NOT NULL default '' PRIMARY KEY,
  img_size int unsigned NOT NULL default 0,
  img_width int NOT NULL default 0,
  img_height int NOT NULL default 0,
  img_metadata mediumblob NOT NULL,
  img_bits int NOT NULL default 0,
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  img_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",
  img_minor_mime varbinary(100) NOT NULL default "unknown",
  img_description varbinary(767) NOT NULL,
  img_user int unsigned NOT NULL default 0,
  img_user_text varchar(255) binary NOT NULL,
  img_timestamp varbinary(14) NOT NULL default '',
  img_sha1 varbinary(32) NOT NULL default ''
);

CREATE INDEX /*i*/img_usertext_timestamp ON /*_*/image_tmp (img_user_text,img_timestamp);
CREATE INDEX /*i*/img_size ON /*_*/image_tmp (img_size);
CREATE INDEX /*i*/img_timestamp ON /*_*/image_tmp (img_timestamp);
CREATE INDEX /*i*/img_sha1 ON /*_*/image_tmp (img_sha1(10));
CREATE INDEX /*i*/img_media_mime ON /*_*/image_tmp (img_media_type,img_major_mime,img_minor_mime);


CREATE TABLE /*_*/oldimage_tmp (
  oi_name varchar(255) binary NOT NULL default '',
  oi_archive_name varchar(255) binary NOT NULL default '',
  oi_size int unsigned NOT NULL default 0,
  oi_width int NOT NULL default 0,
  oi_height int NOT NULL default 0,
  oi_bits int NOT NULL default 0,
  oi_description varbinary(767) NOT NULL,
  oi_user int unsigned NOT NULL default 0,
  oi_user_text varchar(255) binary NOT NULL,
  oi_timestamp binary(14) NOT NULL default '',
  oi_metadata mediumblob NOT NULL,
  oi_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  oi_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",
  oi_minor_mime varbinary(100) NOT NULL default "unknown",
  oi_deleted tinyint unsigned NOT NULL default 0,
  oi_sha1 varbinary(32) NOT NULL default ''
);

CREATE INDEX /*i*/oi_usertext_timestamp ON /*_*/oldimage_tmp (oi_user_text,oi_timestamp);
CREATE INDEX /*i*/oi_name_timestamp ON /*_*/oldimage_tmp (oi_name,oi_timestamp);
CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage_tmp (oi_name,oi_archive_name(14));
CREATE INDEX /*i*/oi_sha1 ON /*_*/oldimage_tmp (oi_sha1(10));


CREATE TABLE /*_*/filearchive_tmp (
  fa_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  fa_name varchar(255) binary NOT NULL default '',
  fa_archive_name varchar(255) binary default '',
  fa_storage_group varbinary(16),
  fa_storage_key varbinary(64) default '',
  fa_deleted_user int,
  fa_deleted_timestamp binary(14) default '',
  fa_deleted_reason varbinary(767) default '',
  fa_size int unsigned default 0,
  fa_width int default 0,
  fa_height int default 0,
  fa_metadata mediumblob,
  fa_bits int default 0,
  fa_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  fa_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") default "unknown",
  fa_minor_mime varbinary(100) default "unknown",
  fa_description varbinary(767),
  fa_user int unsigned default 0,
  fa_user_text varchar(255) binary,
  fa_timestamp binary(14) default '',
  fa_deleted tinyint unsigned NOT NULL default 0,
  fa_sha1 varbinary(32) NOT NULL default ''
);

CREATE INDEX /*i*/fa_name ON /*_*/filearchive_tmp (fa_name, fa_timestamp);
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive_tmp (fa_storage_group, fa_storage_key);
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive_tmp (fa_deleted_timestamp);
CREATE INDEX /*i*/fa_user_timestamp ON /*_*/filearchive_tmp (fa_user_text,fa_timestamp);
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive_tmp (fa_sha1(10));


CREATE TABLE /*_*/uploadstash_tmp (
  us_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  us_user int unsigned NOT NULL,
  us_key varchar(255) NOT NULL,
  us_orig_path varchar(255) NOT NULL,
  us_path varchar(255) NOT NULL,
  us_source_type varchar(50),
  us_timestamp varbinary(14) NOT NULL,
  us_status varchar(50) NOT NULL,
  us_chunk_inx int unsigned NULL,
  us_props blob,
  us_size int unsigned NOT NULL,
  us_sha1 varchar(31) NOT NULL,
  us_mime varchar(255),
  us_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  us_image_width int unsigned,
  us_image_height int unsigned,
  us_image_bits smallint unsigned
);

CREATE INDEX /*i*/us_user ON /*_*/uploadstash_tmp (us_user);
CREATE UNIQUE INDEX /*i*/us_key ON /*_*/uploadstash_tmp (us_key);
CREATE INDEX /*i*/us_timestamp ON /*_*/uploadstash_tmp (us_timestamp);


CREATE TABLE /*_*/recentchanges_tmp (
  rc_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rc_timestamp varbinary(14) NOT NULL default '',
  rc_user int unsigned NOT NULL default 0,
  rc_user_text varchar(255) binary NOT NULL,
  rc_namespace int NOT NULL default 0,
  rc_title varchar(255) binary NOT NULL default '',
  rc_comment varbinary(767) NOT NULL default '',
  rc_minor tinyint unsigned NOT NULL default 0,
  rc_bot tinyint unsigned NOT NULL default 0,
  rc_new tinyint unsigned NOT NULL default 0,
  rc_cur_id int unsigned NOT NULL default 0,
  rc_this_oldid int unsigned NOT NULL default 0,
  rc_last_oldid int unsigned NOT NULL default 0,
  rc_type tinyint unsigned NOT NULL default 0,
  rc_source varchar(16) binary not null default '',
  rc_patrolled tinyint unsigned NOT NULL default 0,
  rc_ip varbinary(40) NOT NULL default '',
  rc_old_len int,
  rc_new_len int,
  rc_deleted tinyint unsigned NOT NULL default 0,
  rc_logid int unsigned NOT NULL default 0,
  rc_log_type varbinary(255) NULL default NULL,
  rc_log_action varbinary(255) NULL default NULL,
  rc_params blob NULL
);

CREATE INDEX /*i*/rc_timestamp ON /*_*/recentchanges_tmp (rc_timestamp);
CREATE INDEX /*i*/rc_namespace_title ON /*_*/recentchanges_tmp (rc_namespace, rc_title);
CREATE INDEX /*i*/rc_cur_id ON /*_*/recentchanges_tmp (rc_cur_id);
CREATE INDEX /*i*/new_name_timestamp ON /*_*/recentchanges_tmp (rc_new,rc_namespace,rc_timestamp);
CREATE INDEX /*i*/rc_ip ON /*_*/recentchanges_tmp (rc_ip);
CREATE INDEX /*i*/rc_ns_usertext ON /*_*/recentchanges_tmp (rc_namespace, rc_user_text);
CREATE INDEX /*i*/rc_user_text ON /*_*/recentchanges_tmp (rc_user_text, rc_timestamp);
CREATE INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges_tmp (rc_namespace, rc_type, rc_patrolled, rc_timestamp);


CREATE TABLE /*_*/watchlist_tmp (
  wl_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  wl_user int unsigned NOT NULL,
  wl_namespace int NOT NULL default 0,
  wl_title varchar(255) binary NOT NULL default '',
  wl_notificationtimestamp varbinary(14)
);

CREATE UNIQUE INDEX /*i*/wl_user ON /*_*/watchlist_tmp (wl_user, wl_namespace, wl_title);
CREATE INDEX /*i*/namespace_title ON /*_*/watchlist_tmp (wl_namespace, wl_title);
CREATE INDEX /*i*/wl_user_notificationtimestamp ON /*_*/watchlist_tmp (wl_user, wl_notificationtimestamp);


CREATE TABLE /*_*/interwiki_tmp (
  iw_prefix varchar(32) NOT NULL,
  iw_url blob NOT NULL,
  iw_api blob NOT NULL,
  iw_wikiid varchar(64) NOT NULL,
  iw_local bool NOT NULL,
  iw_trans tinyint NOT NULL default 0
);

CREATE UNIQUE INDEX /*i*/iw_prefix ON /*_*/interwiki_tmp (iw_prefix);


CREATE TABLE /*_*/querycache_tmp (
  qc_type varbinary(32) NOT NULL,
  qc_value int unsigned NOT NULL default 0,
  qc_namespace int NOT NULL default 0,
  qc_title varchar(255) binary NOT NULL default ''
);

CREATE INDEX /*i*/qc_type ON /*_*/querycache_tmp (qc_type,qc_value);


CREATE TABLE /*_*/objectcache_tmp (
  keyname varbinary(255) NOT NULL default '' PRIMARY KEY,
  value mediumblob,
  exptime datetime
);
CREATE INDEX /*i*/exptime ON /*_*/objectcache_tmp (exptime);


CREATE TABLE /*_*/transcache_tmp (
  tc_url varbinary(255) NOT NULL,
  tc_contents text,
  tc_time binary(14) NOT NULL
);

CREATE UNIQUE INDEX /*i*/tc_url_idx ON /*_*/transcache_tmp (tc_url);


CREATE TABLE /*_*/logging_tmp (
  log_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  log_type varbinary(32) NOT NULL default '',
  log_action varbinary(32) NOT NULL default '',
  log_timestamp binary(14) NOT NULL default '19700101000000',
  log_user int unsigned NOT NULL default 0,
  log_user_text varchar(255) binary NOT NULL default '',
  log_namespace int NOT NULL default 0,
  log_title varchar(255) binary NOT NULL default '',
  log_page int unsigned NULL,
  log_comment varbinary(767) NOT NULL default '',
  log_params blob NOT NULL,
  log_deleted tinyint unsigned NOT NULL default 0
);

CREATE INDEX /*i*/type_time ON /*_*/logging_tmp (log_type, log_timestamp);
CREATE INDEX /*i*/user_time ON /*_*/logging_tmp (log_user, log_timestamp);
CREATE INDEX /*i*/page_time ON /*_*/logging_tmp (log_namespace, log_title, log_timestamp);
CREATE INDEX /*i*/times ON /*_*/logging_tmp (log_timestamp);
CREATE INDEX /*i*/log_user_type_time ON /*_*/logging_tmp (log_user, log_type, log_timestamp);
CREATE INDEX /*i*/log_page_id_time ON /*_*/logging_tmp (log_page,log_timestamp);
CREATE INDEX /*i*/type_action ON /*_*/logging_tmp (log_type, log_action, log_timestamp);
CREATE INDEX /*i*/log_user_text_type_time ON /*_*/logging_tmp (log_user_text, log_type, log_timestamp);
CREATE INDEX /*i*/log_user_text_time ON /*_*/logging_tmp (log_user_text, log_timestamp);


CREATE TABLE /*_*/log_search_tmp (
  ls_field varbinary(32) NOT NULL,
  ls_value varchar(255) NOT NULL,
  ls_log_id int unsigned NOT NULL default 0
);
CREATE UNIQUE INDEX /*i*/ls_field_val ON /*_*/log_search_tmp (ls_field,ls_value,ls_log_id);
CREATE INDEX /*i*/ls_log_id ON /*_*/log_search_tmp (ls_log_id);


CREATE TABLE /*_*/job_tmp (
  job_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  job_cmd varbinary(60) NOT NULL default '',
  job_namespace int NOT NULL,
  job_title varchar(255) binary NOT NULL,
  job_timestamp varbinary(14) NULL default NULL,
  job_params blob NOT NULL,
  job_random integer unsigned NOT NULL default 0,
  job_attempts integer unsigned NOT NULL default 0,
  job_token varbinary(32) NOT NULL default '',
  job_token_timestamp varbinary(14) NULL default NULL,
  job_sha1 varbinary(32) NOT NULL default ''
);

CREATE INDEX /*i*/job_sha1 ON /*_*/job_tmp (job_sha1);
CREATE INDEX /*i*/job_cmd_token ON /*_*/job_tmp (job_cmd,job_token,job_random);
CREATE INDEX /*i*/job_cmd_token_id ON /*_*/job_tmp (job_cmd,job_token,job_id);
CREATE INDEX /*i*/job_cmd ON /*_*/job_tmp (job_cmd, job_namespace, job_title, job_params(128));
CREATE INDEX /*i*/job_timestamp ON /*_*/job_tmp (job_timestamp);


-- For each redirect, this table contains exactly one row defining its target
CREATE TABLE /*_*/redirect_tmp (
  rd_from int unsigned NOT NULL default 0 PRIMARY KEY,
  rd_namespace int NOT NULL default 0,
  rd_title varchar(255) binary NOT NULL default '',
  rd_interwiki varchar(32) default NULL,
  rd_fragment varchar(255) binary default NULL
);

CREATE INDEX /*i*/rd_ns_title ON /*_*/redirect_tmp (rd_namespace,rd_title,rd_from);


CREATE TABLE /*_*/querycachetwo_tmp (
  qcc_type varbinary(32) NOT NULL,
  qcc_value int unsigned NOT NULL default 0,
  qcc_namespace int NOT NULL default 0,
  qcc_title varchar(255) binary NOT NULL default '',
  qcc_namespacetwo int NOT NULL default 0,
  qcc_titletwo varchar(255) binary NOT NULL default ''
);

CREATE INDEX /*i*/qcc_type ON /*_*/querycachetwo_tmp (qcc_type,qcc_value);
CREATE INDEX /*i*/qcc_title ON /*_*/querycachetwo_tmp (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX /*i*/qcc_titletwo ON /*_*/querycachetwo_tmp (qcc_type,qcc_namespacetwo,qcc_titletwo);


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
  pt_reason varbinary(767),
  pt_timestamp binary(14) NOT NULL,
  pt_expiry varbinary(14) NOT NULL default '',
  pt_create_perm varbinary(60) NOT NULL
);
CREATE UNIQUE INDEX /*i*/pt_namespace_title ON /*_*/protected_titles_tmp (pt_namespace,pt_title);
CREATE INDEX /*i*/pt_timestamp ON /*_*/protected_titles_tmp (pt_timestamp);

CREATE TABLE /*_*/page_props_tmp (
  pp_page int NOT NULL,
  pp_propname varbinary(60) NOT NULL,
  pp_value blob NOT NULL,
  pp_sortkey float DEFAULT NULL
);
CREATE UNIQUE INDEX /*i*/pp_page_propname ON /*_*/page_props_tmp (pp_page,pp_propname);
CREATE UNIQUE INDEX /*i*/pp_propname_page ON /*_*/page_props_tmp (pp_propname,pp_page);
CREATE UNIQUE INDEX /*i*/pp_propname_sortkey_page ON /*_*/page_props_tmp (pp_propname,pp_sortkey,pp_page);


CREATE TABLE /*_*/updatelog_tmp (
  ul_key varchar(255) NOT NULL PRIMARY KEY,
  ul_value blob
);


CREATE TABLE /*_*/change_tag_tmp (
  ct_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ct_rc_id int NULL,
  ct_log_id int NULL,
  ct_rev_id int NULL,
  ct_tag varchar(255) NOT NULL,
  ct_params blob NULL
);

CREATE UNIQUE INDEX /*i*/change_tag_rc_tag ON /*_*/change_tag_tmp (ct_rc_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag ON /*_*/change_tag_tmp (ct_log_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag ON /*_*/change_tag_tmp (ct_rev_id,ct_tag);
CREATE INDEX /*i*/change_tag_tag_id ON /*_*/change_tag_tmp (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);


CREATE TABLE /*_*/tag_summary_tmp (
  ts_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ts_rc_id int NULL,
  ts_log_id int NULL,
  ts_rev_id int NULL,
  ts_tags blob NOT NULL
);

CREATE UNIQUE INDEX /*i*/tag_summary_rc_id ON /*_*/tag_summary_tmp (ts_rc_id);
CREATE UNIQUE INDEX /*i*/tag_summary_log_id ON /*_*/tag_summary_tmp (ts_log_id);
CREATE UNIQUE INDEX /*i*/tag_summary_rev_id ON /*_*/tag_summary_tmp (ts_rev_id);


CREATE TABLE /*_*/valid_tag_tmp (
  vt_tag varchar(255) NOT NULL PRIMARY KEY
);


CREATE TABLE /*_*/l10n_cache_tmp (
  lc_lang varbinary(32) NOT NULL,
  lc_key varchar(255) NOT NULL,
  lc_value mediumblob NOT NULL
);
CREATE INDEX /*i*/lc_lang_key ON /*_*/l10n_cache_tmp (lc_lang, lc_key);


CREATE TABLE /*_*/module_deps_tmp (
  md_module varbinary(255) NOT NULL,
  md_skin varbinary(32) NOT NULL,
  md_deps mediumblob NOT NULL
);
CREATE UNIQUE INDEX /*i*/md_module_skin ON /*_*/module_deps_tmp (md_module, md_skin);


CREATE TABLE /*_*/sites_tmp (
  site_id                    INT UNSIGNED        NOT NULL PRIMARY KEY AUTO_INCREMENT,
  site_global_key            varbinary(32)       NOT NULL,
  site_type                  varbinary(32)       NOT NULL,
  site_group                 varbinary(32)       NOT NULL,
  site_source                varbinary(32)       NOT NULL,
  site_language              varbinary(32)       NOT NULL,
  site_protocol              varbinary(32)       NOT NULL,
  site_domain                VARCHAR(255)        NOT NULL,
  site_data                  BLOB                NOT NULL,
  site_forward              bool                NOT NULL,
  site_config               BLOB                NOT NULL
);

CREATE UNIQUE INDEX /*i*/sites_global_key ON /*_*/sites_tmp (site_global_key);
CREATE INDEX /*i*/sites_type ON /*_*/sites_tmp (site_type);
CREATE INDEX /*i*/sites_group ON /*_*/sites_tmp (site_group);
CREATE INDEX /*i*/sites_source ON /*_*/sites_tmp (site_source);
CREATE INDEX /*i*/sites_language ON /*_*/sites_tmp (site_language);
CREATE INDEX /*i*/sites_protocol ON /*_*/sites_tmp (site_protocol);
CREATE INDEX /*i*/sites_domain ON /*_*/sites_tmp (site_domain);
CREATE INDEX /*i*/sites_forward ON /*_*/sites_tmp (site_forward);


CREATE TABLE /*_*/site_identifiers_tmp (
  si_site                    INT UNSIGNED        NOT NULL,
  si_type                    varbinary(32)       NOT NULL,
  si_key                     varbinary(32)       NOT NULL
);

CREATE UNIQUE INDEX /*i*/site_ids_type ON /*_*/site_identifiers_tmp (si_type, si_key);
CREATE INDEX /*i*/site_ids_site ON /*_*/site_identifiers_tmp (si_site);
CREATE INDEX /*i*/site_ids_key ON /*_*/site_identifiers_tmp (si_key);


--------------------------------------------------------------------------------
-- Populate the new tables using INSERT SELECT
--------------------------------------------------------------------------------

INSERT OR IGNORE INTO /*_*/user_tmp SELECT * FROM /*_*/user;
INSERT OR IGNORE INTO /*_*/user_groups_tmp SELECT * FROM /*_*/user_groups;
INSERT OR IGNORE INTO /*_*/user_former_groups_tmp SELECT * FROM /*_*/user_former_groups;
INSERT OR IGNORE INTO /*_*/user_newtalk_tmp SELECT * FROM /*_*/user_newtalk;
INSERT OR IGNORE INTO /*_*/user_properties_tmp SELECT * FROM /*_*/user_properties;
INSERT OR IGNORE INTO /*_*/bot_passwords_tmp SELECT * FROM /*_*/bot_passwords;
INSERT OR IGNORE INTO /*_*/page_tmp SELECT * FROM /*_*/page;
INSERT OR IGNORE INTO /*_*/revision_tmp SELECT * FROM /*_*/revision;
INSERT OR IGNORE INTO /*_*/text_tmp SELECT * FROM /*_*/text;
INSERT OR IGNORE INTO /*_*/archive_tmp SELECT * FROM /*_*/archive;
INSERT OR IGNORE INTO /*_*/pagelinks_tmp SELECT * FROM /*_*/pagelinks;
INSERT OR IGNORE INTO /*_*/templatelinks_tmp SELECT * FROM /*_*/templatelinks;
INSERT OR IGNORE INTO /*_*/imagelinks_tmp SELECT * FROM /*_*/imagelinks;
INSERT OR IGNORE INTO /*_*/categorylinks_tmp SELECT * FROM /*_*/categorylinks;
INSERT OR IGNORE INTO /*_*/category_tmp SELECT * FROM /*_*/category;
INSERT OR IGNORE INTO /*_*/externallinks_tmp SELECT * FROM /*_*/externallinks;
INSERT OR IGNORE INTO /*_*/langlinks_tmp SELECT * FROM /*_*/langlinks;
INSERT OR IGNORE INTO /*_*/iwlinks_tmp SELECT * FROM /*_*/iwlinks;
INSERT OR IGNORE INTO /*_*/site_stats_tmp SELECT * FROM /*_*/site_stats;
INSERT OR IGNORE INTO /*_*/image_tmp SELECT * FROM /*_*/image;
INSERT OR IGNORE INTO /*_*/oldimage_tmp SELECT * FROM /*_*/oldimage;
INSERT OR IGNORE INTO /*_*/filearchive_tmp SELECT * FROM /*_*/filearchive;
INSERT OR IGNORE INTO /*_*/uploadstash_tmp SELECT * FROM /*_*/uploadstash;
INSERT OR IGNORE INTO /*_*/recentchanges_tmp SELECT * FROM /*_*/recentchanges;
INSERT OR IGNORE INTO /*_*/watchlist_tmp SELECT * FROM /*_*/watchlist;
INSERT OR IGNORE INTO /*_*/interwiki_tmp SELECT * FROM /*_*/interwiki;
INSERT OR IGNORE INTO /*_*/querycache_tmp SELECT * FROM /*_*/querycache;
INSERT OR IGNORE INTO /*_*/objectcache_tmp SELECT * FROM /*_*/objectcache;
INSERT OR IGNORE INTO /*_*/transcache_tmp SELECT * FROM /*_*/transcache;
INSERT OR IGNORE INTO /*_*/logging_tmp SELECT * FROM /*_*/logging;
INSERT OR IGNORE INTO /*_*/log_search_tmp SELECT * FROM /*_*/log_search;
INSERT OR IGNORE INTO /*_*/job_tmp SELECT * FROM /*_*/job;
INSERT OR IGNORE INTO /*_*/redirect_tmp SELECT * FROM /*_*/redirect;
INSERT OR IGNORE INTO /*_*/querycachetwo_tmp SELECT * FROM /*_*/querycachetwo;
INSERT OR IGNORE INTO /*_*/page_restrictions_tmp SELECT * FROM /*_*/page_restrictions;
INSERT OR IGNORE INTO /*_*/protected_titles_tmp SELECT * FROM /*_*/protected_titles;
INSERT OR IGNORE INTO /*_*/page_props_tmp SELECT * FROM /*_*/page_props;
INSERT OR IGNORE INTO /*_*/updatelog_tmp SELECT * FROM /*_*/updatelog;
INSERT OR IGNORE INTO /*_*/change_tag_tmp SELECT * FROM /*_*/change_tag;
INSERT OR IGNORE INTO /*_*/tag_summary_tmp SELECT * FROM /*_*/tag_summary;
INSERT OR IGNORE INTO /*_*/valid_tag_tmp SELECT * FROM /*_*/valid_tag;
INSERT OR IGNORE INTO /*_*/l10n_cache_tmp SELECT * FROM /*_*/l10n_cache;
INSERT OR IGNORE INTO /*_*/module_deps_tmp SELECT * FROM /*_*/module_deps;
INSERT OR IGNORE INTO /*_*/sites_tmp SELECT * FROM /*_*/sites;
INSERT OR IGNORE INTO /*_*/site_identifiers_tmp SELECT * FROM /*_*/site_identifiers;

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

DROP TABLE IF EXISTS /*_*/transcache;
CREATE TABLE /*_*/transcache (
  tc_url varbinary(255) NOT NULL,
  tc_contents text,
  tc_time binary(14) NOT NULL
);
CREATE UNIQUE INDEX /*i*/tc_url_idx ON /*_*/transcache (tc_url);

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


INSERT INTO /*_*/updatelog (ul_key) VALUES ('initial_indexes');

-- vim: sw=2 sts=2 et
