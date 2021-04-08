CREATE TABLE /*_*/user (
  user_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_name varchar(255) binary NOT NULL default '',
  user_real_name varchar(255) binary NOT NULL default '',
  user_password tinyblob NOT NULL,
  user_newpassword tinyblob NOT NULL,
  user_newpass_time binary(14),
  user_email tinytext NOT NULL,
  user_touched binary(14) NOT NULL default '',
  user_token binary(32) NOT NULL default '',
  user_email_authenticated binary(14),
  user_email_token binary(32),
  user_email_token_expires binary(14),
  user_registration binary(14),
  user_editcount int,
  user_password_expires varbinary(14) DEFAULT NULL

) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/user_name ON /*_*/user (user_name);
CREATE INDEX /*i*/user_email_token ON /*_*/user (user_email_token);
CREATE INDEX /*i*/user_email ON /*_*/user (user_email(50));
CREATE TABLE /*_*/actor (
  actor_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  actor_user int unsigned,
  actor_name varchar(255) binary NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX /*i*/actor_name ON /*_*/actor (actor_name);
CREATE TABLE /*_*/user_groups (
  ug_user int unsigned NOT NULL default 0,
  ug_group varbinary(255) NOT NULL default '',
  ug_expiry varbinary(14) NULL default NULL,

  PRIMARY KEY (ug_user, ug_group)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/ug_group ON /*_*/user_groups (ug_group);
CREATE INDEX /*i*/ug_expiry ON /*_*/user_groups (ug_expiry);
CREATE TABLE /*_*/user_former_groups (
  ufg_user int unsigned NOT NULL default 0,
  ufg_group varbinary(255) NOT NULL default '',
  PRIMARY KEY (ufg_user,ufg_group)
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/user_newtalk (
  user_id int unsigned NOT NULL default 0,
  user_ip varbinary(40) NOT NULL default '',
  user_last_timestamp varbinary(14) NULL default NULL
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/un_user_id ON /*_*/user_newtalk (user_id);
CREATE INDEX /*i*/un_user_ip ON /*_*/user_newtalk (user_ip);
CREATE TABLE /*_*/user_properties (
  up_user int unsigned NOT NULL,
  up_property varbinary(255) NOT NULL,
  up_value blob,
  PRIMARY KEY (up_user,up_property)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/user_properties_property ON /*_*/user_properties (up_property);
CREATE TABLE /*_*/bot_passwords (
  bp_user int unsigned NOT NULL,
  bp_app_id varbinary(32) NOT NULL,
  bp_password tinyblob NOT NULL,
  bp_token binary(32) NOT NULL default '',
  bp_restrictions blob NOT NULL,
  bp_grants blob NOT NULL,

  PRIMARY KEY ( bp_user, bp_app_id )
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/page (
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
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/name_title ON /*_*/page (page_namespace,page_title);
CREATE INDEX /*i*/page_random ON /*_*/page (page_random);
CREATE INDEX /*i*/page_len ON /*_*/page (page_len);
CREATE INDEX /*i*/page_redirect_namespace_len ON /*_*/page (page_is_redirect, page_namespace, page_len);
CREATE TABLE /*_*/revision (
  rev_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rev_page int unsigned NOT NULL,
  rev_text_id int unsigned NOT NULL default 0,
  rev_comment varbinary(767) NOT NULL default '',
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

) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;
CREATE INDEX /*i*/rev_page_id ON /*_*/revision (rev_page, rev_id);
CREATE INDEX /*i*/rev_timestamp ON /*_*/revision (rev_timestamp);
CREATE INDEX /*i*/page_timestamp ON /*_*/revision (rev_page,rev_timestamp);
CREATE INDEX /*i*/user_timestamp ON /*_*/revision (rev_user,rev_timestamp);
CREATE INDEX /*i*/usertext_timestamp ON /*_*/revision (rev_user_text,rev_timestamp);
CREATE INDEX /*i*/page_user_timestamp ON /*_*/revision (rev_page,rev_user,rev_timestamp);
CREATE TABLE /*_*/revision_comment_temp (
  revcomment_rev int unsigned NOT NULL,
  revcomment_comment_id bigint unsigned NOT NULL,
  PRIMARY KEY (revcomment_rev, revcomment_comment_id)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revcomment_rev ON /*_*/revision_comment_temp (revcomment_rev);
CREATE TABLE /*_*/revision_actor_temp (
  revactor_rev int unsigned NOT NULL,
  revactor_actor bigint unsigned NOT NULL,
  revactor_timestamp binary(14) NOT NULL default '',
  revactor_page int unsigned NOT NULL,
  PRIMARY KEY (revactor_rev, revactor_actor)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revactor_rev ON /*_*/revision_actor_temp (revactor_rev);
CREATE INDEX /*i*/actor_timestamp ON /*_*/revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);
CREATE TABLE /*_*/ip_changes (
  ipc_rev_id int unsigned NOT NULL PRIMARY KEY DEFAULT '0',
  ipc_rev_timestamp binary(14) NOT NULL DEFAULT '',
  ipc_hex varbinary(35) NOT NULL DEFAULT ''

) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/ipc_rev_timestamp ON /*_*/ip_changes (ipc_rev_timestamp);
CREATE INDEX /*i*/ipc_hex_time ON /*_*/ip_changes (ipc_hex,ipc_rev_timestamp);
CREATE TABLE /*_*/text (
  old_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  old_text mediumblob NOT NULL,
  old_flags tinyblob NOT NULL
) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=10240;
CREATE TABLE /*_*/comment (
  comment_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  comment_hash INT NOT NULL,
  comment_text BLOB NOT NULL,
  comment_data BLOB
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/comment_hash ON /*_*/comment (comment_hash);
CREATE TABLE /*_*/archive (
  ar_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ar_namespace int NOT NULL default 0,
  ar_title varchar(255) binary NOT NULL default '',
  ar_comment_id bigint unsigned NOT NULL,
  ar_user int unsigned NOT NULL default 0, -- Deprecated in favor of ar_actor
  ar_user_text varchar(255) binary NOT NULL DEFAULT '', -- Deprecated in favor of ar_actor
  ar_actor bigint unsigned NOT NULL DEFAULT 0, -- ("DEFAULT 0" is temporary, signaling that ar_user/ar_user_text should be used)
  ar_timestamp binary(14) NOT NULL default '',
  ar_minor_edit tinyint NOT NULL default 0,
  ar_rev_id int unsigned NOT NULL,
  ar_text_id int unsigned NOT NULL DEFAULT 0,
  ar_deleted tinyint unsigned NOT NULL default 0,
  ar_len int unsigned,
  ar_page_id int unsigned,
  ar_parent_id int unsigned default NULL,
  ar_sha1 varbinary(32) NOT NULL default '',
  ar_content_model varbinary(32) DEFAULT NULL,
  ar_content_format varbinary(64) DEFAULT NULL
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/name_title_timestamp ON /*_*/archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*i*/ar_usertext_timestamp ON /*_*/archive (ar_user_text,ar_timestamp);
CREATE INDEX /*i*/ar_actor_timestamp ON /*_*/archive (ar_actor,ar_timestamp);
CREATE UNIQUE INDEX /*i*/ar_revid_uniq ON /*_*/archive (ar_rev_id);
CREATE TABLE /*_*/slots (
  slot_revision_id bigint unsigned NOT NULL,
  slot_role_id smallint unsigned NOT NULL,
  slot_content_id bigint unsigned NOT NULL,
  slot_origin bigint unsigned NOT NULL,

  PRIMARY KEY ( slot_revision_id, slot_role_id )
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/slot_revision_origin_role ON /*_*/slots (slot_revision_id, slot_origin, slot_role_id);
CREATE TABLE /*_*/content (
  content_id bigint unsigned PRIMARY KEY AUTO_INCREMENT,
  content_size int unsigned NOT NULL,
  content_sha1 varbinary(32) NOT NULL,
  content_model smallint unsigned NOT NULL,
  content_address varbinary(255) NOT NULL
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/slot_roles (
  role_id smallint PRIMARY KEY AUTO_INCREMENT,
  role_name varbinary(64) NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/role_name ON /*_*/slot_roles (role_name);
CREATE TABLE /*_*/content_models (
  model_id smallint PRIMARY KEY AUTO_INCREMENT,
  model_name varbinary(64) NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/model_name ON /*_*/content_models (model_name);
CREATE TABLE /*_*/pagelinks (
  pl_from int unsigned NOT NULL default 0,
  pl_from_namespace int NOT NULL default 0,
  pl_namespace int NOT NULL default 0,
  pl_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (pl_from,pl_namespace,pl_title)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/pl_namespace ON /*_*/pagelinks (pl_namespace,pl_title,pl_from);
CREATE INDEX /*i*/pl_backlinks_namespace ON /*_*/pagelinks (pl_from_namespace,pl_namespace,pl_title,pl_from);
CREATE TABLE /*_*/templatelinks (
  tl_from int unsigned NOT NULL default 0,
  tl_from_namespace int NOT NULL default 0,
  tl_namespace int NOT NULL default 0,
  tl_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (tl_from,tl_namespace,tl_title)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/tl_namespace ON /*_*/templatelinks (tl_namespace,tl_title,tl_from);
CREATE INDEX /*i*/tl_backlinks_namespace ON /*_*/templatelinks (tl_from_namespace,tl_namespace,tl_title,tl_from);
CREATE TABLE /*_*/imagelinks (
  il_from int unsigned NOT NULL default 0,
  il_from_namespace int NOT NULL default 0,
  il_to varchar(255) binary NOT NULL default '',
  PRIMARY KEY (il_from,il_to)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/il_to ON /*_*/imagelinks (il_to,il_from);
CREATE INDEX /*i*/il_backlinks_namespace ON /*_*/imagelinks (il_from_namespace,il_to,il_from);
CREATE TABLE /*_*/categorylinks (
  cl_from int unsigned NOT NULL default 0,
  cl_to varchar(255) binary NOT NULL default '',
  cl_sortkey varbinary(230) NOT NULL default '',
  cl_sortkey_prefix varchar(255) binary NOT NULL default '',
  cl_timestamp timestamp NOT NULL,
  cl_collation varbinary(32) NOT NULL default '',
  cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page',
  PRIMARY KEY (cl_from,cl_to)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/cl_sortkey ON /*_*/categorylinks (cl_to,cl_type,cl_sortkey,cl_from);
CREATE INDEX /*i*/cl_timestamp ON /*_*/categorylinks (cl_to,cl_timestamp);
CREATE INDEX /*i*/cl_collation_ext ON /*_*/categorylinks (cl_collation, cl_to, cl_type, cl_from);
CREATE TABLE /*_*/category (
  cat_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  cat_title varchar(255) binary NOT NULL,
  cat_pages int signed NOT NULL default 0,
  cat_subcats int signed NOT NULL default 0,
  cat_files int signed NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/cat_title ON /*_*/category (cat_title);
CREATE INDEX /*i*/cat_pages ON /*_*/category (cat_pages);
CREATE TABLE /*_*/externallinks (
  el_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  el_from int unsigned NOT NULL default 0,
  el_to blob NOT NULL,
  el_index blob NOT NULL,
  el_index_60 varbinary(60) NOT NULL
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/el_from ON /*_*/externallinks (el_from, el_to(40));
CREATE INDEX /*i*/el_to ON /*_*/externallinks (el_to(60), el_from);
CREATE INDEX /*i*/el_index ON /*_*/externallinks (el_index(60));
CREATE INDEX /*i*/el_index_60 ON /*_*/externallinks (el_index_60, el_id);
CREATE INDEX /*i*/el_from_index_60 ON /*_*/externallinks (el_from, el_index_60, el_id);
CREATE TABLE /*_*/langlinks (
  ll_from int unsigned NOT NULL default 0,
  ll_lang varbinary(20) NOT NULL default '',
  ll_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (ll_from,ll_lang)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/ll_lang ON /*_*/langlinks (ll_lang, ll_title);
CREATE TABLE /*_*/iwlinks (
  iwl_from int unsigned NOT NULL default 0,
  iwl_prefix varbinary(20) NOT NULL default '',
  iwl_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (iwl_from,iwl_prefix,iwl_title)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);
CREATE INDEX /*i*/iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);
CREATE TABLE /*_*/site_stats (
  ss_row_id int unsigned NOT NULL PRIMARY KEY,
  ss_total_edits bigint unsigned default NULL,
  ss_good_articles bigint unsigned default NULL,
  ss_total_pages bigint unsigned default NULL,
  ss_users bigint unsigned default NULL,
  ss_active_users bigint unsigned default NULL,
  ss_images bigint unsigned default NULL
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/ipblocks (
  ipb_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ipb_address tinyblob NOT NULL,
  ipb_user int unsigned NOT NULL default 0,
  ipb_by int unsigned NOT NULL default 0, -- Deprecated in favor of ipb_by_actor
  ipb_by_text varchar(255) binary NOT NULL default '', -- Deprecated in favor of ipb_by_actor
  ipb_by_actor bigint unsigned NOT NULL DEFAULT 0, -- ("DEFAULT 0" is temporary, signaling that ipb_by/ipb_by_text should be used)
  ipb_reason_id bigint unsigned NOT NULL,
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
  ipb_parent_block_id int default NULL,
  ipb_sitewide bool NOT NULL default 1

) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/ipb_address ON /*_*/ipblocks (ipb_address(255), ipb_user, ipb_auto, ipb_anon_only);
CREATE INDEX /*i*/ipb_user ON /*_*/ipblocks (ipb_user);
CREATE INDEX /*i*/ipb_range ON /*_*/ipblocks (ipb_range_start(8), ipb_range_end(8));
CREATE INDEX /*i*/ipb_timestamp ON /*_*/ipblocks (ipb_timestamp);
CREATE INDEX /*i*/ipb_expiry ON /*_*/ipblocks (ipb_expiry);
CREATE INDEX /*i*/ipb_parent_block_id ON /*_*/ipblocks (ipb_parent_block_id);
CREATE TABLE /*_*/ipblocks_restrictions (
  ir_ipb_id int NOT NULL,
  ir_type tinyint(1) NOT NULL,
  ir_value int NOT NULL,

  PRIMARY KEY (ir_ipb_id, ir_type, ir_value)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/ir_type_value ON /*_*/ipblocks_restrictions (ir_type, ir_value);
CREATE TABLE /*_*/image (
  img_name varchar(255) binary NOT NULL default '' PRIMARY KEY,
  img_size int unsigned NOT NULL default 0,
  img_width int NOT NULL default 0,
  img_height int NOT NULL default 0,
  img_metadata mediumblob NOT NULL,
  img_bits int NOT NULL default 0,
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  img_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",
  img_minor_mime varbinary(100) NOT NULL default "unknown",
  img_description_id bigint unsigned NOT NULL,
  img_user int unsigned NOT NULL default 0,
  img_user_text varchar(255) binary NOT NULL DEFAULT '',
  img_actor bigint unsigned NOT NULL DEFAULT 0,
  img_timestamp varbinary(14) NOT NULL default '',
  img_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/img_user_timestamp ON /*_*/image (img_user,img_timestamp);
CREATE INDEX /*i*/img_usertext_timestamp ON /*_*/image (img_user_text,img_timestamp);
CREATE INDEX /*i*/img_actor_timestamp ON /*_*/image (img_actor,img_timestamp);
CREATE INDEX /*i*/img_size ON /*_*/image (img_size);
CREATE INDEX /*i*/img_timestamp ON /*_*/image (img_timestamp);
CREATE INDEX /*i*/img_sha1 ON /*_*/image (img_sha1(10));
CREATE INDEX /*i*/img_media_mime ON /*_*/image (img_media_type,img_major_mime,img_minor_mime);
CREATE TABLE /*_*/oldimage (
  oi_name varchar(255) binary NOT NULL default '',
  oi_archive_name varchar(255) binary NOT NULL default '',
  oi_size int unsigned NOT NULL default 0,
  oi_width int NOT NULL default 0,
  oi_height int NOT NULL default 0,
  oi_bits int NOT NULL default 0,
  oi_description_id bigint unsigned NOT NULL,
  oi_user int unsigned NOT NULL default 0, -- Deprecated in favor of oi_actor
  oi_user_text varchar(255) binary NOT NULL DEFAULT '', -- Deprecated in favor of oi_actor
  oi_actor bigint unsigned NOT NULL DEFAULT 0, -- ("DEFAULT 0" is temporary, signaling that oi_user/oi_user_text should be used)
  oi_timestamp binary(14) NOT NULL default '',

  oi_metadata mediumblob NOT NULL,
  oi_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  oi_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",
  oi_minor_mime varbinary(100) NOT NULL default "unknown",
  oi_deleted tinyint unsigned NOT NULL default 0,
  oi_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/oi_usertext_timestamp ON /*_*/oldimage (oi_user_text,oi_timestamp);
CREATE INDEX /*i*/oi_actor_timestamp ON /*_*/oldimage (oi_actor,oi_timestamp);
CREATE INDEX /*i*/oi_name_timestamp ON /*_*/oldimage (oi_name,oi_timestamp);
CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name,oi_archive_name(14));
CREATE INDEX /*i*/oi_sha1 ON /*_*/oldimage (oi_sha1(10));
CREATE TABLE /*_*/filearchive (
  fa_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  fa_name varchar(255) binary NOT NULL default '',
  fa_archive_name varchar(255) binary default '',
  fa_storage_group varbinary(16),
  fa_storage_key varbinary(64) default '',
  fa_deleted_user int,
  fa_deleted_timestamp binary(14) default '',
  fa_deleted_reason_id bigint unsigned NOT NULL,
  fa_size int unsigned default 0,
  fa_width int default 0,
  fa_height int default 0,
  fa_metadata mediumblob,
  fa_bits int default 0,
  fa_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  fa_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") default "unknown",
  fa_minor_mime varbinary(100) default "unknown",
  fa_description_id bigint unsigned NOT NULL,
  fa_user int unsigned default 0, -- Deprecated in favor of fa_actor
  fa_user_text varchar(255) binary DEFAULT '', -- Deprecated in favor of fa_actor
  fa_actor bigint unsigned NOT NULL DEFAULT 0, -- ("DEFAULT 0" is temporary, signaling that fa_user/fa_user_text should be used)
  fa_timestamp binary(14) default '',
  fa_deleted tinyint unsigned NOT NULL default 0,
  fa_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
CREATE INDEX /*i*/fa_user_timestamp ON /*_*/filearchive (fa_user_text,fa_timestamp);
CREATE INDEX /*i*/fa_actor_timestamp ON /*_*/filearchive (fa_actor,fa_timestamp);
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive (fa_sha1(10));
CREATE TABLE /*_*/uploadstash (
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
  us_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  us_image_width int unsigned,
  us_image_height int unsigned,
  us_image_bits smallint unsigned

) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/us_user ON /*_*/uploadstash (us_user);
CREATE UNIQUE INDEX /*i*/us_key ON /*_*/uploadstash (us_key);
CREATE INDEX /*i*/us_timestamp ON /*_*/uploadstash (us_timestamp);
CREATE TABLE /*_*/recentchanges (
  rc_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rc_timestamp varbinary(14) NOT NULL default '',
  rc_user int unsigned NOT NULL default 0, -- Deprecated in favor of rc_actor
  rc_user_text varchar(255) binary NOT NULL DEFAULT '', -- Deprecated in favor of rc_actor
  rc_actor bigint unsigned NOT NULL DEFAULT 0, -- ("DEFAULT 0" is temporary, signaling that rc_user/rc_user_text should be used)
  rc_namespace int NOT NULL default 0,
  rc_title varchar(255) binary NOT NULL default '',
  rc_comment_id bigint unsigned NOT NULL,
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
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/rc_timestamp ON /*_*/recentchanges (rc_timestamp);
CREATE INDEX /*i*/rc_namespace_title_timestamp ON /*_*/recentchanges (rc_namespace, rc_title, rc_timestamp);
CREATE INDEX /*i*/rc_cur_id ON /*_*/recentchanges (rc_cur_id);
CREATE INDEX /*i*/new_name_timestamp ON /*_*/recentchanges (rc_new,rc_namespace,rc_timestamp);
CREATE INDEX /*i*/rc_ip ON /*_*/recentchanges (rc_ip);
CREATE INDEX /*i*/rc_ns_usertext ON /*_*/recentchanges (rc_namespace, rc_user_text);
CREATE INDEX /*i*/rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX /*i*/rc_user_text ON /*_*/recentchanges (rc_user_text, rc_timestamp);
CREATE INDEX /*i*/rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);
CREATE INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);
CREATE INDEX /*i*/rc_this_oldid ON /*_*/recentchanges (rc_this_oldid);

CREATE TABLE /*_*/watchlist (
  wl_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  wl_user int unsigned NOT NULL,
  wl_namespace int NOT NULL default 0,
  wl_title varchar(255) binary NOT NULL default '',
  wl_notificationtimestamp varbinary(14)

) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/wl_user ON /*_*/watchlist (wl_user, wl_namespace, wl_title);
CREATE INDEX /*i*/namespace_title ON /*_*/watchlist (wl_namespace, wl_title);
CREATE INDEX /*i*/wl_user_notificationtimestamp ON /*_*/watchlist (wl_user, wl_notificationtimestamp);
CREATE TABLE /*_*/searchindex (
  si_page int unsigned NOT NULL,
  si_title varchar(255) NOT NULL default '',
  si_text mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX /*i*/si_page ON /*_*/searchindex (si_page);
CREATE FULLTEXT INDEX /*i*/si_title ON /*_*/searchindex (si_title);
CREATE FULLTEXT INDEX /*i*/si_text ON /*_*/searchindex (si_text);
CREATE TABLE /*_*/interwiki (
  iw_prefix varchar(32) NOT NULL PRIMARY KEY,
  iw_url blob NOT NULL,
  iw_api blob NOT NULL,
  iw_wikiid varchar(64) NOT NULL,
  iw_local bool NOT NULL,
  iw_trans tinyint NOT NULL default 0
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/querycache (
  qc_type varbinary(32) NOT NULL,
  qc_value int unsigned NOT NULL default 0,
  qc_namespace int NOT NULL default 0,
  qc_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/qc_type ON /*_*/querycache (qc_type,qc_value);
CREATE TABLE /*_*/objectcache (
  keyname varbinary(255) NOT NULL default '' PRIMARY KEY,
  value mediumblob,
  exptime datetime
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/exptime ON /*_*/objectcache (exptime);


CREATE TABLE /*_*/logging (
  log_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  log_type varbinary(32) NOT NULL default '',
  log_action varbinary(32) NOT NULL default '',
  log_timestamp binary(14) NOT NULL default '19700101000000',
  log_user int unsigned NOT NULL default 0, -- Deprecated in favor of log_actor
  log_user_text varchar(255) binary NOT NULL default '', -- Deprecated in favor of log_actor
  log_actor bigint unsigned NOT NULL DEFAULT 0, -- ("DEFAULT 0" is temporary, signaling that log_user/log_user_text should be used)
  log_namespace int NOT NULL default 0,
  log_title varchar(255) binary NOT NULL default '',
  log_page int unsigned NULL,
  log_comment_id bigint unsigned NOT NULL,
  log_params blob NOT NULL,
  log_deleted tinyint unsigned NOT NULL default 0
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/type_time ON /*_*/logging (log_type, log_timestamp);
CREATE INDEX /*i*/user_time ON /*_*/logging (log_user, log_timestamp);
CREATE INDEX /*i*/actor_time ON /*_*/logging (log_actor, log_timestamp);
CREATE INDEX /*i*/page_time ON /*_*/logging (log_namespace, log_title, log_timestamp);
CREATE INDEX /*i*/times ON /*_*/logging (log_timestamp);
CREATE INDEX /*i*/log_user_type_time ON /*_*/logging (log_user, log_type, log_timestamp);
CREATE INDEX /*i*/log_actor_type_time ON /*_*/logging (log_actor, log_type, log_timestamp);
CREATE INDEX /*i*/log_page_id_time ON /*_*/logging (log_page,log_timestamp);
CREATE INDEX /*i*/log_type_action ON /*_*/logging (log_type, log_action, log_timestamp);
CREATE INDEX /*i*/log_user_text_type_time ON /*_*/logging (log_user_text, log_type, log_timestamp);
CREATE INDEX /*i*/log_user_text_time ON /*_*/logging (log_user_text, log_timestamp);


CREATE TABLE /*_*/log_search (
  ls_field varbinary(32) NOT NULL,
  ls_value varchar(255) NOT NULL,
  ls_log_id int unsigned NOT NULL default 0,
  PRIMARY KEY (ls_field,ls_value,ls_log_id)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/ls_log_id ON /*_*/log_search (ls_log_id);
CREATE TABLE /*_*/job (
  job_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  job_cmd varbinary(60) NOT NULL default '',
  job_namespace int NOT NULL,
  job_title varchar(255) binary NOT NULL,
  job_timestamp varbinary(14) NULL default NULL,
  job_params mediumblob NOT NULL,
  job_random integer unsigned NOT NULL default 0,
  job_attempts integer unsigned NOT NULL default 0,
  job_token varbinary(32) NOT NULL default '',
  job_token_timestamp varbinary(14) NULL default NULL,
  job_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/job_sha1 ON /*_*/job (job_sha1);
CREATE INDEX /*i*/job_cmd_token ON /*_*/job (job_cmd,job_token,job_random);
CREATE INDEX /*i*/job_cmd_token_id ON /*_*/job (job_cmd,job_token,job_id);
CREATE INDEX /*i*/job_cmd ON /*_*/job (job_cmd, job_namespace, job_title, job_params(128));
CREATE INDEX /*i*/job_timestamp ON /*_*/job (job_timestamp);
CREATE TABLE /*_*/querycache_info (
  qci_type varbinary(32) NOT NULL default '' PRIMARY KEY,
  qci_timestamp binary(14) NOT NULL default '19700101000000'
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/redirect (
  rd_from int unsigned NOT NULL default 0 PRIMARY KEY,
  rd_namespace int NOT NULL default 0,
  rd_title varchar(255) binary NOT NULL default '',
  rd_interwiki varchar(32) default NULL,
  rd_fragment varchar(255) binary default NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/rd_ns_title ON /*_*/redirect (rd_namespace,rd_title,rd_from);
CREATE TABLE /*_*/querycachetwo (
  qcc_type varbinary(32) NOT NULL,
  qcc_value int unsigned NOT NULL default 0,
  qcc_namespace int NOT NULL default 0,
  qcc_title varchar(255) binary NOT NULL default '',
  qcc_namespacetwo int NOT NULL default 0,
  qcc_titletwo varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/qcc_type ON /*_*/querycachetwo (qcc_type,qcc_value);
CREATE INDEX /*i*/qcc_title ON /*_*/querycachetwo (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX /*i*/qcc_titletwo ON /*_*/querycachetwo (qcc_type,qcc_namespacetwo,qcc_titletwo);
CREATE TABLE /*_*/page_restrictions (
  pr_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  pr_page int NOT NULL,
  pr_type varbinary(60) NOT NULL,
  pr_level varbinary(60) NOT NULL,
  pr_cascade tinyint NOT NULL,
  pr_user int unsigned NULL,
  pr_expiry varbinary(14) NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/pr_pagetype ON /*_*/page_restrictions (pr_page,pr_type);
CREATE INDEX /*i*/pr_typelevel ON /*_*/page_restrictions (pr_type,pr_level);
CREATE INDEX /*i*/pr_level ON /*_*/page_restrictions (pr_level);
CREATE INDEX /*i*/pr_cascade ON /*_*/page_restrictions (pr_cascade);
CREATE TABLE /*_*/protected_titles (
  pt_namespace int NOT NULL,
  pt_title varchar(255) binary NOT NULL,
  pt_user int unsigned NOT NULL,
  pt_reason_id bigint unsigned NOT NULL,
  pt_timestamp binary(14) NOT NULL,
  pt_expiry varbinary(14) NOT NULL default '',
  pt_create_perm varbinary(60) NOT NULL,

  PRIMARY KEY (pt_namespace,pt_title)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/pt_timestamp ON /*_*/protected_titles (pt_timestamp);
CREATE TABLE /*_*/page_props (
  pp_page int NOT NULL,
  pp_propname varbinary(60) NOT NULL,
  pp_value blob NOT NULL,
  pp_sortkey float DEFAULT NULL,

  PRIMARY KEY (pp_page,pp_propname)
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/pp_propname_page ON /*_*/page_props (pp_propname,pp_page);
CREATE UNIQUE INDEX /*i*/pp_propname_sortkey_page ON /*_*/page_props (pp_propname,pp_sortkey,pp_page);
CREATE TABLE /*_*/updatelog (
  ul_key varchar(255) NOT NULL PRIMARY KEY,
  ul_value blob
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/change_tag (
  ct_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ct_rc_id int NULL,
  ct_log_id int unsigned NULL,
  ct_rev_id int unsigned NULL,
  ct_params blob NULL,
  ct_tag_id int unsigned NOT NULL
) /*$wgDBTableOptions*/;


CREATE UNIQUE INDEX /*i*/change_tag_rc_tag_id ON /*_*/change_tag (ct_rc_id,ct_tag_id);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag_id ON /*_*/change_tag (ct_log_id,ct_tag_id);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag_id ON /*_*/change_tag (ct_rev_id,ct_tag_id);
CREATE INDEX /*i*/change_tag_tag_id_id ON /*_*/change_tag (ct_tag_id,ct_rc_id,ct_rev_id,ct_log_id);
CREATE TABLE /*_*/l10n_cache (
  lc_lang varbinary(32) NOT NULL,
  lc_key varchar(255) NOT NULL,
  lc_value mediumblob NOT NULL,
  PRIMARY KEY (lc_lang, lc_key)
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/module_deps (
  md_module varbinary(255) NOT NULL,
  md_skin varbinary(32) NOT NULL,
  md_deps mediumblob NOT NULL,
  PRIMARY KEY (md_module,md_skin)
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/sites (
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
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/sites_global_key ON /*_*/sites (site_global_key);
CREATE INDEX /*i*/sites_type ON /*_*/sites (site_type);
CREATE INDEX /*i*/sites_group ON /*_*/sites (site_group);
CREATE INDEX /*i*/sites_source ON /*_*/sites (site_source);
CREATE INDEX /*i*/sites_language ON /*_*/sites (site_language);
CREATE INDEX /*i*/sites_protocol ON /*_*/sites (site_protocol);
CREATE INDEX /*i*/sites_domain ON /*_*/sites (site_domain);
CREATE INDEX /*i*/sites_forward ON /*_*/sites (site_forward);
CREATE TABLE /*_*/site_identifiers (
  si_site                    INT UNSIGNED        NOT NULL,
  si_type                    varbinary(32)       NOT NULL,
  si_key                     varbinary(32)       NOT NULL,

  PRIMARY KEY (si_type, si_key)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/site_ids_site ON /*_*/site_identifiers (si_site);
CREATE INDEX /*i*/site_ids_key ON /*_*/site_identifiers (si_key);
CREATE TABLE /*_*/change_tag_def (
    ctd_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ctd_name varbinary(255) NOT NULL,
    ctd_user_defined tinyint(1) NOT NULL,
    ctd_count bigint unsigned NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/ctd_name ON /*_*/change_tag_def (ctd_name);
CREATE INDEX /*i*/ctd_count ON /*_*/change_tag_def (ctd_count);
CREATE INDEX /*i*/ctd_user_defined ON /*_*/change_tag_def (ctd_user_defined);
