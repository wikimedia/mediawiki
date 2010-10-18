-- defines must comply with ^define\s*([^\s=]*)\s*=\s?'\{\$([^\}]*)\}';
define mw_prefix='{$wgDBprefix}';


CREATE SEQUENCE user_user_id_seq MINVALUE 0 START WITH 0;
CREATE TABLE &mw_prefix.mwuser ( -- replace reserved word 'user'
  user_id                   NUMBER  NOT NULL,
  user_name                 VARCHAR2(255)     NOT NULL,
  user_real_name            VARCHAR2(512),
  user_password             VARCHAR2(255),
  user_newpassword          VARCHAR2(255),
  user_newpass_time         TIMESTAMP(6) WITH TIME ZONE,
  user_token                VARCHAR2(32),
  user_email                VARCHAR2(255),
  user_email_token          VARCHAR2(32),
  user_email_token_expires  TIMESTAMP(6) WITH TIME ZONE,
  user_email_authenticated  TIMESTAMP(6) WITH TIME ZONE,
  user_options              CLOB,
  user_touched              TIMESTAMP(6) WITH TIME ZONE,
  user_registration         TIMESTAMP(6) WITH TIME ZONE,
  user_editcount            NUMBER
);
ALTER TABLE &mw_prefix.mwuser ADD CONSTRAINT &mw_prefix.mwuser_pk PRIMARY KEY (user_id);
CREATE UNIQUE INDEX &mw_prefix.mwuser_u01 ON &mw_prefix.mwuser (user_name);
CREATE INDEX &mw_prefix.mwuser_i01 ON &mw_prefix.mwuser (user_email_token);

