-- SQL to create the initial tables for the MediaWiki database.
-- This is read and executed by the install script; you should
-- not have to run it by itself unless doing a manual install.
-- This is the PostgreSQL version.
-- For information about each table, please see the notes in maintenance/tables.sql
-- Please make sure all dollar-quoting uses $mw$ at the start of the line
-- TODO: Change CHAR/SMALLINT to BOOL (still used in a non-bool fashion in PHP code)

BEGIN;
SET client_min_messages = 'ERROR';

DROP SEQUENCE IF EXISTS user_user_id_seq CASCADE;
DROP SEQUENCE IF EXISTS actor_actor_id_seq CASCADE;
DROP SEQUENCE IF EXISTS page_page_id_seq CASCADE;
DROP SEQUENCE IF EXISTS revision_rev_id_seq CASCADE;
DROP SEQUENCE IF EXISTS comment_comment_id_seq CASCADE;
DROP SEQUENCE IF EXISTS text_old_id_seq CASCADE;
DROP SEQUENCE IF EXISTS page_restrictions_pr_id_seq CASCADE;
DROP SEQUENCE IF EXISTS ipblocks_ipb_id_seq CASCADE;
DROP SEQUENCE IF EXISTS filearchive_fa_id_seq CASCADE;
DROP SEQUENCE IF EXISTS uploadstash_us_id_seq CASCADE;
DROP SEQUENCE IF EXISTS recentchanges_rc_id_seq CASCADE;
DROP SEQUENCE IF EXISTS watchlist_wl_id_seq CASCADE;
DROP SEQUENCE IF EXISTS logging_log_id_seq CASCADE;
DROP SEQUENCE IF EXISTS job_job_id_seq CASCADE;
DROP SEQUENCE IF EXISTS category_cat_id_seq CASCADE;
DROP SEQUENCE IF EXISTS archive_ar_id_seq CASCADE;
DROP SEQUENCE IF EXISTS externallinks_el_id_seq CASCADE;
DROP SEQUENCE IF EXISTS sites_site_id_seq CASCADE;
DROP SEQUENCE IF EXISTS change_tag_ct_id_seq CASCADE;
DROP SEQUENCE IF EXISTS watchlist_expiry_we_item_seq CASCADE;
DROP FUNCTION IF EXISTS page_deleted() CASCADE;
DROP FUNCTION IF EXISTS ts2_page_title() CASCADE;
DROP FUNCTION IF EXISTS ts2_page_text() CASCADE;
DROP FUNCTION IF EXISTS add_interwiki(TEXT,INT,SMALLINT) CASCADE;
DROP TYPE IF EXISTS media_type CASCADE;

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
  user_touched              TIMESTAMPTZ,
  user_registration         TIMESTAMPTZ,
  user_editcount            INTEGER,
  user_password_expires     TIMESTAMPTZ NULL
);
ALTER SEQUENCE user_user_id_seq OWNED BY mwuser.user_id;
CREATE INDEX user_email_token_idx ON mwuser (user_email_token);

