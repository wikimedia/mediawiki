-- Experimental table definitions for Microsoft SQL Server with
-- content-holding fields switched to explicit BINARY charset.
-- ------------------------------------------------------------

-- SQL to create the initial tables for the MediaWiki database.
-- This is read and executed by the install script; you should
-- not have to run it by itself unless doing a manual install.

--
-- General notes:
--
-- The comments in this and other files are
-- replaced with the defined table prefix by the installer
-- and updater scripts. If you are installing or running
-- updates manually, you will need to manually insert the
-- table prefix if any when running these scripts.
--


--
-- The user table contains basic account information,
-- authentication keys, etc.
--
-- Some multi-wiki sites may share a single central user table
-- between separate wikis using the $wgSharedDB setting.
--
-- Note that when a external authentication plugin is used,
-- user table entries still need to be created to store
-- preferences and to key tracking information in the other
-- tables.

-- LINE:53
CREATE TABLE /*$wgDBprefix*/user (
   user_id           INT           NOT NULL  PRIMARY KEY IDENTITY(0,1),
   user_name         NVARCHAR(255)  NOT NULL UNIQUE DEFAULT '',
   user_real_name    NVARCHAR(255)  NOT NULL DEFAULT '',
   user_password     NVARCHAR(255)  NOT NULL DEFAULT '',
   user_newpassword  NVARCHAR(255)  NOT NULL DEFAULT '',
   user_newpass_time DATETIME NULL,
   user_email        NVARCHAR(255)  NOT NULL DEFAULT '',
   user_options      NVARCHAR(MAX) NOT NULL DEFAULT '',
   user_touched      DATETIME      NOT NULL DEFAULT GETDATE(),
   user_token        NCHAR(32)      NOT NULL DEFAULT '',
   user_email_authenticated DATETIME DEFAULT NULL,
   user_email_token  NCHAR(32) DEFAULT '',
   user_email_token_expires DATETIME DEFAULT NULL,
   user_registration DATETIME DEFAULT NULL,
   user_editcount    INT NULL
);
CREATE        INDEX /*$wgDBprefix*/user_email_token ON /*$wgDBprefix*/[user](user_email_token);
CREATE UNIQUE INDEX /*$wgDBprefix*/[user_name]        ON /*$wgDBprefix*/[user]([user_name]);
;

--
-- User permissions have been broken out to a separate table;
-- this allows sites with a shared user table to have different
-- permissions assigned to a user in each project.
--
-- This table replaces the old user_rights field which used a
-- comma-separated blob.
CREATE TABLE /*$wgDBprefix*/user_groups (
   ug_user  INT     NOT NULL REFERENCES /*$wgDBprefix*/[user](user_id) ON DELETE CASCADE,
   ug_group NVARCHAR(16) NOT NULL DEFAULT '',
);
CREATE UNIQUE clustered INDEX /*$wgDBprefix*/user_groups_unique ON /*$wgDBprefix*/user_groups(ug_user, ug_group);
CREATE INDEX /*$wgDBprefix*/user_group ON /*$wgDBprefix*/user_groups(ug_group);

-- Stores notifications of user talk page changes, for the display
-- of the "you have new messages" box
-- Changed user_id column to mwuser_id to avoid clashing with user_id function
CREATE TABLE /*$wgDBprefix*/user_newtalk (
   user_id INT         NOT NULL DEFAULT 0 REFERENCES /*$wgDBprefix*/[user](user_id) ON DELETE CASCADE,
   user_ip NVARCHAR(40) NOT NULL DEFAULT '',
   user_last_timestamp DATETIME NOT NULL DEFAULT '',
);
CREATE INDEX /*$wgDBprefix*/user_group_id ON /*$wgDBprefix*/user_newtalk([user_id]);
CREATE INDEX /*$wgDBprefix*/user_ip       ON /*$wgDBprefix*/user_newtalk(user_ip);

-- 
-- User preferences and other fun stuff
-- replaces old user.user_options BLOB
-- 
CREATE TABLE /*$wgDBprefix*/user_properties (
	up_user INT NOT NULL,
	up_property NVARCHAR(32) NOT NULL,
	up_value NVARCHAR(MAX),
);
CREATE UNIQUE clustered INDEX /*$wgDBprefix*/user_props_user_prop ON /*$wgDBprefix*/user_properties(up_user, up_property);
CREATE INDEX /*$wgDBprefix*/user_props_prop ON /*$wgDBprefix*/user_properties(up_property);


--
-- Core of the wiki: each page has an entry here which identifies
-- it by title and contains some essential metadata.
--
CREATE TABLE /*$wgDBprefix*/page (
   page_id        INT          NOT NULL  PRIMARY KEY clustered IDENTITY,
   page_namespace INT          NOT NULL,
   page_title     NVARCHAR(255)  NOT NULL,
   page_restrictions NVARCHAR(255) NULL,
   page_counter BIGINT            NOT NULL DEFAULT 0,
   page_is_redirect BIT           NOT NULL DEFAULT 0,
   page_is_new BIT                NOT NULL DEFAULT 0,
   page_random NUMERIC(15,14)     NOT NULL DEFAULT RAND(),
   page_touched DATETIME NOT NULL DEFAULT GETDATE(),
   page_latest INT NOT NULL,
   page_len INT NOT NULL,
);
CREATE UNIQUE INDEX /*$wgDBprefix*/page_unique_name ON /*$wgDBprefix*/page(page_namespace, page_title);
CREATE        INDEX /*$wgDBprefix*/page_random_idx  ON /*$wgDBprefix*/page(page_random);
CREATE        INDEX /*$wgDBprefix*/page_len_idx     ON /*$wgDBprefix*/page(page_len);
;

