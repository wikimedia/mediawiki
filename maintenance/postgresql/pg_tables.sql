-- $Id$
--
-- Database schema for MediaWiki PostgreSQL support
--
--

CREATE SCHEMA mediawiki;
SET search_path=mediawiki;

CREATE TABLE cur (
    cur_id serial PRIMARY KEY,
    cur_namespace smallint NOT NULL,
    cur_title varchar(255) NOT NULL,
    cur_text text NOT NULL,
    cur_comment text,
    cur_user integer DEFAULT 0 NOT NULL,
    cur_user_text varchar(255) DEFAULT ''::varchar NOT NULL,
    cur_timestamp timestamp without time zone NOT NULL,
    cur_restrictions text DEFAULT ''::text NOT NULL,
    cur_counter bigint DEFAULT 0 NOT NULL,
    cur_is_redirect smallint DEFAULT 0 NOT NULL,
    cur_minor_edit smallint DEFAULT 0 NOT NULL,
    cur_is_new smallint DEFAULT 0 NOT NULL,
    cur_random double precision DEFAULT random(),
    cur_touched timestamp without time zone,
    inverse_timestamp varchar(14)
);
CREATE INDEX cur_title_namespace_idx ON cur (cur_title, cur_namespace);
CREATE INDEX cur_random_idx ON cur (cur_random);
CREATE INDEX cur_name_title_timestamp_idx ON cur (cur_namespace, cur_title, cur_timestamp);
CREATE INDEX cur_timestamp_idx ON cur (cur_timestamp);

CREATE TABLE "old" (
    old_id serial PRIMARY KEY,
    old_namespace smallint NOT NULL,
    old_title varchar(255) NOT NULL,
    old_text text NOT NULL,
    old_comment text NOT NULL,
    old_user integer NOT NULL,
    old_user_text varchar(255) NOT NULL,
    old_timestamp timestamp without time zone NOT NULL,
    old_minor_edit smallint NOT NULL,
    old_flags text NOT NULL,
    inverse_timestamp varchar(14) NOT NULL
);
CREATE INDEX old_name_title_ts_idx ON "old" (old_namespace, old_title, old_timestamp);
CREATE INDEX old_timestamp ON "old" (old_timestamp);

CREATE TABLE brokenlinks (
    bl_from integer DEFAULT 0 NOT NULL,
    bl_to varchar(255) NOT NULL,
    PRIMARY KEY (bl_from,bl_to)

);
CREATE INDEX bl_to_idx ON brokenlinks (bl_to);

CREATE TABLE hitcounter (
    hc_id bigint DEFAULT 0 NOT NULL
);
CREATE INDEX hc_id_idx on hitcounter (hc_id);

CREATE TABLE image (
    img_name varchar(255) PRIMARY KEY,
    img_size integer NOT NULL,
    img_description text NOT NULL,
    img_user integer NOT NULL,
    img_user_text varchar(255) NOT NULL,
    img_timestamp timestamp without time zone
);
CREATE INDEX img_size_idx ON image (img_size);
CREATE INDEX img_timestamp ON image (img_timestamp);

CREATE TABLE imagelinks (
    il_from integer,
    il_to varchar(255),
    PRIMARY KEY (il_from, il_to)
);
CREATE INDEX il_to_idx ON imagelinks (il_to);


CREATE TABLE categorylinks (
    cl_from integer DEFAULT 0 NOT NULL,
    cl_to varchar(255) NOT NULL,
    cl_sortkey varchar(255) NOT NULL,
    cl_timestamp timestamp without time zone,
    PRIMARY KEY (cl_from,cl_to)
);
CREATE INDEX cl_to_sortkey_idx ON categorylinks (cl_to, cl_sortkey);
CREATE INDEX cl_to_timestamp ON categorylinks (cl_to, cl_timestamp);

CREATE TABLE links (
    l_from integer NOT NULL,
    l_to integer NOT NULL,
    PRIMARY KEY (l_from,l_to)
);
CREATE INDEX l_to_idx ON links (l_to);


CREATE TABLE linkscc (
    lcc_pageid integer PRIMARY KEY,
    lcc_title varchar(255) DEFAULT ''::character varying NOT NULL,
    lcc_cacheobj text NOT NULL
);
CREATE RULE links_del AS ON DELETE TO links DO DELETE FROM linkscc WHERE (linkscc.lcc_pageid = old.l_from);

CREATE TABLE searchindex (
    si_page integer PRIMARY KEY,
    si_title varchar(255) NOT NULL,
    si_text text NOT NULL
);

CREATE TABLE "user" (
    user_id serial PRIMARY KEY,
    user_name varchar(255) UNIQUE NOT NULL,
    user_real_name varchar(255) NOT NULL,
    user_rights text DEFAULT ''::text NOT NULL,
    user_password text DEFAULT ''::text NOT NULL,
    user_newpassword text DEFAULT ''::text NOT NULL,
    user_email text DEFAULT ''::text NOT NULL,
    user_options text DEFAULT ''::text NOT NULL,
    user_touched timestamp without time zone DEFAULT '1900-01-01 00:00:00'::timestamp without time zone NOT NULL,
    user_token char(32) DEFAULT '' NOT NULL
);


CREATE TABLE user_newtalk (
    user_id integer NOT NULL,
    user_ip inet NOT NULL
);
CREATE INDEX user_newtalk_id_idx ON user_newtalk (user_id);
CREATE INDEX user_newtalk_ip_idx ON user_newtalk (user_ip);

