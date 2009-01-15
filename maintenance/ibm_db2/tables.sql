-- DB2

-- SQL to create the initial tables for the MediaWiki database.
-- This is read and executed by the install script; you should
-- not have to run it by itself unless doing a manual install.
-- This is the IBM DB2 version.
-- For information about each table, please see the notes in maintenance/tables.sql
-- Please make sure all dollar-quoting uses $mw$ at the start of the line
-- TODO: Change CHAR/SMALLINT to BOOL (still used in a non-bool fashion in PHP code)




CREATE SEQUENCE user_user_id_seq AS INTEGER START WITH 0 INCREMENT BY 1;
CREATE TABLE mwuser ( -- replace reserved word 'user'
  user_id                   INTEGER  NOT NULL PRIMARY KEY, -- DEFAULT nextval('user_user_id_seq'),
  user_name                 VARCHAR(255)     NOT NULL  UNIQUE,
  user_real_name            VARCHAR(255),
  user_password             clob(1K),
  user_newpassword          clob(1K),
  user_newpass_time         TIMESTAMP,
  user_token                VARCHAR(255),
  user_email                VARCHAR(255),
  user_email_token          VARCHAR(255),
  user_email_token_expires  TIMESTAMP,
  user_email_authenticated  TIMESTAMP,
  user_options              CLOB(64K),
  user_touched              TIMESTAMP,
  user_registration         TIMESTAMP,
  user_editcount            INTEGER
);
CREATE INDEX user_email_token_idx ON mwuser (user_email_token);

-- Create a dummy user to satisfy fk contraints especially with revisions
INSERT INTO mwuser
  VALUES (NEXTVAL FOR user_user_id_seq,'Anonymous','', NULL,NULL,CURRENT_TIMESTAMP,NULL, NULL,NULL,NULL,NULL, NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,0);

CREATE TABLE user_groups (
  ug_user   INTEGER    REFERENCES mwuser(user_id) ON DELETE CASCADE,
  ug_group  VARCHAR(255)     NOT NULL
);
CREATE UNIQUE INDEX user_groups_unique ON user_groups (ug_user, ug_group);

CREATE TABLE user_newtalk (
  user_id              INTEGER      NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE,
  user_ip              VARCHAR(255),
  user_last_timestamp  TIMESTAMP
);
CREATE INDEX user_newtalk_id_idx ON user_newtalk (user_id);
CREATE INDEX user_newtalk_ip_idx ON user_newtalk (user_ip);


CREATE SEQUENCE page_page_id_seq;
CREATE TABLE page (
  page_id            INTEGER        NOT NULL  PRIMARY KEY, -- DEFAULT NEXT VALUE FOR user_user_id_seq,
  page_namespace     SMALLINT       NOT NULL,
  page_title         VARCHAR(255)   NOT NULL,
  page_restrictions  clob(1K),
  page_counter       BIGINT         NOT NULL  DEFAULT 0,
  page_is_redirect   SMALLINT       NOT NULL  DEFAULT 0,
  page_is_new        SMALLINT       NOT NULL  DEFAULT 0,
  page_random        NUMERIC(15,14) NOT NULL,
  page_touched       TIMESTAMP,
  page_latest        INTEGER        NOT NULL, -- FK?
  page_len           INTEGER        NOT NULL
);
CREATE UNIQUE INDEX page_unique_name ON page (page_namespace, page_title);
--CREATE INDEX page_main_title         ON page (page_title) WHERE page_namespace = 0;
--CREATE INDEX page_talk_title         ON page (page_title) WHERE page_namespace = 1;
--CREATE INDEX page_user_title         ON page (page_title) WHERE page_namespace = 2;
--CREATE INDEX page_utalk_title        ON page (page_title) WHERE page_namespace = 3;
--CREATE INDEX page_project_title      ON page (page_title) WHERE page_namespace = 4;
CREATE INDEX page_random_idx         ON page (page_random);
CREATE INDEX page_len_idx            ON page (page_len);

--CREATE FUNCTION page_deleted() RETURNS TRIGGER LANGUAGE plpgsql AS
--$mw$
--BEGIN
--DELETE FROM recentchanges WHERE rc_namespace = OLD.page_namespace AND rc_title = OLD.page_title;
--RETURN NULL;
--END;
--$mw$;

