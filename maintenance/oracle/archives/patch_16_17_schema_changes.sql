define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive MODIFY ar_user DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.archive MODIFY ar_deleted CHAR(1);
CREATE INDEX &mw_prefix.archive_i03 ON &mw_prefix.archive (ar_rev_id);

ALTER TABLE &mw_prefix.page MODIFY page_is_redirect default '0';
ALTER TABLE &mw_prefix.page MODIFY page_is_new default '0';
ALTER TABLE &mw_prefix.page MODIFY page_latest default 0;
ALTER TABLE &mw_prefix.page MODIFY page_len default 0;

ALTER TABLE &mw_prefix.categorylinks MODIFY cl_sortkey VARCHAR2(230);
ALTER TABLE &mw_prefix.categorylinks ADD cl_sortkey_prefix VARCHAR2(255) DEFAULT '' NOT NULL;
ALTER TABLE &mw_prefix.categorylinks ADD cl_collation VARCHAR2(32) DEFAULT '' NOT NULL;
ALTER TABLE &mw_prefix.categorylinks ADD cl_type VARCHAR2(6) DEFAULT 'page' NOT NULL;
DROP INDEX &mw_prefix.categorylinks_i01;
CREATE INDEX &mw_prefix.categorylinks_i01 ON &mw_prefix.categorylinks (cl_to,cl_type,cl_sortkey,cl_from);
CREATE INDEX &mw_prefix.categorylinks_i03 ON &mw_prefix.categorylinks (cl_collation);

ALTER TABLE &mw_prefix.filearchive MODIFY fa_deleted_user DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.filearchive MODIFY fa_size DEFAULT 0;
ALTER TABLE &mw_prefix.filearchive MODIFY fa_width DEFAULT 0;
ALTER TABLE &mw_prefix.filearchive MODIFY fa_height DEFAULT 0;
ALTER TABLE &mw_prefix.filearchive MODIFY fa_bits DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.filearchive MODIFY fa_user DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.filearchive MODIFY fa_deleted DEFAULT 0;

ALTER TABLE &mw_prefix.image MODIFY img_size DEFAULT 0;
ALTER TABLE &mw_prefix.image MODIFY img_width DEFAULT 0;
ALTER TABLE &mw_prefix.image MODIFY img_height DEFAULT 0;
ALTER TABLE &mw_prefix.image MODIFY img_bits DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.image MODIFY img_user DEFAULT 0 NOT NULL;

ALTER TABLE &mw_prefix.interwiki ADD iw_api BLOB DEFAULT EMPTY_BLOB();
ALTER TABLE &mw_prefix.interwiki MODIFY iw_api DEFAULT NULL NOT NULL;
ALTER TABLE &mw_prefix.interwiki ADD iw_wikiid VARCHAR2(64);

ALTER TABLE &mw_prefix.ipblocks MODIFY ipb_user DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.ipblocks MODIFY ipb_by DEFAULT 0;

CREATE TABLE &mw_prefix.iwlinks (
  iwl_from NUMBER DEFAULT 0 NOT NULL,
  iwl_prefix VARCHAR2(20) DEFAULT '' NOT NULL,
  iwl_title VARCHAR2(255) DEFAULT '' NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.iwlinks_ui01 ON &mw_prefix.iwlinks (iwl_from, iwl_prefix, iwl_title);
CREATE UNIQUE INDEX &mw_prefix.iwlinks_ui02 ON &mw_prefix.iwlinks (iwl_prefix, iwl_title, iwl_from);

ALTER TABLE &mw_prefix.logging MODIFY log_user DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.logging MODIFY log_deleted CHAR(1);

CREATE TABLE &mw_prefix.module_deps (
  md_module VARCHAR2(255) NOT NULL,
  md_skin VARCHAR2(32) NOT NULL,
  md_deps BLOB NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.module_deps_u01 ON &mw_prefix.module_deps (md_module, md_skin);

CREATE TABLE &mw_prefix.msg_resource_links (
  mrl_resource VARCHAR2(255) NOT NULL,
  mrl_message VARCHAR2(255) NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.msg_resource_links_u01 ON &mw_prefix.msg_resource_links (mrl_message, mrl_resource);

CREATE TABLE &mw_prefix.msg_resource (
  mr_resource VARCHAR2(255) NOT NULL,
  mr_lang varchar2(32) NOT NULL,
  mr_blob BLOB NOT NULL,
  mr_timestamp TIMESTAMP(6) WITH TIME ZONE NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.msg_resource_u01 ON &mw_prefix.msg_resource (mr_resource, mr_lang);

ALTER TABLE &mw_prefix.oldimage MODIFY oi_name DEFAULT 0;
ALTER TABLE &mw_prefix.oldimage MODIFY oi_size DEFAULT 0;
ALTER TABLE &mw_prefix.oldimage MODIFY oi_width DEFAULT 0;
ALTER TABLE &mw_prefix.oldimage MODIFY oi_height DEFAULT 0;
ALTER TABLE &mw_prefix.oldimage MODIFY oi_bits DEFAULT 0;
ALTER TABLE &mw_prefix.oldimage MODIFY oi_user DEFAULT 0 NOT NULL;

ALTER TABLE &mw_prefix.querycache MODIFY qc_value DEFAULT 0;

ALTER TABLE &mw_prefix.recentchanges MODIFY rc_user DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.recentchanges MODIFY rc_cur_id DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.recentchanges MODIFY rc_this_oldid DEFAULT 0;
ALTER TABLE &mw_prefix.recentchanges MODIFY rc_last_oldid DEFAULT 0;
ALTER TABLE &mw_prefix.recentchanges MODIFY rc_moved_to_ns DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.recentchanges MODIFY rc_deleted CHAR(1);
ALTER TABLE &mw_prefix.recentchanges MODIFY rc_logid DEFAULT 0;

ALTER TABLE &mw_prefix.revision MODIFY rev_page NOT NULL;
ALTER TABLE &mw_prefix.revision MODIFY rev_user DEFAULT 0;

ALTER TABLE &mw_prefix.updatelog ADD ul_value BLOB;

ALTER TABLE &mw_prefix.user_groups MODIFY ug_user DEFAULT 0 NOT NULL;

ALTER TABLE &mw_prefix.user_newtalk MODIFY user_id DEFAULT 0;

