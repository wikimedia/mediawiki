-- SQL to create the initial tables for the MediaWiki database.
-- This is read and executed by the install script; you should
-- not have to run it by itself unless doing a manual install.
-- This is the PostgreSQL version.
-- For information about each table, please see the notes in maintenance/tables.sql
-- Please make sure all dollar-quoting uses $mw$ at the start of the line
-- TODO: Change CHAR/SMALLINT to BOOL (still used in a non-bool fashion in PHP code)

BEGIN;
SET client_min_messages = 'ERROR';

CREATE SEQUENCE user_user_id_seq MINVALUE 0 START WITH 0;
CREATE TABLE mwuser ( -- replace reserved word 'user'
  user_id                   INTEGER  NOT NULL  PRIMARY KEY DEFAULT nextval('user_user_id_seq'),
  user_name                 TEXT     NOT NULL  UNIQUE,
  user_real_name            TEXT,
  user_password             TEXT,
  user_newpassword          TEXT,
  user_newpass_time         TIMESTAMPTZ,
  user_token                TEXT,
  user_email                TEXT,
  user_email_token          TEXT,
  user_email_token_expires  TIMESTAMPTZ,
  user_email_authenticated  TIMESTAMPTZ,
  user_options              TEXT,
  user_touched              TIMESTAMPTZ,
  user_registration         TIMESTAMPTZ,
  user_editcount            INTEGER
);
CREATE INDEX user_email_token_idx ON mwuser (user_email_token);