--CREATE TRIGGER page_deleted AFTER DELETE ON page
--  FOR EACH ROW EXECUTE PROCEDURE page_deleted();

CREATE SEQUENCE rev_rev_id_val;
CREATE TABLE revision (
  rev_id          INTEGER      NOT NULL  UNIQUE, --DEFAULT nextval('rev_rev_id_val'),
  rev_page        INTEGER      REFERENCES page (page_id) ON DELETE CASCADE,
  rev_text_id     INTEGER, -- FK
  rev_comment     clob(1K),			-- changed from VARCHAR(255)
  rev_user        INTEGER      NOT NULL  REFERENCES mwuser(user_id) ON DELETE RESTRICT,
  rev_user_text   VARCHAR(255) NOT NULL,
  rev_timestamp   TIMESTAMP    NOT NULL,
  rev_minor_edit  SMALLINT     NOT NULL  DEFAULT 0,
  rev_deleted     SMALLINT     NOT NULL  DEFAULT 0,
  rev_len         INTEGER,
  rev_parent_id   INTEGER
);
CREATE UNIQUE INDEX revision_unique ON revision (rev_page, rev_id);
CREATE INDEX rev_text_id_idx        ON revision (rev_text_id);
CREATE INDEX rev_timestamp_idx      ON revision (rev_timestamp);
CREATE INDEX rev_user_idx           ON revision (rev_user);
CREATE INDEX rev_user_text_idx      ON revision (rev_user_text);


CREATE SEQUENCE text_old_id_val;
CREATE TABLE pagecontent ( -- replaces reserved word 'text'
  old_id     INTEGER  NOT NULL,
  --PRIMARY KEY DEFAULT nextval('text_old_id_val'),
  old_text   CLOB(16M),
  old_flags  clob(1K)
);

CREATE SEQUENCE pr_id_val;
CREATE TABLE page_restrictions (
  pr_id      INTEGER      NOT NULL  UNIQUE,
  --DEFAULT nextval('pr_id_val'),
  pr_page    INTEGER              NOT NULL
  --(used to be nullable)
    REFERENCES page (page_id) ON DELETE CASCADE,
  pr_type    VARCHAR(255)         NOT NULL,
  pr_level   VARCHAR(255)         NOT NULL,
  pr_cascade SMALLINT             NOT NULL,
  pr_user    INTEGER,
  pr_expiry  TIMESTAMP,
  PRIMARY KEY (pr_page, pr_type)
);
--ALTER TABLE page_restrictions ADD CONSTRAINT page_restrictions_pk PRIMARY KEY (pr_page,pr_type);

CREATE TABLE page_props (
  pp_page      INTEGER  NOT NULL  REFERENCES page (page_id) ON DELETE CASCADE,
  pp_propname  VARCHAR(255)     NOT NULL,
  pp_value     CLOB(64K)     NOT NULL,
  PRIMARY KEY (pp_page,pp_propname) 
);
--ALTER TABLE page_props ADD CONSTRAINT page_props_pk PRIMARY KEY (pp_page,pp_propname);
CREATE INDEX page_props_propname ON page_props (pp_propname);



CREATE TABLE archive (
  ar_namespace   SMALLINT     NOT NULL,
  ar_title       VARCHAR(255)         NOT NULL,
  ar_text        CLOB(16M),
  ar_page_id     INTEGER,
  ar_parent_id   INTEGER,
  ar_comment     clob(1K),
  ar_user        INTEGER  REFERENCES mwuser(user_id) ON DELETE SET NULL,
  ar_user_text   VARCHAR(255)         NOT NULL,
  ar_timestamp   TIMESTAMP  NOT NULL,
  ar_minor_edit  SMALLINT     NOT NULL  DEFAULT 0,
  ar_flags       clob(1K),
  ar_rev_id      INTEGER,
  ar_text_id     INTEGER,
  ar_deleted     SMALLINT     NOT NULL  DEFAULT 0,
  ar_len         INTEGER
);
CREATE INDEX archive_name_title_timestamp ON archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX archive_user_text            ON archive (ar_user_text);



CREATE TABLE redirect (
  rd_from       INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  rd_namespace  SMALLINT NOT NULL,
  rd_title      VARCHAR(255)     NOT NULL
);
CREATE INDEX redirect_ns_title ON redirect (rd_namespace,rd_title,rd_from);