-- Create a dummy user to satisfy fk contraints especially with revisions
INSERT INTO &mw_prefix.mwuser
  VALUES (user_user_id_seq.nextval,'Anonymous','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL, '', current_timestamp, current_timestamp, 0);

CREATE TABLE &mw_prefix.user_groups (
  ug_user   NUMBER      NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE,
  ug_group  VARCHAR2(16)     NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.user_groups_u01 ON &mw_prefix.user_groups (ug_user,ug_group);
CREATE INDEX &mw_prefix.user_groups_i01 ON &mw_prefix.user_groups (ug_group);

CREATE TABLE &mw_prefix.user_newtalk (
  user_id  NUMBER NOT NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE,
  user_ip  VARCHAR2(40)        NULL,
  user_last_timestamp         TIMESTAMP(6) WITH TIME ZONE
);
CREATE INDEX &mw_prefix.user_newtalk_i01 ON &mw_prefix.user_newtalk (user_id);
CREATE INDEX &mw_prefix.user_newtalk_i02 ON &mw_prefix.user_newtalk (user_ip);

CREATE TABLE &mw_prefix.user_properties (
  up_user NUMBER NOT NULL,
  up_property VARCHAR2(32) NOT NULL,
  up_value CLOB
);
CREATE UNIQUE INDEX &mw_prefix.user_properties_u01 on &mw_prefix.user_properties (up_user,up_property);
CREATE INDEX &mw_prefix.user_properties_i01 on &mw_prefix.user_properties (up_property);


CREATE SEQUENCE page_page_id_seq;
CREATE TABLE &mw_prefix.page (
  page_id            NUMBER        NOT NULL,
  page_namespace     NUMBER       NOT NULL,
  page_title         VARCHAR2(255)           NOT NULL,
  page_restrictions  VARCHAR2(255),
  page_counter       NUMBER         DEFAULT 0 NOT NULL,
  page_is_redirect   CHAR(1)           DEFAULT 0 NOT NULL,
  page_is_new        CHAR(1)           DEFAULT 0 NOT NULL,
  page_random        NUMBER(15,14) NOT NULL,
  page_touched       TIMESTAMP(6) WITH TIME ZONE,
  page_latest        NUMBER        NOT NULL, -- FK?
  page_len           NUMBER        NOT NULL
);
ALTER TABLE &mw_prefix.page ADD CONSTRAINT &mw_prefix.page_pk PRIMARY KEY (page_id);
CREATE UNIQUE INDEX &mw_prefix.page_u01 ON &mw_prefix.page (page_namespace,page_title);
CREATE INDEX &mw_prefix.page_i01 ON &mw_prefix.page (page_random);
CREATE INDEX &mw_prefix.page_i02 ON &mw_prefix.page (page_len);

/*$mw$*/
CREATE TRIGGER &mw_prefix.page_set_random BEFORE INSERT ON &mw_prefix.page
	FOR EACH ROW WHEN (new.page_random IS NULL)
BEGIN
	SELECT dbms_random.value INTO :NEW.page_random FROM dual;
END;
/*$mw$*/

CREATE SEQUENCE revision_rev_id_seq;
CREATE TABLE &mw_prefix.revision (
  rev_id          NUMBER      NOT NULL,
  rev_page        NUMBER          NULL  REFERENCES &mw_prefix.page (page_id) ON DELETE CASCADE,
  rev_text_id     NUMBER          NULL,
  rev_comment     VARCHAR2(255),
  rev_user        NUMBER      NOT NULL  REFERENCES &mw_prefix.mwuser(user_id),
  rev_user_text   VARCHAR2(255)         NOT NULL,
  rev_timestamp   TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  rev_minor_edit  CHAR(1)         DEFAULT '0' NOT NULL,
  rev_deleted     CHAR(1)         DEFAULT '0' NOT NULL,
  rev_len         NUMBER          NULL,
  rev_parent_id   NUMBER      	   DEFAULT NULL
);
ALTER TABLE &mw_prefix.revision ADD CONSTRAINT &mw_prefix.revision_pk PRIMARY KEY (rev_id);
CREATE UNIQUE INDEX &mw_prefix.revision_u01 ON &mw_prefix.revision (rev_page, rev_id);
CREATE INDEX &mw_prefix.revision_i01 ON &mw_prefix.revision (rev_timestamp);
CREATE INDEX &mw_prefix.revision_i02 ON &mw_prefix.revision (rev_page,rev_timestamp);
CREATE INDEX &mw_prefix.revision_i03 ON &mw_prefix.revision (rev_user,rev_timestamp);
CREATE INDEX &mw_prefix.revision_i04 ON &mw_prefix.revision (rev_user_text,rev_timestamp);

CREATE SEQUENCE text_old_id_seq;
CREATE TABLE &mw_prefix.pagecontent ( -- replaces reserved word 'text'
  old_id     NUMBER  NOT NULL,
  old_text   CLOB,
  old_flags  VARCHAR2(255)
);
ALTER TABLE &mw_prefix.pagecontent ADD CONSTRAINT &mw_prefix.pagecontent_pk PRIMARY KEY (old_id);

CREATE TABLE &mw_prefix.archive (
  ar_namespace   NUMBER    NOT NULL,
  ar_title       VARCHAR2(255)         NOT NULL,
  ar_text        CLOB,
  ar_comment     VARCHAR2(255),
  ar_user        NUMBER          NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL,
  ar_user_text   VARCHAR2(255)         NOT NULL,
  ar_timestamp   TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  ar_minor_edit  CHAR(1)         DEFAULT '0' NOT NULL,
  ar_flags       VARCHAR2(255),
  ar_rev_id      NUMBER,
  ar_text_id     NUMBER,
  ar_deleted     NUMBER      DEFAULT '0' NOT NULL,
  ar_len         NUMBER,
  ar_page_id     NUMBER,
  ar_parent_id   NUMBER
);
CREATE INDEX &mw_prefix.archive_i01 ON &mw_prefix.archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX &mw_prefix.archive_i02 ON &mw_prefix.archive (ar_user_text,ar_timestamp);
CREATE INDEX &mw_prefix.archive_i03 ON &mw_prefix.archive (ar_namespace, ar_title, ar_rev_id);

CREATE TABLE &mw_prefix.pagelinks (
  pl_from       NUMBER   NOT NULL  REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE,
  pl_namespace  NUMBER  NOT NULL,
  pl_title      VARCHAR2(255)      NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.pagelinks_u01 ON &mw_prefix.pagelinks (pl_from,pl_namespace,pl_title);
CREATE UNIQUE INDEX &mw_prefix.pagelinks_u02 ON &mw_prefix.pagelinks (pl_namespace,pl_title,pl_from);

CREATE TABLE &mw_prefix.templatelinks (
  tl_from       NUMBER  NOT NULL  REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE,
  tl_namespace  NUMBER     NOT NULL,
  tl_title      VARCHAR2(255)     NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.templatelinks_u01 ON &mw_prefix.templatelinks (tl_from,tl_namespace,tl_title);
CREATE UNIQUE INDEX &mw_prefix.templatelinks_u02 ON &mw_prefix.templatelinks (tl_namespace,tl_title,tl_from);

CREATE TABLE &mw_prefix.imagelinks (
  il_from  NUMBER  NOT NULL  REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE,
  il_to    VARCHAR2(255)     NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.imagelinks_u01 ON &mw_prefix.imagelinks (il_from,il_to);
CREATE UNIQUE INDEX &mw_prefix.imagelinks_u02 ON &mw_prefix.imagelinks (il_to,il_from);


CREATE TABLE &mw_prefix.categorylinks (
  cl_from       NUMBER      NOT NULL  REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE,
  cl_to         VARCHAR2(255)         NOT NULL,
  cl_sortkey    VARCHAR2(230),
  cl_sortkey_prefix VARCHAR2(255) DEFAULT '' NOT NULL,
  cl_timestamp  TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  cl_collation	VARCHAR2(32) DEFAULT '' NOT NULL,
  cl_type 		VARCHAR2(6) DEFAULT 'page' NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.categorylinks_u01 ON &mw_prefix.categorylinks (cl_from,cl_to);
CREATE INDEX &mw_prefix.categorylinks_i01 ON &mw_prefix.categorylinks (cl_to,cl_type,cl_sortkey,cl_from);
CREATE INDEX &mw_prefix.categorylinks_i02 ON &mw_prefix.categorylinks (cl_to,cl_timestamp);
CREATE INDEX &mw_prefix.categorylinks_i03 ON &mw_prefix.categorylinks (cl_collation);

CREATE SEQUENCE category_cat_id_seq;
CREATE TABLE &mw_prefix.category (
  cat_id NUMBER NOT NULL,
  cat_title VARCHAR2(255) NOT NULL,
  cat_pages NUMBER DEFAULT 0 NOT NULL,
  cat_subcats NUMBER DEFAULT 0 NOT NULL,
  cat_files NUMBER DEFAULT 0 NOT NULL,
  cat_hidden NUMBER DEFAULT 0 NOT NULL
);
ALTER TABLE &mw_prefix.category ADD CONSTRAINT &mw_prefix.category_pk PRIMARY KEY (cat_id);
CREATE UNIQUE INDEX &mw_prefix.category_u01 ON &mw_prefix.category (cat_title);
CREATE INDEX &mw_prefix.category_i01 ON &mw_prefix.category (cat_pages);

CREATE TABLE &mw_prefix.externallinks (
  el_from   NUMBER  NOT NULL  REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE,
  el_to     VARCHAR2(2048) NOT NULL,
  el_index  VARCHAR2(2048) NOT NULL
);
CREATE INDEX &mw_prefix.externallinks_i01 ON &mw_prefix.externallinks (el_from, el_to);
CREATE INDEX &mw_prefix.externallinks_i02 ON &mw_prefix.externallinks (el_to, el_from);
CREATE INDEX &mw_prefix.externallinks_i03 ON &mw_prefix.externallinks (el_index);

CREATE TABLE &mw_prefix.external_user (
  eu_local_id NUMBER NOT NULL,
  eu_external_id varchar2(255) NOT NULL
);
ALTER TABLE &mw_prefix.external_user ADD CONSTRAINT &mw_prefix.external_user_pk PRIMARY KEY (eu_local_id);
CREATE UNIQUE INDEX &mw_prefix.external_user_u01 ON &mw_prefix.external_user (eu_external_id);

CREATE TABLE &mw_prefix.langlinks (
  ll_from    NUMBER  NOT NULL  REFERENCES &mw_prefix.page (page_id) ON DELETE CASCADE,
  ll_lang    VARCHAR2(20),
  ll_title   VARCHAR2(255)
);
CREATE UNIQUE INDEX &mw_prefix.langlinks_u01 ON &mw_prefix.langlinks (ll_from, ll_lang);
CREATE INDEX &mw_prefix.langlinks_i01 ON &mw_prefix.langlinks (ll_lang, ll_title);

CREATE TABLE &mw_prefix.iwlinks (
  iwl_from NUMBER DEFAULT 0 NOT NULL,
  iwl_prefix VARCHAR2(20) DEFAULT '' NOT NULL,
  iwl_title VARCHAR2(255) DEFAULT '' NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.iwlinks_ui01 ON &mw_prefix.iwlinks (iwl_from, iwl_prefix, iwl_title);
CREATE UNIQUE INDEX &mw_prefix.iwlinks_ui02 ON &mw_prefix.iwlinks (iwl_prefix, iwl_title, iwl_from);

CREATE TABLE &mw_prefix.site_stats (
  ss_row_id         NUMBER  NOT NULL ,
  ss_total_views    NUMBER            DEFAULT 0,
  ss_total_edits    NUMBER            DEFAULT 0,
  ss_good_articles  NUMBER            DEFAULT 0,
  ss_total_pages    NUMBER            DEFAULT -1,
  ss_users          NUMBER            DEFAULT -1,
  ss_active_users   NUMBER            DEFAULT -1,
  ss_admins         NUMBER            DEFAULT -1,
  ss_images         NUMBER            DEFAULT 0
);
CREATE UNIQUE INDEX &mw_prefix.site_stats_u01 ON &mw_prefix.site_stats (ss_row_id);

CREATE TABLE &mw_prefix.hitcounter (
  hc_id  NUMBER  NOT NULL
);

CREATE SEQUENCE ipblocks_ipb_id_seq;
CREATE TABLE &mw_prefix.ipblocks (
  ipb_id                NUMBER      NOT NULL,
  ipb_address           VARCHAR2(255)     NULL,
  ipb_user              NUMBER          NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL,
  ipb_by                NUMBER      NOT NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE,
  ipb_by_text           VARCHAR2(255)      NOT NULL,
  ipb_reason            VARCHAR2(255)         NOT NULL,
  ipb_timestamp         TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  ipb_auto              CHAR(1)         DEFAULT '0' NOT NULL,
  ipb_anon_only         CHAR(1)         DEFAULT '0' NOT NULL,
  ipb_create_account    CHAR(1)         DEFAULT '1' NOT NULL,
  ipb_enable_autoblock  CHAR(1)         DEFAULT '1' NOT NULL,
  ipb_expiry            TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  ipb_range_start       VARCHAR2(255),
  ipb_range_end         VARCHAR2(255),
  ipb_deleted           CHAR(1)      DEFAULT '0' NOT NULL,
  ipb_block_email       CHAR(1)      DEFAULT '0' NOT NULL,
  ipb_allow_usertalk    CHAR(1)      DEFAULT '0' NOT NULL
);
ALTER TABLE &mw_prefix.ipblocks ADD CONSTRAINT &mw_prefix.ipblocks_pk PRIMARY KEY (ipb_id);
CREATE UNIQUE INDEX &mw_prefix.ipblocks_u01 ON &mw_prefix.ipblocks (ipb_address, ipb_user, ipb_auto, ipb_anon_only);
CREATE INDEX &mw_prefix.ipblocks_i01 ON &mw_prefix.ipblocks (ipb_user);
CREATE INDEX &mw_prefix.ipblocks_i02 ON &mw_prefix.ipblocks (ipb_range_start, ipb_range_end);
CREATE INDEX &mw_prefix.ipblocks_i03 ON &mw_prefix.ipblocks (ipb_timestamp);
CREATE INDEX &mw_prefix.ipblocks_i04 ON &mw_prefix.ipblocks (ipb_expiry);

CREATE TABLE &mw_prefix.image (
  img_name         VARCHAR2(255)      NOT NULL,
  img_size         NUMBER   NOT NULL,
  img_width        NUMBER   NOT NULL,
  img_height       NUMBER   NOT NULL,
  img_metadata     CLOB,
  img_bits         NUMBER,
  img_media_type   VARCHAR2(32),
  img_major_mime   VARCHAR2(32) DEFAULT 'unknown',
  img_minor_mime   VARCHAR2(100) DEFAULT 'unknown',
  img_description  VARCHAR2(255),
  img_user         NUMBER       NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL,
  img_user_text    VARCHAR2(255)      NOT NULL,
  img_timestamp    TIMESTAMP(6) WITH TIME ZONE,
	img_sha1         VARCHAR2(32)
);
ALTER TABLE &mw_prefix.image ADD CONSTRAINT &mw_prefix.image_pk PRIMARY KEY (img_name);
CREATE INDEX &mw_prefix.image_i01 ON &mw_prefix.image (img_user_text,img_timestamp);
CREATE INDEX &mw_prefix.image_i02 ON &mw_prefix.image (img_size);
CREATE INDEX &mw_prefix.image_i03 ON &mw_prefix.image (img_timestamp);
CREATE INDEX &mw_prefix.image_i04 ON &mw_prefix.image (img_sha1);


CREATE TABLE &mw_prefix.oldimage (
  oi_name          VARCHAR2(255)         NOT NULL  REFERENCES &mw_prefix.image(img_name),
  oi_archive_name  VARCHAR2(255),
  oi_size          NUMBER      NOT NULL,
  oi_width         NUMBER      NOT NULL,
  oi_height        NUMBER      NOT NULL,
  oi_bits          NUMBER     NOT NULL,
  oi_description   VARCHAR2(255),
  oi_user          NUMBER          NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL,
  oi_user_text     VARCHAR2(255)         NOT NULL,
  oi_timestamp     TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  oi_metadata      CLOB,
  oi_media_type    VARCHAR2(32) DEFAULT NULL,
  oi_major_mime    VARCHAR2(32) DEFAULT 'unknown',
  oi_minor_mime    VARCHAR2(100) DEFAULT 'unknown',
  oi_deleted       NUMBER DEFAULT 0 NOT NULL,
  oi_sha1          VARCHAR2(32)
);
CREATE INDEX &mw_prefix.oldimage_i01 ON &mw_prefix.oldimage (oi_user_text,oi_timestamp);
CREATE INDEX &mw_prefix.oldimage_i02 ON &mw_prefix.oldimage (oi_name,oi_timestamp);
CREATE INDEX &mw_prefix.oldimage_i03 ON &mw_prefix.oldimage (oi_name,oi_archive_name);
CREATE INDEX &mw_prefix.oldimage_i04 ON &mw_prefix.oldimage (oi_sha1);


CREATE SEQUENCE filearchive_fa_id_seq;
CREATE TABLE &mw_prefix.filearchive (
  fa_id                 NUMBER       NOT NULL,
  fa_name               VARCHAR2(255)         NOT NULL,
  fa_archive_name       VARCHAR2(255),
  fa_storage_group      VARCHAR2(16),
  fa_storage_key        VARCHAR2(64),
  fa_deleted_user       NUMBER          NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL,
  fa_deleted_timestamp  TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  fa_deleted_reason     CLOB,
  fa_size               NUMBER     NOT NULL,
  fa_width              NUMBER     NOT NULL,
  fa_height             NUMBER     NOT NULL,
  fa_metadata           CLOB,
  fa_bits               NUMBER,
  fa_media_type         VARCHAR2(32) DEFAULT NULL,
  fa_major_mime         VARCHAR2(32) DEFAULT 'unknown',
  fa_minor_mime         VARCHAR2(100) DEFAULT 'unknown',
  fa_description        VARCHAR2(255),
  fa_user               NUMBER          NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL,
  fa_user_text          VARCHAR2(255)         NOT NULL,
  fa_timestamp          TIMESTAMP(6) WITH TIME ZONE,
  fa_deleted            NUMBER      DEFAULT '0' NOT NULL
);
ALTER TABLE &mw_prefix.filearchive ADD CONSTRAINT &mw_prefix.filearchive_pk PRIMARY KEY (fa_id);
CREATE INDEX &mw_prefix.filearchive_i01 ON &mw_prefix.filearchive (fa_name, fa_timestamp);
CREATE INDEX &mw_prefix.filearchive_i02 ON &mw_prefix.filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX &mw_prefix.filearchive_i03 ON &mw_prefix.filearchive (fa_deleted_timestamp);
CREATE INDEX &mw_prefix.filearchive_i04 ON &mw_prefix.filearchive (fa_user_text,fa_timestamp);

CREATE SEQUENCE recentchanges_rc_id_seq;
CREATE TABLE &mw_prefix.recentchanges (
  rc_id              NUMBER      NOT NULL,
  rc_timestamp       TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  rc_cur_time        TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  rc_user            NUMBER          NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL,
  rc_user_text       VARCHAR2(255)         NOT NULL,
  rc_namespace       NUMBER     NOT NULL,
  rc_title           VARCHAR2(255)         NOT NULL,
  rc_comment         VARCHAR2(255),
  rc_minor           CHAR(1)         DEFAULT '0' NOT NULL,
  rc_bot             CHAR(1)         DEFAULT '0' NOT NULL,
  rc_new             CHAR(1)         DEFAULT '0' NOT NULL,
  rc_cur_id          NUMBER          NULL  REFERENCES &mw_prefix.page(page_id) ON DELETE SET NULL,
  rc_this_oldid      NUMBER      NOT NULL,
  rc_last_oldid      NUMBER      NOT NULL,
  rc_type            CHAR(1)         DEFAULT '0' NOT NULL,
  rc_moved_to_ns     NUMBER,
  rc_moved_to_title  VARCHAR2(255),
  rc_patrolled       CHAR(1)         DEFAULT '0' NOT NULL,
  rc_ip              VARCHAR2(15),
  rc_old_len         NUMBER,
  rc_new_len         NUMBER,
  rc_deleted         NUMBER      DEFAULT '0' NOT NULL,
  rc_logid           NUMBER      DEFAULT '0' NOT NULL,
  rc_log_type        VARCHAR2(255),
  rc_log_action      VARCHAR2(255),
  rc_params          CLOB
);
ALTER TABLE &mw_prefix.recentchanges ADD CONSTRAINT &mw_prefix.recentchanges_pk PRIMARY KEY (rc_id);
CREATE INDEX &mw_prefix.recentchanges_i01 ON &mw_prefix.recentchanges (rc_timestamp);
CREATE INDEX &mw_prefix.recentchanges_i02 ON &mw_prefix.recentchanges (rc_namespace, rc_title);
CREATE INDEX &mw_prefix.recentchanges_i03 ON &mw_prefix.recentchanges (rc_cur_id);
CREATE INDEX &mw_prefix.recentchanges_i04 ON &mw_prefix.recentchanges (rc_new,rc_namespace,rc_timestamp);
CREATE INDEX &mw_prefix.recentchanges_i05 ON &mw_prefix.recentchanges (rc_ip);
CREATE INDEX &mw_prefix.recentchanges_i06 ON &mw_prefix.recentchanges (rc_namespace, rc_user_text);
CREATE INDEX &mw_prefix.recentchanges_i07 ON &mw_prefix.recentchanges (rc_user_text, rc_timestamp);

CREATE TABLE &mw_prefix.watchlist (
  wl_user                   NUMBER     NOT NULL  REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE,
  wl_namespace              NUMBER    DEFAULT 0 NOT NULL,
  wl_title                  VARCHAR2(255)        NOT NULL,
  wl_notificationtimestamp  TIMESTAMP(6) WITH TIME ZONE
);
CREATE UNIQUE INDEX &mw_prefix.watchlist_u01 ON &mw_prefix.watchlist (wl_user, wl_namespace, wl_title);
CREATE INDEX &mw_prefix.watchlist_i01 ON &mw_prefix.watchlist (wl_namespace, wl_title);


CREATE TABLE &mw_prefix.math (
  math_inputhash              VARCHAR2(32)      NOT NULL,
  math_outputhash             VARCHAR2(32)      NOT NULL,
  math_html_conservativeness  NUMBER  NOT NULL,
  math_html                   CLOB,
  math_mathml                 CLOB
);
CREATE UNIQUE INDEX &mw_prefix.math_u01 ON &mw_prefix.math (math_inputhash);

CREATE TABLE &mw_prefix.searchindex (
  si_page	NUMBER NOT NULL,
  si_title	VARCHAR2(255) DEFAULT '' NOT NULL,
  si_text	CLOB NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.searchindex_u01 ON &mw_prefix.searchindex (si_page);

CREATE TABLE &mw_prefix.interwiki (
  iw_prefix  VARCHAR2(32)   NOT NULL,
  iw_url     VARCHAR2(127)  NOT NULL,
  iw_api 	BLOB NOT NULL,
  iw_wikiid VARCHAR2(64),
  iw_local   CHAR(1)  NOT NULL,
  iw_trans   CHAR(1)  DEFAULT '0' NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.interwiki_u01 ON &mw_prefix.interwiki (iw_prefix);

CREATE TABLE &mw_prefix.querycache (
  qc_type       VARCHAR2(32)      NOT NULL,
  qc_value      NUMBER  NOT NULL,
  qc_namespace  NUMBER  NOT NULL,
  qc_title      VARCHAR2(255)      NOT NULL
);
CREATE INDEX &mw_prefix.querycache_u01 ON &mw_prefix.querycache (qc_type,qc_value);

CREATE TABLE &mw_prefix.objectcache (
  keyname  VARCHAR2(255)              ,
  value    BLOB,
  exptime  TIMESTAMP(6) WITH TIME ZONE  NOT NULL
);
CREATE INDEX &mw_prefix.objectcache_i01 ON &mw_prefix.objectcache (exptime);

CREATE TABLE &mw_prefix.transcache (
  tc_url       VARCHAR2(255)         NOT NULL,
  tc_contents  CLOB         NOT NULL,
  tc_time      TIMESTAMP(6) WITH TIME ZONE  NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.transcache_u01 ON &mw_prefix.transcache (tc_url);


CREATE SEQUENCE logging_log_id_seq;
CREATE TABLE &mw_prefix.logging (
  log_id          NUMBER      NOT NULL,
  log_type        VARCHAR2(10)         NOT NULL,
  log_action      VARCHAR2(10)         NOT NULL,
  log_timestamp   TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  log_user        NUMBER                REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL,
  log_user_text 	VARCHAR2(255),
  log_namespace   NUMBER     NOT NULL,
  log_title       VARCHAR2(255)         NOT NULL,
	log_page 				NUMBER,
  log_comment     VARCHAR2(255),
  log_params      CLOB,
  log_deleted     NUMBER      DEFAULT '0' NOT NULL
);
ALTER TABLE &mw_prefix.logging ADD CONSTRAINT &mw_prefix.logging_pk PRIMARY KEY (log_id);
CREATE INDEX &mw_prefix.logging_i01 ON &mw_prefix.logging (log_type, log_timestamp);
CREATE INDEX &mw_prefix.logging_i02 ON &mw_prefix.logging (log_user, log_timestamp);
CREATE INDEX &mw_prefix.logging_i03 ON &mw_prefix.logging (log_namespace, log_title, log_timestamp);
CREATE INDEX &mw_prefix.logging_i04 ON &mw_prefix.logging (log_timestamp);

CREATE TABLE &mw_prefix.log_search (
  ls_field VARCHAR2(32) NOT NULL,
  ls_value VARCHAR2(255) NOT NULL,
  ls_log_id NuMBER DEFAULT 0 NOT NULL
);
ALTER TABLE &mw_prefix.log_search ADD CONSTRAINT log_search_pk PRIMARY KEY (ls_field,ls_value,ls_log_id);
CREATE INDEX &mw_prefix.log_search_i01 ON &mw_prefix.log_search (ls_log_id);

CREATE SEQUENCE trackbacks_tb_id_seq;
CREATE TABLE &mw_prefix.trackbacks (
  tb_id     NUMBER   NOT NULL,
  tb_page   NUMBER            REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE,
  tb_title  VARCHAR2(255)     NOT NULL,
  tb_url    VARCHAR2(255)     NOT NULL,
  tb_ex     CLOB,
  tb_name   VARCHAR2(255) 
);
ALTER TABLE &mw_prefix.trackbacks ADD CONSTRAINT &mw_prefix.trackbacks_pk PRIMARY KEY (tb_id);
CREATE INDEX &mw_prefix.trackbacks_i01 ON &mw_prefix.trackbacks (tb_page);

CREATE SEQUENCE job_job_id_seq;
CREATE TABLE &mw_prefix.job (
  job_id         NUMBER   NOT NULL,
  job_cmd        VARCHAR2(60)      NOT NULL,
  job_namespace  NUMBER  NOT NULL,
  job_title      VARCHAR2(255)      NOT NULL,
  job_params     CLOB      NOT NULL
);
ALTER TABLE &mw_prefix.job ADD CONSTRAINT &mw_prefix.job_pk PRIMARY KEY (job_id);
CREATE INDEX &mw_prefix.job_i01 ON &mw_prefix.job (job_cmd, job_namespace, job_title);

CREATE TABLE &mw_prefix.querycache_info (
  qci_type       VARCHAR2(32) NOT NULL,
  qci_timestamp  TIMESTAMP(6) WITH TIME ZONE NULL
);
CREATE UNIQUE INDEX &mw_prefix.querycache_info_u01 ON &mw_prefix.querycache_info (qci_type);

CREATE TABLE &mw_prefix.redirect (
  rd_from       NUMBER  NOT NULL  REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE,
  rd_namespace  NUMBER NOT NULL,
  rd_title      VARCHAR2(255)     NOT NULL,
  rd_interwiki  VARCHAR2(32),
  rd_fragment   VARCHAR2(255)
);
CREATE INDEX &mw_prefix.redirect_i01 ON &mw_prefix.redirect (rd_namespace,rd_title,rd_from);

CREATE TABLE &mw_prefix.querycachetwo (
  qcc_type          VARCHAR2(32)     NOT NULL,
  qcc_value         NUMBER  DEFAULT 0 NOT NULL,
  qcc_namespace     NUMBER  DEFAULT 0 NOT NULL,
  qcc_title         VARCHAR2(255)     DEFAULT '' NOT NULL,
  qcc_namespacetwo  NUMBER  DEFAULT 0 NOT NULL,
  qcc_titletwo      VARCHAR2(255)     DEFAULT '' NOT NULL
);
CREATE INDEX &mw_prefix.querycachetwo_i01 ON &mw_prefix.querycachetwo (qcc_type,qcc_value);
CREATE INDEX &mw_prefix.querycachetwo_i02 ON &mw_prefix.querycachetwo (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX &mw_prefix.querycachetwo_i03 ON &mw_prefix.querycachetwo (qcc_type,qcc_namespacetwo,qcc_titletwo);

CREATE SEQUENCE page_restrictions_pr_id_seq;
CREATE TABLE &mw_prefix.page_restrictions (
  pr_id      NUMBER      NOT NULL,
  pr_page    NUMBER          NULL  REFERENCES &mw_prefix.page (page_id) ON DELETE CASCADE,
  pr_type    VARCHAR2(255)         NOT NULL,
  pr_level   VARCHAR2(255)         NOT NULL,
  pr_cascade NUMBER     NOT NULL,
  pr_user    NUMBER          NULL,
  pr_expiry  TIMESTAMP(6) WITH TIME ZONE      NULL
);
ALTER TABLE &mw_prefix.page_restrictions ADD CONSTRAINT &mw_prefix.page_restrictions_pk PRIMARY KEY (pr_page,pr_type);
CREATE INDEX &mw_prefix.page_restrictions_i01 ON &mw_prefix.page_restrictions (pr_type,pr_level);
CREATE INDEX &mw_prefix.page_restrictions_i02 ON &mw_prefix.page_restrictions (pr_level);
CREATE INDEX &mw_prefix.page_restrictions_i03 ON &mw_prefix.page_restrictions (pr_cascade);

CREATE TABLE &mw_prefix.protected_titles (
  pt_namespace   NUMBER           NOT NULL,
  pt_title       VARCHAR2(255)    NOT NULL,
  pt_user        NUMBER	          NOT NULL,
  pt_reason      VARCHAR2(255),
  pt_timestamp   TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
  pt_expiry      VARCHAR2(14) NOT NULL,
  pt_create_perm VARCHAR2(60) NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.protected_titles_u01 ON &mw_prefix.protected_titles (pt_namespace,pt_title);
CREATE INDEX &mw_prefix.protected_titles_i01 ON &mw_prefix.protected_titles (pt_timestamp);

CREATE TABLE &mw_prefix.page_props (
  pp_page NUMBER NOT NULL,
  pp_propname VARCHAR2(60) NOT NULL,
  pp_value BLOB NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.page_props_u01 ON &mw_prefix.page_props (pp_page,pp_propname);


CREATE TABLE &mw_prefix.updatelog (
  ul_key VARCHAR2(255) NOT NULL,
  ul_value BLOB
);
ALTER TABLE &mw_prefix.updatelog ADD CONSTRAINT &mw_prefix.updatelog_pk PRIMARY KEY (ul_key);

CREATE TABLE &mw_prefix.change_tag (
  ct_rc_id NUMBER NULL,
  ct_log_id NUMBER NULL,
  ct_rev_id NUMBER NULL,
  ct_tag VARCHAR2(255) NOT NULL,
  ct_params BLOB NULL
);
CREATE UNIQUE INDEX &mw_prefix.change_tag_u01 ON &mw_prefix.change_tag (ct_rc_id,ct_tag);
CREATE UNIQUE INDEX &mw_prefix.change_tag_u02 ON &mw_prefix.change_tag (ct_log_id,ct_tag);
CREATE UNIQUE INDEX &mw_prefix.change_tag_u03 ON &mw_prefix.change_tag (ct_rev_id,ct_tag);
CREATE INDEX &mw_prefix.change_tag_i01 ON &mw_prefix.change_tag (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);

CREATE TABLE &mw_prefix.tag_summary (
  ts_rc_id NUMBER NULL,
  ts_log_id NUMBER NULL,
  ts_rev_id NUMBER NULL,
  ts_tags BLOB NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.tag_summary_u01 ON &mw_prefix.tag_summary (ts_rc_id);
CREATE UNIQUE INDEX &mw_prefix.tag_summary_u02 ON &mw_prefix.tag_summary (ts_log_id);
CREATE UNIQUE INDEX &mw_prefix.tag_summary_u03 ON &mw_prefix.tag_summary (ts_rev_id);

CREATE TABLE &mw_prefix.valid_tag (
  vt_tag VARCHAR2(255) NOT NULL
);
ALTER TABLE &mw_prefix.valid_tag ADD CONSTRAINT &mw_prefix.valid_tag_pk PRIMARY KEY (vt_tag);

-- This table is not used unless profiling is turned on
--CREATE TABLE &mw_prefix.profiling (
--  pf_count   NUMBER         DEFAULT 0 NOT NULL,
--  pf_time    NUMERIC(18,10)  DEFAULT 0 NOT NULL,
--  pf_name    CLOB            NOT NULL,
--  pf_server  CLOB            NULL
--);
--CREATE UNIQUE INDEX &mw_prefix.profiling_u01 ON &mw_prefix.profiling (pf_name, pf_server);

CREATE INDEX si_title_idx ON &mw_prefix.searchindex(si_title) INDEXTYPE IS ctxsys.context;
CREATE INDEX si_text_idx ON &mw_prefix.searchindex(si_text) INDEXTYPE IS ctxsys.context;

CREATE TABLE &mw_prefix.l10n_cache (
  lc_lang varchar2(32) NOT NULL,
  lc_key varchar2(255) NOT NULL,
  lc_value clob NOT NULL
);
CREATE INDEX &mw_prefix.l10n_cache_u01 ON &mw_prefix.l10n_cache (lc_lang, lc_key);

CREATE TABLE &mw_prefix.msg_resource (
  mr_resource VARCHAR2(255) NOT NULL,
  mr_lang varchar2(32) NOT NULL,
  mr_blob BLOB NOT NULL,
  mr_timestamp TIMESTAMP(6) WITH TIME ZONE NOT NULL
) ;
CREATE UNIQUE INDEX &mw_prefix.msg_resource_u01 ON &mw_prefix.msg_resource (mr_resource, mr_lang);

CREATE TABLE &mw_prefix.msg_resource_links (
  mrl_resource VARCHAR2(255) NOT NULL,
  mrl_message VARCHAR2(255) NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.msg_resource_links_u01 ON &mw_prefix.msg_resource_links (mrl_message, mrl_resource);

CREATE TABLE &mw_prefix.module_deps (
  md_module VARCHAR2(255) NOT NULL,
  md_skin VARCHAR2(32) NOT NULL,
  md_deps BLOB NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.module_deps_u01 ON &mw_prefix.module_deps (md_module, md_skin);

-- do not prefix this table as it breaks parserTests
CREATE TABLE wiki_field_info_full (
table_name VARCHAR2(35) NOT NULL,
column_name VARCHAR2(35) NOT NULL,
data_default VARCHAR2(4000),
data_length NUMBER NOT NULL,
data_type VARCHAR2(106),
not_null CHAR(1) NOT NULL,
prim NUMBER(1), 
uniq NUMBER(1),
nonuniq NUMBER(1) 
);
ALTER TABLE wiki_field_info_full ADD CONSTRAINT wiki_field_info_full_pk PRIMARY KEY (table_name, column_name);

/*$mw$*/
CREATE PROCEDURE fill_wiki_info IS
	BEGIN
		DELETE      wiki_field_info_full;

		FOR x_rec IN (SELECT t.table_name table_name, t.column_name,
												t.data_default, t.data_length, t.data_type,
												DECODE (t.nullable, 'Y', '1', 'N', '0') not_null,
												(SELECT 1
														FROM user_cons_columns ucc,
																user_constraints uc
													WHERE ucc.table_name = t.table_name
														AND ucc.column_name = t.column_name
														AND uc.constraint_name = ucc.constraint_name
														AND uc.constraint_type = 'P'
														AND ROWNUM < 2) prim,
												(SELECT 1
														FROM user_ind_columns uic,
																user_indexes ui
													WHERE uic.table_name = t.table_name
														AND uic.column_name = t.column_name
														AND ui.index_name = uic.index_name
														AND ui.uniqueness = 'UNIQUE'
														AND ROWNUM < 2) uniq,
												(SELECT 1
														FROM user_ind_columns uic,
																user_indexes ui
													WHERE uic.table_name = t.table_name
														AND uic.column_name = t.column_name
														AND ui.index_name = uic.index_name
														AND ui.uniqueness = 'NONUNIQUE'
														AND ROWNUM < 2) nonuniq
										FROM user_tab_columns t, user_tables ut
									WHERE ut.table_name = t.table_name)
		LOOP
			INSERT INTO wiki_field_info_full
									(table_name, column_name,
										data_default, data_length,
										data_type, not_null, prim,
										uniq, nonuniq
									)
						VALUES (x_rec.table_name, x_rec.column_name,
										x_rec.data_default, x_rec.data_length,
										x_rec.data_type, x_rec.not_null, x_rec.prim,
										x_rec.uniq, x_rec.nonuniq
									);
		END LOOP;
		COMMIT;
END;
/*$mw$*/

/*$mw$*/
CREATE OR REPLACE PROCEDURE duplicate_table(p_tabname   IN VARCHAR2,
                                            p_oldprefix IN VARCHAR2,
                                            p_newprefix IN VARCHAR2,
                                            p_temporary IN BOOLEAN) IS
  e_table_not_exist EXCEPTION;
  PRAGMA EXCEPTION_INIT(e_table_not_exist, -00942);
BEGIN
  BEGIN
    EXECUTE IMMEDIATE 'DROP TABLE ' || p_newprefix || p_tabname ||
                      ' CASCADE CONSTRAINTS';
  EXCEPTION
    WHEN e_table_not_exist THEN
      NULL;
  END;
  IF (p_temporary) THEN
    EXECUTE IMMEDIATE 'CREATE GLOBAL TEMPORARY TABLE ' || p_newprefix ||
                      p_tabname || ' AS SELECT * FROM ' || p_oldprefix ||
                      p_tabname || ' WHERE ROWNUM = 0';
  ELSE
    EXECUTE IMMEDIATE 'CREATE TABLE ' || p_newprefix || p_tabname ||
                      ' AS SELECT * FROM ' || p_oldprefix || p_tabname ||
                      ' WHERE ROWNUM = 0';
  END IF;
  FOR rc IN (SELECT column_name, data_default
               FROM user_tab_columns
              WHERE table_name = p_oldprefix || p_tabname
                AND data_default IS NOT NULL) LOOP
    EXECUTE IMMEDIATE 'ALTER TABLE ' || p_newprefix || p_tabname ||
                      ' MODIFY ' || rc.column_name || ' DEFAULT ' ||
                      substr(rc.data_default, 1, 2000);
  END LOOP;
  FOR rc IN (SELECT REPLACE(REPLACE(DBMS_LOB.SUBSTR(DBMS_METADATA.get_ddl('CONSTRAINT',
                                                                          constraint_name),
                                                    32767,
                                                    1),
                                    USER || '"."' || p_oldprefix,
                                    USER || '"."' || p_newprefix),
                            '"' || constraint_name || '"',
                            '"' || p_newprefix || constraint_name || '"') DDLVC2,
                    constraint_name
               FROM user_constraints uc
              WHERE table_name = p_oldprefix || p_tabname
                AND constraint_type = 'P') LOOP
    dbms_output.put_line(SUBSTR(rc.ddlvc2,
                                1,
                                INSTR(rc.ddlvc2, 'PCTFREE') - 1));
    EXECUTE IMMEDIATE SUBSTR(rc.ddlvc2, 1, INSTR(rc.ddlvc2, 'PCTFREE') - 1);
  END LOOP;
  FOR rc IN (SELECT REPLACE(DBMS_LOB.SUBSTR(DBMS_METADATA.get_ddl('REF_CONSTRAINT',
                                                                  constraint_name),
                                            32767,
                                            1),
                            USER || '"."' || p_oldprefix,
                            USER || '"."' || p_newprefix) DDLVC2,
                    constraint_name
               FROM user_constraints uc
              WHERE table_name = p_oldprefix || p_tabname
                AND constraint_type = 'R') LOOP
    EXECUTE IMMEDIATE rc.ddlvc2;
  END LOOP;
  FOR rc IN (SELECT REPLACE(REPLACE(DBMS_LOB.SUBSTR(DBMS_METADATA.get_ddl('INDEX',
                                                                          index_name),
                                                    32767,
                                                    1),
                                    USER || '"."' || p_oldprefix,
                                    USER || '"."' || p_newprefix),
                            '"' || index_name || '"',
                            '"' || p_newprefix || index_name || '"') DDLVC2,
                    index_name
               FROM user_indexes ui
              WHERE table_name = p_oldprefix || p_tabname
                AND index_type != 'LOB'
                AND NOT EXISTS
              (SELECT NULL
                       FROM user_constraints
                      WHERE table_name = ui.table_name
                        AND constraint_name = ui.index_name)) LOOP
    EXECUTE IMMEDIATE SUBSTR(rc.ddlvc2, 1, INSTR(rc.ddlvc2, 'PCTFREE') - 1);
  END LOOP;
  FOR rc IN (SELECT REPLACE(REPLACE(UPPER(DBMS_LOB.SUBSTR(DBMS_METADATA.get_ddl('TRIGGER',
                                                                                trigger_name),
                                                          32767,
                                                          1)),
                                    USER || '"."' || p_oldprefix,
                                    USER || '"."' || p_newprefix),
                            ' ON ' || p_oldprefix || p_tabname,
                            ' ON ' || p_newprefix || p_tabname) DDLVC2,
                    trigger_name
               FROM user_triggers
              WHERE table_name = p_oldprefix || p_tabname) LOOP
    EXECUTE IMMEDIATE SUBSTR(rc.ddlvc2, 1, INSTR(rc.ddlvc2, 'ALTER ') - 1);
  END LOOP;
END;
/*$mw$*/

/*$mw$*/
BEGIN 
	fill_wiki_info;
END;
/*$mw$*/

/*$mw$*/
CREATE OR REPLACE FUNCTION BITOR (x IN NUMBER, y IN NUMBER) RETURN NUMBER AS
BEGIN
  RETURN (x + y - BITAND(x, y));
END;
/*$mw$*/

/*$mw$*/
CREATE OR REPLACE FUNCTION BITNOT (x IN NUMBER) RETURN NUMBER AS
BEGIN
  RETURN (4294967295 - x);
END;
/*$mw$*/

/*$mw$*/
CREATE OR REPLACE TYPE GET_OUTPUT_TYPE IS TABLE OF VARCHAR2(255);
/*$mw$*/

/*$mw$*/
CREATE OR REPLACE FUNCTION GET_OUTPUT_LINES RETURN GET_OUTPUT_TYPE PIPELINED AS
  v_line VARCHAR2(255);
  v_status INTEGER := 0;
BEGIN

  LOOP
    DBMS_OUTPUT.GET_LINE(v_line, v_status);
    IF (v_status = 0) THEN RETURN; END IF;
    PIPE ROW (v_line);
  END LOOP;
  RETURN;
EXCEPTION
  WHEN OTHERS THEN
    RETURN;
END;
/*$mw$*/

/*$mw$*/
CREATE OR REPLACE FUNCTION GET_SEQUENCE_VALUE(seq IN VARCHAR2) RETURN NUMBER AS
	v_value NUMBER;
BEGIN
	EXECUTE IMMEDIATE 'SELECT '||seq||'.NEXTVAL INTO :outVar FROM DUAL' INTO v_value;
	RETURN v_value;
END;
/*$mw$*/
