-- SQL to create the initial tables for the MediaWiki database.
-- This is read and executed by the install script; you should
-- not have to run it by itself unless doing a manual install.
-- This is the PostgreSQL version.
-- For information about each table, please see the notes in maintenance/tables.sql
-- Please make sure all dollar-quoting uses $mw$ at the start of the line
-- We can't use SERIAL everywhere: the sequence names are hard-coded into the PHP
-- TODO: Change CHAR to BOOL

BEGIN;
SET client_min_messages = 'ERROR';

CREATE SEQUENCE user_user_id_seq MINVALUE 0 START WITH 0;
CREATE TABLE mwuser ( -- replace reserved word 'user'
  user_id                   INTEGER  NOT NULL  PRIMARY KEY DEFAULT nextval('user_user_id_seq'),
  user_name                 TEXT     NOT NULL  UNIQUE,
  user_real_name            TEXT,
  user_password             TEXT,
  user_newpassword          TEXT,
  user_token                CHAR(32),
  user_email                TEXT,
  user_email_token          CHAR(32),
  user_email_token_expires  TIMESTAMPTZ,
  user_email_authenticated  TIMESTAMPTZ,
  user_options              TEXT,
  user_touched              TIMESTAMPTZ,
  user_registration         TIMESTAMPTZ
);
CREATE INDEX user_email_token_idx ON mwuser (user_email_token);

