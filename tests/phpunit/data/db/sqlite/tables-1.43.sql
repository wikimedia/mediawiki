CREATE TABLE /*_*/site_identifiers (
  si_type BLOB NOT NULL,
  si_key BLOB NOT NULL,
  si_site INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(si_type, si_key)
);
CREATE INDEX si_site ON /*_*/site_identifiers (si_site);
CREATE INDEX si_key ON /*_*/site_identifiers (si_key);
CREATE TABLE /*_*/updatelog (
  ul_key VARCHAR(255) NOT NULL,
  ul_value BLOB DEFAULT NULL,
  PRIMARY KEY(ul_key)
);
CREATE TABLE /*_*/actor (
  actor_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  actor_user INTEGER UNSIGNED DEFAULT NULL,
  actor_name BLOB NOT NULL
);
CREATE UNIQUE INDEX actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX actor_name ON /*_*/actor (actor_name);
CREATE TABLE /*_*/user_former_groups (
  ufg_user INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  ufg_group BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(ufg_user, ufg_group)
);
CREATE TABLE /*_*/bot_passwords (
  bp_user INTEGER UNSIGNED NOT NULL,
  bp_app_id BLOB NOT NULL,
  bp_password BLOB NOT NULL,
  bp_token BLOB DEFAULT '' NOT NULL,
  bp_restrictions BLOB NOT NULL,
  bp_grants BLOB NOT NULL,
  PRIMARY KEY(bp_user, bp_app_id)
);
CREATE TABLE /*_*/comment (
  comment_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  comment_hash INTEGER NOT NULL, comment_text BLOB NOT NULL,
  comment_data BLOB DEFAULT NULL
);
CREATE INDEX comment_hash ON /*_*/comment (comment_hash);
CREATE TABLE /*_*/slots (
  slot_revision_id BIGINT UNSIGNED NOT NULL,
  slot_role_id SMALLINT UNSIGNED NOT NULL,
  slot_content_id BIGINT UNSIGNED NOT NULL,
  slot_origin BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY(slot_revision_id, slot_role_id)
);
CREATE INDEX slot_revision_origin_role ON /*_*/slots (
  slot_revision_id, slot_origin, slot_role_id
);
CREATE TABLE /*_*/site_stats (
  ss_row_id INTEGER UNSIGNED NOT NULL,
  ss_total_edits BIGINT UNSIGNED DEFAULT NULL,
  ss_good_articles BIGINT UNSIGNED DEFAULT NULL,
  ss_total_pages BIGINT UNSIGNED DEFAULT NULL,
  ss_users BIGINT UNSIGNED DEFAULT NULL,
  ss_active_users BIGINT UNSIGNED DEFAULT NULL,
  ss_images BIGINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY(ss_row_id)
);
CREATE TABLE /*_*/user_properties (
  up_user INTEGER UNSIGNED NOT NULL,
  up_property BLOB NOT NULL,
  up_value BLOB DEFAULT NULL,
  PRIMARY KEY(up_user, up_property)
);
CREATE INDEX up_property ON /*_*/user_properties (up_property);
CREATE TABLE /*_*/log_search (
  ls_field BLOB NOT NULL,
  ls_value VARCHAR(255) NOT NULL,
  ls_log_id INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  PRIMARY KEY(ls_field, ls_value, ls_log_id)
);
CREATE INDEX ls_log_id ON /*_*/log_search (ls_log_id);
CREATE TABLE /*_*/change_tag (
  ct_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  ct_rc_id BIGINT UNSIGNED DEFAULT NULL,
  ct_log_id INTEGER UNSIGNED DEFAULT NULL,
  ct_rev_id INTEGER UNSIGNED DEFAULT NULL,
  ct_params BLOB DEFAULT NULL, ct_tag_id INTEGER UNSIGNED NOT NULL
);
CREATE UNIQUE INDEX ct_rc_tag_id ON /*_*/change_tag (ct_rc_id, ct_tag_id);
CREATE UNIQUE INDEX ct_log_tag_id ON /*_*/change_tag (ct_log_id, ct_tag_id);
CREATE UNIQUE INDEX ct_rev_tag_id ON /*_*/change_tag (ct_rev_id, ct_tag_id);
CREATE INDEX ct_tag_id_id ON /*_*/change_tag (
  ct_tag_id, ct_rc_id, ct_rev_id, ct_log_id
);
CREATE TABLE /*_*/content (
  content_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  content_size INTEGER UNSIGNED NOT NULL,
  content_sha1 BLOB NOT NULL, content_model SMALLINT UNSIGNED NOT NULL,
  content_address BLOB NOT NULL
);
CREATE TABLE /*_*/l10n_cache (
  lc_lang BLOB NOT NULL,
  lc_key VARCHAR(255) NOT NULL,
  lc_value BLOB NOT NULL,
  PRIMARY KEY(lc_lang, lc_key)
);
CREATE TABLE /*_*/module_deps (
  md_module BLOB NOT NULL,
  md_skin BLOB NOT NULL,
  md_deps BLOB NOT NULL,
  PRIMARY KEY(md_module, md_skin)
);
CREATE TABLE /*_*/redirect (
  rd_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  rd_namespace INTEGER DEFAULT 0 NOT NULL,
  rd_title BLOB DEFAULT '' NOT NULL,
  rd_interwiki VARCHAR(32) DEFAULT NULL,
  rd_fragment BLOB DEFAULT NULL,
  PRIMARY KEY(rd_from)
);
CREATE INDEX rd_ns_title ON /*_*/redirect (rd_namespace, rd_title, rd_from);
CREATE TABLE /*_*/pagelinks (
  pl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  pl_target_id BIGINT UNSIGNED NOT NULL,
  pl_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(pl_from, pl_target_id)
);
CREATE INDEX pl_target_id ON /*_*/pagelinks (pl_target_id, pl_from);
CREATE INDEX pl_backlinks_namespace_target_id ON /*_*/pagelinks (
  pl_from_namespace, pl_target_id,
  pl_from
);
CREATE TABLE /*_*/templatelinks (
  tl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  tl_target_id BIGINT UNSIGNED NOT NULL,
  tl_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(tl_from, tl_target_id)
);
CREATE INDEX tl_target_id ON /*_*/templatelinks (tl_target_id, tl_from);
CREATE INDEX tl_backlinks_namespace_target_id ON /*_*/templatelinks (
  tl_from_namespace, tl_target_id,
  tl_from
);
CREATE TABLE /*_*/imagelinks (
  il_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  il_to BLOB DEFAULT '' NOT NULL,
  il_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(il_from, il_to)
);
CREATE INDEX il_to ON /*_*/imagelinks (il_to, il_from);
CREATE INDEX il_backlinks_namespace ON /*_*/imagelinks (
  il_from_namespace, il_to, il_from
);
CREATE TABLE /*_*/langlinks (
  ll_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  ll_lang BLOB DEFAULT '' NOT NULL,
  ll_title BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(ll_from, ll_lang)
);
CREATE INDEX ll_lang ON /*_*/langlinks (ll_lang, ll_title);
CREATE TABLE /*_*/iwlinks (
  iwl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  iwl_prefix BLOB DEFAULT '' NOT NULL,
  iwl_title BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(iwl_from, iwl_prefix, iwl_title)
);
CREATE INDEX iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);
CREATE TABLE /*_*/category (
  cat_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  cat_title BLOB NOT NULL, cat_pages INTEGER DEFAULT 0 NOT NULL,
  cat_subcats INTEGER DEFAULT 0 NOT NULL,
  cat_files INTEGER DEFAULT 0 NOT NULL
);
CREATE UNIQUE INDEX cat_title ON /*_*/category (cat_title);
CREATE INDEX cat_pages ON /*_*/category (cat_pages);
CREATE TABLE /*_*/watchlist_expiry (
  we_item INTEGER UNSIGNED NOT NULL,
  we_expiry BLOB NOT NULL,
  PRIMARY KEY(we_item)
);
CREATE INDEX we_expiry ON /*_*/watchlist_expiry (we_expiry);
CREATE TABLE /*_*/change_tag_def (
  ctd_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  ctd_name BLOB NOT NULL, ctd_user_defined SMALLINT NOT NULL,
  ctd_count BIGINT UNSIGNED DEFAULT 0 NOT NULL
);
CREATE UNIQUE INDEX ctd_name ON /*_*/change_tag_def (ctd_name);
CREATE INDEX ctd_count ON /*_*/change_tag_def (ctd_count);
CREATE INDEX ctd_user_defined ON /*_*/change_tag_def (ctd_user_defined);
CREATE TABLE /*_*/ipblocks_restrictions (
  ir_ipb_id INTEGER UNSIGNED NOT NULL,
  ir_type SMALLINT NOT NULL,
  ir_value INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(ir_ipb_id, ir_type, ir_value)
);
CREATE INDEX ir_type_value ON /*_*/ipblocks_restrictions (ir_type, ir_value);
CREATE TABLE /*_*/querycache (
  qc_type BLOB NOT NULL, qc_value INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  qc_namespace INTEGER DEFAULT 0 NOT NULL,
  qc_title BLOB DEFAULT '' NOT NULL
);
CREATE INDEX qc_type ON /*_*/querycache (qc_type, qc_value);
CREATE TABLE /*_*/querycachetwo (
  qcc_type BLOB NOT NULL, qcc_value INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  qcc_namespace INTEGER DEFAULT 0 NOT NULL,
  qcc_title BLOB DEFAULT '' NOT NULL,
  qcc_namespacetwo INTEGER DEFAULT 0 NOT NULL,
  qcc_titletwo BLOB DEFAULT '' NOT NULL
);
CREATE INDEX qcc_type ON /*_*/querycachetwo (qcc_type, qcc_value);
CREATE INDEX qcc_title ON /*_*/querycachetwo (
  qcc_type, qcc_namespace, qcc_title
);
CREATE INDEX qcc_titletwo ON /*_*/querycachetwo (
  qcc_type, qcc_namespacetwo, qcc_titletwo
);
CREATE TABLE /*_*/page_restrictions (
  pr_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  pr_page INTEGER UNSIGNED NOT NULL,
  pr_type BLOB NOT NULL, pr_level BLOB NOT NULL,
  pr_cascade SMALLINT NOT NULL, pr_expiry BLOB DEFAULT NULL
);
CREATE UNIQUE INDEX pr_pagetype ON /*_*/page_restrictions (pr_page, pr_type);
CREATE INDEX pr_typelevel ON /*_*/page_restrictions (pr_type, pr_level);
CREATE INDEX pr_level ON /*_*/page_restrictions (pr_level);
CREATE INDEX pr_cascade ON /*_*/page_restrictions (pr_cascade);
CREATE TABLE /*_*/user_groups (
  ug_user INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  ug_group BLOB DEFAULT '' NOT NULL,
  ug_expiry BLOB DEFAULT NULL,
  PRIMARY KEY(ug_user, ug_group)
);
CREATE INDEX ug_group ON /*_*/user_groups (ug_group);
CREATE INDEX ug_expiry ON /*_*/user_groups (ug_expiry);
CREATE TABLE /*_*/querycache_info (
  qci_type BLOB DEFAULT '' NOT NULL,
  qci_timestamp BLOB DEFAULT '19700101000000' NOT NULL,
  PRIMARY KEY(qci_type)
);
CREATE TABLE /*_*/watchlist (
  wl_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  wl_user INTEGER UNSIGNED NOT NULL,
  wl_namespace INTEGER DEFAULT 0 NOT NULL,
  wl_title BLOB DEFAULT '' NOT NULL, wl_notificationtimestamp BLOB DEFAULT NULL
);
CREATE UNIQUE INDEX wl_user ON /*_*/watchlist (wl_user, wl_namespace, wl_title);
CREATE INDEX wl_namespace_title ON /*_*/watchlist (wl_namespace, wl_title);
CREATE INDEX wl_user_notificationtimestamp ON /*_*/watchlist (
  wl_user, wl_notificationtimestamp
);
CREATE TABLE /*_*/sites (
  site_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  site_global_key BLOB NOT NULL,
  site_type BLOB NOT NULL,
  site_group BLOB NOT NULL,
  site_source BLOB NOT NULL,
  site_language BLOB NOT NULL,
  site_protocol BLOB NOT NULL,
  site_domain VARCHAR(255) NOT NULL,
  site_data BLOB NOT NULL,
  site_forward SMALLINT NOT NULL,
  site_config BLOB NOT NULL
);
CREATE UNIQUE INDEX site_global_key ON /*_*/sites (site_global_key);
CREATE TABLE /*_*/user_newtalk (
  user_id INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  user_ip BLOB DEFAULT '' NOT NULL, user_last_timestamp BLOB DEFAULT NULL
);
CREATE INDEX un_user_id ON /*_*/user_newtalk (user_id);
CREATE INDEX un_user_ip ON /*_*/user_newtalk (user_ip);
CREATE TABLE /*_*/interwiki (
  iw_prefix VARCHAR(32) NOT NULL,
  iw_url BLOB NOT NULL,
  iw_api BLOB NOT NULL,
  iw_wikiid VARCHAR(64) NOT NULL,
  iw_local SMALLINT NOT NULL,
  iw_trans SMALLINT DEFAULT 0 NOT NULL,
  PRIMARY KEY(iw_prefix)
);
CREATE TABLE /*_*/protected_titles (
  pt_namespace INTEGER NOT NULL,
  pt_title BLOB NOT NULL,
  pt_user INTEGER UNSIGNED NOT NULL,
  pt_reason_id BIGINT UNSIGNED NOT NULL,
  pt_timestamp BLOB NOT NULL,
  pt_expiry BLOB NOT NULL,
  pt_create_perm BLOB NOT NULL,
  PRIMARY KEY(pt_namespace, pt_title)
);
CREATE INDEX pt_timestamp ON /*_*/protected_titles (pt_timestamp);
CREATE TABLE /*_*/externallinks (
  el_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  el_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  el_to_domain_index BLOB DEFAULT '' NOT NULL,
  el_to_path BLOB DEFAULT NULL
);
CREATE INDEX el_from ON /*_*/externallinks (el_from);
CREATE INDEX el_to_domain_index_to_path ON /*_*/externallinks (el_to_domain_index, el_to_path);
CREATE TABLE /*_*/ip_changes (
  ipc_rev_id INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  ipc_rev_timestamp BLOB NOT NULL,
  ipc_hex BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(ipc_rev_id)
);
CREATE INDEX ipc_rev_timestamp ON /*_*/ip_changes (ipc_rev_timestamp);
CREATE INDEX ipc_hex_time ON /*_*/ip_changes (ipc_hex, ipc_rev_timestamp);
CREATE TABLE /*_*/page_props (
  pp_page INTEGER UNSIGNED NOT NULL,
  pp_propname BLOB NOT NULL,
  pp_value BLOB NOT NULL,
  pp_sortkey DOUBLE PRECISION DEFAULT NULL,
  PRIMARY KEY(pp_page, pp_propname)
);
CREATE UNIQUE INDEX pp_propname_page ON /*_*/page_props (pp_propname, pp_page);
CREATE UNIQUE INDEX pp_propname_sortkey_page ON /*_*/page_props (pp_propname, pp_sortkey, pp_page);
CREATE TABLE /*_*/job (
  job_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  job_cmd BLOB DEFAULT '' NOT NULL, job_namespace INTEGER NOT NULL,
  job_title BLOB NOT NULL, job_timestamp BLOB DEFAULT NULL,
  job_params BLOB NOT NULL, job_random INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  job_attempts INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  job_token BLOB DEFAULT '' NOT NULL,
  job_token_timestamp BLOB DEFAULT NULL,
  job_sha1 BLOB DEFAULT '' NOT NULL
);
CREATE INDEX job_sha1 ON /*_*/job (job_sha1);
CREATE INDEX job_cmd_token ON /*_*/job (job_cmd, job_token, job_random);
CREATE INDEX job_cmd_token_id ON /*_*/job (job_cmd, job_token, job_id);
CREATE INDEX job_cmd ON /*_*/job (
  job_cmd, job_namespace, job_title,
  job_params
);
CREATE INDEX job_timestamp ON /*_*/job (job_timestamp);
CREATE TABLE /*_*/slot_roles (
  role_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  role_name BLOB NOT NULL
);
CREATE UNIQUE INDEX role_name ON /*_*/slot_roles (role_name);
CREATE TABLE /*_*/content_models (
  model_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  model_name BLOB NOT NULL
);
CREATE UNIQUE INDEX model_name ON /*_*/content_models (model_name);
CREATE TABLE /*_*/categorylinks (
  cl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  cl_to BLOB DEFAULT '' NOT NULL,
  cl_sortkey BLOB DEFAULT '' NOT NULL,
  cl_sortkey_prefix BLOB DEFAULT '' NOT NULL,
  cl_timestamp DATETIME NOT NULL,
  cl_collation BLOB DEFAULT '' NOT NULL,
  cl_type TEXT DEFAULT 'page' NOT NULL,
  PRIMARY KEY(cl_from, cl_to)
);
CREATE INDEX cl_sortkey ON /*_*/categorylinks (
  cl_to, cl_type, cl_sortkey, cl_from
);
CREATE INDEX cl_timestamp ON /*_*/categorylinks (cl_to, cl_timestamp);
CREATE TABLE /*_*/logging (
  log_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  log_type BLOB DEFAULT '' NOT NULL, log_action BLOB DEFAULT '' NOT NULL,
  log_timestamp BLOB DEFAULT '19700101000000' NOT NULL,
  log_actor BIGINT UNSIGNED NOT NULL,
  log_namespace INTEGER DEFAULT 0 NOT NULL,
  log_title BLOB DEFAULT '' NOT NULL,
  log_page INTEGER UNSIGNED DEFAULT NULL,
  log_comment_id BIGINT UNSIGNED NOT NULL,
  log_params BLOB NOT NULL, log_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL
);
CREATE INDEX log_type_time ON /*_*/logging (log_type, log_timestamp);
CREATE INDEX log_actor_time ON /*_*/logging (log_actor, log_timestamp);
CREATE INDEX log_page_time ON /*_*/logging (
  log_namespace, log_title, log_timestamp
);
CREATE INDEX log_times ON /*_*/logging (log_timestamp);
CREATE INDEX log_actor_type_time ON /*_*/logging (
  log_actor, log_type, log_timestamp
);
CREATE INDEX log_page_id_time ON /*_*/logging (log_page, log_timestamp);
CREATE INDEX log_type_action ON /*_*/logging (
  log_type, log_action, log_timestamp
);
CREATE TABLE /*_*/uploadstash (
  us_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  us_user INTEGER UNSIGNED NOT NULL,
  us_key VARCHAR(255) NOT NULL,
  us_orig_path VARCHAR(255) NOT NULL,
  us_path VARCHAR(255) NOT NULL,
  us_source_type VARCHAR(50) DEFAULT NULL,
  us_timestamp BLOB NOT NULL,
  us_status VARCHAR(50) NOT NULL,
  us_chunk_inx INTEGER UNSIGNED DEFAULT NULL,
  us_props BLOB DEFAULT NULL,
  us_size BIGINT UNSIGNED NOT NULL,
  us_sha1 VARCHAR(31) NOT NULL,
  us_mime VARCHAR(255) DEFAULT NULL,
  us_media_type TEXT DEFAULT NULL,
  us_image_width INTEGER UNSIGNED DEFAULT NULL,
  us_image_height INTEGER UNSIGNED DEFAULT NULL,
  us_image_bits SMALLINT UNSIGNED DEFAULT NULL
);
CREATE INDEX us_user ON /*_*/uploadstash (us_user);
CREATE UNIQUE INDEX us_key ON /*_*/uploadstash (us_key);
CREATE INDEX us_timestamp ON /*_*/uploadstash (us_timestamp);
CREATE TABLE /*_*/filearchive (
  fa_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  fa_name BLOB DEFAULT '' NOT NULL, fa_archive_name BLOB DEFAULT '',
  fa_storage_group BLOB DEFAULT NULL,
  fa_storage_key BLOB DEFAULT '', fa_deleted_user INTEGER DEFAULT NULL,
  fa_deleted_timestamp BLOB DEFAULT NULL,
  fa_deleted_reason_id BIGINT UNSIGNED NOT NULL,
  fa_size BIGINT UNSIGNED DEFAULT 0,
  fa_width INTEGER DEFAULT 0, fa_height INTEGER DEFAULT 0,
  fa_metadata BLOB DEFAULT NULL, fa_bits INTEGER DEFAULT 0,
  fa_media_type TEXT DEFAULT NULL, fa_major_mime TEXT DEFAULT 'unknown',
  fa_minor_mime BLOB DEFAULT 'unknown',
  fa_description_id BIGINT UNSIGNED NOT NULL,
  fa_actor BIGINT UNSIGNED NOT NULL,
  fa_timestamp BLOB DEFAULT NULL, fa_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  fa_sha1 BLOB DEFAULT '' NOT NULL
);
CREATE INDEX fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
CREATE INDEX fa_storage_group ON /*_*/filearchive (
  fa_storage_group, fa_storage_key
);
CREATE INDEX fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
CREATE INDEX fa_actor_timestamp ON /*_*/filearchive (fa_actor, fa_timestamp);
CREATE INDEX fa_sha1 ON /*_*/filearchive (fa_sha1);
CREATE TABLE /*_*/text (
  old_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  old_text BLOB NOT NULL, old_flags BLOB NOT NULL
);
CREATE TABLE /*_*/oldimage (
  oi_name BLOB DEFAULT '' NOT NULL, oi_archive_name BLOB DEFAULT '' NOT NULL,
  oi_size BIGINT UNSIGNED DEFAULT 0 NOT NULL,
  oi_width INTEGER DEFAULT 0 NOT NULL,
  oi_height INTEGER DEFAULT 0 NOT NULL,
  oi_bits INTEGER DEFAULT 0 NOT NULL,
  oi_description_id BIGINT UNSIGNED NOT NULL,
  oi_actor BIGINT UNSIGNED NOT NULL,
  oi_timestamp BLOB NOT NULL, oi_metadata BLOB NOT NULL,
  oi_media_type TEXT DEFAULT NULL, oi_major_mime TEXT DEFAULT 'unknown' NOT NULL,
  oi_minor_mime BLOB DEFAULT 'unknown' NOT NULL,
  oi_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  oi_sha1 BLOB DEFAULT '' NOT NULL
);
CREATE INDEX oi_actor_timestamp ON /*_*/oldimage (oi_actor, oi_timestamp);
CREATE INDEX oi_name_timestamp ON /*_*/oldimage (oi_name, oi_timestamp);
CREATE INDEX oi_name_archive_name ON /*_*/oldimage (oi_name, oi_archive_name);
CREATE INDEX oi_sha1 ON /*_*/oldimage (oi_sha1);
CREATE INDEX oi_timestamp ON /*_*/oldimage (oi_timestamp);
CREATE TABLE /*_*/objectcache (
  keyname BLOB DEFAULT '' NOT NULL,
  value BLOB DEFAULT NULL,
  exptime BLOB NOT NULL,
  modtoken VARCHAR(17) DEFAULT '00000000000000000' NOT NULL,
  flags INTEGER UNSIGNED DEFAULT NULL,
  PRIMARY KEY(keyname)
);
CREATE INDEX exptime ON /*_*/objectcache (exptime);
CREATE TABLE /*_*/block (
  bl_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  bl_target INTEGER UNSIGNED NOT NULL,
  bl_by_actor BIGINT UNSIGNED NOT NULL,
  bl_reason_id BIGINT UNSIGNED NOT NULL,
  bl_timestamp BLOB NOT NULL, bl_anon_only SMALLINT DEFAULT 0 NOT NULL,
  bl_create_account SMALLINT DEFAULT 1 NOT NULL,
  bl_enable_autoblock SMALLINT DEFAULT 1 NOT NULL,
  bl_expiry BLOB NOT NULL, bl_deleted SMALLINT DEFAULT 0 NOT NULL,
  bl_block_email SMALLINT DEFAULT 0 NOT NULL,
  bl_allow_usertalk SMALLINT DEFAULT 0 NOT NULL,
  bl_parent_block_id INTEGER UNSIGNED DEFAULT NULL,
  bl_sitewide SMALLINT DEFAULT 1 NOT NULL
);
CREATE INDEX bl_timestamp ON /*_*/block (bl_timestamp);
CREATE INDEX bl_target ON /*_*/block (bl_target);
CREATE INDEX bl_expiry ON /*_*/block (bl_expiry);
CREATE INDEX bl_parent_block_id ON /*_*/block (bl_parent_block_id);
CREATE TABLE /*_*/block_target (
  bt_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  bt_address BLOB DEFAULT NULL, bt_user INTEGER UNSIGNED DEFAULT NULL,
  bt_user_text BLOB DEFAULT NULL, bt_auto SMALLINT DEFAULT 0 NOT NULL,
  bt_range_start BLOB DEFAULT NULL,
  bt_range_end BLOB DEFAULT NULL, bt_ip_hex BLOB DEFAULT NULL,
  bt_count INTEGER DEFAULT 0 NOT NULL
);
CREATE INDEX bt_address ON /*_*/block_target (bt_address);
CREATE INDEX bt_ip_user_text ON /*_*/block_target (bt_ip_hex, bt_user_text);
CREATE INDEX bt_range ON /*_*/block_target (bt_range_start, bt_range_end);
CREATE INDEX bt_user ON /*_*/block_target (bt_user);
CREATE TABLE /*_*/image (
  img_name BLOB DEFAULT '' NOT NULL,
  img_size BIGINT UNSIGNED DEFAULT 0 NOT NULL,
  img_width INTEGER DEFAULT 0 NOT NULL,
  img_height INTEGER DEFAULT 0 NOT NULL,
  img_metadata BLOB NOT NULL,
  img_bits INTEGER DEFAULT 0 NOT NULL,
  img_media_type TEXT DEFAULT NULL,
  img_major_mime TEXT DEFAULT 'unknown' NOT NULL,
  img_minor_mime BLOB DEFAULT 'unknown' NOT NULL,
  img_description_id BIGINT UNSIGNED NOT NULL,
  img_actor BIGINT UNSIGNED NOT NULL,
  img_timestamp BLOB NOT NULL,
  img_sha1 BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(img_name)
);
CREATE INDEX img_actor_timestamp ON /*_*/image (img_actor, img_timestamp);
CREATE INDEX img_size ON /*_*/image (img_size);
CREATE INDEX img_timestamp ON /*_*/image (img_timestamp);
CREATE INDEX img_sha1 ON /*_*/image (img_sha1);
CREATE INDEX img_media_mime ON /*_*/image (
  img_media_type, img_major_mime, img_minor_mime
);
CREATE TABLE /*_*/recentchanges (
  rc_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  rc_timestamp BLOB NOT NULL, rc_actor BIGINT UNSIGNED NOT NULL,
  rc_namespace INTEGER DEFAULT 0 NOT NULL,
  rc_title BLOB DEFAULT '' NOT NULL, rc_comment_id BIGINT UNSIGNED NOT NULL,
  rc_minor SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  rc_bot SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  rc_new SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  rc_cur_id INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  rc_this_oldid INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  rc_last_oldid INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  rc_type SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  rc_source BLOB DEFAULT '' NOT NULL,
  rc_patrolled SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  rc_ip BLOB DEFAULT '' NOT NULL, rc_old_len INTEGER DEFAULT NULL,
  rc_new_len INTEGER DEFAULT NULL, rc_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  rc_logid INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  rc_log_type BLOB DEFAULT NULL, rc_log_action BLOB DEFAULT NULL,
  rc_params BLOB DEFAULT NULL
);
CREATE INDEX rc_timestamp ON /*_*/recentchanges (rc_timestamp);
CREATE INDEX rc_namespace_title_timestamp ON /*_*/recentchanges (
  rc_namespace, rc_title, rc_timestamp
);
CREATE INDEX rc_cur_id ON /*_*/recentchanges (rc_cur_id);
CREATE INDEX rc_new_name_timestamp ON /*_*/recentchanges (
  rc_new, rc_namespace, rc_timestamp
);
CREATE INDEX rc_ip ON /*_*/recentchanges (rc_ip);
CREATE INDEX rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);
CREATE INDEX rc_name_type_patrolled_timestamp ON /*_*/recentchanges (
  rc_namespace, rc_type, rc_patrolled,
  rc_timestamp
);
CREATE INDEX rc_this_oldid ON /*_*/recentchanges (rc_this_oldid);
CREATE TABLE /*_*/archive (
  ar_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  ar_namespace INTEGER DEFAULT 0 NOT NULL,
  ar_title BLOB DEFAULT '' NOT NULL, ar_comment_id BIGINT UNSIGNED NOT NULL,
  ar_actor BIGINT UNSIGNED NOT NULL,
  ar_timestamp BLOB NOT NULL, ar_minor_edit SMALLINT DEFAULT 0 NOT NULL,
  ar_rev_id INTEGER UNSIGNED NOT NULL,
  ar_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  ar_len INTEGER UNSIGNED DEFAULT NULL,
  ar_page_id INTEGER UNSIGNED DEFAULT NULL,
  ar_parent_id INTEGER UNSIGNED DEFAULT NULL,
  ar_sha1 BLOB DEFAULT '' NOT NULL
);
CREATE INDEX ar_name_title_timestamp ON /*_*/archive (
  ar_namespace, ar_title, ar_timestamp
);
CREATE INDEX ar_actor_timestamp ON /*_*/archive (ar_actor, ar_timestamp);
CREATE UNIQUE INDEX ar_revid_uniq ON /*_*/archive (ar_rev_id);
CREATE TABLE /*_*/page (
  page_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  page_namespace INTEGER NOT NULL, page_title BLOB NOT NULL,
  page_is_redirect SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  page_is_new SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  page_random DOUBLE PRECISION NOT NULL,
  page_touched BLOB NOT NULL, page_links_updated BLOB DEFAULT NULL,
  page_latest INTEGER UNSIGNED NOT NULL,
  page_len INTEGER UNSIGNED NOT NULL,
  page_content_model BLOB DEFAULT NULL,
  page_lang BLOB DEFAULT NULL
);
CREATE UNIQUE INDEX page_name_title ON /*_*/page (page_namespace, page_title);
CREATE INDEX page_random ON /*_*/page (page_random);
CREATE INDEX page_len ON /*_*/page (page_len);
CREATE INDEX page_redirect_namespace_len ON /*_*/page (
  page_is_redirect, page_namespace,
  page_len
);
CREATE TABLE /*_*/user (
  user_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  user_name BLOB DEFAULT '' NOT NULL,
  user_real_name BLOB DEFAULT '' NOT NULL,
  user_password BLOB NOT NULL, user_newpassword BLOB NOT NULL,
  user_newpass_time BLOB DEFAULT NULL,
  user_email CLOB NOT NULL, user_touched BLOB NOT NULL,
  user_token BLOB DEFAULT '' NOT NULL,
  user_email_authenticated BLOB DEFAULT NULL,
  user_email_token BLOB DEFAULT NULL,
  user_email_token_expires BLOB DEFAULT NULL,
  user_registration BLOB DEFAULT NULL,
  user_editcount INTEGER UNSIGNED DEFAULT NULL,
  user_password_expires BLOB DEFAULT NULL,
  user_is_temp SMALLINT DEFAULT 0 NOT NULL
);
CREATE UNIQUE INDEX user_name ON /*_*/user (user_name);
CREATE INDEX user_email_token ON /*_*/user (user_email_token);
CREATE INDEX user_email ON /*_*/user (user_email);
CREATE TABLE /*_*/user_autocreate_serial (
  uas_shard INTEGER UNSIGNED NOT NULL,
  uas_year SMALLINT UNSIGNED NOT NULL,
  uas_value INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(uas_shard, uas_year)
);
CREATE TABLE /*_*/revision (
  rev_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  rev_page INTEGER UNSIGNED NOT NULL,
  rev_comment_id BIGINT UNSIGNED NOT NULL,
  rev_actor BIGINT UNSIGNED NOT NULL,
  rev_timestamp BLOB NOT NULL, rev_minor_edit SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  rev_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  rev_len INTEGER UNSIGNED DEFAULT NULL,
  rev_parent_id BIGINT UNSIGNED DEFAULT NULL,
  rev_sha1 BLOB DEFAULT '' NOT NULL
);
CREATE INDEX rev_timestamp ON /*_*/revision (rev_timestamp);
CREATE INDEX rev_page_timestamp ON /*_*/revision (rev_page, rev_timestamp);
CREATE INDEX rev_actor_timestamp ON /*_*/revision (rev_actor, rev_timestamp, rev_id);
CREATE INDEX rev_page_actor_timestamp ON /*_*/revision (
  rev_page, rev_actor, rev_timestamp
);
CREATE TABLE /*_*/searchindex (
  si_page INTEGER UNSIGNED NOT NULL,
  si_title CLOB NOT NULL,
  si_text CLOB NOT NULL,
  PRIMARY KEY(si_page)
);
CREATE INDEX si_title ON /*_*/searchindex (si_title);
CREATE INDEX si_text ON /*_*/searchindex (si_text);
CREATE TABLE /*_*/linktarget (
  lt_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  lt_namespace INTEGER NOT NULL, lt_title BLOB NOT NULL
);
CREATE UNIQUE INDEX lt_namespace_title ON /*_*/linktarget (lt_namespace, lt_title);
