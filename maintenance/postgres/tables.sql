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
  user_touched              TIMESTAMPTZ NOT NULL,
  user_registration         TIMESTAMPTZ,
  user_editcount            INTEGER,
  user_password_expires     TIMESTAMPTZ NULL
);
ALTER SEQUENCE user_user_id_seq OWNED BY mwuser.user_id;
CREATE UNIQUE INDEX user_name ON mwuser (user_name);
CREATE INDEX user_email_token ON mwuser (user_email_token);
CREATE INDEX user_email ON mwuser (user_email);

-- Create a dummy user to satisfy fk contraints especially with revisions
INSERT INTO mwuser
  VALUES (DEFAULT,'Anonymous','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,now(),now());

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
CREATE UNIQUE INDEX rev_page_id ON revision (rev_page, rev_id);
CREATE INDEX rev_timestamp      ON revision (rev_timestamp);
CREATE INDEX rev_actor_timestamp ON revision (rev_actor,rev_timestamp,rev_id);
CREATE INDEX rev_page_timestamp ON revision (rev_page,rev_timestamp);
CREATE INDEX rev_page_actor_timestamp ON revision (rev_page,rev_actor,rev_timestamp);


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