--
-- Every edit of a page creates also a revision row.
-- This stores metadata about the revision, and a reference
-- to the TEXT storage backend.
--
CREATE TABLE /*$wgDBprefix*/revision (
   rev_id INT NOT NULL UNIQUE IDENTITY,
   rev_page INT NOT NULL,
   rev_text_id INT  NOT NULL,
   rev_comment NVARCHAR(max) NOT NULL,
   rev_user INT  NOT NULL DEFAULT 0 /*REFERENCES [user](user_id)*/,
   rev_user_text NVARCHAR(255) NOT NULL DEFAULT '',
   rev_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   rev_minor_edit BIT NOT NULL DEFAULT 0,
   rev_deleted BIT  NOT NULL DEFAULT 0,
   rev_len INT,
   rev_parent_id INT DEFAULT NULL,

);
CREATE UNIQUE clustered INDEX /*$wgDBprefix*/revision_unique ON /*$wgDBprefix*/revision(rev_page, rev_id);
CREATE UNIQUE INDEX /*$wgDBprefix*/rev_id             ON /*$wgDBprefix*/revision(rev_id);
CREATE        INDEX /*$wgDBprefix*/rev_timestamp      ON /*$wgDBprefix*/revision(rev_timestamp);
CREATE        INDEX /*$wgDBprefix*/page_timestamp     ON /*$wgDBprefix*/revision(rev_page, rev_timestamp);
CREATE        INDEX /*$wgDBprefix*/user_timestamp     ON /*$wgDBprefix*/revision(rev_user, rev_timestamp);
CREATE        INDEX /*$wgDBprefix*/usertext_timestamp ON /*$wgDBprefix*/revision(rev_user_text, rev_timestamp);
;

--
-- Holds TEXT of individual page revisions.
--
-- Field names are a holdover from the 'old' revisions table in
-- MediaWiki 1.4 and earlier: an upgrade will transform that
-- table INTo the 'text' table to minimize unnecessary churning
-- and downtime. If upgrading, the other fields will be left unused.
CREATE TABLE /*$wgDBprefix*/text (
   old_id INT NOT NULL  PRIMARY KEY clustered IDENTITY,
   old_text TEXT NOT NULL,
   old_flags NVARCHAR(255) NOT NULL,
);

