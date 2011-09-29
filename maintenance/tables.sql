-- SQL to create the initial tables for the MediaWiki database.
-- This is read and executed by the install script; you should
-- not have to run it by itself unless doing a manual install.

-- This is a shared schema file used for both MySQL and SQLite installs.

--
-- General notes:
--
-- If possible, create tables as InnoDB to benefit from the
-- superior resiliency against crashes and ability to read
-- during writes (and write during reads!)
--
-- Only the 'searchindex' table requires MyISAM due to the
-- requirement for fulltext index support, which is missing
-- from InnoDB.
--
--
-- The MySQL table backend for MediaWiki currently uses
-- 14-character BINARY or VARBINARY fields to store timestamps.
-- The format is YYYYMMDDHHMMSS, which is derived from the
-- text format of MySQL's TIMESTAMP fields.
--
-- Historically TIMESTAMP fields were used, but abandoned
-- in early 2002 after a lot of trouble with the fields
-- auto-updating.
--
-- The Postgres backend uses DATETIME fields for timestamps,
-- and we will migrate the MySQL definitions at some point as
-- well.
--
--
-- The /*_*/ comments in this and other files are
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
--
CREATE TABLE /*_*/user (
  user_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Usernames must be unique, must not be in the form of
  -- an IP address. _Shouldn't_ allow slashes or case
  -- conflicts. Spaces are allowed, and are _not_ converted
  -- to underscores like titles. See the User::newFromName() for
  -- the specific tests that usernames have to pass.
  user_name varchar(255) binary NOT NULL default '',

  -- Optional 'real name' to be displayed in credit listings
  user_real_name varchar(255) binary NOT NULL default '',

  -- Password hashes, see User::crypt() and User::comparePasswords()
  -- in User.php for the algorithm
  user_password tinyblob NOT NULL,

  -- When using 'mail me a new password', a random
  -- password is generated and the hash stored here.
  -- The previous password is left in place until
  -- someone actually logs in with the new password,
  -- at which point the hash is moved to user_password
  -- and the old password is invalidated.
  user_newpassword tinyblob NOT NULL,

  -- Timestamp of the last time when a new password was
  -- sent, for throttling and expiring purposes
  -- Emailed passwords will expire $wgNewPasswordExpiry
  -- (a week) after being set. If user_newpass_time is NULL
  -- (eg. created by mail) it doesn't expire.
  user_newpass_time binary(14),

  -- Note: email should be restricted, not public info.
  -- Same with passwords.
  user_email tinytext NOT NULL,

  -- This is a timestamp which is updated when a user
  -- logs in, logs out, changes preferences, or performs
  -- some other action requiring HTML cache invalidation
  -- to ensure that the UI is updated.
  user_touched binary(14) NOT NULL default '',

  -- A pseudorandomly generated value that is stored in
  -- a cookie when the "remember password" feature is
  -- used (previously, a hash of the password was used, but
  -- this was vulnerable to cookie-stealing attacks)
  user_token binary(32) NOT NULL default '',

  -- Initially NULL; when a user's e-mail address has been
  -- validated by returning with a mailed token, this is
  -- set to the current timestamp.
  user_email_authenticated binary(14),

  -- Randomly generated token created when the e-mail address
  -- is set and a confirmation test mail sent.
  user_email_token binary(32),

  -- Expiration date for the user_email_token
  user_email_token_expires binary(14),

  -- Timestamp of account registration.
  -- Accounts predating this schema addition may contain NULL.
  user_registration binary(14),

  -- Count of edits and edit-like actions.
  --
  -- *NOT* intended to be an accurate copy of COUNT(*) WHERE rev_user=user_id
  -- May contain NULL for old accounts if batch-update scripts haven't been
  -- run, as well as listing deleted edits and other myriad ways it could be
  -- out of sync.
  --
  -- Meant primarily for heuristic checks to give an impression of whether
  -- the account has been used much.
  --
  user_editcount int
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/user_name ON /*_*/user (user_name);
CREATE INDEX /*i*/user_email_token ON /*_*/user (user_email_token);
CREATE INDEX /*i*/user_email ON /*_*/user (user_email(50));


--
-- User permissions have been broken out to a separate table;
-- this allows sites with a shared user table to have different
-- permissions assigned to a user in each project.
--
-- This table replaces the old user_rights field which used a
-- comma-separated blob.
--
CREATE TABLE /*_*/user_groups (
  -- Key to user_id
  ug_user int unsigned NOT NULL default 0,

  -- Group names are short symbolic string keys.
  -- The set of group names is open-ended, though in practice
  -- only some predefined ones are likely to be used.
  --
  -- At runtime $wgGroupPermissions will associate group keys
  -- with particular permissions. A user will have the combined
  -- permissions of any group they're explicitly in, plus
  -- the implicit '*' and 'user' groups.
  ug_group varbinary(16) NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/ug_user_group ON /*_*/user_groups (ug_user,ug_group);
CREATE INDEX /*i*/ug_group ON /*_*/user_groups (ug_group);

-- Stores the groups the user has once belonged to.
-- The user may still belong these groups. Check user_groups.
CREATE TABLE /*_*/user_former_groups (
  -- Key to user_id
  ufg_user int unsigned NOT NULL default 0,
  ufg_group varbinary(16) NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/ufg_user_group ON /*_*/user_former_groups (ufg_user,ufg_group);

--
-- Stores notifications of user talk page changes, for the display
-- of the "you have new messages" box
--
CREATE TABLE /*_*/user_newtalk (
  -- Key to user.user_id
  user_id int NOT NULL default 0,
  -- If the user is an anonymous user their IP address is stored here
  -- since the user_id of 0 is ambiguous
  user_ip varbinary(40) NOT NULL default '',
  -- The highest timestamp of revisions of the talk page viewed
  -- by this user
  user_last_timestamp varbinary(14) NULL default NULL
) /*$wgDBTableOptions*/;

-- Indexes renamed for SQLite in 1.14
CREATE INDEX /*i*/un_user_id ON /*_*/user_newtalk (user_id);
CREATE INDEX /*i*/un_user_ip ON /*_*/user_newtalk (user_ip);


--
-- User preferences and perhaps other fun stuff. :)
-- Replaces the old user.user_options blob, with a couple nice properties:
--
-- 1) We only store non-default settings, so changes to the defauls
--    are now reflected for everybody, not just new accounts.
-- 2) We can more easily do bulk lookups, statistics, or modifications of
--    saved options since it's a sane table structure.
--
CREATE TABLE /*_*/user_properties (
  -- Foreign key to user.user_id
  up_user int NOT NULL,

  -- Name of the option being saved. This is indexed for bulk lookup.
  up_property varbinary(255) NOT NULL,

  -- Property value as a string.
  up_value blob
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/user_properties_user_property ON /*_*/user_properties (up_user,up_property);
CREATE INDEX /*i*/user_properties_property ON /*_*/user_properties (up_property);

--
-- Core of the wiki: each page has an entry here which identifies
-- it by title and contains some essential metadata.
--
CREATE TABLE /*_*/page (
  -- Unique identifier number. The page_id will be preserved across
  -- edits and rename operations, but not deletions and recreations.
  page_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- A page name is broken into a namespace and a title.
  -- The namespace keys are UI-language-independent constants,
  -- defined in includes/Defines.php
  page_namespace int NOT NULL,

  -- The rest of the title, as text.
  -- Spaces are transformed into underscores in title storage.
  page_title varchar(255) binary NOT NULL,

  -- Comma-separated set of permission keys indicating who
  -- can move or edit the page.
  page_restrictions tinyblob NOT NULL,

  -- Number of times this page has been viewed.
  page_counter bigint unsigned NOT NULL default 0,

  -- 1 indicates the article is a redirect.
  page_is_redirect tinyint unsigned NOT NULL default 0,

  -- 1 indicates this is a new entry, with only one edit.
  -- Not all pages with one edit are new pages.
  page_is_new tinyint unsigned NOT NULL default 0,

  -- Random value between 0 and 1, used for Special:Randompage
  page_random real unsigned NOT NULL,

  -- This timestamp is updated whenever the page changes in
  -- a way requiring it to be re-rendered, invalidating caches.
  -- Aside from editing this includes permission changes,
  -- creation or deletion of linked pages, and alteration
  -- of contained templates.
  page_touched binary(14) NOT NULL default '',

  -- Handy key to revision.rev_id of the current revision.
  -- This may be 0 during page creation, but that shouldn't
  -- happen outside of a transaction... hopefully.
  page_latest int unsigned NOT NULL,

  -- Uncompressed length in bytes of the page's current source text.
  page_len int unsigned NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/name_title ON /*_*/page (page_namespace,page_title);
CREATE INDEX /*i*/page_random ON /*_*/page (page_random);
CREATE INDEX /*i*/page_len ON /*_*/page (page_len);


--
-- Every edit of a page creates also a revision row.
-- This stores metadata about the revision, and a reference
-- to the text storage backend.
--
CREATE TABLE /*_*/revision (
  rev_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Key to page_id. This should _never_ be invalid.
  rev_page int unsigned NOT NULL,

  -- Key to text.old_id, where the actual bulk text is stored.
  -- It's possible for multiple revisions to use the same text,
  -- for instance revisions where only metadata is altered
  -- or a rollback to a previous version.
  rev_text_id int unsigned NOT NULL,

  -- Text comment summarizing the change.
  -- This text is shown in the history and other changes lists,
  -- rendered in a subset of wiki markup by Linker::formatComment()
  rev_comment tinyblob NOT NULL,

  -- Key to user.user_id of the user who made this edit.
  -- Stores 0 for anonymous edits and for some mass imports.
  rev_user int unsigned NOT NULL default 0,

  -- Text username or IP address of the editor.
  rev_user_text varchar(255) binary NOT NULL default '',

  -- Timestamp
  rev_timestamp binary(14) NOT NULL default '',

  -- Records whether the user marked the 'minor edit' checkbox.
  -- Many automated edits are marked as minor.
  rev_minor_edit tinyint unsigned NOT NULL default 0,

  -- Not yet used; reserved for future changes to the deletion system.
  rev_deleted tinyint unsigned NOT NULL default 0,

  -- Length of this revision in bytes
  rev_len int unsigned,

  -- Key to revision.rev_id
  -- This field is used to add support for a tree structure (The Adjacency List Model)
  rev_parent_id int unsigned default NULL

) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;
-- In case tables are created as MyISAM, use row hints for MySQL <5.0 to avoid 4GB limit

CREATE UNIQUE INDEX /*i*/rev_page_id ON /*_*/revision (rev_page, rev_id);
CREATE INDEX /*i*/rev_timestamp ON /*_*/revision (rev_timestamp);
CREATE INDEX /*i*/page_timestamp ON /*_*/revision (rev_page,rev_timestamp);
CREATE INDEX /*i*/user_timestamp ON /*_*/revision (rev_user,rev_timestamp);
CREATE INDEX /*i*/usertext_timestamp ON /*_*/revision (rev_user_text,rev_timestamp);

--
-- Holds text of individual page revisions.
--
-- Field names are a holdover from the 'old' revisions table in
-- MediaWiki 1.4 and earlier: an upgrade will transform that
-- table into the 'text' table to minimize unnecessary churning
-- and downtime. If upgrading, the other fields will be left unused.
--
CREATE TABLE /*_*/text (
  -- Unique text storage key number.
  -- Note that the 'oldid' parameter used in URLs does *not*
  -- refer to this number anymore, but to rev_id.
  --
  -- revision.rev_text_id is a key to this column
  old_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Depending on the contents of the old_flags field, the text
  -- may be convenient plain text, or it may be funkily encoded.
  old_text mediumblob NOT NULL,

  -- Comma-separated list of flags:
  -- gzip: text is compressed with PHP's gzdeflate() function.
  -- utf8: text was stored as UTF-8.
  --       If $wgLegacyEncoding option is on, rows *without* this flag
  --       will be converted to UTF-8 transparently at load time.
  -- object: text field contained a serialized PHP object.
  --         The object either contains multiple versions compressed
  --         together to achieve a better compression ratio, or it refers
  --         to another row where the text can be found.
  old_flags tinyblob NOT NULL
) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=10240;
-- In case tables are created as MyISAM, use row hints for MySQL <5.0 to avoid 4GB limit


--
-- Holding area for deleted articles, which may be viewed
-- or restored by admins through the Special:Undelete interface.
-- The fields generally correspond to the page, revision, and text
-- fields, with several caveats.
--
CREATE TABLE /*_*/archive (
  ar_namespace int NOT NULL default 0,
  ar_title varchar(255) binary NOT NULL default '',

  -- Newly deleted pages will not store text in this table,
  -- but will reference the separately existing text rows.
  -- This field is retained for backwards compatibility,
  -- so old archived pages will remain accessible after
  -- upgrading from 1.4 to 1.5.
  -- Text may be gzipped or otherwise funky.
  ar_text mediumblob NOT NULL,

  -- Basic revision stuff...
  ar_comment tinyblob NOT NULL,
  ar_user int unsigned NOT NULL default 0,
  ar_user_text varchar(255) binary NOT NULL,
  ar_timestamp binary(14) NOT NULL default '',
  ar_minor_edit tinyint NOT NULL default 0,

  -- See ar_text note.
  ar_flags tinyblob NOT NULL,

  -- When revisions are deleted, their unique rev_id is stored
  -- here so it can be retained after undeletion. This is necessary
  -- to retain permalinks to given revisions after accidental delete
  -- cycles or messy operations like history merges.
  --
  -- Old entries from 1.4 will be NULL here, and a new rev_id will
  -- be created on undeletion for those revisions.
  ar_rev_id int unsigned,

  -- For newly deleted revisions, this is the text.old_id key to the
  -- actual stored text. To avoid breaking the block-compression scheme
  -- and otherwise making storage changes harder, the actual text is
  -- *not* deleted from the text table, merely hidden by removal of the
  -- page and revision entries.
  --
  -- Old entries deleted under 1.2-1.4 will have NULL here, and their
  -- ar_text and ar_flags fields will be used to create a new text
  -- row upon undeletion.
  ar_text_id int unsigned,

  -- rev_deleted for archives
  ar_deleted tinyint unsigned NOT NULL default 0,

  -- Length of this revision in bytes
  ar_len int unsigned,

  -- Reference to page_id. Useful for sysadmin fixing of large pages
  -- merged together in the archives, or for cleanly restoring a page
  -- at its original ID number if possible.
  --
  -- Will be NULL for pages deleted prior to 1.11.
  ar_page_id int unsigned,

  -- Original previous revision
  ar_parent_id int unsigned default NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/name_title_timestamp ON /*_*/archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*i*/ar_usertext_timestamp ON /*_*/archive (ar_user_text,ar_timestamp);
CREATE INDEX /*i*/ar_revid ON /*_*/archive (ar_rev_id);


--
-- Track page-to-page hyperlinks within the wiki.
--
CREATE TABLE /*_*/pagelinks (
  -- Key to the page_id of the page containing the link.
  pl_from int unsigned NOT NULL default 0,

  -- Key to page_namespace/page_title of the target page.
  -- The target page may or may not exist, and due to renames
  -- and deletions may refer to different page records as time
  -- goes by.
  pl_namespace int NOT NULL default 0,
  pl_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/pl_from ON /*_*/pagelinks (pl_from,pl_namespace,pl_title);
CREATE UNIQUE INDEX /*i*/pl_namespace ON /*_*/pagelinks (pl_namespace,pl_title,pl_from);


--
-- Track template inclusions.
--
CREATE TABLE /*_*/templatelinks (
  -- Key to the page_id of the page containing the link.
  tl_from int unsigned NOT NULL default 0,

  -- Key to page_namespace/page_title of the target page.
  -- The target page may or may not exist, and due to renames
  -- and deletions may refer to different page records as time
  -- goes by.
  tl_namespace int NOT NULL default 0,
  tl_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/tl_from ON /*_*/templatelinks (tl_from,tl_namespace,tl_title);
CREATE UNIQUE INDEX /*i*/tl_namespace ON /*_*/templatelinks (tl_namespace,tl_title,tl_from);


--
-- Track links to images *used inline*
-- We don't distinguish live from broken links here, so
-- they do not need to be changed on upload/removal.
--
CREATE TABLE /*_*/imagelinks (
  -- Key to page_id of the page containing the image / media link.
  il_from int unsigned NOT NULL default 0,

  -- Filename of target image.
  -- This is also the page_title of the file's description page;
  -- all such pages are in namespace 6 (NS_FILE).
  il_to varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/il_from ON /*_*/imagelinks (il_from,il_to);
CREATE UNIQUE INDEX /*i*/il_to ON /*_*/imagelinks (il_to,il_from);


--
-- Track category inclusions *used inline*
-- This tracks a single level of category membership
--
CREATE TABLE /*_*/categorylinks (
  -- Key to page_id of the page defined as a category member.
  cl_from int unsigned NOT NULL default 0,

  -- Name of the category.
  -- This is also the page_title of the category's description page;
  -- all such pages are in namespace 14 (NS_CATEGORY).
  cl_to varchar(255) binary NOT NULL default '',

  -- A binary string obtained by applying a sortkey generation algorithm
  -- (Collation::getSortKey()) to page_title, or cl_sortkey_prefix . "\n"
  -- . page_title if cl_sortkey_prefix is nonempty.
  cl_sortkey varbinary(230) NOT NULL default '',

  -- A prefix for the raw sortkey manually specified by the user, either via
  -- [[Category:Foo|prefix]] or {{defaultsort:prefix}}.  If nonempty, it's
  -- concatenated with a line break followed by the page title before the sortkey
  -- conversion algorithm is run.  We store this so that we can update
  -- collations without reparsing all pages.
  -- Note: If you change the length of this field, you also need to change
  -- code in LinksUpdate.php. See bug 25254.
  cl_sortkey_prefix varchar(255) binary NOT NULL default '',

  -- This isn't really used at present. Provided for an optional
  -- sorting method by approximate addition time.
  cl_timestamp timestamp NOT NULL,

  -- Stores $wgCategoryCollation at the time cl_sortkey was generated.  This
  -- can be used to install new collation versions, tracking which rows are not
  -- yet updated.  '' means no collation, this is a legacy row that needs to be
  -- updated by updateCollation.php.  In the future, it might be possible to
  -- specify different collations per category.
  cl_collation varbinary(32) NOT NULL default '',

  -- Stores whether cl_from is a category, file, or other page, so we can
  -- paginate the three categories separately.  This never has to be updated
  -- after the page is created, since none of these page types can be moved to
  -- any other.
  cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page'
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/cl_from ON /*_*/categorylinks (cl_from,cl_to);

-- We always sort within a given category, and within a given type.  FIXME:
-- Formerly this index didn't cover cl_type (since that didn't exist), so old
-- callers won't be using an index: fix this?
CREATE INDEX /*i*/cl_sortkey ON /*_*/categorylinks (cl_to,cl_type,cl_sortkey,cl_from);

-- Not really used?
CREATE INDEX /*i*/cl_timestamp ON /*_*/categorylinks (cl_to,cl_timestamp);

-- For finding rows with outdated collation
CREATE INDEX /*i*/cl_collation ON /*_*/categorylinks (cl_collation);

--
-- Track all existing categories.  Something is a category if 1) it has an en-
-- try somewhere in categorylinks, or 2) it once did.  Categories might not
-- have corresponding pages, so they need to be tracked separately.
--
CREATE TABLE /*_*/category (
  -- Primary key
  cat_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Name of the category, in the same form as page_title (with underscores).
  -- If there is a category page corresponding to this category, by definition,
  -- it has this name (in the Category namespace).
  cat_title varchar(255) binary NOT NULL,

  -- The numbers of member pages (including categories and media), subcatego-
  -- ries, and Image: namespace members, respectively.  These are signed to
  -- make underflow more obvious.  We make the first number include the second
  -- two for better sorting: subtracting for display is easy, adding for order-
  -- ing is not.
  cat_pages int signed NOT NULL default 0,
  cat_subcats int signed NOT NULL default 0,
  cat_files int signed NOT NULL default 0,

  -- Reserved for future use
  cat_hidden tinyint unsigned NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/cat_title ON /*_*/category (cat_title);

-- For Special:Mostlinkedcategories
CREATE INDEX /*i*/cat_pages ON /*_*/category (cat_pages);


--
-- Track links to external URLs
--
CREATE TABLE /*_*/externallinks (
  -- page_id of the referring page
  el_from int unsigned NOT NULL default 0,

  -- The URL
  el_to blob NOT NULL,

  -- In the case of HTTP URLs, this is the URL with any username or password
  -- removed, and with the labels in the hostname reversed and converted to
  -- lower case. An extra dot is added to allow for matching of either
  -- example.com or *.example.com in a single scan.
  -- Example:
  --      http://user:password@sub.example.com/page.html
  --   becomes
  --      http://com.example.sub./page.html
  -- which allows for fast searching for all pages under example.com with the
  -- clause:
  --      WHERE el_index LIKE 'http://com.example.%'
  el_index blob NOT NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/el_from ON /*_*/externallinks (el_from, el_to(40));
CREATE INDEX /*i*/el_to ON /*_*/externallinks (el_to(60), el_from);
CREATE INDEX /*i*/el_index ON /*_*/externallinks (el_index(60));


--
-- Track external user accounts, if ExternalAuth is used
--
CREATE TABLE /*_*/external_user (
  -- Foreign key to user_id
  eu_local_id int unsigned NOT NULL PRIMARY KEY,

  -- Some opaque identifier provided by the external database
  eu_external_id varchar(255) binary NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/eu_external_id ON /*_*/external_user (eu_external_id);


--
-- Track interlanguage links
--
CREATE TABLE /*_*/langlinks (
  -- page_id of the referring page
  ll_from int unsigned NOT NULL default 0,

  -- Language code of the target
  ll_lang varbinary(20) NOT NULL default '',

  -- Title of the target, including namespace
  ll_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/ll_from ON /*_*/langlinks (ll_from, ll_lang);
CREATE INDEX /*i*/ll_lang ON /*_*/langlinks (ll_lang, ll_title);


--
-- Track inline interwiki links
--
CREATE TABLE /*_*/iwlinks (
  -- page_id of the referring page
  iwl_from int unsigned NOT NULL default 0,

  -- Interwiki prefix code of the target
  iwl_prefix varbinary(20) NOT NULL default '',

  -- Title of the target, including namespace
  iwl_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/iwl_from ON /*_*/iwlinks (iwl_from, iwl_prefix, iwl_title);
CREATE UNIQUE INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);


--
-- Contains a single row with some aggregate info
-- on the state of the site.
--
CREATE TABLE /*_*/site_stats (
  -- The single row should contain 1 here.
  ss_row_id int unsigned NOT NULL,

  -- Total number of page views, if hit counters are enabled.
  ss_total_views bigint unsigned default 0,

  -- Total number of edits performed.
  ss_total_edits bigint unsigned default 0,

  -- An approximate count of pages matching the following criteria:
  -- * in namespace 0
  -- * not a redirect
  -- * contains the text '[['
  -- See Article::isCountable() in includes/Article.php
  ss_good_articles bigint unsigned default 0,

  -- Total pages, theoretically equal to SELECT COUNT(*) FROM page; except faster
  ss_total_pages bigint default '-1',

  -- Number of users, theoretically equal to SELECT COUNT(*) FROM user;
  ss_users bigint default '-1',

  -- Number of users that still edit
  ss_active_users bigint default '-1',

  -- Deprecated, no longer updated as of 1.5
  ss_admins int default '-1',

  -- Number of images, equivalent to SELECT COUNT(*) FROM image
  ss_images int default 0
) /*$wgDBTableOptions*/;

-- Pointless index to assuage developer superstitions
CREATE UNIQUE INDEX /*i*/ss_row_id ON /*_*/site_stats (ss_row_id);


--
-- Stores an ID for every time any article is visited;
-- depending on $wgHitcounterUpdateFreq, it is
-- periodically cleared and the page_counter column
-- in the page table updated for all the articles
-- that have been visited.)
--
CREATE TABLE /*_*/hitcounter (
  hc_id int unsigned NOT NULL
) ENGINE=HEAP MAX_ROWS=25000;


--
-- The internet is full of jerks, alas. Sometimes it's handy
-- to block a vandal or troll account.
--
CREATE TABLE /*_*/ipblocks (
  -- Primary key, introduced for privacy.
  ipb_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Blocked IP address in dotted-quad form or user name.
  ipb_address tinyblob NOT NULL,

  -- Blocked user ID or 0 for IP blocks.
  ipb_user int unsigned NOT NULL default 0,

  -- User ID who made the block.
  ipb_by int unsigned NOT NULL default 0,

  -- User name of blocker
  ipb_by_text varchar(255) binary NOT NULL default '',

  -- Text comment made by blocker.
  ipb_reason tinyblob NOT NULL,

  -- Creation (or refresh) date in standard YMDHMS form.
  -- IP blocks expire automatically.
  ipb_timestamp binary(14) NOT NULL default '',

  -- Indicates that the IP address was banned because a banned
  -- user accessed a page through it. If this is 1, ipb_address
  -- will be hidden, and the block identified by block ID number.
  ipb_auto bool NOT NULL default 0,

  -- If set to 1, block applies only to logged-out users
  ipb_anon_only bool NOT NULL default 0,

  -- Block prevents account creation from matching IP addresses
  ipb_create_account bool NOT NULL default 1,

  -- Block triggers autoblocks
  ipb_enable_autoblock bool NOT NULL default '1',

  -- Time at which the block will expire.
  -- May be "infinity"
  ipb_expiry varbinary(14) NOT NULL default '',

  -- Start and end of an address range, in hexadecimal
  -- Size chosen to allow IPv6
  ipb_range_start tinyblob NOT NULL,
  ipb_range_end tinyblob NOT NULL,

  -- Flag for entries hidden from users and Sysops
  ipb_deleted bool NOT NULL default 0,

  -- Block prevents user from accessing Special:Emailuser
  ipb_block_email bool NOT NULL default 0,

  -- Block allows user to edit their own talk page
  ipb_allow_usertalk bool NOT NULL default 0

) /*$wgDBTableOptions*/;

-- Unique index to support "user already blocked" messages
-- Any new options which prevent collisions should be included
CREATE UNIQUE INDEX /*i*/ipb_address ON /*_*/ipblocks (ipb_address(255), ipb_user, ipb_auto, ipb_anon_only);

CREATE INDEX /*i*/ipb_user ON /*_*/ipblocks (ipb_user);
CREATE INDEX /*i*/ipb_range ON /*_*/ipblocks (ipb_range_start(8), ipb_range_end(8));
CREATE INDEX /*i*/ipb_timestamp ON /*_*/ipblocks (ipb_timestamp);
CREATE INDEX /*i*/ipb_expiry ON /*_*/ipblocks (ipb_expiry);


--
-- Uploaded images and other files.
--
CREATE TABLE /*_*/image (
  -- Filename.
  -- This is also the title of the associated description page,
  -- which will be in namespace 6 (NS_FILE).
  img_name varchar(255) binary NOT NULL default '' PRIMARY KEY,

  -- File size in bytes.
  img_size int unsigned NOT NULL default 0,

  -- For images, size in pixels.
  img_width int NOT NULL default 0,
  img_height int NOT NULL default 0,

  -- Extracted EXIF metadata stored as a serialized PHP array.
  img_metadata mediumblob NOT NULL,

  -- For images, bits per pixel if known.
  img_bits int NOT NULL default 0,

  -- Media type as defined by the MEDIATYPE_xxx constants
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,

  -- major part of a MIME media type as defined by IANA
  -- see http://www.iana.org/assignments/media-types/
  img_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart") NOT NULL default "unknown",

  -- minor part of a MIME media type as defined by IANA
  -- the minor parts are not required to adher to any standard
  -- but should be consistent throughout the database
  -- see http://www.iana.org/assignments/media-types/
  img_minor_mime varbinary(100) NOT NULL default "unknown",

  -- Description field as entered by the uploader.
  -- This is displayed in image upload history and logs.
  img_description tinyblob NOT NULL,

  -- user_id and user_name of uploader.
  img_user int unsigned NOT NULL default 0,
  img_user_text varchar(255) binary NOT NULL,

  -- Time of the upload.
  img_timestamp varbinary(14) NOT NULL default '',

  -- SHA-1 content hash in base-36
  img_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/img_usertext_timestamp ON /*_*/image (img_user_text,img_timestamp);
-- Used by Special:ListFiles for sort-by-size
CREATE INDEX /*i*/img_size ON /*_*/image (img_size);
-- Used by Special:Newimages and Special:ListFiles
CREATE INDEX /*i*/img_timestamp ON /*_*/image (img_timestamp);
-- Used in API and duplicate search
CREATE INDEX /*i*/img_sha1 ON /*_*/image (img_sha1);


--
-- Previous revisions of uploaded files.
-- Awkwardly, image rows have to be moved into
-- this table at re-upload time.
--
CREATE TABLE /*_*/oldimage (
  -- Base filename: key to image.img_name
  oi_name varchar(255) binary NOT NULL default '',

  -- Filename of the archived file.
  -- This is generally a timestamp and '!' prepended to the base name.
  oi_archive_name varchar(255) binary NOT NULL default '',

  -- Other fields as in image...
  oi_size int unsigned NOT NULL default 0,
  oi_width int NOT NULL default 0,
  oi_height int NOT NULL default 0,
  oi_bits int NOT NULL default 0,
  oi_description tinyblob NOT NULL,
  oi_user int unsigned NOT NULL default 0,
  oi_user_text varchar(255) binary NOT NULL,
  oi_timestamp binary(14) NOT NULL default '',

  oi_metadata mediumblob NOT NULL,
  oi_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  oi_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart") NOT NULL default "unknown",
  oi_minor_mime varbinary(100) NOT NULL default "unknown",
  oi_deleted tinyint unsigned NOT NULL default 0,
  oi_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/oi_usertext_timestamp ON /*_*/oldimage (oi_user_text,oi_timestamp);
CREATE INDEX /*i*/oi_name_timestamp ON /*_*/oldimage (oi_name,oi_timestamp);
-- oi_archive_name truncated to 14 to avoid key length overflow
CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name,oi_archive_name(14));
CREATE INDEX /*i*/oi_sha1 ON /*_*/oldimage (oi_sha1);


--
-- Record of deleted file data
--
CREATE TABLE /*_*/filearchive (
  -- Unique row id
  fa_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Original base filename; key to image.img_name, page.page_title, etc
  fa_name varchar(255) binary NOT NULL default '',

  -- Filename of archived file, if an old revision
  fa_archive_name varchar(255) binary default '',

  -- Which storage bin (directory tree or object store) the file data
  -- is stored in. Should be 'deleted' for files that have been deleted;
  -- any other bin is not yet in use.
  fa_storage_group varbinary(16),

  -- SHA-1 of the file contents plus extension, used as a key for storage.
  -- eg 8f8a562add37052a1848ff7771a2c515db94baa9.jpg
  --
  -- If NULL, the file was missing at deletion time or has been purged
  -- from the archival storage.
  fa_storage_key varbinary(64) default '',

  -- Deletion information, if this file is deleted.
  fa_deleted_user int,
  fa_deleted_timestamp binary(14) default '',
  fa_deleted_reason text,

  -- Duped fields from image
  fa_size int unsigned default 0,
  fa_width int default 0,
  fa_height int default 0,
  fa_metadata mediumblob,
  fa_bits int default 0,
  fa_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  fa_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart") default "unknown",
  fa_minor_mime varbinary(100) default "unknown",
  fa_description tinyblob,
  fa_user int unsigned default 0,
  fa_user_text varchar(255) binary,
  fa_timestamp binary(14) default '',

  -- Visibility of deleted revisions, bitfield
  fa_deleted tinyint unsigned NOT NULL default 0
) /*$wgDBTableOptions*/;

-- pick out by image name
CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
-- pick out dupe files
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive (fa_storage_group, fa_storage_key);
-- sort by deletion time
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
-- sort by uploader
CREATE INDEX /*i*/fa_user_timestamp ON /*_*/filearchive (fa_user_text,fa_timestamp);


--
-- Store information about newly uploaded files before they're
-- moved into the actual filestore
--
CREATE TABLE /*_*/uploadstash (
	us_id int unsigned NOT NULL PRIMARY KEY auto_increment,

	-- the user who uploaded the file.
	us_user int unsigned NOT NULL,

	-- file key. this is how applications actually search for the file.
	-- this might go away, or become the primary key.
	us_key varchar(255) NOT NULL,

	-- the original path
	us_orig_path varchar(255) NOT NULL,

	-- the temporary path at which the file is actually stored
	us_path varchar(255) NOT NULL,

	-- which type of upload the file came from (sometimes)
	us_source_type varchar(50),

	-- the date/time on which the file was added
	us_timestamp varbinary(14) not null,

	us_status varchar(50) not null,

	-- file properties from File::getPropsFromPath.  these may prove unnecessary.
	--
	us_size int unsigned NOT NULL,
	-- this hash comes from File::sha1Base36(), and is 31 characters
	us_sha1 varchar(31) NOT NULL,
	us_mime varchar(255),
	-- Media type as defined by the MEDIATYPE_xxx constants, should duplicate definition in the image table
  	us_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
	-- image-specific properties
	us_image_width int unsigned,
	us_image_height int unsigned,
	us_image_bits smallint unsigned

) /*$wgDBTableOptions*/;

-- sometimes there's a delete for all of a user's stuff.
CREATE INDEX /*i*/us_user ON /*_*/uploadstash (us_user);
-- pick out files by key, enforce key uniqueness
CREATE UNIQUE INDEX /*i*/us_key ON /*_*/uploadstash (us_key);
-- the abandoned upload cleanup script needs this
CREATE INDEX /*i*/us_timestamp ON /*_*/uploadstash (us_timestamp);


--
-- Primarily a summary table for Special:Recentchanges,
-- this table contains some additional info on edits from
-- the last few days, see Article::editUpdates()
--
CREATE TABLE /*_*/recentchanges (
  rc_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rc_timestamp varbinary(14) NOT NULL default '',
  rc_cur_time varbinary(14) NOT NULL default '',

  -- As in revision
  rc_user int unsigned NOT NULL default 0,
  rc_user_text varchar(255) binary NOT NULL,

  -- When pages are renamed, their RC entries do _not_ change.
  rc_namespace int NOT NULL default 0,
  rc_title varchar(255) binary NOT NULL default '',

  -- as in revision...
  rc_comment varchar(255) binary NOT NULL default '',
  rc_minor tinyint unsigned NOT NULL default 0,

  -- Edits by user accounts with the 'bot' rights key are
  -- marked with a 1 here, and will be hidden from the
  -- default view.
  rc_bot tinyint unsigned NOT NULL default 0,

  rc_new tinyint unsigned NOT NULL default 0,

  -- Key to page_id (was cur_id prior to 1.5).
  -- This will keep links working after moves while
  -- retaining the at-the-time name in the changes list.
  rc_cur_id int unsigned NOT NULL default 0,

  -- rev_id of the given revision
  rc_this_oldid int unsigned NOT NULL default 0,

  -- rev_id of the prior revision, for generating diff links.
  rc_last_oldid int unsigned NOT NULL default 0,

  -- These may no longer be used, with the new move log.
  rc_type tinyint unsigned NOT NULL default 0,
  rc_moved_to_ns tinyint unsigned NOT NULL default 0,
  rc_moved_to_title varchar(255) binary NOT NULL default '',

  -- If the Recent Changes Patrol option is enabled,
  -- users may mark edits as having been reviewed to
  -- remove a warning flag on the RC list.
  -- A value of 1 indicates the page has been reviewed.
  rc_patrolled tinyint unsigned NOT NULL default 0,

  -- Recorded IP address the edit was made from, if the
  -- $wgPutIPinRC option is enabled.
  rc_ip varbinary(40) NOT NULL default '',

  -- Text length in characters before
  -- and after the edit
  rc_old_len int,
  rc_new_len int,

  -- Visibility of recent changes items, bitfield
  rc_deleted tinyint unsigned NOT NULL default 0,

  -- Value corresonding to log_id, specific log entries
  rc_logid int unsigned NOT NULL default 0,
  -- Store log type info here, or null
  rc_log_type varbinary(255) NULL default NULL,
  -- Store log action or null
  rc_log_action varbinary(255) NULL default NULL,
  -- Log params
  rc_params blob NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/rc_timestamp ON /*_*/recentchanges (rc_timestamp);
CREATE INDEX /*i*/rc_namespace_title ON /*_*/recentchanges (rc_namespace, rc_title);
CREATE INDEX /*i*/rc_cur_id ON /*_*/recentchanges (rc_cur_id);
CREATE INDEX /*i*/new_name_timestamp ON /*_*/recentchanges (rc_new,rc_namespace,rc_timestamp);
CREATE INDEX /*i*/rc_ip ON /*_*/recentchanges (rc_ip);
CREATE INDEX /*i*/rc_ns_usertext ON /*_*/recentchanges (rc_namespace, rc_user_text);
CREATE INDEX /*i*/rc_user_text ON /*_*/recentchanges (rc_user_text, rc_timestamp);


CREATE TABLE /*_*/watchlist (
  -- Key to user.user_id
  wl_user int unsigned NOT NULL,

  -- Key to page_namespace/page_title
  -- Note that users may watch pages which do not exist yet,
  -- or existed in the past but have been deleted.
  wl_namespace int NOT NULL default 0,
  wl_title varchar(255) binary NOT NULL default '',

  -- Timestamp when user was last sent a notification e-mail;
  -- cleared when the user visits the page.
  wl_notificationtimestamp varbinary(14)

) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/wl_user ON /*_*/watchlist (wl_user, wl_namespace, wl_title);
CREATE INDEX /*i*/namespace_title ON /*_*/watchlist (wl_namespace, wl_title);


--
-- When using the default MySQL search backend, page titles
-- and text are munged to strip markup, do Unicode case folding,
-- and prepare the result for MySQL's fulltext index.
--
-- This table must be MyISAM; InnoDB does not support the needed
-- fulltext index.
--
CREATE TABLE /*_*/searchindex (
  -- Key to page_id
  si_page int unsigned NOT NULL,

  -- Munged version of title
  si_title varchar(255) NOT NULL default '',

  -- Munged version of body text
  si_text mediumtext NOT NULL
) ENGINE=MyISAM;

CREATE UNIQUE INDEX /*i*/si_page ON /*_*/searchindex (si_page);
CREATE FULLTEXT INDEX /*i*/si_title ON /*_*/searchindex (si_title);
CREATE FULLTEXT INDEX /*i*/si_text ON /*_*/searchindex (si_text);


--
-- Recognized interwiki link prefixes
--
CREATE TABLE /*_*/interwiki (
  -- The interwiki prefix, (e.g. "Meatball", or the language prefix "de")
  iw_prefix varchar(32) NOT NULL,

  -- The URL of the wiki, with "$1" as a placeholder for an article name.
  -- Any spaces in the name will be transformed to underscores before
  -- insertion.
  iw_url blob NOT NULL,

  -- The URL of the file api.php
  iw_api blob NOT NULL,

  -- The name of the database (for a connection to be established with wfGetLB( 'wikiid' ))
  iw_wikiid varchar(64) NOT NULL,

  -- A boolean value indicating whether the wiki is in this project
  -- (used, for example, to detect redirect loops)
  iw_local bool NOT NULL,

  -- Boolean value indicating whether interwiki transclusions are allowed.
  iw_trans tinyint NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/iw_prefix ON /*_*/interwiki (iw_prefix);


--
-- Used for caching expensive grouped queries
--
CREATE TABLE /*_*/querycache (
  -- A key name, generally the base name of of the special page.
  qc_type varbinary(32) NOT NULL,

  -- Some sort of stored value. Sizes, counts...
  qc_value int unsigned NOT NULL default 0,

  -- Target namespace+title
  qc_namespace int NOT NULL default 0,
  qc_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/qc_type ON /*_*/querycache (qc_type,qc_value);


--
-- For a few generic cache operations if not using Memcached
--
CREATE TABLE /*_*/objectcache (
  keyname varbinary(255) NOT NULL default '' PRIMARY KEY,
  value mediumblob,
  exptime datetime
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/exptime ON /*_*/objectcache (exptime);


--
-- Cache of interwiki transclusion
--
CREATE TABLE /*_*/transcache (
  tc_url varbinary(255) NOT NULL,
  tc_contents text,
  tc_time binary(14) NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/tc_url_idx ON /*_*/transcache (tc_url);


CREATE TABLE /*_*/logging (
  -- Log ID, for referring to this specific log entry, probably for deletion and such.
  log_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Symbolic keys for the general log type and the action type
  -- within the log. The output format will be controlled by the
  -- action field, but only the type controls categorization.
  log_type varbinary(32) NOT NULL default '',
  log_action varbinary(32) NOT NULL default '',

  -- Timestamp. Duh.
  log_timestamp binary(14) NOT NULL default '19700101000000',

  -- The user who performed this action; key to user_id
  log_user int unsigned NOT NULL default 0,

  -- Name of the user who performed this action
  log_user_text varchar(255) binary NOT NULL default '',

  -- Key to the page affected. Where a user is the target,
  -- this will point to the user page.
  log_namespace int NOT NULL default 0,
  log_title varchar(255) binary NOT NULL default '',
  log_page int unsigned NULL,

  -- Freeform text. Interpreted as edit history comments.
  log_comment varchar(255) NOT NULL default '',

  -- LF separated list of miscellaneous parameters
  log_params blob NOT NULL,

  -- rev_deleted for logs
  log_deleted tinyint unsigned NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/type_time ON /*_*/logging (log_type, log_timestamp);
CREATE INDEX /*i*/user_time ON /*_*/logging (log_user, log_timestamp);
CREATE INDEX /*i*/page_time ON /*_*/logging (log_namespace, log_title, log_timestamp);
CREATE INDEX /*i*/times ON /*_*/logging (log_timestamp);
CREATE INDEX /*i*/log_user_type_time ON /*_*/logging (log_user, log_type, log_timestamp);
CREATE INDEX /*i*/log_page_id_time ON /*_*/logging (log_page,log_timestamp);
CREATE INDEX /*i*/type_action ON /*_*/logging(log_type, log_action, log_timestamp);


CREATE TABLE /*_*/log_search (
  -- The type of ID (rev ID, log ID, rev timestamp, username)
  ls_field varbinary(32) NOT NULL,
  -- The value of the ID
  ls_value varchar(255) NOT NULL,
  -- Key to log_id
  ls_log_id int unsigned NOT NULL default 0
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/ls_field_val ON /*_*/log_search (ls_field,ls_value,ls_log_id);
CREATE INDEX /*i*/ls_log_id ON /*_*/log_search (ls_log_id);


CREATE TABLE /*_*/trackbacks (
  tb_id int PRIMARY KEY AUTO_INCREMENT,
  tb_page int REFERENCES /*_*/page(page_id) ON DELETE CASCADE,
  tb_title varchar(255) NOT NULL,
  tb_url blob NOT NULL,
  tb_ex text,
  tb_name varchar(255)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/tb_page ON /*_*/trackbacks (tb_page);


-- Jobs performed by parallel apache threads or a command-line daemon
CREATE TABLE /*_*/job (
  job_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Command name
  -- Limited to 60 to prevent key length overflow
  job_cmd varbinary(60) NOT NULL default '',

  -- Namespace and title to act on
  -- Should be 0 and '' if the command does not operate on a title
  job_namespace int NOT NULL,
  job_title varchar(255) binary NOT NULL,

  -- Any other parameters to the command
  -- Stored as a PHP serialized array, or an empty string if there are no parameters
  job_params blob NOT NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/job_cmd ON /*_*/job (job_cmd, job_namespace, job_title, job_params(128));


-- Details of updates to cached special pages
CREATE TABLE /*_*/querycache_info (
  -- Special page name
  -- Corresponds to a qc_type value
  qci_type varbinary(32) NOT NULL default '',

  -- Timestamp of last update
  qci_timestamp binary(14) NOT NULL default '19700101000000'
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/qci_type ON /*_*/querycache_info (qci_type);


-- For each redirect, this table contains exactly one row defining its target
CREATE TABLE /*_*/redirect (
  -- Key to the page_id of the redirect page
  rd_from int unsigned NOT NULL default 0 PRIMARY KEY,

  -- Key to page_namespace/page_title of the target page.
  -- The target page may or may not exist, and due to renames
  -- and deletions may refer to different page records as time
  -- goes by.
  rd_namespace int NOT NULL default 0,
  rd_title varchar(255) binary NOT NULL default '',
  rd_interwiki varchar(32) default NULL,
  rd_fragment varchar(255) binary default NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/rd_ns_title ON /*_*/redirect (rd_namespace,rd_title,rd_from);


-- Used for caching expensive grouped queries that need two links (for example double-redirects)
CREATE TABLE /*_*/querycachetwo (
  -- A key name, generally the base name of of the special page.
  qcc_type varbinary(32) NOT NULL,

  -- Some sort of stored value. Sizes, counts...
  qcc_value int unsigned NOT NULL default 0,

  -- Target namespace+title
  qcc_namespace int NOT NULL default 0,
  qcc_title varchar(255) binary NOT NULL default '',

  -- Target namespace+title2
  qcc_namespacetwo int NOT NULL default 0,
  qcc_titletwo varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/qcc_type ON /*_*/querycachetwo (qcc_type,qcc_value);
CREATE INDEX /*i*/qcc_title ON /*_*/querycachetwo (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX /*i*/qcc_titletwo ON /*_*/querycachetwo (qcc_type,qcc_namespacetwo,qcc_titletwo);


-- Used for storing page restrictions (i.e. protection levels)
CREATE TABLE /*_*/page_restrictions (
  -- Page to apply restrictions to (Foreign Key to page).
  pr_page int NOT NULL,
  -- The protection type (edit, move, etc)
  pr_type varbinary(60) NOT NULL,
  -- The protection level (Sysop, autoconfirmed, etc)
  pr_level varbinary(60) NOT NULL,
  -- Whether or not to cascade the protection down to pages transcluded.
  pr_cascade tinyint NOT NULL,
  -- Field for future support of per-user restriction.
  pr_user int NULL,
  -- Field for time-limited protection.
  pr_expiry varbinary(14) NULL,
  -- Field for an ID for this restrictions row (sort-key for Special:ProtectedPages)
  pr_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/pr_pagetype ON /*_*/page_restrictions (pr_page,pr_type);
CREATE INDEX /*i*/pr_typelevel ON /*_*/page_restrictions (pr_type,pr_level);
CREATE INDEX /*i*/pr_level ON /*_*/page_restrictions (pr_level);
CREATE INDEX /*i*/pr_cascade ON /*_*/page_restrictions (pr_cascade);


-- Protected titles - nonexistent pages that have been protected
CREATE TABLE /*_*/protected_titles (
  pt_namespace int NOT NULL,
  pt_title varchar(255) binary NOT NULL,
  pt_user int unsigned NOT NULL,
  pt_reason tinyblob,
  pt_timestamp binary(14) NOT NULL,
  pt_expiry varbinary(14) NOT NULL default '',
  pt_create_perm varbinary(60) NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/pt_namespace_title ON /*_*/protected_titles (pt_namespace,pt_title);
CREATE INDEX /*i*/pt_timestamp ON /*_*/protected_titles (pt_timestamp);


-- Name/value pairs indexed by page_id
CREATE TABLE /*_*/page_props (
  pp_page int NOT NULL,
  pp_propname varbinary(60) NOT NULL,
  pp_value blob NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/pp_page_propname ON /*_*/page_props (pp_page,pp_propname);


-- A table to log updates, one text key row per update.
CREATE TABLE /*_*/updatelog (
  ul_key varchar(255) NOT NULL PRIMARY KEY,
  ul_value blob
) /*$wgDBTableOptions*/;


-- A table to track tags for revisions, logs and recent changes.
CREATE TABLE /*_*/change_tag (
  -- RCID for the change
  ct_rc_id int NULL,
  -- LOGID for the change
  ct_log_id int NULL,
  -- REVID for the change
  ct_rev_id int NULL,
  -- Tag applied
  ct_tag varchar(255) NOT NULL,
  -- Parameters for the tag, presently unused
  ct_params blob NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/change_tag_rc_tag ON /*_*/change_tag (ct_rc_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag ON /*_*/change_tag (ct_log_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag ON /*_*/change_tag (ct_rev_id,ct_tag);
-- Covering index, so we can pull all the info only out of the index.
CREATE INDEX /*i*/change_tag_tag_id ON /*_*/change_tag (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);


-- Rollup table to pull a LIST of tags simply without ugly GROUP_CONCAT
-- that only works on MySQL 4.1+
CREATE TABLE /*_*/tag_summary (
  -- RCID for the change
  ts_rc_id int NULL,
  -- LOGID for the change
  ts_log_id int NULL,
  -- REVID for the change
  ts_rev_id int NULL,
  -- Comma-separated list of tags
  ts_tags blob NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/tag_summary_rc_id ON /*_*/tag_summary (ts_rc_id);
CREATE UNIQUE INDEX /*i*/tag_summary_log_id ON /*_*/tag_summary (ts_log_id);
CREATE UNIQUE INDEX /*i*/tag_summary_rev_id ON /*_*/tag_summary (ts_rev_id);


CREATE TABLE /*_*/valid_tag (
  vt_tag varchar(255) NOT NULL PRIMARY KEY
) /*$wgDBTableOptions*/;

-- Table for storing localisation data
CREATE TABLE /*_*/l10n_cache (
  -- Language code
  lc_lang varbinary(32) NOT NULL,
  -- Cache key
  lc_key varchar(255) NOT NULL,
  -- Value
  lc_value mediumblob NOT NULL
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/lc_lang_key ON /*_*/l10n_cache (lc_lang, lc_key);

-- Table for storing JSON message blobs for the resource loader
CREATE TABLE /*_*/msg_resource (
  -- Resource name
  mr_resource varbinary(255) NOT NULL,
  -- Language code
  mr_lang varbinary(32) NOT NULL,
  -- JSON blob
  mr_blob mediumblob NOT NULL,
  -- Timestamp of last update
  mr_timestamp binary(14) NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/mr_resource_lang ON /*_*/msg_resource (mr_resource, mr_lang);

-- Table for administering which message is contained in which resource
CREATE TABLE /*_*/msg_resource_links (
  mrl_resource varbinary(255) NOT NULL,
  -- Message key
  mrl_message varbinary(255) NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/mrl_message_resource ON /*_*/msg_resource_links (mrl_message, mrl_resource);

-- Table for tracking which local files a module depends on that aren't
-- registered directly.
-- Currently only used for tracking images that CSS depends on
CREATE TABLE /*_*/module_deps (
  -- Module name
  md_module varbinary(255) NOT NULL,
  -- Skin name
  md_skin varbinary(32) NOT NULL,
  -- JSON blob with file dependencies
  md_deps mediumblob NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/md_module_skin ON /*_*/module_deps (md_module, md_skin);

-- Table for holding configuration changes
CREATE TABLE /*_*/config (
  -- Config var name
  cf_name varbinary(255) NOT NULL PRIMARY KEY,
  -- Config var value
  cf_value blob NOT NULL
) /*$wgDBTableOptions*/;
-- Should cover *most* configuration - strings, ints, bools, etc.
CREATE INDEX /*i*/cf_name_value ON /*_*/config (cf_name,cf_value(255));

-- vim: sw=2 sts=2 et
