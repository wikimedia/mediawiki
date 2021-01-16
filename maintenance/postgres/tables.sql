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
CREATE UNIQUE INDEX name_title       ON page (page_namespace, page_title);
CREATE INDEX page_main_title         ON page (page_title text_pattern_ops) WHERE page_namespace = 0;
CREATE INDEX page_talk_title         ON page (page_title text_pattern_ops) WHERE page_namespace = 1;
CREATE INDEX page_user_title         ON page (page_title text_pattern_ops) WHERE page_namespace = 2;
CREATE INDEX page_utalk_title        ON page (page_title text_pattern_ops) WHERE page_namespace = 3;
CREATE INDEX page_project_title      ON page (page_title text_pattern_ops) WHERE page_namespace = 4;
CREATE INDEX page_mediawiki_title    ON page (page_title text_pattern_ops) WHERE page_namespace = 8;
CREATE INDEX page_random             ON page (page_random);
CREATE INDEX page_len                ON page (page_len);
CREATE INDEX page_redirect_namespace_len ON page (page_is_redirect, page_namespace, page_len);

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
CREATE INDEX ipb_timestamp ON ipblocks (ipb_timestamp);
CREATE INDEX ipb_expiry ON ipblocks (ipb_expiry);
CREATE INDEX ipb_range   ON ipblocks (ipb_range_start,ipb_range_end);
CREATE INDEX ipb_parent_block_id   ON ipblocks (ipb_parent_block_id);

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
CREATE INDEX rc_namespace_title_timestamp ON recentchanges (rc_namespace, rc_title, rc_timestamp);
CREATE INDEX rc_cur_id          ON recentchanges (rc_cur_id);
CREATE INDEX new_name_timestamp ON recentchanges (rc_new, rc_namespace, rc_timestamp);
CREATE INDEX rc_ip              ON recentchanges (rc_ip);
CREATE INDEX rc_ns_actor        ON recentchanges (rc_namespace, rc_actor);
CREATE INDEX rc_actor           ON recentchanges (rc_actor, rc_timestamp);
CREATE INDEX rc_name_type_patrolled_timestamp ON recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);
CREATE INDEX rc_this_oldid      ON recentchanges (rc_this_oldid);


CREATE TABLE objectcache (
  keyname  TEXT                   UNIQUE,
  value    BYTEA        NOT NULL  DEFAULT '',
  exptime  TIMESTAMPTZ  NOT NULL
);
CREATE INDEX objectcacache_exptime ON objectcache (exptime);

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


ALTER TABLE text ADD textvector tsvector;
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

CREATE TRIGGER ts2_page_text BEFORE INSERT OR UPDATE ON text
  FOR EACH ROW EXECUTE PROCEDURE ts2_page_text();

CREATE INDEX ts2_page_title ON page USING gin(titlevector);
CREATE INDEX ts2_page_text ON text USING gin(textvector);
