--
-- Totally untested postgresql dump for the table "tables".
--
--
--

--
-- PostgreSQL database dump
--

SET client_encoding = 'UNICODE';
SET check_function_bodies = false;

SET SESSION AUTHORIZATION 'postgres';

--
-- TOC entry 4 (OID 2200)
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;

-- FIXME ! Either remove line or use the mediawiki database user there
SET SESSION AUTHORIZATION 'hashar';

SET search_path = public, pg_catalog;

--
-- TOC entry 5 (OID 17145)
-- Name: user_user_id_seq; Type: SEQUENCE; Schema: public; Owner: hashar
--

CREATE SEQUENCE user_user_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 9 (OID 17147)
-- Name: user; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE "user" (
    user_id integer DEFAULT nextval('user_user_id_seq'::text),
    user_name character varying(255) DEFAULT ''::character varying NOT NULL,
    user_real_name character varying(255) DEFAULT ''::character varying NOT NULL,
    user_rights text DEFAULT ''::text NOT NULL,
    user_password text DEFAULT ''::text NOT NULL,
    user_newpassword text DEFAULT ''::text NOT NULL,
    user_email text DEFAULT ''::text NOT NULL,
    user_options text DEFAULT ''::text NOT NULL,
    user_touched character(14) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 10 (OID 17161)
-- Name: user_newtalk; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE user_newtalk (
    user_id integer DEFAULT 0 NOT NULL,
    user_ip character varying(40) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 6 (OID 17167)
-- Name: cur_cur_id_seq; Type: SEQUENCE; Schema: public; Owner: hashar
--

CREATE SEQUENCE cur_cur_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 11 (OID 17169)
-- Name: cur; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE cur (
    cur_id integer DEFAULT nextval('cur_cur_id_seq'::text),
    cur_namespace smallint DEFAULT 0::smallint NOT NULL,
    cur_title character varying(255) DEFAULT ''::character varying NOT NULL,
    cur_text text DEFAULT ''::text NOT NULL,
    cur_comment text DEFAULT ''::text NOT NULL,
    cur_user integer DEFAULT 0 NOT NULL,
    cur_user_text character varying(255) DEFAULT ''::character varying NOT NULL,
    cur_timestamp character(14) DEFAULT ''::bpchar NOT NULL,
    cur_restrictions text DEFAULT ''::text NOT NULL,
    cur_counter bigint DEFAULT 0::bigint NOT NULL,
    cur_is_redirect smallint DEFAULT 0::smallint NOT NULL,
    cur_minor_edit smallint DEFAULT 0::smallint NOT NULL,
    cur_is_new smallint DEFAULT 0::smallint NOT NULL,
    cur_random double precision NOT NULL,
    cur_touched character(14) DEFAULT ''::bpchar NOT NULL,
    inverse_timestamp character(14) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 7 (OID 17191)
-- Name: old_old_id_seq; Type: SEQUENCE; Schema: public; Owner: hashar
--

CREATE SEQUENCE old_old_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 12 (OID 17193)
-- Name: old; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE "old" (
    old_id integer DEFAULT nextval('old_old_id_seq'::text),
    old_namespace smallint DEFAULT 0::smallint NOT NULL,
    old_title character varying(255) DEFAULT ''::character varying NOT NULL,
    old_text text DEFAULT ''::text NOT NULL,
    old_comment text DEFAULT ''::text NOT NULL,
    old_user integer DEFAULT 0 NOT NULL,
    old_user_text character varying(255) NOT NULL,
    old_timestamp character(14) DEFAULT ''::bpchar NOT NULL,
    old_minor_edit smallint DEFAULT 0::smallint NOT NULL,
    old_flags text DEFAULT ''::text NOT NULL,
    inverse_timestamp character(14) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 13 (OID 17208)
-- Name: archive; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE archive (
    ar_namespace smallint DEFAULT 0::smallint NOT NULL,
    ar_title character varying(255) DEFAULT ''::character varying NOT NULL,
    ar_text text DEFAULT ''::text NOT NULL,
    ar_comment text DEFAULT ''::text NOT NULL,
    ar_user integer DEFAULT 0 NOT NULL,
    ar_user_text character varying(255) NOT NULL,
    ar_timestamp character(14) DEFAULT ''::bpchar NOT NULL,
    ar_minor_edit smallint DEFAULT 0::smallint NOT NULL,
    ar_flags text DEFAULT ''::text NOT NULL
);


--
-- TOC entry 14 (OID 17221)
-- Name: links; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE links (
    l_from integer DEFAULT 0 NOT NULL,
    l_to integer DEFAULT 0 NOT NULL
);


--
-- TOC entry 15 (OID 17227)
-- Name: brokenlinks; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE brokenlinks (
    bl_from integer DEFAULT 0 NOT NULL,
    bl_to character varying(255) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 16 (OID 17233)
-- Name: imagelinks; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE imagelinks (
    il_from integer DEFAULT 0 NOT NULL,
    il_to character varying(255) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 17 (OID 17239)
-- Name: categorylinks; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE categorylinks (
    cl_from integer DEFAULT 0 NOT NULL,
    cl_to character varying(255) DEFAULT ''::character varying NOT NULL,
    cl_sortkey character varying(255) DEFAULT ''::character varying NOT NULL,
    cl_timestamp timestamp without time zone NOT NULL
);


--
-- TOC entry 18 (OID 17244)
-- Name: linkscc; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE linkscc (
    lcc_pageid integer NOT NULL,
    lcc_cacheobj text DEFAULT ''::text NOT NULL
);


--
-- TOC entry 19 (OID 17252)
-- Name: site_stats; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE site_stats (
    ss_row_id integer NOT NULL,
    ss_total_views bigint DEFAULT 0::bigint,
    ss_total_edits bigint DEFAULT 0::bigint,
    ss_good_articles bigint DEFAULT 0::bigint
);


--
-- TOC entry 20 (OID 17257)
-- Name: hitcounter; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE hitcounter (
    hc_id integer NOT NULL
);


--
-- TOC entry 8 (OID 17259)
-- Name: ipblocks_ipb_id_seq; Type: SEQUENCE; Schema: public; Owner: hashar
--

CREATE SEQUENCE ipblocks_ipb_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 21 (OID 17261)
-- Name: ipblocks; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE ipblocks (
    ipb_id integer DEFAULT nextval('ipblocks_ipb_id_seq'::text),
    ipb_address character varying(40) DEFAULT ''::character varying NOT NULL,
    ipb_user integer DEFAULT 0 NOT NULL,
    ipb_by integer DEFAULT 0 NOT NULL,
    ipb_reason text DEFAULT ''::text NOT NULL,
    ipb_timestamp character(14) DEFAULT ''::bpchar NOT NULL,
    ipb_auto smallint DEFAULT 0::smallint NOT NULL,
    ipb_expiry character(14) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 22 (OID 17274)
-- Name: image; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE image (
    img_name character varying(255) DEFAULT ''::character varying NOT NULL,
    img_size integer DEFAULT 0 NOT NULL,
    img_description text DEFAULT ''::text NOT NULL,
    img_user integer DEFAULT 0 NOT NULL,
    img_user_text character varying(255) DEFAULT ''::character varying NOT NULL,
    img_timestamp character(14) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 23 (OID 17285)
-- Name: oldimage; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE oldimage (
    oi_name character varying(255) DEFAULT ''::character varying NOT NULL,
    oi_archive_name character varying(255) DEFAULT ''::character varying NOT NULL,
    oi_size integer DEFAULT 0 NOT NULL,
    oi_description text DEFAULT ''::text NOT NULL,
    oi_user integer DEFAULT 0 NOT NULL,
    oi_user_text character varying(255) DEFAULT ''::character varying NOT NULL,
    oi_timestamp character(14) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 24 (OID 17297)
-- Name: recentchanges; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE recentchanges (
    rc_timestamp character varying(14) DEFAULT ''::character varying NOT NULL,
    rc_cur_time character varying(14) DEFAULT ''::character varying NOT NULL,
    rc_user integer DEFAULT 0 NOT NULL,
    rc_user_text character varying(255) DEFAULT ''::character varying NOT NULL,
    rc_namespace smallint DEFAULT 0::smallint NOT NULL,
    rc_title character varying(255) DEFAULT ''::character varying NOT NULL,
    rc_comment character varying(255) DEFAULT ''::character varying NOT NULL,
    rc_minor smallint DEFAULT 0::smallint NOT NULL,
    rc_bot smallint DEFAULT 0::smallint NOT NULL,
    rc_new smallint DEFAULT 0::smallint NOT NULL,
    rc_cur_id integer DEFAULT 0 NOT NULL,
    rc_this_oldid integer DEFAULT 0 NOT NULL,
    rc_last_oldid integer DEFAULT 0 NOT NULL,
    rc_type smallint DEFAULT 0::smallint NOT NULL,
    rc_moved_to_ns smallint DEFAULT 0::smallint NOT NULL,
    rc_moved_to_title character varying(255) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 25 (OID 17318)
-- Name: watchlist; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE watchlist (
    wl_user integer NOT NULL,
    wl_namespace smallint DEFAULT 0::smallint NOT NULL,
    wl_title character varying(255) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 26 (OID 17322)
-- Name: math; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE math (
    math_inputhash character varying(16) NOT NULL,
    math_outputhash character varying(16) NOT NULL,
    math_html_conservativeness smallint NOT NULL,
    math_html text,
    math_mathml text
);


--
-- TOC entry 27 (OID 17327)
-- Name: searchindex; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE searchindex (
    si_page integer NOT NULL,
    si_title character varying(255) DEFAULT ''::character varying NOT NULL,
    si_text text DEFAULT ''::text NOT NULL
);


--
-- TOC entry 28 (OID 17334)
-- Name: interwiki; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE interwiki (
    iw_prefix character(32) NOT NULL,
    iw_url character(127) NOT NULL,
    iw_local boolean NOT NULL
);


--
-- TOC entry 29 (OID 17336)
-- Name: querycache; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE querycache (
    qc_type character(32) NOT NULL,
    qc_value integer DEFAULT 0 NOT NULL,
    qc_namespace smallint DEFAULT 0::smallint NOT NULL,
    qc_title character(255) DEFAULT ''::bpchar NOT NULL
);


--
-- TOC entry 30 (OID 17343)
-- Name: objectcache; Type: TABLE; Schema: public; Owner: hashar
--

CREATE TABLE objectcache (
    keyname character(255) DEFAULT ''::bpchar NOT NULL,
    value text,
    exptime timestamp without time zone NOT NULL
);


--
-- TOC entry 47 (OID 17351)
-- Name: math_inputhash_math_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX math_inputhash_math_index ON math USING btree (math_inputhash);


--
-- TOC entry 49 (OID 17352)
-- Name: iw_prefix_interwiki_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX iw_prefix_interwiki_index ON interwiki USING btree (iw_prefix);


--
-- TOC entry 44 (OID 17353)
-- Name: ss_row_id_site_stats_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX ss_row_id_site_stats_index ON site_stats USING btree (ss_row_id);


--
-- TOC entry 33 (OID 17354)
-- Name: old_id_old_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX old_id_old_index ON "old" USING btree (old_id);


--
-- TOC entry 36 (OID 17355)
-- Name: bl_from_brokenlinks_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX bl_from_brokenlinks_index ON brokenlinks USING btree (bl_from, bl_to);


--
-- TOC entry 45 (OID 17356)
-- Name: ipb_id_ipblocks_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX ipb_id_ipblocks_index ON ipblocks USING btree (ipb_id);


--
-- TOC entry 32 (OID 17357)
-- Name: cur_id_cur_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX cur_id_cur_index ON cur USING btree (cur_id);


--
-- TOC entry 38 (OID 17358)
-- Name: il_from_imagelinks_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX il_from_imagelinks_index ON imagelinks USING btree (il_from, il_to);


--
-- TOC entry 31 (OID 17359)
-- Name: user_id_user_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX user_id_user_index ON "user" USING btree (user_id);


--
-- TOC entry 48 (OID 17360)
-- Name: key_searchindex_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX key_searchindex_index ON searchindex USING btree (si_page);


--
-- TOC entry 51 (OID 17361)
-- Name: key_objectcache_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX key_objectcache_index ON objectcache USING btree (keyname);


--
-- TOC entry 46 (OID 17362)
-- Name: key_watchlist_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX key_watchlist_index ON watchlist USING btree (wl_user, wl_namespace, wl_title);


--
-- TOC entry 34 (OID 17363)
-- Name: l_from_links_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX l_from_links_index ON links USING btree (l_from, l_to);


--
-- TOC entry 40 (OID 17364)
-- Name: cl_from_categorylinks_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE UNIQUE INDEX cl_from_categorylinks_index ON categorylinks USING btree (cl_from, cl_to);


--
-- TOC entry 41 (OID 17365)
-- Name: cl_sortkey_categorylinks_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE INDEX cl_sortkey_categorylinks_index ON categorylinks USING btree (cl_to, cl_sortkey);


--
-- TOC entry 42 (OID 17366)
-- Name: cl_timestamp_categorylinks_index; Type: INDEX; Schema: public; Owner: hashar
--

CREATE INDEX cl_timestamp_categorylinks_index ON categorylinks USING btree (cl_to, cl_timestamp);


--
-- TOC entry 35 (OID 17225)
-- Name: links_pkey; Type: CONSTRAINT; Schema: public; Owner: hashar
--

ALTER TABLE ONLY links
    ADD CONSTRAINT links_pkey PRIMARY KEY (l_from, l_to);


--
-- TOC entry 37 (OID 17231)
-- Name: brokenlinks_pkey; Type: CONSTRAINT; Schema: public; Owner: hashar
--

ALTER TABLE ONLY brokenlinks
    ADD CONSTRAINT brokenlinks_pkey PRIMARY KEY (bl_to);


--
-- TOC entry 39 (OID 17237)
-- Name: imagelinks_pkey; Type: CONSTRAINT; Schema: public; Owner: hashar
--

ALTER TABLE ONLY imagelinks
    ADD CONSTRAINT imagelinks_pkey PRIMARY KEY (il_to);


--
-- TOC entry 43 (OID 17250)
-- Name: linkscc_pkey; Type: CONSTRAINT; Schema: public; Owner: hashar
--

ALTER TABLE ONLY linkscc
    ADD CONSTRAINT linkscc_pkey PRIMARY KEY (lcc_pageid);


--
-- TOC entry 50 (OID 17341)
-- Name: querycache_pkey; Type: CONSTRAINT; Schema: public; Owner: hashar
--

ALTER TABLE ONLY querycache
    ADD CONSTRAINT querycache_pkey PRIMARY KEY (qc_type, qc_value);


--
-- TOC entry 52 (OID 17349)
-- Name: objectcache_pkey; Type: CONSTRAINT; Schema: public; Owner: hashar
--

ALTER TABLE ONLY objectcache
    ADD CONSTRAINT objectcache_pkey PRIMARY KEY (exptime);


SET SESSION AUTHORIZATION 'postgres';

--
-- TOC entry 3 (OID 2200)
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';