CREATE TABLE pagelinks (
  pl_from       INTEGER   NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  pl_namespace  SMALLINT  NOT NULL,
  pl_title      VARCHAR(255)      NOT NULL
);
CREATE UNIQUE INDEX pagelink_unique ON pagelinks (pl_from,pl_namespace,pl_title);

CREATE TABLE templatelinks (
  tl_from       INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  tl_namespace  SMALLINT NOT NULL,
  tl_title      VARCHAR(255)     NOT NULL
);
CREATE UNIQUE INDEX templatelinks_unique ON templatelinks (tl_namespace,tl_title,tl_from);

CREATE TABLE imagelinks (
  il_from  INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  il_to    VARCHAR(255)     NOT NULL
);
CREATE UNIQUE INDEX il_from ON imagelinks (il_to,il_from);

CREATE TABLE categorylinks (
  cl_from       INTEGER      NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  cl_to         VARCHAR(255)         NOT NULL,
  cl_sortkey    VARCHAR(255),
  cl_timestamp  TIMESTAMP  NOT NULL
);
CREATE UNIQUE INDEX cl_from ON categorylinks (cl_from, cl_to);
CREATE INDEX cl_sortkey     ON categorylinks (cl_to, cl_sortkey, cl_from);



CREATE TABLE externallinks (
  el_from   INTEGER  NOT NULL  REFERENCES page(page_id) ON DELETE CASCADE,
  el_to     VARCHAR(255)     NOT NULL,
  el_index  VARCHAR(255)     NOT NULL
);
CREATE INDEX externallinks_from_to ON externallinks (el_from,el_to);
CREATE INDEX externallinks_index   ON externallinks (el_index);

CREATE TABLE langlinks (
  ll_from    INTEGER  NOT NULL  REFERENCES page (page_id) ON DELETE CASCADE,
  ll_lang    VARCHAR(255),
  ll_title   VARCHAR(255)
);
CREATE UNIQUE INDEX langlinks_unique ON langlinks (ll_from,ll_lang);
CREATE INDEX langlinks_lang_title    ON langlinks (ll_lang,ll_title);


CREATE TABLE site_stats (
  ss_row_id         INTEGER  NOT NULL  UNIQUE,
  ss_total_views    INTEGER            DEFAULT 0,
  ss_total_edits    INTEGER            DEFAULT 0,
  ss_good_articles  INTEGER             DEFAULT 0,
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
  ipb_id                INTEGER      NOT NULL  PRIMARY KEY,
  --DEFAULT nextval('ipblocks_ipb_id_val'),
  ipb_address           VARCHAR(255),
  ipb_user              INTEGER            REFERENCES mwuser(user_id) ON DELETE SET NULL,
  ipb_by                INTEGER      NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE,
  ipb_by_text           VARCHAR(255)         NOT NULL  DEFAULT '',
  ipb_reason            VARCHAR(255)         NOT NULL,
  ipb_timestamp         TIMESTAMP  NOT NULL,
  ipb_auto              SMALLINT     NOT NULL  DEFAULT 0,
  ipb_anon_only         SMALLINT     NOT NULL  DEFAULT 0,
  ipb_create_account    SMALLINT     NOT NULL  DEFAULT 1,
  ipb_enable_autoblock  SMALLINT     NOT NULL  DEFAULT 1,
  ipb_expiry            TIMESTAMP  NOT NULL,
  ipb_range_start       VARCHAR(255),
  ipb_range_end         VARCHAR(255),
  ipb_deleted           SMALLINT     NOT NULL  DEFAULT 0,
  ipb_block_email       SMALLINT     NOT NULL  DEFAULT 0

);
CREATE INDEX ipb_address ON ipblocks (ipb_address);
CREATE INDEX ipb_user    ON ipblocks (ipb_user);
CREATE INDEX ipb_range   ON ipblocks (ipb_range_start,ipb_range_end);