--
-- Holding area for deleted articles, which may be viewed
-- or restored by admins through the Special:Undelete interface.
-- The fields generally correspond to the page, revision, and text
-- fields, with several caveats.
-- Cannot reasonably create views on this table, due to the presence of TEXT
-- columns. 
CREATE TABLE /*$wgDBprefix*/archive (
   ar_namespace SMALLINT NOT NULL DEFAULT 0,
   ar_title NVARCHAR(255) NOT NULL DEFAULT '',
   ar_text NVARCHAR(MAX) NOT NULL,
   ar_comment NVARCHAR(255) NOT NULL,
   ar_user INT NULL REFERENCES /*$wgDBprefix*/[user](user_id) ON DELETE SET NULL,
   ar_user_text NVARCHAR(255) NOT NULL,
   ar_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   ar_minor_edit BIT NOT NULL DEFAULT 0,
   ar_flags NVARCHAR(255) NOT NULL,
   ar_rev_id INT,
   ar_text_id INT,
   ar_deleted BIT NOT NULL DEFAULT 0,
   ar_len INT DEFAULT NULL,
   ar_page_id INT NULL,
   ar_parent_id INT NULL,
);
CREATE INDEX /*$wgDBprefix*/ar_name_title_timestamp ON /*$wgDBprefix*/archive(ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*$wgDBprefix*/ar_usertext_timestamp ON /*$wgDBprefix*/archive(ar_user_text,ar_timestamp);
CREATE INDEX /*$wgDBprefix*/ar_user_text    ON /*$wgDBprefix*/archive(ar_user_text);


--
-- Track page-to-page hyperlinks within the wiki.
--
CREATE TABLE /*$wgDBprefix*/pagelinks (
   pl_from INT NOT NULL DEFAULT 0 REFERENCES /*$wgDBprefix*/page(page_id) ON DELETE CASCADE,
   pl_namespace SMALLINT NOT NULL DEFAULT 0,
   pl_title NVARCHAR(255) NOT NULL DEFAULT '',
);
CREATE UNIQUE INDEX /*$wgDBprefix*/pl_from ON /*$wgDBprefix*/pagelinks(pl_from,pl_namespace,pl_title);
CREATE UNIQUE INDEX /*$wgDBprefix*/pl_namespace ON /*$wgDBprefix*/pagelinks(pl_namespace,pl_title,pl_from);

--
-- Track template inclusions.
--
CREATE TABLE /*$wgDBprefix*/templatelinks (
   tl_from INT NOT NULL DEFAULT 0 REFERENCES /*$wgDBprefix*/page(page_id) ON DELETE CASCADE,
   tl_namespace SMALLINT NOT NULL DEFAULT 0,
   tl_title NVARCHAR(255) NOT NULL DEFAULT '',
);
CREATE UNIQUE INDEX /*$wgDBprefix*/tl_from ON /*$wgDBprefix*/templatelinks(tl_from,tl_namespace,tl_title);
CREATE UNIQUE INDEX /*$wgDBprefix*/tl_namespace ON /*$wgDBprefix*/templatelinks(tl_namespace,tl_title,tl_from);

--
-- Track links to images *used inline*
-- We don't distinguish live from broken links here, so
-- they do not need to be changed ON upload/removal.
--
CREATE TABLE /*$wgDBprefix*/imagelinks (
   il_from INT NOT NULL DEFAULT 0 REFERENCES /*$wgDBprefix*/page(page_id) ON DELETE CASCADE,
   il_to NVARCHAR(255)  NOT NULL DEFAULT '',
   CONSTRAINT /*$wgDBprefix*/il_from PRIMARY KEY(il_from,il_to),
);
CREATE UNIQUE INDEX /*$wgDBprefix*/il_from_to ON /*$wgDBprefix*/imagelinks(il_from,il_to);
CREATE UNIQUE INDEX /*$wgDBprefix*/il_to_from ON /*$wgDBprefix*/imagelinks(il_to,il_from);

--
-- Track category inclusions *used inline*
-- This tracks a single level of category membership
-- (folksonomic tagging, really).
--
CREATE TABLE /*$wgDBprefix*/categorylinks (
   cl_from INT NOT NULL DEFAULT 0,
   cl_to NVARCHAR(255)  NOT NULL DEFAULT '',
   cl_sortkey NVARCHAR(150)  NOT NULL DEFAULT '',
   cl_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   CONSTRAINT /*$wgDBprefix*/cl_from PRIMARY KEY(cl_from, cl_to),
);
CREATE UNIQUE INDEX /*$wgDBprefix*/cl_from_to   ON /*$wgDBprefix*/categorylinks(cl_from,cl_to);
-- We always sort within a given category...
CREATE INDEX /*$wgDBprefix*/cl_sortkey   ON /*$wgDBprefix*/categorylinks(cl_to,cl_sortkey);
-- Not really used?
CREATE INDEX /*$wgDBprefix*/cl_timestamp ON /*$wgDBprefix*/categorylinks(cl_to,cl_timestamp);
--;

-- 
-- Track all existing categories.  Something is a category if 1) it has an en-
-- try somewhere in categorylinks, or 2) it once did.  Categories might not
-- have corresponding pages, so they need to be tracked separately.
--
CREATE TABLE /*$wgDBprefix*/category (
  cat_id int NOT NULL IDENTITY(1,1),
  cat_title nvarchar(255)  NOT NULL,
  cat_pages int NOT NULL default 0,
  cat_subcats int NOT NULL default 0,
  cat_files int NOT NULL default 0,
  cat_hidden tinyint NOT NULL default 0,
);

CREATE UNIQUE INDEX /*$wgDBprefix*/cat_title   ON /*$wgDBprefix*/category(cat_title);
-- For Special:Mostlinkedcategories
CREATE INDEX /*$wgDBprefix*/cat_pages   ON /*$wgDBprefix*/category(cat_pages);


CREATE TABLE /*$wgDBprefix*/change_tag (
  ct_rc_id   int  NOT NULL default 0,
  ct_log_id  int  NOT NULL default 0,
  ct_rev_id  int  NOT NULL default 0,
  ct_tag     varchar(255)  NOT NULL,
  ct_params  varchar(255)  NOT NULL,
);
CREATE UNIQUE INDEX /*$wgDBprefix*/change_tag_rc_tag ON /*$wgDBprefix*/change_tag(ct_rc_id,ct_tag);
CREATE UNIQUE INDEX /*$wgDBprefix*/change_tag_log_tag ON /*$wgDBprefix*/change_tag(ct_log_id,ct_tag);
CREATE UNIQUE INDEX /*$wgDBprefix*/change_tag_rev_tag ON /*$wgDBprefix*/change_tag(ct_rev_id,ct_tag);
CREATE INDEX /*$wgDBprefix*/change_tag_tag_id ON /*$wgDBprefix*/change_tag(ct_tag,ct_rc_id,ct_rev_id,ct_log_id);

CREATE TABLE /*$wgDBprefix*/tag_summary (
  ts_rc_id   INT NOT NULL default 0,
  ts_log_id  INT NOT NULL default 0,
  ts_rev_id  INT NOT NULL default 0,
  ts_tags    varchar(255)  NOT NULL
);
CREATE UNIQUE INDEX /*$wgDBprefix*/tag_summary_rc_id ON /*$wgDBprefix*/tag_summary(ts_rc_id);
CREATE UNIQUE INDEX /*$wgDBprefix*/tag_summary_log_id ON /*$wgDBprefix*/tag_summary(ts_log_id);
CREATE UNIQUE INDEX /*$wgDBprefix*/tag_summary_rev_id ON /*$wgDBprefix*/tag_summary(ts_rev_id);

CREATE TABLE /*$wgDBprefix*/valid_tag (
  vt_tag varchar(255) NOT NULL PRIMARY KEY
);