-- Create a dummy user to satisfy fk contraints especially with revisions
INSERT INTO mwuser
  VALUES (DEFAULT,'Anonymous','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,now(),now());

CREATE TABLE user_groups (
  ug_user   INTEGER      NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE,
  ug_group  TEXT     NOT NULL
);
CREATE UNIQUE INDEX user_groups_unique ON user_groups (ug_user, ug_group);

CREATE TABLE user_newtalk (
  user_id  INTEGER NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE,
  user_ip  CIDR        NULL
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
  page_is_redirect   CHAR           NOT NULL  DEFAULT 0,
  page_is_new        CHAR           NOT NULL  DEFAULT 0,
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

CREATE SEQUENCE rev_rev_id_val;
CREATE TABLE revision (
  rev_id          INTEGER      NOT NULL  UNIQUE DEFAULT nextval('rev_rev_id_val'),
  rev_page        INTEGER          NULL  REFERENCES page (page_id) ON DELETE CASCADE,
  rev_text_id     INTEGER          NULL, -- FK
  rev_comment     TEXT,
  rev_user        INTEGER      NOT NULL  REFERENCES mwuser(user_id),
  rev_user_text   TEXT         NOT NULL,
  rev_timestamp   TIMESTAMPTZ  NOT NULL,
  rev_minor_edit  CHAR         NOT NULL  DEFAULT '0',
  rev_deleted     CHAR         NOT NULL  DEFAULT '0'
);
CREATE UNIQUE INDEX revision_unique ON revision (rev_page, rev_id);
CREATE INDEX rev_timestamp_idx      ON revision (rev_timestamp);
CREATE INDEX rev_user_idx           ON revision (rev_user);
CREATE INDEX rev_user_text_idx      ON revision (rev_user_text);


CREATE SEQUENCE text_old_id_val;
CREATE TABLE pagecontent ( -- replaces reserved word 'text'
  old_id     INTEGER  NOT NULL  PRIMARY KEY DEFAULT nextval('text_old_id_val'),
  old_text   TEXT,
  old_flags  TEXT
);


CREATE TABLE archive2 (
  ar_namespace   SMALLINT     NOT NULL,
  ar_title       TEXT         NOT NULL,
  ar_text        TEXT,
  ar_comment     TEXT,
  ar_user        INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL,
  ar_user_text   TEXT         NOT NULL,
  ar_timestamp   TIMESTAMPTZ  NOT NULL,
  ar_minor_edit  CHAR         NOT NULL  DEFAULT '0',
  ar_flags       TEXT,
  ar_rev_id      INTEGER,
  ar_text_id     INTEGER
);
CREATE INDEX archive_name_title_timestamp ON archive2 (ar_namespace,ar_title,ar_timestamp);

-- This is the easiest way to work around the char(15) timestamp hack without modifying PHP code
CREATE VIEW archive AS 
SELECT 
  ar_namespace, ar_title, ar_text, ar_comment, ar_user, ar_user_text, 
  ar_minor_edit, ar_flags, ar_rev_id, ar_text_id,
       TO_CHAR(ar_timestamp, 'YYYYMMDDHH24MISS') AS ar_timestamp
FROM archive2;

CREATE RULE archive_insert AS ON INSERT TO archive
DO INSTEAD INSERT INTO archive2 VALUES (
  NEW.ar_namespace, NEW.ar_title, NEW.ar_text, NEW.ar_comment, NEW.ar_user, NEW.ar_user_text, 
  TO_DATE(NEW.ar_timestamp, 'YYYYMMDDHH24MISS'),
  NEW.ar_minor_edit, NEW.ar_flags, NEW.ar_rev_id, NEW.ar_text_id
);


CREATE TABLE pagelinks (
  pl_from       INTEGER   NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  pl_namespace  SMALLINT  NOT NULL,
  pl_title      TEXT      NOT NULL
);
CREATE UNIQUE INDEX pagelink_unique ON pagelinks (pl_from,pl_namespace,pl_title);

CREATE TABLE templatelinks (
  tl_from       INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  tl_namespace  TEXT     NOT NULL,
  tl_title      TEXT     NOT NULL
);
CREATE UNIQUE INDEX templatelinks_unique ON templatelinks (tl_namespace,tl_title,tl_from);

CREATE TABLE imagelinks (
  il_from  INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  il_to    TEXT     NOT NULL
);
CREATE UNIQUE INDEX il_from ON imagelinks (il_to,il_from);

CREATE TABLE categorylinks (
  cl_from       INTEGER      NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  cl_to         TEXT         NOT NULL,
  cl_sortkey    TEXT,
  cl_timestamp  TIMESTAMPTZ  NOT NULL
);
CREATE UNIQUE INDEX cl_from ON categorylinks (cl_from, cl_to);
CREATE INDEX cl_sortkey     ON categorylinks (cl_to, cl_sortkey);

CREATE TABLE externallinks (
  el_from   INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  el_to     TEXT     NOT NULL,
  el_index  TEXT     NOT NULL
);
CREATE INDEX externallinks_from_to ON externallinks (el_from,el_to);
CREATE INDEX externallinks_index   ON externallinks (el_index);

CREATE TABLE langlinks (
  ll_from    INTEGER  NOT NULL  REFERENCES page (page_id) ON DELETE CASCADE,
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
  ss_admins         INTEGER            DEFAULT -1,
  ss_images         INTEGER            DEFAULT 0
);

CREATE TABLE hitcounter (
  hc_id  BIGINT  NOT NULL
);


CREATE SEQUENCE ipblocks_ipb_id_val;
CREATE TABLE ipblocks (
  ipb_id              INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('ipblocks_ipb_id_val'),
  ipb_address         CIDR             NULL,
  ipb_user            INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL,
  ipb_by              INTEGER      NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE,
  ipb_reason          TEXT         NOT NULL,
  ipb_timestamp       TIMESTAMPTZ  NOT NULL,
  ipb_auto            CHAR         NOT NULL  DEFAULT '0',
  ipb_anon_only       CHAR         NOT NULL  DEFAULT '0',
  ipb_create_account  CHAR         NOT NULL  DEFAULT '1',
  ipb_expiry          TIMESTAMPTZ  NOT NULL,
  ipb_range_start     TEXT,
  ipb_range_end       TEXT
);
CREATE INDEX ipb_address ON ipblocks (ipb_address);
CREATE INDEX ipb_user    ON ipblocks (ipb_user);
CREATE INDEX ipb_range   ON ipblocks (ipb_range_start,ipb_range_end);


CREATE TABLE image (
  img_name         TEXT      NOT NULL  PRIMARY KEY,
  img_size         INTEGER   NOT NULL,
  img_width        INTEGER   NOT NULL,
  img_height       INTEGER   NOT NULL,
  img_metadata     TEXT,
  img_bits         SMALLINT,
  img_media_type   TEXT,
  img_major_mime   TEXT                DEFAULT 'unknown',
  img_minor_mime   TEXT                DEFAULT 'unknown',
  img_description  TEXT      NOT NULL,
  img_user         INTEGER       NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL,
  img_user_text    TEXT      NOT NULL,
  img_timestamp    TIMESTAMPTZ
);
CREATE INDEX img_size_idx      ON image (img_size);
CREATE INDEX img_timestamp_idx ON image (img_timestamp);

CREATE TABLE oldimage (
  oi_name          TEXT         NOT NULL  REFERENCES image(img_name),
  oi_archive_name  TEXT         NOT NULL,
  oi_size          INTEGER      NOT NULL,
  oi_width         INTEGER      NOT NULL,
  oi_height        INTEGER      NOT NULL,
  oi_bits          SMALLINT     NOT NULL,
  oi_description   TEXT,
  oi_user          INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL,
  oi_user_text     TEXT         NOT NULL,
  oi_timestamp     TIMESTAMPTZ  NOT NULL
);
CREATE INDEX oi_name ON oldimage (oi_name);


CREATE TABLE filearchive (
  fa_id                 SERIAL       NOT NULL  PRIMARY KEY,
  fa_name               TEXT         NOT NULL,
  fa_archive_name       TEXT,
  fa_storage_group      VARCHAR(16),
  fa_storage_key        CHAR(64),
  fa_deleted_user       INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL,
  fa_deleted_timestamp  TIMESTAMPTZ  NOT NULL,
  fa_deleted_reason     TEXT,
  fa_size               SMALLINT     NOT NULL,
  fa_width              SMALLINT     NOT NULL,
  fa_height             SMALLINT     NOT NULL,
  fa_metadata           TEXT,
  fa_bits               SMALLINT,
  fa_media_type         TEXT,
  fa_major_mime         TEXT                   DEFAULT 'unknown',
  fa_minor_mime         TEXT                   DEFAULT 'unknown',
  fa_description        TEXT         NOT NULL,
  fa_user               INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL,
  fa_user_text          TEXT         NOT NULL,
  fa_timestamp          TIMESTAMPTZ
);
CREATE INDEX fa_name_time ON filearchive (fa_name, fa_timestamp);
CREATE INDEX fa_dupe      ON filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX fa_notime    ON filearchive (fa_deleted_timestamp);
CREATE INDEX fa_nouser    ON filearchive (fa_deleted_user);


CREATE SEQUENCE rc_rc_id_seq;
CREATE TABLE recentchanges (
  rc_id              INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('rc_rc_id_seq'),
  rc_timestamp       TIMESTAMPTZ  NOT NULL,
  rc_cur_time        TIMESTAMPTZ  NOT NULL,
  rc_user            INTEGER          NULL  REFERENCES mwuser(user_id) ON DELETE SET NULL,
  rc_user_text       TEXT         NOT NULL,
  rc_namespace       SMALLINT     NOT NULL,
  rc_title           TEXT         NOT NULL,
  rc_comment         TEXT,
  rc_minor           CHAR         NOT NULL  DEFAULT '0',
  rc_bot             CHAR         NOT NULL  DEFAULT '0',
  rc_new             CHAR         NOT NULL  DEFAULT '0',
  rc_cur_id          INTEGER          NULL  REFERENCES page(page_id) ON DELETE SET NULL,
  rc_this_oldid      INTEGER      NOT NULL,
  rc_last_oldid      INTEGER      NOT NULL,
  rc_type            CHAR         NOT NULL  DEFAULT '0',
  rc_moved_to_ns     SMALLINT,
  rc_moved_to_title  TEXT,
  rc_patrolled       CHAR         NOT NULL  DEFAULT '0',
  rc_ip              CIDR
);
CREATE INDEX rc_timestamp       ON recentchanges (rc_timestamp);
CREATE INDEX rc_namespace_title ON recentchanges (rc_namespace, rc_title);
CREATE INDEX rc_cur_id          ON recentchanges (rc_cur_id);
CREATE INDEX new_name_timestamp ON recentchanges (rc_new, rc_namespace, rc_timestamp);
CREATE INDEX rc_ip              ON recentchanges (rc_ip);


CREATE TABLE watchlist (
  wl_user                   INTEGER     NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE,
  wl_namespace              SMALLINT    NOT NULL  DEFAULT 0,
  wl_title                  TEXT        NOT NULL,
  wl_notificationtimestamp  TIMESTAMPTZ
);
CREATE UNIQUE INDEX wl_user_namespace_title ON watchlist (wl_namespace, wl_title, wl_user);


CREATE TABLE math (
  math_inputhash              TEXT      NOT NULL  UNIQUE,
  math_outputhash             TEXT      NOT NULL,
  math_html_conservativeness  SMALLINT  NOT NULL,
  math_html                   TEXT,
  math_mathml                 TEXT
);


CREATE TABLE interwiki (
  iw_prefix  TEXT  NOT NULL  UNIQUE,
  iw_url     TEXT  NOT NULL,
  iw_local   CHAR  NOT NULL,
  iw_trans   CHAR  NOT NULL  DEFAULT '0'
);


CREATE TABLE querycache (
  qc_type       TEXT      NOT NULL,
  qc_value      SMALLINT  NOT NULL,
  qc_namespace  SMALLINT  NOT NULL,
  qc_title      TEXT      NOT NULL
);
CREATE INDEX querycache_type_value ON querycache (qc_type, qc_value);

CREATE TABLE querycache_info (
  qci_type       TEXT              UNIQUE,
  qci_timestamp  TIMESTAMPTZ NULL
);

CREATE TABLE objectcache (
  keyname  CHAR(255)              UNIQUE,
  value    BYTEA        NOT NULL  DEFAULT '',
  exptime  TIMESTAMPTZ  NOT NULL
);
CREATE INDEX objectcacache_exptime ON objectcache (exptime);

CREATE TABLE transcache (
  tc_url       TEXT         NOT NULL  UNIQUE,
  tc_contents  TEXT         NOT NULL,
  tc_time      TIMESTAMPTZ  NOT NULL
);


CREATE TABLE logging (
  log_type        TEXT         NOT NULL,
  log_action      TEXT         NOT NULL,
  log_timestamp   TIMESTAMPTZ  NOT NULL,
  log_user        INTEGER                REFERENCES mwuser(user_id) ON DELETE SET NULL,
  log_namespace   SMALLINT     NOT NULL,
  log_title       TEXT         NOT NULL,
  log_comment     TEXT,
  log_params      TEXT
);
CREATE INDEX logging_type_name ON logging (log_type, log_timestamp);
CREATE INDEX logging_user_time ON logging (log_timestamp, log_user);
CREATE INDEX logging_page_time ON logging (log_namespace, log_title, log_timestamp);


CREATE TABLE trackbacks (
  tb_id     SERIAL   NOT NULL  PRIMARY KEY,
  tb_page   INTEGER            REFERENCES page(page_id) ON DELETE CASCADE,
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

ALTER TABLE page ADD titlevector tsvector;
CREATE INDEX ts2_page_title ON page USING gist(titlevector);
CREATE FUNCTION ts2_page_title() RETURNS TRIGGER LANGUAGE plpgsql AS
$mw$
BEGIN
IF TG_OP = 'INSERT' THEN
  NEW.titlevector = to_tsvector('default',NEW.page_title);
ELSIF NEW.page_title != OLD.page_title THEN
  NEW.titlevector := to_tsvector('default',NEW.page_title);
END IF;
RETURN NEW;
END;
$mw$;

CREATE TRIGGER ts2_page_title BEFORE INSERT OR UPDATE ON page
  FOR EACH ROW EXECUTE PROCEDURE ts2_page_title();


ALTER TABLE pagecontent ADD textvector tsvector;
CREATE INDEX ts2_page_text ON pagecontent USING gist(textvector);
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

CREATE FUNCTION add_interwiki (TEXT,INT,CHAR) RETURNS INT LANGUAGE SQL AS
$mw$
  INSERT INTO interwiki (iw_prefix, iw_url, iw_local) VALUES ($1,$2,$3);
  SELECT 1;
$mw$;

-- This table is not used unless profiling is turned on
CREATE TABLE profiling (
  pf_count   INTEGER         NOT NULL DEFAULT 0,
  pf_time    NUMERIC(18,10)  NOT NULL DEFAULT 0,
  pf_name    TEXT            NOT NULL,
  pf_server  TEXT            NULL
);
CREATE UNIQUE INDEX pf_name_server ON profiling (pf_name, pf_server);


CREATE TABLE mediawiki_version (
  type         TEXT         NOT NULL,
  mw_version   TEXT         NOT NULL,
  notes        TEXT             NULL,

  pg_version   TEXT             NULL,
  pg_dbname    TEXT             NULL,
  pg_user      TEXT             NULL,
  pg_port      TEXT             NULL,
  mw_schema    TEXT             NULL,
  ts2_schema   TEXT             NULL,
  ctype        TEXT             NULL,

  sql_version  TEXT             NULL,
  sql_date     TEXT             NULL,
  cdate        TIMESTAMPTZ  NOT NULL DEFAULT now()
);

INSERT INTO mediawiki_version (type,mw_version,sql_version,sql_date)
  VALUES ('Creation','??','$LastChangedRevision$','$LastChangedDate$');


COMMIT;