CREATE TABLE image (
  img_name         VARCHAR(255)      NOT NULL  PRIMARY KEY,
  img_size         INTEGER   NOT NULL,
  img_width        INTEGER   NOT NULL,
  img_height       INTEGER   NOT NULL,
  img_metadata     CLOB(16M)     NOT NULL  DEFAULT '',
  img_bits         SMALLINT,
  img_media_type   VARCHAR(255),
  img_major_mime   VARCHAR(255)                DEFAULT 'unknown',
  img_minor_mime   VARCHAR(255)                DEFAULT 'unknown',
  img_description  clob(1K)      NOT NULL	DEFAULT '',
  img_user         INTEGER         REFERENCES mwuser(user_id) ON DELETE SET NULL,
  img_user_text    VARCHAR(255)      NOT NULL DEFAULT '',
  img_timestamp    TIMESTAMP,
  img_sha1         VARCHAR(255)      NOT NULL  DEFAULT ''
);
CREATE INDEX img_size_idx      ON image (img_size);
CREATE INDEX img_timestamp_idx ON image (img_timestamp);
CREATE INDEX img_sha1          ON image (img_sha1);

CREATE TABLE oldimage (
  oi_name          VARCHAR(255)         NOT NULL,
  oi_archive_name  VARCHAR(255)         NOT NULL,
  oi_size          INTEGER      NOT NULL,
  oi_width         INTEGER      NOT NULL,
  oi_height        INTEGER      NOT NULL,
  oi_bits          SMALLINT     NOT NULL,
  oi_description   clob(1K),
  oi_user          INTEGER            REFERENCES mwuser(user_id) ON DELETE SET NULL,
  oi_user_text     VARCHAR(255)         NOT NULL,
  oi_timestamp     TIMESTAMP  NOT NULL,
  oi_metadata      CLOB(16M)        NOT NULL DEFAULT '',
  oi_media_type    VARCHAR(255)             ,
  oi_major_mime    VARCHAR(255)         NOT NULL DEFAULT 'unknown',
  oi_minor_mime    VARCHAR(255)         NOT NULL DEFAULT 'unknown',
  oi_deleted       SMALLINT     NOT NULL DEFAULT 0,
  oi_sha1          VARCHAR(255)         NOT NULL DEFAULT '',
  FOREIGN KEY (oi_name) REFERENCES image(img_name) ON DELETE CASCADE
);
--ALTER TABLE oldimage ADD CONSTRAINT oldimage_oi_name_fkey_cascade FOREIGN KEY (oi_name) REFERENCES image(img_name) ON DELETE CASCADE;
CREATE INDEX oi_name_timestamp    ON oldimage (oi_name,oi_timestamp);
CREATE INDEX oi_name_archive_name ON oldimage (oi_name,oi_archive_name);
CREATE INDEX oi_sha1              ON oldimage (oi_sha1);


CREATE SEQUENCE filearchive_fa_id_seq;
CREATE TABLE filearchive (
  fa_id                 INTEGER      NOT NULL PRIMARY KEY,
  --PRIMARY KEY DEFAULT nextval('filearchive_fa_id_seq'),
  fa_name               VARCHAR(255)         NOT NULL,
  fa_archive_name       VARCHAR(255),
  fa_storage_group      VARCHAR(255),
  fa_storage_key        VARCHAR(255),
  fa_deleted_user       INTEGER            REFERENCES mwuser(user_id) ON DELETE SET NULL,
  fa_deleted_timestamp  TIMESTAMP  NOT NULL,
  fa_deleted_reason     VARCHAR(255),
  fa_size               INTEGER      NOT NULL,
  fa_width              INTEGER      NOT NULL,
  fa_height             INTEGER      NOT NULL,
  fa_metadata           CLOB(16M)        NOT NULL  DEFAULT '',
  fa_bits               SMALLINT,
  fa_media_type         VARCHAR(255),
  fa_major_mime         VARCHAR(255)                   DEFAULT 'unknown',
  fa_minor_mime         VARCHAR(255)                   DEFAULT 'unknown',
  fa_description        clob(1K)         NOT NULL,
  fa_user               INTEGER            REFERENCES mwuser(user_id) ON DELETE SET NULL,
  fa_user_text          VARCHAR(255)         NOT NULL,
  fa_timestamp          TIMESTAMP,
  fa_deleted            SMALLINT     NOT NULL DEFAULT 0
);
CREATE INDEX fa_name_time ON filearchive (fa_name, fa_timestamp);
CREATE INDEX fa_dupe      ON filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX fa_notime    ON filearchive (fa_deleted_timestamp);
CREATE INDEX fa_nouser    ON filearchive (fa_deleted_user);