-- 
-- Table for storing localisation data
-- 
CREATE TABLE /*$wgDBprefix*/l10n_cache (
	-- language code
	lc_lang NVARCHAR(32) NOT NULL,
	
	-- cache key
	lc_key NVARCHAR(255) NOT NULL,
	
	-- Value
	lc_value TEXT NOT NULL DEFAULT '',
);
CREATE INDEX /*$wgDBprefix*/lc_lang_key ON /*$wgDBprefix*/l10n_cache (lc_lang, lc_key);

--
-- Track links to external URLs
-- IE >= 4 supports no more than 2083 characters in a URL
CREATE TABLE /*$wgDBprefix*/externallinks (
   el_from INT NOT NULL DEFAULT '0',
   el_to VARCHAR(2083) NOT NULL,
   el_index VARCHAR(896) NOT NULL,
);
-- Maximum key length ON SQL Server is 900 bytes
CREATE INDEX /*$wgDBprefix*/externallinks_index   ON /*$wgDBprefix*/externallinks(el_index);

-- 
-- Track external user accounts, if ExternalAuth is used
-- 
CREATE TABLE /*$wgDBprefix*/external_user (
	-- Foreign key to user_id
	eu_local_id INT NOT NULL PRIMARY KEY,
	-- opaque identifier provided by the external database
	eu_external_id NVARCHAR(255) NOT NULL,
);
CREATE UNIQUE INDEX /*$wgDBprefix*/eu_external_idx ON /*$wgDBprefix*/external_user(eu_external_id);

--
-- Track INTerlanguage links
--
CREATE TABLE /*$wgDBprefix*/langlinks (
   ll_from  INT          NOT NULL DEFAULT 0,
   ll_lang  NVARCHAR(20)  NOT NULL DEFAULT '',
   ll_title NVARCHAR(255)  NOT NULL DEFAULT '',
   CONSTRAINT /*$wgDBprefix*/langlinks_pk PRIMARY KEY(ll_from, ll_lang),
);
CREATE UNIQUE INDEX /*$wgDBprefix*/langlinks_reverse_key ON /*$wgDBprefix*/langlinks(ll_lang,ll_title);

-- 
-- Track inline interwiki links
-- 
CREATE TABLE /*$wgDBprefix*/iwlinks (
	-- page_id of the referring page
	iwl_from INT NOT NULL DEFAULT 0,
	
	-- Interwiki prefix code of the target
	iwl_prefix NVARCHAR(20) NOT NULL DEFAULT '',
	
	-- Title of the target, including namespace
	iwl_title NVARCHAR(255) NOT NULL DEFAULT '',
);

CREATE UNIQUE INDEX /*$wgDBprefix*/iwl_from ON /*$wgDBprefix*/iwlinks(iwl_from,iwl_prefix,iwl_title);
CREATE UNIQUE INDEX /*$wgDBprefix*/iwl_prefix ON /*$wgDBprefix*/iwlinks(iwl_prefix,iwl_title);


--
-- Contains a single row with some aggregate info
-- ON the state of the site.
--
CREATE TABLE /*$wgDBprefix*/site_stats (
   ss_row_id        INT  NOT NULL DEFAULT 1 PRIMARY KEY,
   ss_total_views   BIGINT DEFAULT 0,
   ss_total_edits   BIGINT DEFAULT 0,
   ss_good_articles BIGINT DEFAULT 0,
   ss_total_pages   BIGINT DEFAULT -1,
   ss_users         BIGINT DEFAULT -1,
   ss_active_users  BIGINT DEFAULT -1,
   ss_admins        INT    DEFAULT -1,
   ss_images INT DEFAULT 0,
);

-- INSERT INTO site_stats DEFAULT VALUES;

--
-- Stores an ID for every time any article is visited;
-- depending ON $wgHitcounterUpdateFreq, it is
-- periodically cleared and the page_counter column
-- in the page table updated for the all articles
-- that have been visited.)
--
CREATE TABLE /*$wgDBprefix*/hitcounter (
   hc_id BIGINT NOT NULL
);

--
-- The Internet is full of jerks, alas. Sometimes it's handy
-- to block a vandal or troll account.
--
CREATE TABLE /*$wgDBprefix*/ipblocks (
	ipb_id      INT NOT NULL  PRIMARY KEY,
	ipb_address NVARCHAR(255) NOT NULL,
	ipb_user    INT NOT NULL DEFAULT 0,
	ipb_by      INT NOT NULL DEFAULT 0,
	ipb_by_text NVARCHAR(255) NOT NULL DEFAULT '',
	ipb_reason  NVARCHAR(255) NOT NULL,
	ipb_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
	ipb_auto BIT NOT NULL DEFAULT 0,
	ipb_anon_only BIT NOT NULL DEFAULT 0,
	ipb_create_account BIT NOT NULL DEFAULT 1,
	ipb_enable_autoblock BIT NOT NULL DEFAULT 1,
	ipb_expiry DATETIME NOT NULL DEFAULT GETDATE(),
	ipb_range_start NVARCHAR(32) NOT NULL DEFAULT '',
	ipb_range_end NVARCHAR(32) NOT NULL DEFAULT '',
	ipb_deleted BIT NOT NULL DEFAULT 0,
	ipb_block_email BIT NOT NULL DEFAULT 0,
	ipb_allow_usertalk BIT NOT NULL DEFAULT 0,
);
-- Unique index to support "user already blocked" messages
-- Any new options which prevent collisions should be included
--UNIQUE INDEX ipb_address (ipb_address(255), ipb_user, ipb_auto, ipb_anon_only),
CREATE UNIQUE INDEX /*$wgDBprefix*/ipb_address   ON /*$wgDBprefix*/ipblocks(ipb_address, ipb_user, ipb_auto, ipb_anon_only);
CREATE        INDEX /*$wgDBprefix*/ipb_user      ON /*$wgDBprefix*/ipblocks(ipb_user);
CREATE        INDEX /*$wgDBprefix*/ipb_range     ON /*$wgDBprefix*/ipblocks(ipb_range_start, ipb_range_end);
CREATE        INDEX /*$wgDBprefix*/ipb_timestamp ON /*$wgDBprefix*/ipblocks(ipb_timestamp);
CREATE        INDEX /*$wgDBprefix*/ipb_expiry    ON /*$wgDBprefix*/ipblocks(ipb_expiry);
;