CREATE TABLE ipblocks (
    ipb_id serial PRIMARY KEY,
    ipb_address inet NOT NULL,
    ipb_user integer NOT NULL,
    ipb_by integer NOT NULL,
    ipb_reason text NOT NULL,
    ipb_timestamp timestamp without time zone NOT NULL,
    ipb_auto smallint NOT NULL,
    ipb_expiry timestamp without time zone NOT NULL
);
CREATE INDEX ipb_address_idx ON ipblocks (ipb_address);
CREATE INDEX ipb_user_idx ON ipblocks (ipb_user);

CREATE TABLE math (
    math_inputhash varchar(16) PRIMARY KEY,
    math_outputhash varchar(16) NOT NULL,
    math_html_conservativeness smallint NOT NULL,
    math_html text,
    math_mathml text
);

CREATE TABLE objectcache (
    keyname varchar(255) PRIMARY KEY,
    value text,
    exptime timestamp without time zone
);
CREATE INDEX oc_exptime ON objectcache (exptime);

CREATE TABLE archive (
    ar_namespace smallint NOT NULL,
    ar_title varchar(255) NOT NULL,
    ar_text text NOT NULL,
    ar_comment text NOT NULL,
    ar_user integer NOT NULL,
    ar_user_text varchar(255) NOT NULL,
    ar_timestamp timestamp without time zone NOT NULL,
    ar_minor_edit smallint NOT NULL,
    ar_flags text NOT NULL
);

CREATE TABLE recentchanges (
    rc_id serial PRIMARY KEY,
    rc_timestamp timestamp without time zone NOT NULL,
    rc_cur_time timestamp without time zone NOT NULL,
    rc_user integer NOT NULL,
    rc_user_text varchar(255) NOT NULL,
    rc_namespace smallint NOT NULL,
    rc_title varchar(255) NOT NULL,
    rc_comment text NOT NULL,
    rc_minor smallint NOT NULL,
    rc_bot smallint NOT NULL,
    rc_new smallint NOT NULL,
    rc_cur_id integer NOT NULL,
    rc_this_oldid integer NOT NULL,
    rc_last_oldid integer NOT NULL,
    rc_type smallint NOT NULL,
    rc_moved_to_ns smallint,
    rc_moved_to_title varchar,
    rc_ip inet,
    rc_patrolled smallint
);
CREATE INDEX rc_ip ON recentchanges (rc_ip);
CREATE INDEX rc_new_name_ts_idx ON recentchanges (rc_new, rc_namespace, rc_timestamp);
CREATE INDEX rc_cur_id_idx ON recentchanges (rc_cur_id);

CREATE TABLE site_stats (
    ss_row_id serial PRIMARY KEY,
    ss_total_views bigint NOT NULL,
    ss_total_edits bigint NOT NULL,
    ss_good_articles bigint NOT NULL
);

CREATE TABLE oldimage (
    oi_name varchar(255) NOT NULL,
    oi_archive_name varchar(255) NOT NULL,
    oi_size integer NOT NULL,
    oi_description text NOT NULL,
    oi_user integer NOT NULL,
    oi_user_text varchar(255) NOT NULL,
    oi_timestamp timestamp without time zone NOT NULL
);
CREATE INDEX oi_name_idx ON oldimage (oi_name);

CREATE TABLE querycache (
    qc_type char(32),
    qc_value integer,
    qc_namespace smallint,
    qc_title char(255)
);
CREATE INDEX qc_type_value_idx ON querycache (qc_type, qc_value);

CREATE TABLE watchlist (
    wl_user integer NOT NULL,
    wl_namespace smallint NOT NULL,
    wl_title varchar(255) NOT NULL,
    PRIMARY KEY (wl_user, wl_namespace, wl_title)
);
CREATE INDEX idx_wl_user ON watchlist (wl_user);
CREATE INDEX idx_wl_title ON watchlist (wl_title);

CREATE TABLE interwiki (
    iw_prefix char(32) PRIMARY KEY,
    iw_url varchar(127) NOT NULL,
    iw_local smallint NOT NULL
);

CREATE TABLE profiling (
    pf_count integer,
    pf_time double precision,
    pf_name varchar(255) PRIMARY KEY
);

CREATE TABLE validate (
    val_user integer DEFAULT 0 NOT NULL,
    val_title varchar(255) NOT NULL,
    val_timestamp timestamp without time zone NOT NULL,
    val_type integer DEFAULT 0 NOT NULL,
    val_value integer DEFAULT 0 NOT NULL,
    val_comment varchar(255) NOT NULL
);
CREATE INDEX val_user ON validate (val_user, val_title, val_timestamp);

CREATE TABLE user_rights (
    user_id integer PRIMARY KEY,
    user_rights text NOT NULL
);

CREATE TABLE logging (
    log_type character(10) NOT NULL,
    log_action character(10) NOT NULL,
    log_timestamp timestamp without time zone NOT NULL,
    log_user integer NOT NULL,
    log_namespace smallint NOT NULL,
    log_title character varying(255) NOT NULL,
    log_comment character varying(255) NOT NULL
);

CREATE INDEX log_type_time ON logging USING btree (log_type, log_timestamp);
CREATE INDEX log_user_time ON logging USING btree (log_user, log_timestamp);
CREATE INDEX log_page_time ON logging USING btree (log_namespace, log_title, log_timestamp);