CREATE SEQUENCE rc_rc_id_seq;
CREATE TABLE recentchanges (
  rc_id              INTEGER      NOT NULL PRIMARY KEY,
  --PRIMARY KEY DEFAULT nextval('rc_rc_id_seq'),
  rc_timestamp       TIMESTAMP  NOT NULL,
  rc_cur_time        TIMESTAMP  NOT NULL,
  rc_user            INTEGER        REFERENCES mwuser(user_id) ON DELETE SET NULL,
  rc_user_text       VARCHAR(255)         NOT NULL,
  rc_namespace       SMALLINT     NOT NULL,
  rc_title           VARCHAR(255)         NOT NULL,
  rc_comment         VARCHAR(255),
  rc_minor           SMALLINT     NOT NULL  DEFAULT 0,
  rc_bot             SMALLINT     NOT NULL  DEFAULT 0,
  rc_new             SMALLINT     NOT NULL  DEFAULT 0,
  rc_cur_id          INTEGER            REFERENCES page(page_id) ON DELETE SET NULL,
  rc_this_oldid      INTEGER      NOT NULL,
  rc_last_oldid      INTEGER      NOT NULL,
  rc_type            SMALLINT     NOT NULL  DEFAULT 0,
  rc_moved_to_ns     SMALLINT,
  rc_moved_to_title  VARCHAR(255),
  rc_patrolled       SMALLINT     NOT NULL  DEFAULT 0,
  rc_ip              VARCHAR(255),	-- was CIDR type
  rc_old_len         INTEGER,
  rc_new_len         INTEGER,
  rc_deleted         SMALLINT     NOT NULL  DEFAULT 0,
  rc_logid           INTEGER      NOT NULL  DEFAULT 0,
  rc_log_type        VARCHAR(255),
  rc_log_action      VARCHAR(255),
  rc_params          CLOB(64K)
);
CREATE INDEX rc_timestamp       ON recentchanges (rc_timestamp);
CREATE INDEX rc_namespace_title ON recentchanges (rc_namespace, rc_title);
CREATE INDEX rc_cur_id          ON recentchanges (rc_cur_id);
CREATE INDEX new_name_timestamp ON recentchanges (rc_new, rc_namespace, rc_timestamp);
CREATE INDEX rc_ip              ON recentchanges (rc_ip);



CREATE TABLE watchlist (
  wl_user                   INTEGER     NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE,
  wl_namespace              SMALLINT    NOT NULL  DEFAULT 0,
  wl_title                  VARCHAR(255)        NOT NULL,
  wl_notificationtimestamp  TIMESTAMP
);
CREATE UNIQUE INDEX wl_user_namespace_title ON watchlist (wl_namespace, wl_title, wl_user);


CREATE TABLE math (
  math_inputhash              VARGRAPHIC(255)     NOT NULL  UNIQUE,
  math_outputhash             VARGRAPHIC(255)     NOT NULL,
  math_html_conservativeness  SMALLINT  NOT NULL,
  math_html                   VARCHAR(255),
  math_mathml                 VARCHAR(255)
);


CREATE TABLE interwiki (
  iw_prefix  VARCHAR(255)      NOT NULL  UNIQUE,
  iw_url     CLOB(64K)      NOT NULL,
  iw_local   SMALLINT  NOT NULL,
  iw_trans   SMALLINT  NOT NULL  DEFAULT 0
);


CREATE TABLE querycache (
  qc_type       VARCHAR(255)      NOT NULL,
  qc_value      INTEGER   NOT NULL,
  qc_namespace  SMALLINT  NOT NULL,
  qc_title      VARCHAR(255)      NOT NULL
);
CREATE INDEX querycache_type_value ON querycache (qc_type, qc_value);



CREATE  TABLE querycache_info (
  qci_type        VARCHAR(255)              UNIQUE NOT NULL,
  qci_timestamp  TIMESTAMP
);