--
-- Uploaded images and other files.
CREATE TABLE /*$wgDBprefix*/image (
   img_name varchar(255) NOT NULL default '',
   img_size INT  NOT NULL DEFAULT 0,
   img_width INT NOT NULL DEFAULT 0,
   img_height INT NOT NULL DEFAULT 0,
   img_metadata TEXT NOT NULL, -- was MEDIUMBLOB
   img_bits SMALLINT NOT NULL DEFAULT 0,
   img_media_type NVARCHAR(MAX) DEFAULT 'UNKNOWN',
   img_major_mime NVARCHAR(MAX) DEFAULT 'UNKNOWN',
   img_minor_mime NVARCHAR(MAX) NOT NULL DEFAULT 'unknown',
   img_description NVARCHAR(MAX) NOT NULL,
   img_user INT NOT NULL DEFAULT 0,
   img_user_text VARCHAR(255) NOT NULL DEFAULT '',
   img_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   img_sha1 VARCHAR(255) NOT NULL default '',
);
-- Used by Special:Imagelist for sort-by-size
CREATE INDEX /*$wgDBprefix*/img_size ON /*$wgDBprefix*/[image](img_size);
-- Used by Special:Newimages and Special:Imagelist
CREATE INDEX /*$wgDBprefix*/img_timestamp ON /*$wgDBprefix*/[image](img_timestamp)
CREATE INDEX /*$wgDBprefix*/[img_sha1] ON /*wgDBprefix*/[image](img_sha1)

--
-- Previous revisions of uploaded files.
-- Awkwardly, image rows have to be moved into
-- this table at re-upload time.
--
CREATE TABLE /*$wgDBprefix*/oldimage (
   oi_name VARCHAR(255) NOT NULL DEFAULT '',
   oi_archive_name VARCHAR(255) NOT NULL DEFAULT '',
   oi_size INT NOT NULL DEFAULT 0,
   oi_width INT NOT NULL DEFAULT 0,
   oi_height INT NOT NULL DEFAULT 0,
   oi_bits SMALLINT NOT NULL DEFAULT 0,
   oi_description NVARCHAR(MAX) NOT NULL,
   oi_user INT NOT NULL DEFAULT 0,
   oi_user_text VARCHAR(255) NOT NULL DEFAULT '',
   oi_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   oi_metadata TEXT NOT NULL,
   oi_media_type NVARCHAR(MAX) DEFAULT 'UNKNOWN',
   oi_major_mime NVARCHAR(MAX) NOT NULL DEFAULT 'UNKNOWN',
   oi_minor_mime NVARCHAR(MAX) NOT NULL DEFAULT 'unknown',
   oi_deleted BIT NOT NULL default 0,
   oi_sha1 VARCHAR(255) NOT NULL default '',
);
CREATE INDEX /*$wgDBprefix*/oi_usertext_timestamp ON /*$wgDBprefix*/oldimage(oi_user_text,oi_timestamp);
CREATE INDEX /*$wgDBprefix*/oi_name_timestamp ON /*$wgDBprefix*/oldimage(oi_name, oi_timestamp);
CREATE INDEX /*$wgDBprefix*/oi_name_archive_name ON /*$wgDBprefix*/oldimage(oi_name,oi_archive_name);
CREATE INDEX /*$wgDBprefix*/[oi_sha1] ON /*$wgDBprefix*/oldimage(oi_sha1);