-- Create a dummy user to satisfy fk contraints especially with revisions
INSERT INTO mwuser
  VALUES (DEFAULT,'Anonymous','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,now(),now());

CREATE TABLE user_groups (
  ug_user    INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  ug_group   TEXT         NOT NULL,
  ug_expiry  TIMESTAMPTZ  NULL,
  PRIMARY KEY(ug_user, ug_group)
);
CREATE INDEX user_groups_group  ON user_groups (ug_group);
CREATE INDEX user_groups_expiry ON user_groups (ug_expiry);

CREATE TABLE user_newtalk (
  user_id              INTEGER          NOT NULL DEFAULT 0 REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  user_ip              TEXT             NOT NULL DEFAULT '',
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
  page_is_redirect   SMALLINT       NOT NULL  DEFAULT 0,
  page_is_new        SMALLINT       NOT NULL  DEFAULT 0,
  page_random        NUMERIC(15,14) NOT NULL  DEFAULT RANDOM(),
  page_touched       TIMESTAMPTZ,
  page_links_updated TIMESTAMPTZ    NULL,
  page_latest        INTEGER        NOT NULL, -- FK?
  page_len           INTEGER        NOT NULL,
  page_content_model TEXT,
  page_lang          TEXT                     DEFAULT NULL
);
ALTER SEQUENCE page_page_id_seq OWNED BY page.page_id;
CREATE UNIQUE INDEX page_unique_name ON page (page_namespace, page_title);
CREATE INDEX page_main_title         ON page (page_title text_pattern_ops) WHERE page_namespace = 0;
CREATE INDEX page_talk_title         ON page (page_title text_pattern_ops) WHERE page_namespace = 1;
CREATE INDEX page_user_title         ON page (page_title text_pattern_ops) WHERE page_namespace = 2;
CREATE INDEX page_utalk_title        ON page (page_title text_pattern_ops) WHERE page_namespace = 3;
CREATE INDEX page_project_title      ON page (page_title text_pattern_ops) WHERE page_namespace = 4;
CREATE INDEX page_mediawiki_title    ON page (page_title text_pattern_ops) WHERE page_namespace = 8;
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
  rev_id             INTEGER      NOT NULL  UNIQUE DEFAULT nextval('revision_rev_id_seq'),
  rev_page           INTEGER          NULL  REFERENCES page (page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  rev_comment_id     INTEGER      NOT NULL DEFAULT 0,
  rev_actor          INTEGER      NOT NULL DEFAULT 0,
  rev_timestamp      TIMESTAMPTZ  NOT NULL,
  rev_minor_edit     SMALLINT     NOT NULL  DEFAULT 0,
  rev_deleted        SMALLINT     NOT NULL  DEFAULT 0,
  rev_len            INTEGER          NULL,
  rev_parent_id      INTEGER          NULL,
  rev_sha1           TEXT         NOT NULL DEFAULT ''
);
ALTER SEQUENCE revision_rev_id_seq OWNED BY revision.rev_id;
CREATE UNIQUE INDEX revision_unique ON revision (rev_page, rev_id);
CREATE INDEX rev_timestamp_idx      ON revision (rev_timestamp);
CREATE INDEX rev_actor_timestamp    ON revision (rev_actor,rev_timestamp,rev_id);
CREATE INDEX rev_page_actor_timestamp ON revision (rev_page,rev_actor,rev_timestamp);

CREATE TABLE revision_comment_temp (
	revcomment_rev        INTEGER NOT NULL,
	revcomment_comment_id INTEGER NOT NULL,
	PRIMARY KEY (revcomment_rev, revcomment_comment_id)
);
CREATE UNIQUE INDEX revcomment_rev ON revision_comment_temp (revcomment_rev);

CREATE TABLE revision_actor_temp (
  revactor_rev       INTEGER NOT NULL,
  revactor_actor     INTEGER NOT NULL,
  revactor_timestamp TIMESTAMPTZ  NOT NULL,
  revactor_page      INTEGER          NULL  REFERENCES page (page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  PRIMARY KEY (revactor_rev, revactor_actor)
);
CREATE UNIQUE INDEX revactor_rev ON revision_actor_temp (revactor_rev);
CREATE INDEX revactor_actor_timestamp ON revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX revactor_page_actor_timestamp ON revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);

CREATE SEQUENCE ip_changes_ipc_rev_id_seq;
CREATE TABLE ip_changes (
  ipc_rev_id        INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('ip_changes_ipc_rev_id_seq'),
  ipc_rev_timestamp TIMESTAMPTZ NOT NULL,
  ipc_hex           BYTEA NOT NULL DEFAULT ''
);
ALTER SEQUENCE ip_changes_ipc_rev_id_seq OWNED BY ip_changes.ipc_rev_id;
CREATE INDEX ipc_rev_timestamp ON ip_changes (ipc_rev_timestamp);
CREATE INDEX ipc_hex_time ON ip_changes (ipc_hex,ipc_rev_timestamp);

CREATE SEQUENCE text_old_id_seq;
CREATE TABLE pagecontent ( -- replaces reserved word 'text'
  old_id     INTEGER  NOT NULL  PRIMARY KEY DEFAULT nextval('text_old_id_seq'),
  old_text   TEXT,
  old_flags  TEXT
);
ALTER SEQUENCE text_old_id_seq OWNED BY pagecontent.old_id;

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
ALTER SEQUENCE page_restrictions_pr_id_seq OWNED BY page_restrictions.pr_id;
ALTER TABLE page_restrictions ADD CONSTRAINT page_restrictions_pk PRIMARY KEY (pr_page,pr_type);

CREATE TABLE page_props (
  pp_page      INTEGER  NOT NULL  REFERENCES page (page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  pp_propname  TEXT     NOT NULL,
  pp_value     TEXT     NOT NULL,
  pp_sortkey   FLOAT
);
ALTER TABLE page_props ADD CONSTRAINT page_props_pk PRIMARY KEY (pp_page,pp_propname);
CREATE UNIQUE INDEX pp_propname_page ON page_props (pp_propname,pp_page);
CREATE INDEX pp_propname_sortkey_page ON page_props (pp_propname, pp_sortkey, pp_page) WHERE (pp_sortkey IS NOT NULL);

CREATE SEQUENCE archive_ar_id_seq;
CREATE TABLE archive (
  ar_id             INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('archive_ar_id_seq'),
  ar_namespace      SMALLINT     NOT NULL,
  ar_title          TEXT         NOT NULL,
  ar_page_id        INTEGER          NULL,
  ar_parent_id      INTEGER          NULL,
  ar_sha1           TEXT         NOT NULL DEFAULT '',
  ar_comment_id     INTEGER      NOT NULL,
  ar_actor          INTEGER      NOT NULL,
  ar_timestamp      TIMESTAMPTZ  NOT NULL,
  ar_minor_edit     SMALLINT     NOT NULL  DEFAULT 0,
  ar_rev_id         INTEGER      NOT NULL,
  ar_deleted        SMALLINT     NOT NULL  DEFAULT 0,
  ar_len            INTEGER          NULL
);
ALTER SEQUENCE archive_ar_id_seq OWNED BY archive.ar_id;
CREATE INDEX archive_name_title_timestamp ON archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX archive_actor                ON archive (ar_actor);
CREATE UNIQUE INDEX ar_revid_uniq ON archive (ar_rev_id);

CREATE SEQUENCE slot_roles_role_id_seq;
CREATE TABLE slot_roles (
  role_id    SMALLINT  NOT NULL PRIMARY KEY DEFAULT nextval('slot_roles_role_id_seq'),
  role_name  TEXT      NOT NULL
);
ALTER SEQUENCE slot_roles_role_id_seq OWNED BY slot_roles.role_id;

CREATE UNIQUE INDEX role_name ON slot_roles (role_name);


CREATE SEQUENCE content_models_model_id_seq;
CREATE TABLE content_models (
  model_id    SMALLINT  NOT NULL PRIMARY KEY DEFAULT nextval('content_models_model_id_seq'),
  model_name  TEXT      NOT NULL
);
ALTER SEQUENCE content_models_model_id_seq OWNED BY content_models.model_id;

CREATE UNIQUE INDEX model_name ON content_models (model_name);


CREATE TABLE categorylinks (
  cl_from           INTEGER      NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  cl_to             TEXT         NOT NULL,
  cl_sortkey        TEXT             NULL,
  cl_timestamp      TIMESTAMPTZ  NOT NULL,
  cl_sortkey_prefix TEXT         NOT NULL  DEFAULT '',
  cl_collation      TEXT         NOT NULL  DEFAULT 0,
  cl_type           TEXT         NOT NULL  DEFAULT 'page'
);
CREATE UNIQUE INDEX cl_from ON categorylinks (cl_from, cl_to);
CREATE INDEX cl_sortkey     ON categorylinks (cl_to, cl_sortkey, cl_from);

CREATE SEQUENCE externallinks_el_id_seq;
CREATE TABLE externallinks (
  el_id       INTEGER     NOT NULL  PRIMARY KEY DEFAULT nextval('externallinks_el_id_seq'),
  el_from     INTEGER     NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  el_to       TEXT        NOT NULL,
  el_index    TEXT        NOT NULL,
  el_index_60 BYTEA       NOT NULL
);
ALTER SEQUENCE externallinks_el_id_seq OWNED BY externallinks.el_id;
CREATE INDEX externallinks_from_to ON externallinks (el_from,el_to);
CREATE INDEX externallinks_index   ON externallinks (el_index);
CREATE INDEX el_index_60           ON externallinks (el_index_60, el_id);
CREATE INDEX el_from_index_60      ON externallinks (el_from, el_index_60, el_id);

CREATE SEQUENCE ipblocks_ipb_id_seq;
CREATE TABLE ipblocks (
  ipb_id                INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('ipblocks_ipb_id_seq'),
  ipb_address           TEXT             NULL,
  ipb_user              INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  ipb_by_actor          INTEGER      NOT NULL,
  ipb_reason_id         INTEGER      NOT NULL,
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
  ipb_allow_usertalk    SMALLINT     NOT NULL  DEFAULT 0,
  ipb_parent_block_id   INTEGER          NULL            REFERENCES ipblocks(ipb_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  ipb_sitewide          SMALLINT     NOT NULL  DEFAULT 1
);
ALTER SEQUENCE ipblocks_ipb_id_seq OWNED BY ipblocks.ipb_id;
CREATE UNIQUE INDEX ipb_address_unique ON ipblocks (ipb_address,ipb_user,ipb_auto);
CREATE INDEX ipb_user    ON ipblocks (ipb_user);
CREATE INDEX ipb_range   ON ipblocks (ipb_range_start,ipb_range_end);
CREATE INDEX ipb_parent_block_id   ON ipblocks (ipb_parent_block_id);

CREATE TABLE ipblocks_restrictions (
  ir_ipb_id INTEGER  NOT NULL REFERENCES ipblocks(ipb_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  ir_type   SMALLINT NOT NULL,
  ir_value  INTEGER  NOT NULL,
  PRIMARY KEY (ir_ipb_id, ir_type, ir_value)
);
CREATE INDEX /*i*/ir_type_value ON /*_*/ipblocks_restrictions (ir_type, ir_value);


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
  img_description_id INTEGER NOT NULL,
  img_actor        INTEGER   NOT NULL,
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
  oi_description_id INTEGER     NOT NULL,
  oi_actor         INTEGER      NOT NULL,
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
  fa_deleted_reason_id  INTEGER      NOT NULL,
  fa_size               INTEGER      NOT NULL,
  fa_width              INTEGER      NOT NULL,
  fa_height             INTEGER      NOT NULL,
  fa_metadata           BYTEA        NOT NULL  DEFAULT '',
  fa_bits               SMALLINT,
  fa_media_type         TEXT,
  fa_major_mime         TEXT                   DEFAULT 'unknown',
  fa_minor_mime         TEXT                   DEFAULT 'unknown',
  fa_description_id     INTEGER      NOT NULL,
  fa_actor              INTEGER      NOT NULL,
  fa_timestamp          TIMESTAMPTZ,
  fa_deleted            SMALLINT     NOT NULL DEFAULT 0,
  fa_sha1               TEXT         NOT NULL DEFAULT ''
);
ALTER SEQUENCE filearchive_fa_id_seq OWNED BY filearchive.fa_id;
CREATE INDEX fa_name_time ON filearchive (fa_name, fa_timestamp);
CREATE INDEX fa_dupe      ON filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX fa_notime    ON filearchive (fa_deleted_timestamp);
CREATE INDEX fa_nouser    ON filearchive (fa_deleted_user);
CREATE INDEX fa_sha1      ON filearchive (fa_sha1);

CREATE SEQUENCE uploadstash_us_id_seq;
CREATE TYPE media_type AS ENUM ('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE','3D');
CREATE TABLE uploadstash (
  us_id           INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('uploadstash_us_id_seq'),
  us_user         INTEGER,
  us_key          TEXT,
  us_orig_path    TEXT,
  us_path         TEXT,
  us_props        BYTEA,
  us_source_type  TEXT,
  us_timestamp    TIMESTAMPTZ,
  us_status       TEXT,
  us_chunk_inx    INTEGER NULL,
  us_size         INTEGER,
  us_sha1         TEXT,
  us_mime         TEXT,
  us_media_type   media_type DEFAULT NULL,
  us_image_width  INTEGER,
  us_image_height INTEGER,
  us_image_bits   SMALLINT
);
ALTER SEQUENCE uploadstash_us_id_seq OWNED BY uploadstash.us_id;

CREATE INDEX us_user_idx ON uploadstash (us_user);
CREATE UNIQUE INDEX us_key_idx ON uploadstash (us_key);
CREATE INDEX us_timestamp_idx ON uploadstash (us_timestamp);


CREATE SEQUENCE recentchanges_rc_id_seq;
CREATE TABLE recentchanges (
  rc_id              INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('recentchanges_rc_id_seq'),
  rc_timestamp       TIMESTAMPTZ  NOT NULL,
  rc_actor           INTEGER      NOT NULL,
  rc_namespace       SMALLINT     NOT NULL,
  rc_title           TEXT         NOT NULL,
  rc_comment_id      INTEGER      NOT NULL,
  rc_minor           SMALLINT     NOT NULL  DEFAULT 0,
  rc_bot             SMALLINT     NOT NULL  DEFAULT 0,
  rc_new             SMALLINT     NOT NULL  DEFAULT 0,
  rc_cur_id          INTEGER          NULL,
  rc_this_oldid      INTEGER      NOT NULL,
  rc_last_oldid      INTEGER      NOT NULL,
  rc_type            SMALLINT     NOT NULL  DEFAULT 0,
  rc_source          TEXT         NOT NULL,
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
ALTER SEQUENCE recentchanges_rc_id_seq OWNED BY recentchanges.rc_id;
CREATE INDEX rc_timestamp       ON recentchanges (rc_timestamp);
CREATE INDEX rc_timestamp_bot   ON recentchanges (rc_timestamp) WHERE rc_bot = 0;
CREATE INDEX rc_namespace_title_timestamp ON recentchanges (rc_namespace, rc_title, rc_timestamp);
CREATE INDEX rc_cur_id          ON recentchanges (rc_cur_id);
CREATE INDEX new_name_timestamp ON recentchanges (rc_new, rc_namespace, rc_timestamp);
CREATE INDEX rc_ip              ON recentchanges (rc_ip);
CREATE INDEX rc_name_type_patrolled_timestamp ON recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);
CREATE INDEX rc_this_oldid      ON recentchanges (rc_this_oldid);


CREATE SEQUENCE watchlist_wl_id_seq;
CREATE TABLE watchlist (
  wl_id                     INTEGER     NOT NULL  PRIMARY KEY DEFAULT nextval('watchlist_wl_id_seq'),
  wl_user                   INTEGER     NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  wl_namespace              SMALLINT    NOT NULL  DEFAULT 0,
  wl_title                  TEXT        NOT NULL,
  wl_notificationtimestamp  TIMESTAMPTZ
);
ALTER SEQUENCE watchlist_wl_id_seq OWNED BY watchlist.wl_id;
CREATE UNIQUE INDEX wl_user_namespace_title ON watchlist (wl_namespace, wl_title, wl_user);
CREATE INDEX wl_user ON watchlist (wl_user);
CREATE INDEX wl_user_notificationtimestamp ON watchlist (wl_user, wl_notificationtimestamp);


CREATE TABLE interwiki (
  iw_prefix  TEXT      NOT NULL  PRIMARY KEY,
  iw_url     TEXT      NOT NULL,
  iw_local   SMALLINT  NOT NULL,
  iw_trans   SMALLINT  NOT NULL  DEFAULT 0,
  iw_api     TEXT      NOT NULL  DEFAULT '',
  iw_wikiid  TEXT      NOT NULL  DEFAULT ''
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


CREATE SEQUENCE logging_log_id_seq;
CREATE TABLE logging (
  log_id          INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('logging_log_id_seq'),
  log_type        TEXT         NOT NULL,
  log_action      TEXT         NOT NULL,
  log_timestamp   TIMESTAMPTZ  NOT NULL,
  log_actor       INTEGER      NOT NULL,
  log_namespace   SMALLINT     NOT NULL,
  log_title       TEXT         NOT NULL,
  log_comment_id  INTEGER      NOT NULL,
  log_params      TEXT,
  log_deleted     SMALLINT     NOT NULL DEFAULT 0,
  log_page        INTEGER
);
ALTER SEQUENCE logging_log_id_seq OWNED BY logging.log_id;
CREATE INDEX logging_type_name ON logging (log_type, log_timestamp);
CREATE INDEX logging_actor_time_backwards ON logging (log_timestamp, log_actor);
CREATE INDEX logging_page_time ON logging (log_namespace, log_title, log_timestamp);
CREATE INDEX logging_times ON logging (log_timestamp);
CREATE INDEX logging_actor_type_time ON logging (log_actor, log_type, log_timestamp);
CREATE INDEX logging_page_id_time ON logging (log_page, log_timestamp);
CREATE INDEX logging_actor_time ON logging (log_actor, log_timestamp);
CREATE INDEX logging_type_action ON logging (log_type, log_action, log_timestamp);


CREATE SEQUENCE job_job_id_seq;
CREATE TABLE job (
  job_id              INTEGER   NOT NULL  PRIMARY KEY DEFAULT nextval('job_job_id_seq'),
  job_cmd             TEXT      NOT NULL,
  job_namespace       SMALLINT  NOT NULL,
  job_title           TEXT      NOT NULL,
  job_timestamp       TIMESTAMPTZ,
  job_params          TEXT      NOT NULL,
  job_random          INTEGER   NOT NULL DEFAULT 0,
  job_attempts        INTEGER   NOT NULL DEFAULT 0,
  job_token           TEXT      NOT NULL DEFAULT '',
  job_token_timestamp TIMESTAMPTZ,
  job_sha1            TEXT NOT NULL DEFAULT ''
);
ALTER SEQUENCE job_job_id_seq OWNED BY job.job_id;
CREATE INDEX job_sha1 ON job (job_sha1);
CREATE INDEX job_cmd_token ON job (job_cmd, job_token, job_random);
CREATE INDEX job_cmd_token_id ON job (job_cmd, job_token, job_id);
CREATE INDEX job_cmd_namespace_title ON job (job_cmd, job_namespace, job_title);
CREATE INDEX job_timestamp_idx ON job (job_timestamp);

-- Tsearch2 2 stuff. Will fail if we don't have proper access to the tsearch2 tables
-- Make sure you also change patch-tsearch2funcs.sql if the funcs below change.

ALTER TABLE page ADD titlevector tsvector;
CREATE FUNCTION ts2_page_title() RETURNS TRIGGER LANGUAGE plpgsql AS
$mw$
BEGIN
IF TG_OP = 'INSERT' THEN
  NEW.titlevector = to_tsvector(REPLACE(NEW.page_title,'/',' '));
ELSIF NEW.page_title != OLD.page_title THEN
  NEW.titlevector := to_tsvector(REPLACE(NEW.page_title,'/',' '));
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
  NEW.textvector = to_tsvector(NEW.old_text);
ELSIF NEW.old_text != OLD.old_text THEN
  NEW.textvector := to_tsvector(NEW.old_text);
END IF;
RETURN NEW;
END;
$mw$;

CREATE TRIGGER ts2_page_text BEFORE INSERT OR UPDATE ON pagecontent
  FOR EACH ROW EXECUTE PROCEDURE ts2_page_text();

CREATE INDEX ts2_page_title ON page USING gin(titlevector);
CREATE INDEX ts2_page_text ON pagecontent USING gin(textvector);

CREATE FUNCTION add_interwiki (TEXT,INT,SMALLINT) RETURNS INT LANGUAGE SQL AS
$mw$
  INSERT INTO interwiki (iw_prefix, iw_url, iw_local) VALUES ($1,$2,$3);
  SELECT 1;
$mw$;

CREATE TABLE protected_titles (
  pt_namespace   SMALLINT    NOT NULL,
  pt_title       TEXT        NOT NULL,
  pt_user        INTEGER         NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED,
  pt_reason_id   INTEGER     NOT NULL,
  pt_timestamp   TIMESTAMPTZ NOT NULL,
  pt_expiry      TIMESTAMPTZ     NULL,
  pt_create_perm TEXT        NOT NULL DEFAULT '',

  PRIMARY KEY (pt_namespace, pt_title)
);


CREATE SEQUENCE sites_site_id_seq;
CREATE TABLE sites (
  site_id           INTEGER     NOT NULL    PRIMARY KEY DEFAULT nextval('sites_site_id_seq'),
  site_global_key   TEXT        NOT NULL,
  site_type         TEXT        NOT NULL,
  site_group        TEXT        NOT NULL,
  site_source       TEXT        NOT NULL,
  site_language     TEXT        NOT NULL,
  site_protocol     TEXT        NOT NULL,
  site_domain       TEXT        NOT NULL,
  site_data         TEXT        NOT NULL,
  site_forward      SMALLINT    NOT NULL,
  site_config       TEXT        NOT NULL
);
ALTER SEQUENCE sites_site_id_seq OWNED BY sites.site_id;
CREATE UNIQUE INDEX site_global_key ON sites (site_global_key);
CREATE INDEX site_type ON sites (site_type);
CREATE INDEX site_group ON sites (site_group);
CREATE INDEX site_source ON sites (site_source);
CREATE INDEX site_language ON sites (site_language);
CREATE INDEX site_protocol ON sites (site_protocol);
CREATE INDEX site_domain ON sites (site_domain);
CREATE INDEX site_forward ON sites (site_forward);