CREATE TABLE querycachetwo (
  qcc_type           VARCHAR(255)     NOT NULL,
  qcc_value         INTEGER  NOT NULL  DEFAULT 0,
  qcc_namespace     INTEGER  NOT NULL  DEFAULT 0,
  qcc_title          VARCHAR(255)     NOT NULL  DEFAULT '',
  qcc_namespacetwo  INTEGER  NOT NULL  DEFAULT 0,
  qcc_titletwo       VARCHAR(255)     NOT NULL  DEFAULT ''
);
CREATE INDEX querycachetwo_type_value ON querycachetwo (qcc_type, qcc_value);
CREATE INDEX querycachetwo_title      ON querycachetwo (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX querycachetwo_titletwo   ON querycachetwo (qcc_type,qcc_namespacetwo,qcc_titletwo);

CREATE TABLE objectcache (
  keyname   VARCHAR(255)           NOT NULL        UNIQUE, -- was nullable
  value     CLOB(16M)                   NOT NULL  DEFAULT '',
  exptime  TIMESTAMP               NOT NULL
);
CREATE INDEX objectcacache_exptime ON objectcache (exptime);



CREATE TABLE transcache (
  tc_url       VARCHAR(255)         NOT NULL  UNIQUE,
  tc_contents  VARCHAR(255)         NOT NULL,
  tc_time      TIMESTAMP  NOT NULL
);

CREATE SEQUENCE log_log_id_seq;
CREATE TABLE logging (
  log_id          INTEGER      NOT NULL PRIMARY KEY,
  --PRIMARY KEY DEFAULT nextval('log_log_id_seq'),
  log_type        VARCHAR(255)         NOT NULL,
  log_action      VARCHAR(255)         NOT NULL,
  log_timestamp   TIMESTAMP  NOT NULL,
  log_user        INTEGER                REFERENCES mwuser(user_id) ON DELETE SET NULL,
  log_namespace   SMALLINT     NOT NULL,
  log_title       VARCHAR(255)         NOT NULL,
  log_comment     VARCHAR(255),
  log_params      CLOB(64K),
  log_deleted     SMALLINT     NOT NULL DEFAULT 0
);
CREATE INDEX logging_type_name ON logging (log_type, log_timestamp);
CREATE INDEX logging_user_time ON logging (log_timestamp, log_user);
CREATE INDEX logging_page_time ON logging (log_namespace, log_title, log_timestamp);

CREATE SEQUENCE trackbacks_tb_id_seq;
CREATE TABLE trackbacks (
  tb_id     INTEGER  NOT NULL PRIMARY KEY,
  --PRIMARY KEY DEFAULT nextval('trackbacks_tb_id_seq'),
  tb_page   INTEGER            REFERENCES page(page_id) ON DELETE CASCADE,
  tb_title  VARCHAR(255)     NOT NULL,
  tb_url    CLOB(64K)	     NOT NULL,
  tb_ex     VARCHAR(255),
  tb_name   VARCHAR(255)
);
CREATE INDEX trackback_page ON trackbacks (tb_page);


CREATE SEQUENCE job_job_id_seq;
CREATE TABLE job (
  job_id         INTEGER   NOT NULL PRIMARY KEY,
  --PRIMARY KEY DEFAULT nextval('job_job_id_seq'),
  job_cmd        VARCHAR(255)      NOT NULL,
  job_namespace  SMALLINT  NOT NULL,
  job_title      VARCHAR(255)      NOT NULL,
  job_params     CLOB(64K)      NOT NULL
);
CREATE INDEX job_cmd_namespace_title ON job (job_cmd, job_namespace, job_title);



-- Postgres' Tsearch2 dropped
--ALTER TABLE page ADD titlevector tsvector;
--CREATE FUNCTION ts2_page_title() RETURNS TRIGGER LANGUAGE plpgsql AS
--$mw$
--BEGIN
--IF TG_OP = 'INSERT' THEN
--  NEW.titlevector = to_tsvector('default',REPLACE(NEW.page_title,'/',' '));
--ELSIF NEW.page_title != OLD.page_title THEN
--  NEW.titlevector := to_tsvector('default',REPLACE(NEW.page_title,'/',' '));
--END IF;
--RETURN NEW;
--END;
--$mw$;

--CREATE TRIGGER ts2_page_title BEFORE INSERT OR UPDATE ON page
--  FOR EACH ROW EXECUTE PROCEDURE ts2_page_title();


--ALTER TABLE pagecontent ADD textvector tsvector;
--CREATE FUNCTION ts2_page_text() RETURNS TRIGGER LANGUAGE plpgsql AS
--$mw$
--BEGIN
--IF TG_OP = 'INSERT' THEN
--  NEW.textvector = to_tsvector('default',NEW.old_text);
--ELSIF NEW.old_text != OLD.old_text THEN
--  NEW.textvector := to_tsvector('default',NEW.old_text);
--END IF;
--RETURN NEW;
--END;
--$mw$;

--CREATE TRIGGER ts2_page_text BEFORE INSERT OR UPDATE ON pagecontent
--  FOR EACH ROW EXECUTE PROCEDURE ts2_page_text();

-- These are added by the setup script due to version compatibility issues
-- If using 8.1, we switch from "gin" to "gist"

--CREATE INDEX ts2_page_title ON page USING gin(titlevector);
--CREATE INDEX ts2_page_text ON pagecontent USING gin(textvector);

--TODO
--CREATE FUNCTION add_interwiki (TEXT,INT,SMALLINT) RETURNS INT LANGUAGE SQL AS
--$mw$
--  INSERT INTO interwiki (iw_prefix, iw_url, iw_local) VALUES ($1,$2,$3);
--  SELECT 1;
--$mw$;

-- hack implementation
-- should be replaced with OmniFind, Contains(), etc
CREATE TABLE searchindex (
  si_page int NOT NULL,
  si_title varchar(255) NOT NULL default '',
  si_text clob NOT NULL
);

-- This table is not used unless profiling is turned on
CREATE TABLE profiling (
  pf_count   INTEGER         NOT NULL DEFAULT 0,
  pf_time    NUMERIC(18,10)  NOT NULL DEFAULT 0,
  pf_memory  NUMERIC(18,10)  NOT NULL DEFAULT 0,
  pf_name    VARCHAR(255)            NOT NULL,
  pf_server  VARCHAR(255)            
);
CREATE UNIQUE INDEX pf_name_server ON profiling (pf_name, pf_server);

CREATE TABLE protected_titles (
  pt_namespace   SMALLINT    NOT NULL,
  pt_title       VARCHAR(255)        NOT NULL,
  pt_user        INTEGER       REFERENCES mwuser(user_id) ON DELETE SET NULL,
  pt_reason      clob(1K),
  pt_timestamp   TIMESTAMP NOT NULL,
  pt_expiry      TIMESTAMP     ,
  pt_create_perm VARCHAR(255)        NOT NULL DEFAULT ''
);
CREATE UNIQUE INDEX protected_titles_unique ON protected_titles(pt_namespace, pt_title);



CREATE TABLE updatelog (
  ul_key VARCHAR(255) NOT NULL PRIMARY KEY
);

CREATE SEQUENCE category_id_seq;
CREATE TABLE category (
  cat_id       INTEGER  NOT NULL PRIMARY KEY,
  --PRIMARY KEY DEFAULT nextval('category_id_seq'),
  cat_title    VARCHAR(255)     NOT NULL,
  cat_pages    INTEGER  NOT NULL  DEFAULT 0,
  cat_subcats  INTEGER  NOT NULL  DEFAULT 0,
  cat_files    INTEGER  NOT NULL  DEFAULT 0,
  cat_hidden   SMALLINT NOT NULL  DEFAULT 0
);
CREATE UNIQUE INDEX category_title ON category(cat_title);
CREATE INDEX category_pages ON category(cat_pages);

CREATE TABLE mediawiki_version (
  type         VARCHAR(255)         NOT NULL,
  mw_version   VARCHAR(255)         NOT NULL,
  notes        VARCHAR(255)         ,

  pg_version   VARCHAR(255)         ,
  pg_dbname    VARCHAR(255)         ,
  pg_user      VARCHAR(255)         ,
  pg_port      VARCHAR(255)         ,
  mw_schema    VARCHAR(255)         ,
  ts2_schema   VARCHAR(255)         ,
  ctype        VARCHAR(255)         ,

  sql_version  VARCHAR(255)         ,
  sql_date     VARCHAR(255)         ,
  cdate        TIMESTAMP  NOT NULL DEFAULT CURRENT TIMESTAMP
);

INSERT INTO mediawiki_version (type,mw_version,sql_version,sql_date)
  VALUES ('Creation','??','$LastChangedRevision: 34049 $','$LastChangedDate: 2008-04-30 10:20:36 -0400 (Wed, 30 Apr 2008) $');