--
-- Record of deleted file data
--
CREATE TABLE /*$wgDBprefix*/filearchive (
   fa_id INT NOT NULL PRIMARY KEY,
   fa_name NVARCHAR(255)  NOT NULL DEFAULT '',
   fa_archive_name NVARCHAR(255)  DEFAULT '',
   fa_storage_group NVARCHAR(16),
   fa_storage_key NVARCHAR(64)  DEFAULT '',
   fa_deleted_user INT,
   fa_deleted_timestamp NVARCHAR(14) DEFAULT NULL,
   fa_deleted_reason NVARCHAR(255),
   fa_size SMALLINT  DEFAULT 0,
   fa_width SMALLINT DEFAULT 0,
   fa_height SMALLINT DEFAULT 0,
   fa_metadata NVARCHAR(MAX), -- was mediumblob
   fa_bits SMALLINT DEFAULT 0,
   fa_media_type NVARCHAR(11) DEFAULT NULL,
   fa_major_mime NVARCHAR(11) DEFAULT 'unknown',
   fa_minor_mime NVARCHAR(32) DEFAULT 'unknown',
   fa_description NVARCHAR(255),
   fa_user INT DEFAULT 0,
   fa_user_text NVARCHAR(255) DEFAULT '',
   fa_timestamp DATETIME DEFAULT GETDATE(),
   fa_deleted BIT NOT NULL DEFAULT 0,
);
-- Pick by image name
CREATE INDEX /*$wgDBprefix*/filearchive_name ON /*$wgDBprefix*/filearchive(fa_name,fa_timestamp);
-- Pick by dupe files
CREATE INDEX /*$wgDBprefix*/filearchive_dupe ON /*$wgDBprefix*/filearchive(fa_storage_group,fa_storage_key);
-- Pick by deletion time
CREATE INDEX /*$wgDBprefix*/filearchive_time ON /*$wgDBprefix*/filearchive(fa_deleted_timestamp);
-- Pick by deleter
CREATE INDEX /*$wgDBprefix*/filearchive_user ON /*$wgDBprefix*/filearchive(fa_deleted_user);

--
-- Primarily a summary table for Special:Recentchanges,
-- this table contains some additional info on edits from
-- the last few days, see Article::editUpdates()
--
CREATE TABLE /*$wgDBprefix*/recentchanges (
   rc_id INT NOT NULL,
   rc_timestamp DATETIME DEFAULT GETDATE(),
   rc_cur_time DATETIME DEFAULT GETDATE(),
   rc_user INT DEFAULT 0,
   rc_user_text NVARCHAR(255) DEFAULT '',
   rc_namespace SMALLINT DEFAULT 0,
   rc_title NVARCHAR(255)  DEFAULT '',
   rc_comment NVARCHAR(255) DEFAULT '',
   rc_minor BIT DEFAULT 0,
   rc_bot BIT DEFAULT 0,
   rc_new BIT DEFAULT 0,
   rc_cur_id INT DEFAULT 0,
   rc_this_oldid INT DEFAULT 0,
   rc_last_oldid INT DEFAULT 0,
   rc_type tinyint DEFAULT 0,
   rc_moved_to_ns BIT DEFAULT 0,
   rc_moved_to_title NVARCHAR(255)  DEFAULT '',
   rc_patrolled BIT DEFAULT 0,
   rc_ip NCHAR(40) DEFAULT '',
   rc_old_len INT DEFAULT 0,
   rc_new_len INT DEFAULT 0,
   rc_deleted BIT DEFAULT 0,
   rc_logid INT DEFAULT 0,
   rc_log_type NVARCHAR(255) NULL DEFAULT NULL,
   rc_log_action NVARCHAR(255) NULL DEFAULT NULL,
   rc_params NVARCHAR(MAX) DEFAULT '',
);
CREATE INDEX /*$wgDBprefix*/rc_timestamp       ON /*$wgDBprefix*/recentchanges(rc_timestamp);
CREATE INDEX /*$wgDBprefix*/rc_namespace_title ON /*$wgDBprefix*/recentchanges(rc_namespace, rc_title);
CREATE INDEX /*$wgDBprefix*/rc_cur_id          ON /*$wgDBprefix*/recentchanges(rc_cur_id);
CREATE INDEX /*$wgDBprefix*/new_name_timestamp ON /*$wgDBprefix*/recentchanges(rc_new,rc_namespace,rc_timestamp);
CREATE INDEX /*$wgDBprefix*/rc_ip              ON /*$wgDBprefix*/recentchanges(rc_ip);
CREATE INDEX /*$wgDBprefix*/rc_ns_usertext     ON /*$wgDBprefix*/recentchanges(rc_namespace, rc_user_text);
CREATE INDEX /*$wgDBprefix*/rc_user_text       ON /*$wgDBprefix*/recentchanges(rc_user_text, rc_timestamp);
;

CREATE TABLE /*$wgDBprefix*/watchlist (
   wl_user INT NOT NULL,
   wl_namespace SMALLINT NOT NULL DEFAULT 0,
   wl_title NVARCHAR(255)  NOT NULL DEFAULT '',
   wl_notificationtimestamp NVARCHAR(14) DEFAULT NULL,

);
CREATE UNIQUE INDEX /*$wgDBprefix*/namespace_title ON /*$wgDBprefix*/watchlist(wl_namespace,wl_title);

-- Needs fulltext index.
CREATE TABLE /*$wgDBprefix*/searchindex (
   si_page INT NOT NULL unique REFERENCES /*$wgDBprefix*/page(page_id) ON DELETE CASCADE,
   si_title varbinary(max) NOT NULL,
   si_text varbinary(max) NOT NULL,
   si_ext CHAR(4) NOT NULL DEFAULT '.txt',
);
CREATE FULLTEXT CATALOG wikidb AS DEFAULT;
CREATE UNIQUE CLUSTERED INDEX searchindex_page ON searchindex (si_page);
CREATE FULLTEXT INDEX on searchindex (si_title TYPE COLUMN si_ext, si_text  TYPE COLUMN si_ext)
KEY INDEX searchindex_page
;