-- Create a dummy user to satisfy fk contraints especially with revisions
INSERT INTO mwuser
  VALUES (DEFAULT,'Anonymous','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,now(),now());

CREATE TABLE user_groups (
  ug_user   INTEGER      NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  ug_group  TEXT     NOT NULL
);
CREATE UNIQUE INDEX user_groups_unique ON user_groups (ug_user, ug_group);

CREATE TABLE user_newtalk (
  user_id              INTEGER      NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  user_ip              TEXT             NULL,
  user_last_timestamp  TIMESTAMPTZ
);
CREATE INDEX user_newtalk_id_idx ON user_newtalk (user_id);
CREATE INDEX user_newtalk_ip_idx ON user_newtalk (user_ip);


CREATE SEQUENCE page_page_id_seq;
CREATE TABLE page (
  page_id            INTEGER        NOT NULL  PRIMARY KEY DEFAULT nextval('page_page_id_seq'),
  page_namespace     SMALLINT       NOT NULL,
  page_title         TEXT           NOT NULL,
  page_restrictions  TEXT,
  page_counter       BIGINT         NOT NULL  DEFAULT 0,
  page_is_redirect   SMALLINT       NOT NULL  DEFAULT 0,
  page_is_new        SMALLINT       NOT NULL  DEFAULT 0,
  page_random        NUMERIC(15,14) NOT NULL  DEFAULT RANDOM(),
  page_touched       TIMESTAMPTZ,
  page_latest        INTEGER        NOT NULL, -- FK?
  page_len           INTEGER        NOT NULL
);
CREATE UNIQUE INDEX page_unique_name ON page (page_namespace, page_title);
CREATE INDEX page_main_title         ON page (page_title) WHERE page_namespace = 0;
CREATE INDEX page_talk_title         ON page (page_title) WHERE page_namespace = 1;
CREATE INDEX page_user_title         ON page (page_title) WHERE page_namespace = 2;
CREATE INDEX page_utalk_title        ON page (page_title) WHERE page_namespace = 3;
CREATE INDEX page_project_title      ON page (page_title) WHERE page_namespace = 4;
CREATE INDEX page_random_idx         ON page (page_random);
CREATE INDEX page_len_idx            ON page (page_len);

CREATE FUNCTION page_deleted() RETURNS TRIGGER LANGUAGE plpgsql AS
$mw$
BEGIN
DELETE FROM recentchanges WHERE rc_namespace = OLD.page_namespace AND rc_title = OLD.page_title;
RETURN NULL;
END;
$mw$;

CREATE TRIGGER page_deleted AFTER DELETE ON page
  FOR EACH ROW EXECUTE PROCEDURE page_deleted();

CREATE SEQUENCE revision_rev_id_seq;
CREATE TABLE revision (
  rev_id          INTEGER      NOT NULL  UNIQUE DEFAULT nextval('revision_rev_id_seq'),
  rev_page        INTEGER          NULL  REFERENCES page (page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  rev_text_id     INTEGER          NULL, -- FK
  rev_comment     TEXT,
  rev_user        INTEGER      NOT NULL  REFERENCES mwuser(user_id) ON DELETE RESTRICT DEFERRABLE INITIALLY DEFERRED,
  rev_user_text   TEXT         NOT NULL,
  rev_timestamp   TIMESTAMPTZ  NOT NULL,
  rev_minor_edit  SMALLINT     NOT NULL  DEFAULT 0,
  rev_deleted     SMALLINT     NOT NULL  DEFAULT 0,
  rev_len         INTEGER          NULL,
  rev_parent_id   INTEGER          NULL
);
CREATE UNIQUE INDEX revision_unique ON revision (rev_page, rev_id);
CREATE INDEX rev_text_id_idx        ON revision (rev_text_id);
CREATE INDEX rev_timestamp_idx      ON revision (rev_timestamp);
CREATE INDEX rev_user_idx           ON revision (rev_user);
CREATE INDEX rev_user_text_idx      ON revision (rev_user_text);


CREATE SEQUENCE text_old_id_seq;
CREATE TABLE pagecontent ( -- replaces reserved word 'text'
  old_id     INTEGER  NOT NULL  PRIMARY KEY DEFAULT nextval('text_old_id_seq'),
  old_text   TEXT,
  old_flags  TEXT
);


CREATE SEQUENCE page_restrictions_pr_id_seq;
CREATE TABLE page_restrictions (
  pr_id      INTEGER      NOT NULL  UNIQUE DEFAULT nextval('page_restrictions_pr_id_seq'),
  pr_page    INTEGER          NULL  REFERENCES page (page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  pr_type    TEXT         NOT NULL,
  pr_level   TEXT         NOT NULL,
  pr_cascade SMALLINT     NOT NULL,
  pr_user    INTEGER          NULL,
  pr_expiry  TIMESTAMPTZ      NULL
);
ALTER TABLE page_restrictions ADD CONSTRAINT page_restrictions_pk PRIMARY KEY (pr_page,pr_type);

CREATE TABLE page_props (
  pp_page      INTEGER  NOT NULL  REFERENCES page (page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  pp_propname  TEXT     NOT NULL,
  pp_value     TEXT     NOT NULL
);
ALTER TABLE page_props ADD CONSTRAINT page_props_pk PRIMARY KEY (pp_page,pp_propname);
CREATE INDEX page_props_propname ON page_props (pp_propname);

CREATE TABLE archive (
  ar_namespace   SMALLINT     NOT NULL,
  ar_title       TEXT         NOT NULL,
  ar_text        TEXT, -- technically should be bytea, but not used anymore
  ar_page_id     INTEGER          NULL,
  ar_parent_id   INTEGER          NULL,
  ar_comment     TEXT,
  ar_user        INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  ar_user_text   TEXT         NOT NULL,
  ar_timestamp   TIMESTAMPTZ  NOT NULL,
  ar_minor_edit  SMALLINT     NOT NULL  DEFAULT 0,
  ar_flags       TEXT,
  ar_rev_id      INTEGER,
  ar_text_id     INTEGER,
  ar_deleted     SMALLINT     NOT NULL  DEFAULT 0,
  ar_len         INTEGER          NULL
);
CREATE INDEX archive_name_title_timestamp ON archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX archive_user_text            ON archive (ar_user_text);


CREATE TABLE redirect (
  rd_from       INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  rd_namespace  SMALLINT NOT NULL,
  rd_title      TEXT     NOT NULL,
  rd_interwiki  TEXT     NULL,
  rd_fragment   TEXT     NULL
);
CREATE INDEX redirect_ns_title ON redirect (rd_namespace,rd_title,rd_from);


CREATE TABLE pagelinks (
  pl_from       INTEGER   NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  pl_namespace  SMALLINT  NOT NULL,
  pl_title      TEXT      NOT NULL
);
CREATE UNIQUE INDEX pagelink_unique ON pagelinks (pl_from,pl_namespace,pl_title);

CREATE TABLE templatelinks (
  tl_from       INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  tl_namespace  SMALLINT NOT NULL,
  tl_title      TEXT     NOT NULL
);
CREATE UNIQUE INDEX templatelinks_unique ON templatelinks (tl_namespace,tl_title,tl_from);
CREATE INDEX templatelinks_from          ON templatelinks (tl_from);

CREATE TABLE imagelinks (
  il_from  INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  il_to    TEXT     NOT NULL
);
CREATE UNIQUE INDEX il_from ON imagelinks (il_to,il_from);

CREATE TABLE categorylinks (
  cl_from       INTEGER      NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  cl_to         TEXT         NOT NULL,
  cl_sortkey    TEXT,
  cl_timestamp  TIMESTAMPTZ  NOT NULL
);
CREATE UNIQUE INDEX cl_from ON categorylinks (cl_from, cl_to);
CREATE INDEX cl_sortkey     ON categorylinks (cl_to, cl_sortkey, cl_from);

CREATE TABLE externallinks (
  el_from   INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  el_to     TEXT     NOT NULL,
  el_index  TEXT     NOT NULL
);
CREATE INDEX externallinks_from_to ON externallinks (el_from,el_to);
CREATE INDEX externallinks_index   ON externallinks (el_index);

CREATE TABLE external_user (
  eu_local_id     INTEGER  NOT NULL  PRIMARY KEY,
  eu_external_id TEXT
);

CREATE UNIQUE INDEX eu_external_id ON external_user (eu_external_id);

CREATE TABLE langlinks (
  ll_from    INTEGER  NOT NULL  REFERENCES page (page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  ll_lang    TEXT,
  ll_title   TEXT
);
CREATE UNIQUE INDEX langlinks_unique ON langlinks (ll_from,ll_lang);
CREATE INDEX langlinks_lang_title    ON langlinks (ll_lang,ll_title);


CREATE TABLE site_stats (
  ss_row_id         INTEGER  NOT NULL  UNIQUE,
  ss_total_views    INTEGER            DEFAULT 0,
  ss_total_edits    INTEGER            DEFAULT 0,
  ss_good_articles  INTEGER            DEFAULT 0,
  ss_total_pages    INTEGER            DEFAULT -1,
  ss_users          INTEGER            DEFAULT -1,
  ss_active_users   INTEGER            DEFAULT -1,
  ss_admins         INTEGER            DEFAULT -1,
  ss_images         INTEGER            DEFAULT 0
);

CREATE TABLE hitcounter (
  hc_id  BIGINT  NOT NULL
);


CREATE SEQUENCE ipblocks_ipb_id_seq;
CREATE TABLE ipblocks (
  ipb_id                INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('ipblocks_ipb_id_seq'),
  ipb_address           TEXT             NULL,
  ipb_user              INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  ipb_by                INTEGER      NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  ipb_by_text           TEXT         NOT NULL  DEFAULT '',
  ipb_reason            TEXT         NOT NULL,
  ipb_timestamp         TIMESTAMPTZ  NOT NULL,
  ipb_auto              SMALLINT     NOT NULL  DEFAULT 0,
  ipb_anon_only         SMALLINT     NOT NULL  DEFAULT 0,
  ipb_create_account    SMALLINT     NOT NULL  DEFAULT 1,
  ipb_enable_autoblock  SMALLINT     NOT NULL  DEFAULT 1,
  ipb_expiry            TIMESTAMPTZ  NOT NULL,
  ipb_range_start       TEXT,
  ipb_range_end         TEXT,
  ipb_deleted           SMALLINT     NOT NULL  DEFAULT 0,
  ipb_block_email       SMALLINT     NOT NULL  DEFAULT 0,
  ipb_allow_usertalk    SMALLINT     NOT NULL  DEFAULT 0

);
CREATE UNIQUE INDEX ipb_address_unique ON ipblocks (ipb_address,ipb_user,ipb_auto,ipb_anon_only);
CREATE INDEX ipb_user    ON ipblocks (ipb_user);
CREATE INDEX ipb_range   ON ipblocks (ipb_range_start,ipb_range_end);


CREATE TABLE image (
  img_name         TEXT      NOT NULL  PRIMARY KEY,
  img_size         INTEGER   NOT NULL,
  img_width        INTEGER   NOT NULL,
  img_height       INTEGER   NOT NULL,
  img_metadata     BYTEA     NOT NULL  DEFAULT '',
  img_bits         SMALLINT,
  img_media_type   TEXT,
  img_major_mime   TEXT                DEFAULT 'unknown',
  img_minor_mime   TEXT                DEFAULT 'unknown',
  img_description  TEXT      NOT NULL,
  img_user         INTEGER       NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  img_user_text    TEXT      NOT NULL,
  img_timestamp    TIMESTAMPTZ,
  img_sha1         TEXT      NOT NULL  DEFAULT ''
);
CREATE INDEX img_size_idx      ON image (img_size);
CREATE INDEX img_timestamp_idx ON image (img_timestamp);
CREATE INDEX img_sha1          ON image (img_sha1);

CREATE TABLE oldimage (
  oi_name          TEXT         NOT NULL,
  oi_archive_name  TEXT         NOT NULL,
  oi_size          INTEGER      NOT NULL,
  oi_width         INTEGER      NOT NULL,
  oi_height        INTEGER      NOT NULL,
  oi_bits          SMALLINT         NULL,
  oi_description   TEXT,
  oi_user          INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  oi_user_text     TEXT         NOT NULL,
  oi_timestamp     TIMESTAMPTZ      NULL,
  oi_metadata      BYTEA        NOT NULL DEFAULT '',
  oi_media_type    TEXT             NULL,
  oi_major_mime    TEXT             NULL DEFAULT 'unknown',
  oi_minor_mime    TEXT             NULL DEFAULT 'unknown',
  oi_deleted       SMALLINT     NOT NULL DEFAULT 0,
  oi_sha1          TEXT         NOT NULL DEFAULT ''
);
ALTER TABLE oldimage ADD CONSTRAINT oldimage_oi_name_fkey_cascaded FOREIGN KEY (oi_name) REFERENCES image(img_name) ON DELETE CASCADE ON UPDATE CASCADE DEFERRABLE INITIALLY DEFERRED;
CREATE INDEX oi_name_timestamp    ON oldimage (oi_name,oi_timestamp);
CREATE INDEX oi_name_archive_name ON oldimage (oi_name,oi_archive_name);
CREATE INDEX oi_sha1              ON oldimage (oi_sha1);


CREATE SEQUENCE filearchive_fa_id_seq;
CREATE TABLE filearchive (
  fa_id                 INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('filearchive_fa_id_seq'),
  fa_name               TEXT         NOT NULL,
  fa_archive_name       TEXT,
  fa_storage_group      TEXT,
  fa_storage_key        TEXT,
  fa_deleted_user       INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  fa_deleted_timestamp  TIMESTAMPTZ  NOT NULL,
  fa_deleted_reason     TEXT,
  fa_size               INTEGER      NOT NULL,
  fa_width              INTEGER      NOT NULL,
  fa_height             INTEGER      NOT NULL,
  fa_metadata           BYTEA        NOT NULL  DEFAULT '',
  fa_bits               SMALLINT,
  fa_media_type         TEXT,
  fa_major_mime         TEXT                   DEFAULT 'unknown',
  fa_minor_mime         TEXT                   DEFAULT 'unknown',
  fa_description        TEXT         NOT NULL,
  fa_user               INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  fa_user_text          TEXT         NOT NULL,
  fa_timestamp          TIMESTAMPTZ,
  fa_deleted            SMALLINT     NOT NULL DEFAULT 0
);
CREATE INDEX fa_name_time ON filearchive (fa_name, fa_timestamp);
CREATE INDEX fa_dupe      ON filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX fa_notime    ON filearchive (fa_deleted_timestamp);
CREATE INDEX fa_nouser    ON filearchive (fa_deleted_user);


CREATE SEQUENCE recentchanges_rc_id_seq;
CREATE TABLE recentchanges (
  rc_id              INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('recentchanges_rc_id_seq'),
  rc_timestamp       TIMESTAMPTZ  NOT NULL,
  rc_cur_time        TIMESTAMPTZ  NOT NULL,
  rc_user            INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  rc_user_text       TEXT         NOT NULL,
  rc_namespace       SMALLINT     NOT NULL,
  rc_title           TEXT         NOT NULL,
  rc_comment         TEXT,
  rc_minor           SMALLINT     NOT NULL  DEFAULT 0,
  rc_bot             SMALLINT     NOT NULL  DEFAULT 0,
  rc_new             SMALLINT     NOT NULL  DEFAULT 0,
  rc_cur_id          INTEGER          NULL  REFERENCES page(page_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  rc_this_oldid      INTEGER      NOT NULL,
  rc_last_oldid      INTEGER      NOT NULL,
  rc_type            SMALLINT     NOT NULL  DEFAULT 0,
  rc_moved_to_ns     SMALLINT,
  rc_moved_to_title  TEXT,
  rc_patrolled       SMALLINT     NOT NULL  DEFAULT 0,
  rc_ip              CIDR,
  rc_old_len         INTEGER,
  rc_new_len         INTEGER,
  rc_deleted         SMALLINT     NOT NULL  DEFAULT 0,
  rc_logid           INTEGER      NOT NULL  DEFAULT 0,
  rc_log_type        TEXT,
  rc_log_action      TEXT,
  rc_params          TEXT
);
CREATE INDEX rc_timestamp       ON recentchanges (rc_timestamp);
CREATE INDEX rc_timestamp_bot   ON recentchanges (rc_timestamp) WHERE rc_bot = 0;
CREATE INDEX rc_namespace_title ON recentchanges (rc_namespace, rc_title);
CREATE INDEX rc_cur_id          ON recentchanges (rc_cur_id);
CREATE INDEX new_name_timestamp ON recentchanges (rc_new, rc_namespace, rc_timestamp);
CREATE INDEX rc_ip              ON recentchanges (rc_ip);


CREATE TABLE watchlist (
  wl_user                   INTEGER     NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  wl_namespace              SMALLINT    NOT NULL  DEFAULT 0,
  wl_title                  TEXT        NOT NULL,
  wl_notificationtimestamp  TIMESTAMPTZ
);
CREATE UNIQUE INDEX wl_user_namespace_title ON watchlist (wl_namespace, wl_title, wl_user);
CREATE INDEX wl_user ON watchlist (wl_user);

CREATE TABLE math (
  math_inputhash              BYTEA     NOT NULL  UNIQUE,
  math_outputhash             BYTEA     NOT NULL,
  math_html_conservativeness  SMALLINT  NOT NULL,
  math_html                   TEXT,
  math_mathml                 TEXT
);


CREATE TABLE interwiki (
  iw_prefix  TEXT      NOT NULL  UNIQUE,
  iw_url     TEXT      NOT NULL,
  iw_local   SMALLINT  NOT NULL,
  iw_trans   SMALLINT  NOT NULL  DEFAULT 0
);


CREATE TABLE querycache (
  qc_type       TEXT      NOT NULL,
  qc_value      INTEGER   NOT NULL,
  qc_namespace  SMALLINT  NOT NULL,
  qc_title      TEXT      NOT NULL
);
CREATE INDEX querycache_type_value ON querycache (qc_type, qc_value);

CREATE TABLE querycache_info (
  qci_type       TEXT              UNIQUE,
  qci_timestamp  TIMESTAMPTZ NULL
);

CREATE TABLE querycachetwo (
  qcc_type          TEXT     NOT NULL,
  qcc_value         INTEGER  NOT NULL  DEFAULT 0,
  qcc_namespace     INTEGER  NOT NULL  DEFAULT 0,
  qcc_title         TEXT     NOT NULL  DEFAULT '',
  qcc_namespacetwo  INTEGER  NOT NULL  DEFAULT 0,
  qcc_titletwo      TEXT     NOT NULL  DEFAULT ''
);
CREATE INDEX querycachetwo_type_value ON querycachetwo (qcc_type, qcc_value);
CREATE INDEX querycachetwo_title      ON querycachetwo (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX querycachetwo_titletwo   ON querycachetwo (qcc_type,qcc_namespacetwo,qcc_titletwo);

CREATE TABLE objectcache (
  keyname  TEXT                   UNIQUE,
  value    BYTEA        NOT NULL  DEFAULT '',
  exptime  TIMESTAMPTZ  NOT NULL
);
CREATE INDEX objectcacache_exptime ON objectcache (exptime);

CREATE TABLE transcache (
  tc_url       TEXT         NOT NULL  UNIQUE,
  tc_contents  TEXT         NOT NULL,
  tc_time      TIMESTAMPTZ  NOT NULL
);


CREATE SEQUENCE logging_log_id_seq;
CREATE TABLE logging (
  log_id          INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('logging_log_id_seq'),
  log_type        TEXT         NOT NULL,
  log_action      TEXT         NOT NULL,
  log_timestamp   TIMESTAMPTZ  NOT NULL,
  log_user        INTEGER                REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  log_namespace   SMALLINT     NOT NULL,
  log_title       TEXT         NOT NULL,
  log_comment     TEXT,
  log_params      TEXT,
  log_deleted     SMALLINT     NOT NULL DEFAULT 0,
  log_user_text   TEXT         NOT NULL DEFAULT '',
  log_page        INTEGER
);
CREATE INDEX logging_type_name ON logging (log_type, log_timestamp);
CREATE INDEX logging_user_time ON logging (log_timestamp, log_user);
CREATE INDEX logging_page_time ON logging (log_namespace, log_title, log_timestamp);
CREATE INDEX logging_times ON logging (log_timestamp);
CREATE INDEX logging_user_type_time ON logging (log_user, log_type, log_timestamp);
CREATE INDEX logging_page_id_time ON logging (log_page, log_timestamp);

CREATE TABLE log_search (
  ls_field   TEXT     NOT NULL,
  ls_value   TEXT     NOT NULL,
  ls_log_id  INTEGER  NOT NULL DEFAULT 0,
  PRIMARY KEY (ls_field,ls_value,ls_log_id)
);
CREATE INDEX ls_log_id ON log_search (ls_log_id);

CREATE SEQUENCE trackbacks_tb_id_seq;
CREATE TABLE trackbacks (
  tb_id     INTEGER  NOT NULL  PRIMARY KEY DEFAULT nextval('trackbacks_tb_id_seq'),
  tb_page   INTEGER            REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  tb_title  TEXT     NOT NULL,
  tb_url    TEXT     NOT NULL,
  tb_ex     TEXT,
  tb_name   TEXT
);
CREATE INDEX trackback_page ON trackbacks (tb_page);


CREATE SEQUENCE job_job_id_seq;
CREATE TABLE job (
  job_id         INTEGER   NOT NULL  PRIMARY KEY DEFAULT nextval('job_job_id_seq'),
  job_cmd        TEXT      NOT NULL,
  job_namespace  SMALLINT  NOT NULL,
  job_title      TEXT      NOT NULL,
  job_params     TEXT      NOT NULL
);
CREATE INDEX job_cmd_namespace_title ON job (job_cmd, job_namespace, job_title);

-- Tsearch2 2 stuff. Will fail if we don't have proper access to the tsearch2 tables
-- Note: if version 8.3 or higher, we remove the 'default' arg
-- Make sure you also change patch-tsearch2funcs.sql if the funcs below change.

ALTER TABLE page ADD titlevector tsvector;
CREATE FUNCTION ts2_page_title() RETURNS TRIGGER LANGUAGE plpgsql AS
$mw$
BEGIN
IF TG_OP = 'INSERT' THEN
  NEW.titlevector = to_tsvector('default',REPLACE(NEW.page_title,'/',' '));
ELSIF NEW.page_title != OLD.page_title THEN
  NEW.titlevector := to_tsvector('default',REPLACE(NEW.page_title,'/',' '));
END IF;
RETURN NEW;
END;
$mw$;

CREATE TRIGGER ts2_page_title BEFORE INSERT OR UPDATE ON page
  FOR EACH ROW EXECUTE PROCEDURE ts2_page_title();


ALTER TABLE pagecontent ADD textvector tsvector;
CREATE FUNCTION ts2_page_text() RETURNS TRIGGER LANGUAGE plpgsql AS
$mw$
BEGIN
IF TG_OP = 'INSERT' THEN
  NEW.textvector = to_tsvector('default',NEW.old_text);
ELSIF NEW.old_text != OLD.old_text THEN
  NEW.textvector := to_tsvector('default',NEW.old_text);
END IF;
RETURN NEW;
END;
$mw$;

CREATE TRIGGER ts2_page_text BEFORE INSERT OR UPDATE ON pagecontent
  FOR EACH ROW EXECUTE PROCEDURE ts2_page_text();

-- These are added by the setup script due to version compatibility issues
-- If using 8.1, we switch from "gin" to "gist"

CREATE INDEX ts2_page_title ON page USING gin(titlevector);
CREATE INDEX ts2_page_text ON pagecontent USING gin(textvector);

CREATE FUNCTION add_interwiki (TEXT,INT,SMALLINT) RETURNS INT LANGUAGE SQL AS
$mw$
  INSERT INTO interwiki (iw_prefix, iw_url, iw_local) VALUES ($1,$2,$3);
  SELECT 1;
$mw$;

-- This table is not used unless profiling is turned on
CREATE TABLE profiling (
  pf_count   INTEGER         NOT NULL DEFAULT 0,
  pf_time    NUMERIC(18,10)  NOT NULL DEFAULT 0,
  pf_memory  NUMERIC(18,10)  NOT NULL DEFAULT 0,
  pf_name    TEXT            NOT NULL,
  pf_server  TEXT            NULL
);
CREATE UNIQUE INDEX pf_name_server ON profiling (pf_name, pf_server);

CREATE TABLE protected_titles (
  pt_namespace   SMALLINT    NOT NULL,
  pt_title       TEXT        NOT NULL,
  pt_user        INTEGER         NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  pt_reason      TEXT            NULL,
  pt_timestamp   TIMESTAMPTZ NOT NULL,
  pt_expiry      TIMESTAMPTZ     NULL,
  pt_create_perm TEXT        NOT NULL DEFAULT ''
);
CREATE UNIQUE INDEX protected_titles_unique ON protected_titles(pt_namespace, pt_title);


CREATE TABLE updatelog (
  ul_key TEXT NOT NULL PRIMARY KEY
);


CREATE SEQUENCE category_cat_id_seq;
CREATE TABLE category (
  cat_id       INTEGER  NOT NULL  PRIMARY KEY DEFAULT nextval('category_cat_id_seq'),
  cat_title    TEXT     NOT NULL,
  cat_pages    INTEGER  NOT NULL  DEFAULT 0,
  cat_subcats  INTEGER  NOT NULL  DEFAULT 0,
  cat_files    INTEGER  NOT NULL  DEFAULT 0,
  cat_hidden   SMALLINT NOT NULL  DEFAULT 0
);
CREATE UNIQUE INDEX category_title ON category(cat_title);
CREATE INDEX category_pages ON category(cat_pages);

CREATE TABLE change_tag (
  ct_rc_id   INTEGER      NULL,
  ct_log_id  INTEGER      NULL,
  ct_rev_id  INTEGER      NULL,
  ct_tag     TEXT     NOT NULL,
  ct_params  TEXT         NULL
);
CREATE UNIQUE INDEX change_tag_rc_tag ON change_tag(ct_rc_id,ct_tag);
CREATE UNIQUE INDEX change_tag_log_tag ON change_tag(ct_log_id,ct_tag);
CREATE UNIQUE INDEX change_tag_rev_tag ON change_tag(ct_rev_id,ct_tag);
CREATE INDEX change_tag_tag_id ON change_tag(ct_tag,ct_rc_id,ct_rev_id,ct_log_id);

CREATE TABLE tag_summary (
  ts_rc_id   INTEGER     NULL,
  ts_log_id  INTEGER     NULL,
  ts_rev_id  INTEGER     NULL,
  ts_tags    TEXT    NOT NULL
);
CREATE UNIQUE INDEX tag_summary_rc_id ON tag_summary(ts_rc_id);
CREATE UNIQUE INDEX tag_summary_log_id ON tag_summary(ts_log_id);
CREATE UNIQUE INDEX tag_summary_rev_id ON tag_summary(ts_rev_id);

CREATE TABLE valid_tag (
  vt_tag TEXT NOT NULL PRIMARY KEY
);

CREATE TABLE user_properties (
  up_user     INTEGER      NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  up_property TEXT     NOT NULL,
  up_value    TEXT
);
CREATE UNIQUE INDEX user_properties_user_property ON user_properties (up_user,up_property);
CREATE INDEX user_properties_property ON user_properties (up_property);

CREATE TABLE l10n_cache (
  lc_lang   TEXT  NOT NULL,
  lc_key    TEXT  NOT NULL,
  lc_value  TEXT  NOT NULL
);
CREATE INDEX l10n_cache_lc_lang_key ON l10n_cache (lc_lang, lc_key);

CREATE TABLE iwlinks (
  iwl_from    INTEGER  NOT NULL DEFAULT 0,
  iwl_prefix  TEXT     NOT NULL DEFAULT '',
  iwl_title   TEXT     NOT NULL DEFAULT ''
);
CREATE UNIQUE INDEX iwl_from ON iwlinks (iwl_from, iwl_prefix, iwl_title);
CREATE INDEX iwl_prefix_from_title ON iwlinks (iwl_prefix, iwl_from, iwl_title);
