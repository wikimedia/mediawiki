CREATE TABLE /*_*/user (
  user_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  user_name TEXT  NOT NULL default '',
  user_real_name TEXT  NOT NULL default '',
  user_password BLOB NOT NULL,
  user_newpassword BLOB NOT NULL,
  user_newpass_time BLOB,
  user_email TEXT NOT NULL,
  user_touched BLOB NOT NULL default '',
  user_token BLOB NOT NULL default '',
  user_email_authenticated BLOB,
  user_email_token BLOB,
  user_email_token_expires BLOB,
  user_registration BLOB,
  user_editcount INTEGER,
  user_password_expires BLOB DEFAULT NULL

) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/user_name ON /*_*/user (user_name);
CREATE INDEX /*i*/user_email_token ON /*_*/user (user_email_token);
CREATE INDEX /*i*/user_email ON /*_*/user (user_email);
CREATE TABLE /*_*/user_groups (
  ug_user INTEGER  NOT NULL default 0,
  ug_group BLOB NOT NULL default '',
  ug_expiry BLOB NULL default NULL,

  PRIMARY KEY (ug_user, ug_group)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/ug_group ON /*_*/user_groups (ug_group);
CREATE INDEX /*i*/ug_expiry ON /*_*/user_groups (ug_expiry);
CREATE TABLE /*_*/user_newtalk (
  user_id INTEGER  NOT NULL default 0,
  user_ip BLOB NOT NULL default '',
  user_last_timestamp BLOB NULL default NULL
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/un_user_id ON /*_*/user_newtalk (user_id);
CREATE INDEX /*i*/un_user_ip ON /*_*/user_newtalk (user_ip);
CREATE TABLE /*_*/user_properties (
  up_user INTEGER  NOT NULL,
  up_property BLOB NOT NULL,
  up_value BLOB,
  PRIMARY KEY (up_user,up_property)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/user_properties_property ON /*_*/user_properties (up_property);
CREATE TABLE /*_*/bot_passwords (
  bp_user INTEGER  NOT NULL,
  bp_app_id BLOB NOT NULL,
  bp_password BLOB NOT NULL,
  bp_token BLOB NOT NULL default '',
  bp_restrictions BLOB NOT NULL,
  bp_grants BLOB NOT NULL,

  PRIMARY KEY ( bp_user, bp_app_id )
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/page (
  page_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  page_namespace INTEGER NOT NULL,
  page_title TEXT  NOT NULL,
  page_restrictions BLOB NULL,
  page_is_redirect INTEGER  NOT NULL default 0,
  page_is_new INTEGER  NOT NULL default 0,
  page_random real  NOT NULL,
  page_touched BLOB NOT NULL default '',
  page_links_updated BLOB NULL default NULL,
  page_latest INTEGER  NOT NULL,
  page_len INTEGER  NOT NULL,
  page_content_model BLOB DEFAULT NULL,
  page_lang BLOB DEFAULT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/name_title ON /*_*/page (page_namespace,page_title);
CREATE INDEX /*i*/page_random ON /*_*/page (page_random);
CREATE INDEX /*i*/page_len ON /*_*/page (page_len);
CREATE INDEX /*i*/page_redirect_namespace_len ON /*_*/page (page_is_redirect, page_namespace, page_len);
CREATE TABLE /*_*/revision (
  rev_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  rev_page INTEGER  NOT NULL,
  rev_comment_id INTEGER  NOT NULL default 0,
  rev_actor INTEGER  NOT NULL default 0,
  rev_timestamp BLOB NOT NULL default '',
  rev_minor_edit INTEGER  NOT NULL default 0,
  rev_deleted INTEGER  NOT NULL default 0,
  rev_len INTEGER ,
  rev_parent_id INTEGER  default NULL,
  rev_sha1 BLOB NOT NULL default ''
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/rev_page_id ON /*_*/revision (rev_page, rev_id);
CREATE INDEX /*i*/rev_timestamp ON /*_*/revision (rev_timestamp);
CREATE INDEX /*i*/page_timestamp ON /*_*/revision (rev_page,rev_timestamp);
CREATE INDEX /*i*/rev_actor_timestamp ON /*_*/revision (rev_actor,rev_timestamp,rev_id);
CREATE INDEX /*i*/rev_page_actor_timestamp ON /*_*/revision (rev_page,rev_actor,rev_timestamp);
CREATE TABLE /*_*/revision_comment_temp (
  revcomment_rev INTEGER  NOT NULL,
  revcomment_comment_id INTEGER  NOT NULL,
  PRIMARY KEY (revcomment_rev, revcomment_comment_id)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revcomment_rev ON /*_*/revision_comment_temp (revcomment_rev);
CREATE TABLE /*_*/revision_actor_temp (
  revactor_rev INTEGER  NOT NULL,
  revactor_actor INTEGER  NOT NULL,
  revactor_timestamp BLOB NOT NULL default '',
  revactor_page INTEGER  NOT NULL,
  PRIMARY KEY (revactor_rev, revactor_actor)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revactor_rev ON /*_*/revision_actor_temp (revactor_rev);
CREATE INDEX /*i*/actor_timestamp ON /*_*/revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);
CREATE TABLE /*_*/ip_changes (
  ipc_rev_id INTEGER  NOT NULL PRIMARY KEY DEFAULT '0',
  ipc_rev_timestamp BLOB NOT NULL DEFAULT '',
  ipc_hex BLOB NOT NULL DEFAULT ''

) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/ipc_rev_timestamp ON /*_*/ip_changes (ipc_rev_timestamp);
CREATE INDEX /*i*/ipc_hex_time ON /*_*/ip_changes (ipc_hex,ipc_rev_timestamp);
CREATE TABLE /*_*/text (
  old_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  old_text BLOB NOT NULL,
  old_flags BLOB NOT NULL
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/comment (
  comment_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  comment_hash INTEGER NOT NULL,
  comment_text BLOB NOT NULL,
  comment_data BLOB
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/comment_hash ON /*_*/comment (comment_hash);
CREATE TABLE /*_*/archive (
  ar_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  ar_namespace INTEGER NOT NULL default 0,
  ar_title TEXT  NOT NULL default '',
  ar_comment_id INTEGER  NOT NULL,
  ar_actor INTEGER  NOT NULL,
  ar_timestamp BLOB NOT NULL default '',
  ar_minor_edit INTEGER NOT NULL default 0,
  ar_rev_id INTEGER  NOT NULL,
  ar_deleted INTEGER  NOT NULL default 0,
  ar_len INTEGER ,
  ar_page_id INTEGER ,
  ar_parent_id INTEGER  default NULL,
  ar_sha1 BLOB NOT NULL default ''
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/name_title_timestamp ON /*_*/archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*i*/ar_actor_timestamp ON /*_*/archive (ar_actor,ar_timestamp);
CREATE UNIQUE INDEX /*i*/ar_revid_uniq ON /*_*/archive (ar_rev_id);
CREATE TABLE /*_*/slots (
  slot_revision_id INTEGER  NOT NULL,
  slot_role_id INTEGER  NOT NULL,
  slot_content_id INTEGER  NOT NULL,
  slot_origin INTEGER  NOT NULL,

  PRIMARY KEY ( slot_revision_id, slot_role_id )
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/slot_revision_origin_role ON /*_*/slots (slot_revision_id, slot_origin, slot_role_id);
CREATE TABLE /*_*/content (
  content_id INTEGER  PRIMARY KEY AUTOINCREMENT,
  content_size INTEGER  NOT NULL,
  content_sha1 BLOB NOT NULL,
  content_model INTEGER  NOT NULL,
  content_address BLOB NOT NULL
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/slot_roles (
  role_id INTEGER PRIMARY KEY AUTOINCREMENT,
  role_name BLOB NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/role_name ON /*_*/slot_roles (role_name);
CREATE TABLE /*_*/content_models (
  model_id INTEGER PRIMARY KEY AUTOINCREMENT,
  model_name BLOB NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/model_name ON /*_*/content_models (model_name);
CREATE TABLE /*_*/pagelinks (
  pl_from INTEGER  NOT NULL default 0,
  pl_from_namespace INTEGER NOT NULL default 0,
  pl_namespace INTEGER NOT NULL default 0,
  pl_title TEXT  NOT NULL default '',
  PRIMARY KEY (pl_from,pl_namespace,pl_title)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/pl_namespace ON /*_*/pagelinks (pl_namespace,pl_title,pl_from);
CREATE INDEX /*i*/pl_backlinks_namespace ON /*_*/pagelinks (pl_from_namespace,pl_namespace,pl_title,pl_from);
CREATE TABLE /*_*/templatelinks (
  tl_from INTEGER  NOT NULL default 0,
  tl_from_namespace INTEGER NOT NULL default 0,
  tl_namespace INTEGER NOT NULL default 0,
  tl_title TEXT  NOT NULL default '',
  PRIMARY KEY (tl_from,tl_namespace,tl_title)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/tl_namespace ON /*_*/templatelinks (tl_namespace,tl_title,tl_from);
CREATE INDEX /*i*/tl_backlinks_namespace ON /*_*/templatelinks (tl_from_namespace,tl_namespace,tl_title,tl_from);
CREATE TABLE /*_*/imagelinks (
  il_from INTEGER  NOT NULL default 0,
  il_from_namespace INTEGER NOT NULL default 0,
  il_to TEXT  NOT NULL default '',
  PRIMARY KEY (il_from,il_to)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/il_to ON /*_*/imagelinks (il_to,il_from);
CREATE INDEX /*i*/il_backlinks_namespace ON /*_*/imagelinks (il_from_namespace,il_to,il_from);
CREATE TABLE /*_*/categorylinks (
  cl_from INTEGER  NOT NULL default 0,
  cl_to TEXT  NOT NULL default '',
  cl_sortkey BLOB NOT NULL default '',
  cl_sortkey_prefix TEXT  NOT NULL default '',
  cl_timestamp TEXT NOT NULL,
  cl_collation BLOB NOT NULL default '',
  cl_type TEXT NOT NULL default 'page',
  PRIMARY KEY (cl_from,cl_to)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/cl_sortkey ON /*_*/categorylinks (cl_to,cl_type,cl_sortkey,cl_from);
CREATE INDEX /*i*/cl_timestamp ON /*_*/categorylinks (cl_to,cl_timestamp);
CREATE INDEX /*i*/cl_collation_ext ON /*_*/categorylinks (cl_collation, cl_to, cl_type, cl_from);
CREATE TABLE /*_*/category (
  cat_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  cat_title TEXT  NOT NULL,
  cat_pages INTEGER  NOT NULL default 0,
  cat_subcats INTEGER  NOT NULL default 0,
  cat_files INTEGER  NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/cat_title ON /*_*/category (cat_title);
CREATE INDEX /*i*/cat_pages ON /*_*/category (cat_pages);
CREATE TABLE /*_*/externallinks (
  el_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  el_from INTEGER  NOT NULL default 0,
  el_to BLOB NOT NULL,
  el_index BLOB NOT NULL,
  el_index_60 BLOB NOT NULL
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/el_from ON /*_*/externallinks (el_from, el_to);
CREATE INDEX /*i*/el_to ON /*_*/externallinks (el_to, el_from);
CREATE INDEX /*i*/el_index ON /*_*/externallinks (el_index);
CREATE INDEX /*i*/el_index_60 ON /*_*/externallinks (el_index_60, el_id);
CREATE INDEX /*i*/el_from_index_60 ON /*_*/externallinks (el_from, el_index_60, el_id);
CREATE TABLE /*_*/langlinks (
  ll_from INTEGER  NOT NULL default 0,
  ll_lang BLOB NOT NULL default '',
  ll_title TEXT  NOT NULL default '',
  PRIMARY KEY (ll_from,ll_lang)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/ll_lang ON /*_*/langlinks (ll_lang, ll_title);
CREATE TABLE /*_*/iwlinks (
  iwl_from INTEGER  NOT NULL default 0,
  iwl_prefix BLOB NOT NULL default '',
  iwl_title TEXT  NOT NULL default '',
  PRIMARY KEY (iwl_from,iwl_prefix,iwl_title)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);
CREATE INDEX /*i*/iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);
CREATE TABLE /*_*/site_stats (
  ss_row_id INTEGER UNSIGNED  NOT NULL PRIMARY KEY,
  ss_total_edits INTEGER  default NULL,
  ss_good_articles INTEGER  default NULL,
  ss_total_pages INTEGER  default NULL,
  ss_users INTEGER  default NULL,
  ss_active_users INTEGER  default NULL,
  ss_images INTEGER  default NULL
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/ipblocks (
  ipb_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  ipb_address BLOB NOT NULL,
  ipb_user INTEGER  NOT NULL default 0,
  ipb_by_actor INTEGER  NOT NULL,
  ipb_reason_id INTEGER  NOT NULL,
  ipb_timestamp BLOB NOT NULL default '',
  ipb_auto INTEGER NOT NULL default 0,
  ipb_anon_only INTEGER NOT NULL default 0,
  ipb_create_account INTEGER NOT NULL default 1,
  ipb_enable_autoblock INTEGER NOT NULL default '1',
  ipb_expiry BLOB NOT NULL default '',
  ipb_range_start BLOB NOT NULL,
  ipb_range_end BLOB NOT NULL,
  ipb_deleted INTEGER NOT NULL default 0,
  ipb_block_email INTEGER NOT NULL default 0,
  ipb_allow_usertalk INTEGER NOT NULL default 0,
  ipb_parent_block_id INTEGER default NULL,
  ipb_sitewide INTEGER NOT NULL default 1

) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/ipb_address_unique ON /*_*/ipblocks (ipb_address, ipb_user, ipb_auto);
CREATE INDEX /*i*/ipb_user ON /*_*/ipblocks (ipb_user);
CREATE INDEX /*i*/ipb_range ON /*_*/ipblocks (ipb_range_start, ipb_range_end);
CREATE INDEX /*i*/ipb_timestamp ON /*_*/ipblocks (ipb_timestamp);
CREATE INDEX /*i*/ipb_expiry ON /*_*/ipblocks (ipb_expiry);
CREATE INDEX /*i*/ipb_parent_block_id ON /*_*/ipblocks (ipb_parent_block_id);
CREATE TABLE /*_*/ipblocks_restrictions (
  ir_ipb_id INTEGER NOT NULL,
  ir_type INTEGER NOT NULL,
  ir_value INTEGER NOT NULL,

  PRIMARY KEY (ir_ipb_id, ir_type, ir_value)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/ir_type_value ON /*_*/ipblocks_restrictions (ir_type, ir_value);
CREATE TABLE /*_*/image (
  img_name TEXT  NOT NULL default '' PRIMARY KEY,
  img_size INTEGER  NOT NULL default 0,
  img_width INTEGER NOT NULL default 0,
  img_height INTEGER NOT NULL default 0,
  img_metadata BLOB NOT NULL,
  img_bits INTEGER NOT NULL default 0,
  img_media_type TEXT default NULL,
  img_major_mime TEXT NOT NULL default "unknown",
  img_minor_mime BLOB NOT NULL default "unknown",
  img_description_id INTEGER  NOT NULL,
  img_actor INTEGER  NOT NULL,
  img_timestamp BLOB NOT NULL default '',
  img_sha1 BLOB NOT NULL default ''
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/img_actor_timestamp ON /*_*/image (img_actor,img_timestamp);
CREATE INDEX /*i*/img_size ON /*_*/image (img_size);
CREATE INDEX /*i*/img_timestamp ON /*_*/image (img_timestamp);
CREATE INDEX /*i*/img_sha1 ON /*_*/image (img_sha1);
CREATE INDEX /*i*/img_media_mime ON /*_*/image (img_media_type,img_major_mime,img_minor_mime);
CREATE TABLE /*_*/oldimage (
  oi_name TEXT  NOT NULL default '',
  oi_archive_name TEXT  NOT NULL default '',
  oi_size INTEGER  NOT NULL default 0,
  oi_width INTEGER NOT NULL default 0,
  oi_height INTEGER NOT NULL default 0,
  oi_bits INTEGER NOT NULL default 0,
  oi_description_id INTEGER  NOT NULL,
  oi_actor INTEGER  NOT NULL,
  oi_timestamp BLOB NOT NULL default '',

  oi_metadata BLOB NOT NULL,
  oi_media_type TEXT default NULL,
  oi_major_mime TEXT NOT NULL default "unknown",
  oi_minor_mime BLOB NOT NULL default "unknown",
  oi_deleted INTEGER  NOT NULL default 0,
  oi_sha1 BLOB NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/oi_actor_timestamp ON /*_*/oldimage (oi_actor,oi_timestamp);
CREATE INDEX /*i*/oi_name_timestamp ON /*_*/oldimage (oi_name,oi_timestamp);
CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name,oi_archive_name);
CREATE INDEX /*i*/oi_sha1 ON /*_*/oldimage (oi_sha1);
CREATE TABLE /*_*/filearchive (
  fa_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  fa_name TEXT  NOT NULL default '',
  fa_archive_name TEXT  default '',
  fa_storage_group BLOB,
  fa_storage_key BLOB default '',
  fa_deleted_user INTEGER,
  fa_deleted_timestamp BLOB default '',
  fa_deleted_reason_id INTEGER  NOT NULL,
  fa_size INTEGER  default 0,
  fa_width INTEGER default 0,
  fa_height INTEGER default 0,
  fa_metadata BLOB,
  fa_bits INTEGER default 0,
  fa_media_type TEXT default NULL,
  fa_major_mime TEXT default "unknown",
  fa_minor_mime BLOB default "unknown",
  fa_description_id INTEGER  NOT NULL,
  fa_actor INTEGER  NOT NULL,
  fa_timestamp BLOB default '',
  fa_deleted INTEGER  NOT NULL default 0,
  fa_sha1 BLOB NOT NULL default ''
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
CREATE INDEX /*i*/fa_actor_timestamp ON /*_*/filearchive (fa_actor,fa_timestamp);
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive (fa_sha1);
CREATE TABLE /*_*/uploadstash (
  us_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  us_user INTEGER  NOT NULL,
  us_key TEXT NOT NULL,
  us_orig_path TEXT NOT NULL,
  us_path TEXT NOT NULL,
  us_source_type TEXT,
  us_timestamp BLOB NOT NULL,

  us_status TEXT NOT NULL,
  us_chunk_inx INTEGER  NULL,
  us_props BLOB,
  us_size INTEGER  NOT NULL,
  us_sha1 TEXT NOT NULL,
  us_mime TEXT,
  us_media_type TEXT default NULL,
  us_image_width INTEGER ,
  us_image_height INTEGER ,
  us_image_bits INTEGER 

) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/us_user ON /*_*/uploadstash (us_user);
CREATE UNIQUE INDEX /*i*/us_key ON /*_*/uploadstash (us_key);
CREATE INDEX /*i*/us_timestamp ON /*_*/uploadstash (us_timestamp);
CREATE TABLE /*_*/recentchanges (
  rc_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  rc_timestamp BLOB NOT NULL default '',
  rc_actor INTEGER  NOT NULL,
  rc_namespace INTEGER NOT NULL default 0,
  rc_title TEXT  NOT NULL default '',
  rc_comment_id INTEGER  NOT NULL,
  rc_minor INTEGER  NOT NULL default 0,
  rc_bot INTEGER  NOT NULL default 0,
  rc_new INTEGER  NOT NULL default 0,
  rc_cur_id INTEGER  NOT NULL default 0,
  rc_this_oldid INTEGER  NOT NULL default 0,
  rc_last_oldid INTEGER  NOT NULL default 0,
  rc_type INTEGER  NOT NULL default 0,
  rc_source TEXT  not null default '',
  rc_patrolled INTEGER  NOT NULL default 0,
  rc_ip BLOB NOT NULL default '',
  rc_old_len INTEGER,
  rc_new_len INTEGER,
  rc_deleted INTEGER  NOT NULL default 0,
  rc_logid INTEGER  NOT NULL default 0,
  rc_log_type BLOB NULL default NULL,
  rc_log_action BLOB NULL default NULL,
  rc_params BLOB NULL
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/rc_timestamp ON /*_*/recentchanges (rc_timestamp);
CREATE INDEX /*i*/rc_namespace_title_timestamp ON /*_*/recentchanges (rc_namespace, rc_title, rc_timestamp);
CREATE INDEX /*i*/rc_cur_id ON /*_*/recentchanges (rc_cur_id);
CREATE INDEX /*i*/new_name_timestamp ON /*_*/recentchanges (rc_new,rc_namespace,rc_timestamp);
CREATE INDEX /*i*/rc_ip ON /*_*/recentchanges (rc_ip);
CREATE INDEX /*i*/rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX /*i*/rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);
CREATE INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);
CREATE INDEX /*i*/rc_this_oldid ON /*_*/recentchanges (rc_this_oldid);

CREATE TABLE /*_*/watchlist (
  wl_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  wl_user INTEGER  NOT NULL,
  wl_namespace INTEGER NOT NULL default 0,
  wl_title TEXT  NOT NULL default '',
  wl_notificationtimestamp BLOB

) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/wl_user ON /*_*/watchlist (wl_user, wl_namespace, wl_title);
CREATE INDEX /*i*/namespace_title ON /*_*/watchlist (wl_namespace, wl_title);
CREATE INDEX /*i*/wl_user_notificationtimestamp ON /*_*/watchlist (wl_user, wl_notificationtimestamp);
CREATE TABLE /*_*/watchlist_expiry (
  we_item INTEGER UNSIGNED NOT NULL PRIMARY KEY,
  we_expiry BLOB NOT NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/we_expiry ON /*_*/watchlist_expiry (we_expiry);
CREATE TABLE /*_*/searchindex (
  si_page INTEGER  NOT NULL,
  si_title TEXT NOT NULL default '',
  si_text TEXT NOT NULL
);

CREATE UNIQUE INDEX /*i*/si_page ON /*_*/searchindex (si_page);
CREATE INDEX /*i*/si_title ON /*_*/searchindex (si_title);
CREATE INDEX /*i*/si_text ON /*_*/searchindex (si_text);
CREATE TABLE /*_*/interwiki (
  iw_prefix TEXT NOT NULL PRIMARY KEY,
  iw_url BLOB NOT NULL,
  iw_api BLOB NOT NULL,
  iw_wikiid TEXT NOT NULL,
  iw_local INTEGER NOT NULL,
  iw_trans INTEGER NOT NULL default 0
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/querycache (
  qc_type BLOB NOT NULL,
  qc_value INTEGER  NOT NULL default 0,
  qc_namespace INTEGER NOT NULL default 0,
  qc_title TEXT  NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/qc_type ON /*_*/querycache (qc_type,qc_value);
CREATE TABLE /*_*/objectcache (
  keyname BLOB NOT NULL default '' PRIMARY KEY,
  value BLOB,
  exptime TEXT
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/exptime ON /*_*/objectcache (exptime);


CREATE TABLE /*_*/logging (
  log_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  log_type BLOB NOT NULL default '',
  log_action BLOB NOT NULL default '',
  log_timestamp BLOB NOT NULL default '19700101000000',
  log_actor INTEGER  NOT NULL,
  log_namespace INTEGER NOT NULL default 0,
  log_title TEXT  NOT NULL default '',
  log_page INTEGER  NULL,
  log_comment_id INTEGER  NOT NULL,
  log_params BLOB NOT NULL,
  log_deleted INTEGER  NOT NULL default 0
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/type_time ON /*_*/logging (log_type, log_timestamp);
CREATE INDEX /*i*/actor_time ON /*_*/logging (log_actor, log_timestamp);
CREATE INDEX /*i*/page_time ON /*_*/logging (log_namespace, log_title, log_timestamp);
CREATE INDEX /*i*/times ON /*_*/logging (log_timestamp);
CREATE INDEX /*i*/log_actor_type_time ON /*_*/logging (log_actor, log_type, log_timestamp);
CREATE INDEX /*i*/log_page_id_time ON /*_*/logging (log_page,log_timestamp);
CREATE INDEX /*i*/log_type_action ON /*_*/logging (log_type, log_action, log_timestamp);


CREATE TABLE /*_*/log_search (
  ls_field BLOB NOT NULL,
  ls_value TEXT NOT NULL,
  ls_log_id INTEGER  NOT NULL default 0,
  PRIMARY KEY (ls_field,ls_value,ls_log_id)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/ls_log_id ON /*_*/log_search (ls_log_id);
CREATE TABLE /*_*/job (
  job_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  job_cmd BLOB NOT NULL default '',
  job_namespace INTEGER NOT NULL,
  job_title TEXT  NOT NULL,
  job_timestamp BLOB NULL default NULL,
  job_params BLOB NOT NULL,
  job_random integer  NOT NULL default 0,
  job_attempts integer  NOT NULL default 0,
  job_token BLOB NOT NULL default '',
  job_token_timestamp BLOB NULL default NULL,
  job_sha1 BLOB NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/job_sha1 ON /*_*/job (job_sha1);
CREATE INDEX /*i*/job_cmd_token ON /*_*/job (job_cmd,job_token,job_random);
CREATE INDEX /*i*/job_cmd_token_id ON /*_*/job (job_cmd,job_token,job_id);
CREATE INDEX /*i*/job_cmd ON /*_*/job (job_cmd, job_namespace, job_title, job_params);
CREATE INDEX /*i*/job_timestamp ON /*_*/job (job_timestamp);
CREATE TABLE /*_*/querycache_info (
  qci_type BLOB NOT NULL default '' PRIMARY KEY,
  qci_timestamp BLOB NOT NULL default '19700101000000'
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/redirect (
  rd_from INTEGER  NOT NULL default 0 PRIMARY KEY,
  rd_namespace INTEGER NOT NULL default 0,
  rd_title TEXT  NOT NULL default '',
  rd_interwiki TEXT default NULL,
  rd_fragment TEXT  default NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/rd_ns_title ON /*_*/redirect (rd_namespace,rd_title,rd_from);
CREATE TABLE /*_*/querycachetwo (
  qcc_type BLOB NOT NULL,
  qcc_value INTEGER  NOT NULL default 0,
  qcc_namespace INTEGER NOT NULL default 0,
  qcc_title TEXT  NOT NULL default '',
  qcc_namespacetwo INTEGER NOT NULL default 0,
  qcc_titletwo TEXT  NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/qcc_type ON /*_*/querycachetwo (qcc_type,qcc_value);
CREATE INDEX /*i*/qcc_title ON /*_*/querycachetwo (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX /*i*/qcc_titletwo ON /*_*/querycachetwo (qcc_type,qcc_namespacetwo,qcc_titletwo);
CREATE TABLE /*_*/page_restrictions (
  pr_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  pr_page INTEGER NOT NULL,
  pr_type BLOB NOT NULL,
  pr_level BLOB NOT NULL,
  pr_cascade INTEGER NOT NULL,
  pr_user INTEGER  NULL,
  pr_expiry BLOB NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/pr_pagetype ON /*_*/page_restrictions (pr_page,pr_type);
CREATE INDEX /*i*/pr_typelevel ON /*_*/page_restrictions (pr_type,pr_level);
CREATE INDEX /*i*/pr_level ON /*_*/page_restrictions (pr_level);
CREATE INDEX /*i*/pr_cascade ON /*_*/page_restrictions (pr_cascade);
CREATE TABLE /*_*/protected_titles (
  pt_namespace INTEGER NOT NULL,
  pt_title TEXT  NOT NULL,
  pt_user INTEGER  NOT NULL,
  pt_reason_id INTEGER  NOT NULL,
  pt_timestamp BLOB NOT NULL,
  pt_expiry BLOB NOT NULL default '',
  pt_create_perm BLOB NOT NULL,

  PRIMARY KEY (pt_namespace,pt_title)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/pt_timestamp ON /*_*/protected_titles (pt_timestamp);
CREATE TABLE /*_*/page_props (
  pp_page INTEGER NOT NULL,
  pp_propname BLOB NOT NULL,
  pp_value BLOB NOT NULL,
  pp_sortkey REAL DEFAULT NULL,

  PRIMARY KEY (pp_page,pp_propname)
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/pp_propname_page ON /*_*/page_props (pp_propname,pp_page);
CREATE UNIQUE INDEX /*i*/pp_propname_sortkey_page ON /*_*/page_props (pp_propname,pp_sortkey,pp_page);
CREATE TABLE /*_*/change_tag (
  ct_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  ct_rc_id INTEGER NULL,
  ct_log_id INTEGER  NULL,
  ct_rev_id INTEGER  NULL,
  ct_params BLOB NULL,
  ct_tag_id INTEGER  NOT NULL
) /*$wgDBTableOptions*/;


CREATE UNIQUE INDEX /*i*/change_tag_rc_tag_id ON /*_*/change_tag (ct_rc_id,ct_tag_id);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag_id ON /*_*/change_tag (ct_log_id,ct_tag_id);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag_id ON /*_*/change_tag (ct_rev_id,ct_tag_id);
CREATE INDEX /*i*/change_tag_tag_id_id ON /*_*/change_tag (ct_tag_id,ct_rc_id,ct_rev_id,ct_log_id);
CREATE TABLE /*_*/l10n_cache (
  lc_lang BLOB NOT NULL,
  lc_key TEXT NOT NULL,
  lc_value BLOB NOT NULL,
  PRIMARY KEY (lc_lang, lc_key)
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/module_deps (
  md_module BLOB NOT NULL,
  md_skin BLOB NOT NULL,
  md_deps BLOB NOT NULL,
  PRIMARY KEY (md_module,md_skin)
) /*$wgDBTableOptions*/;
CREATE TABLE /*_*/sites (
  site_id                    INTEGER         NOT NULL PRIMARY KEY AUTOINCREMENT,
  site_global_key            BLOB       NOT NULL,
  site_type                  BLOB       NOT NULL,
  site_group                 BLOB       NOT NULL,
  site_source                BLOB       NOT NULL,
  site_language              BLOB       NOT NULL,
  site_protocol              BLOB       NOT NULL,
  site_domain                TEXT        NOT NULL,
  site_data                  BLOB                NOT NULL,
  site_forward              INTEGER                NOT NULL,
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
CREATE TABLE /*_*/change_tag_def (
    ctd_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
    ctd_name BLOB NOT NULL,
    ctd_user_defined INTEGER NOT NULL,
    ctd_count INTEGER  NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/ctd_name ON /*_*/change_tag_def (ctd_name);
CREATE INDEX /*i*/ctd_count ON /*_*/change_tag_def (ctd_count);
CREATE INDEX /*i*/ctd_user_defined ON /*_*/change_tag_def (ctd_user_defined);

CREATE TABLE /*_*/site_identifiers (
  si_type BLOB NOT NULL,
  si_key BLOB NOT NULL,
  si_site INTEGER  NOT NULL,
  PRIMARY KEY(si_type, si_key)
);
CREATE INDEX site_ids_site ON /*_*/site_identifiers (si_site);
CREATE INDEX site_ids_key ON /*_*/site_identifiers (si_key);
CREATE TABLE /*_*/updatelog (
  ul_key TEXT NOT NULL,
  ul_value BLOB DEFAULT NULL,
  PRIMARY KEY(ul_key)
);
CREATE TABLE /*_*/actor (
  actor_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  actor_user INTEGER  DEFAULT NULL,
  actor_name BLOB NOT NULL
);
CREATE UNIQUE INDEX actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX actor_name ON /*_*/actor (actor_name);
CREATE TABLE /*_*/user_former_groups (
  ufg_user INTEGER  DEFAULT 0 NOT NULL,
  ufg_group BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(ufg_user, ufg_group)
);