-- This table is not used unless profiling is turned on
CREATE TABLE profiling (
  pf_count   INTEGER         NOT NULL DEFAULT 0,
  pf_time    NUMERIC(18,10)  NOT NULL DEFAULT 0,
  pf_name    NVARCHAR(200)            NOT NULL,
  pf_server  NVARCHAR(200)            NULL
);
CREATE UNIQUE INDEX pf_name_server ON profiling (pf_name, pf_server);

--
-- Recognized INTerwiki link prefixes
--
CREATE TABLE /*$wgDBprefix*/interwiki (
   iw_prefix NCHAR(32) NOT NULL PRIMARY KEY,
   iw_url NCHAR(127)   NOT NULL,
   iw_api TEXT NOT NULL DEFAULT '',
   iw_wikiid NVARCHAR(64) NOT NULL DEFAULT '',
   iw_local BIT NOT NULL,
   iw_trans BIT NOT NULL DEFAULT 0,
);

--
-- Used for caching expensive grouped queries
--
CREATE TABLE /*$wgDBprefix*/querycache (
   qc_type      NCHAR(32)  NOT NULL,
   qc_value     INT       NOT NULL DEFAULT '0',
   qc_namespace SMALLINT       NOT NULL DEFAULT 0,
   qc_title     NCHAR(255)  NOT NULL DEFAULT '',
   CONSTRAINT /*$wgDBprefix*/qc_pk PRIMARY KEY (qc_type,qc_value)
);

--
-- For a few generic cache operations if not using Memcached
--
CREATE TABLE /*$wgDBprefix*/objectcache (
   keyname NCHAR(255)  NOT NULL DEFAULT '',
   [value] NVARCHAR(MAX), -- IMAGE,
   exptime DATETIME, -- This is treated as a DATETIME
);
CREATE CLUSTERED INDEX /*$wgDBprefix*/[objectcache_time] ON /*$wgDBprefix*/objectcache(exptime);
CREATE UNIQUE INDEX /*$wgDBprefix*/[objectcache_PK] ON /*wgDBprefix*/objectcache(keyname);
--
-- Cache of INTerwiki transclusion
--
CREATE TABLE /*$wgDBprefix*/transcache (
   tc_url      NVARCHAR(255)  NOT NULL PRIMARY KEY,
   tc_contents NVARCHAR(MAX),
   tc_time     INT NOT NULL,
);

CREATE TABLE /*$wgDBprefix*/logging (
   log_id INT  PRIMARY KEY IDENTITY,
   log_type NCHAR(10) NOT NULL DEFAULT '',
   log_action NCHAR(10) NOT NULL DEFAULT '',
   log_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   log_user INT NOT NULL DEFAULT 0,
   log_user_text NVARCHAR(255) NOT NULL DEFAULT '',
   log_namespace INT NOT NULL DEFAULT 0,
   log_title NVARCHAR(255)  NOT NULL DEFAULT '',
   log_page INT NULL DEFAULT NULL,
   log_comment NVARCHAR(255) NOT NULL DEFAULT '',
   log_params NVARCHAR(MAX) NOT NULL,
   log_deleted BIT NOT NULL DEFAULT 0,
);
CREATE INDEX /*$wgDBprefix*/type_time ON /*$wgDBprefix*/logging (log_type, log_timestamp);
CREATE INDEX /*$wgDBprefix*/user_time ON /*$wgDBprefix*/logging (log_user, log_timestamp);
CREATE INDEX /*$wgDBprefix*/page_time ON /*$wgDBprefix*/logging (log_namespace, log_title, log_timestamp);
CREATE INDEX /*$wgDBprefix*/times ON /*$wgDBprefix*/logging (log_timestamp);
CREATE INDEX /*$wgDBprefix*/log_user_type_time ON /*$wgDBprefix*/logging (log_user, log_type, log_timestamp);
CREATE INDEX /*$wgDBprefix*/log_page_id_time ON /*$wgDBprefix*/logging (log_page,log_timestamp);

CREATE TABLE /*$wgDBprefix*/log_search (
	-- The type of ID (rev ID, log ID, rev timestamp, username)
	ls_field NVARCHAR(32) NOT NULL,
	-- The value of the ID
	ls_value NVARCHAR(255) NOT NULL,
	-- Key to log_id
	ls_log_id INT NOT NULL default 0,
);
CREATE UNIQUE INDEX /*$wgDBprefix*/ls_field_val ON /*$wgDBprefix*/log_search (ls_field,ls_value,ls_log_id);
CREATE INDEX /*$wgDBprefix*/ls_log_id ON /*$wgDBprefix*/log_search (ls_log_id);


-- Jobs performed by parallel apache threads or a command-line daemon
CREATE TABLE /*$wgDBprefix*/job (
   job_id INT NOT NULL  PRIMARY KEY,
   job_cmd NVARCHAR(200)  NOT NULL DEFAULT '',
   job_namespace INT NOT NULL,
   job_title NVARCHAR(200)  NOT NULL,
   job_params NVARCHAR(255)  NOT NULL,
);
CREATE INDEX /*$wgDBprefix*/job_idx ON /*$wgDBprefix*/job(job_cmd,job_namespace,job_title);

-- Details of updates to cached special pages
CREATE TABLE /*$wgDBprefix*/querycache_info (
   qci_type NVARCHAR(32) NOT NULL DEFAULT '' PRIMARY KEY,
   qci_timestamp NVARCHAR(14) NOT NULL DEFAULT '19700101000000',
);

-- For each redirect, this table contains exactly one row defining its target
CREATE TABLE /*$wgDBprefix*/redirect (
	rd_from INT NOT NULL DEFAULT 0 REFERENCES /*$wgDBprefix*/[page](page_id) ON DELETE CASCADE,
	rd_namespace SMALLINT NOT NULL DEFAULT '0',
	rd_title NVARCHAR(255)  NOT NULL DEFAULT '',
	rd_interwiki NVARCHAR(32) DEFAULT NULL,
	rd_fragment NVARCHAR(255) DEFAULT NULL,
);
CREATE UNIQUE INDEX /*$wgDBprefix*/rd_ns_title ON /*$wgDBprefix*/redirect(rd_namespace,rd_title,rd_from);

-- Used for caching expensive grouped queries that need two links (for example double-redirects)
CREATE TABLE /*$wgDBprefix*/querycachetwo (
   qcc_type NCHAR(32) NOT NULL,
   qcc_value INT NOT NULL DEFAULT 0,
   qcc_namespace INT NOT NULL DEFAULT 0,
   qcc_title NCHAR(255)  NOT NULL DEFAULT '',
   qcc_namespacetwo INT NOT NULL DEFAULT 0,
   qcc_titletwo NCHAR(255)  NOT NULL DEFAULT '',
   CONSTRAINT /*$wgDBprefix*/qcc_type PRIMARY KEY(qcc_type,qcc_value),
);
CREATE UNIQUE INDEX /*$wgDBprefix*/qcc_title    ON /*$wgDBprefix*/querycachetwo(qcc_type,qcc_namespace,qcc_title);
CREATE UNIQUE INDEX /*$wgDBprefix*/qcc_titletwo ON /*$wgDBprefix*/querycachetwo(qcc_type,qcc_namespacetwo,qcc_titletwo);


--- Used for storing page restrictions (i.e. protection levels)
CREATE TABLE /*$wgDBprefix*/page_restrictions (
   pr_page INT NOT NULL REFERENCES /*$wgDBprefix*/page(page_id) ON DELETE CASCADE,
   pr_type NVARCHAR(200) NOT NULL,
   pr_level NVARCHAR(200) NOT NULL,
   pr_cascade SMALLINT NOT NULL,
   pr_user INT NULL,
   pr_expiry DATETIME NULL,
   pr_id INT UNIQUE IDENTITY,
   CONSTRAINT /*$wgDBprefix*/pr_pagetype PRIMARY KEY(pr_page,pr_type),
);
CREATE INDEX /*$wgDBprefix*/pr_page      ON /*$wgDBprefix*/page_restrictions(pr_page);
CREATE INDEX /*$wgDBprefix*/pr_typelevel ON /*$wgDBprefix*/page_restrictions(pr_type,pr_level);
CREATE INDEX /*$wgDBprefix*/pr_pagelevel ON /*$wgDBprefix*/page_restrictions(pr_level);
CREATE INDEX /*$wgDBprefix*/pr_cascade   ON /*$wgDBprefix*/page_restrictions(pr_cascade);
;

-- Protected titles - nonexistent pages that have been protected
CREATE TABLE /*$wgDBprefix*/protected_titles (
  pt_namespace int NOT NULL,
  pt_title NVARCHAR(255) NOT NULL,
  pt_user int NOT NULL,
  pt_reason NVARCHAR(3555),
  pt_timestamp DATETIME NOT NULL,
  pt_expiry DATETIME NOT NULL default '',
  pt_create_perm NVARCHAR(60) NOT NULL,
  PRIMARY KEY (pt_namespace,pt_title),
);
CREATE INDEX /*$wgDBprefix*/pt_timestamp   ON /*$wgDBprefix*/protected_titles(pt_timestamp);
;

-- Name/value pairs indexed by page_id
CREATE TABLE /*$wgDBprefix*/page_props (
  pp_page int NOT NULL,
  pp_propname NVARCHAR(60) NOT NULL,
  pp_value NVARCHAR(MAX) NOT NULL,
  PRIMARY KEY (pp_page,pp_propname)
);

-- A table to log updates, one text key row per update.
CREATE TABLE /*$wgDBprefix*/updatelog (
  ul_key NVARCHAR(255) NOT NULL,
  PRIMARY KEY (ul_key)
);

-- NOTE To enable full text indexing on SQL 2008 you need to create an account FDH$MSSQLSERVER 
-- AND assign a password for the FDHOST process to run under
-- Once you have assigned a password to that account, you need to run the following stored procedure
-- replacing XXXXX with the password you used.
-- EXEC sp_fulltext_resetfdhostaccount @username = 'FDH$MSSQLSERVER', @password = 'XXXXXX' ;


--- Add the full-text capabilities, depricated in SQL Server 2005, FTS is enabled on all user created tables by default unless you are using SQL Server 2005 Express
--sp_fulltext_database 'enable';
--sp_fulltext_catalog 'WikiCatalog', 'create'
--sp_fulltext_table
--sp_fulltext_column
--sp_fulltext_table 'Articles', 'activate'
