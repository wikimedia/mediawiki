-- SQL to create the initial tables for the MediaWiki database.
-- This is read and executed by the install script; you should
-- not have to run it by itself unless doing a manual install.

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
-- 14-character CHAR or VARCHAR fields to store timestamps.
-- The format is YYYYMMDDHHMMSS, which is derived from the
-- text format of MySQL's TIMESTAMP fields.
--
-- Historically TIMESTAMP fields were used, but abandoned
-- in early 2002 after a lot of trouble with the fields
-- auto-updating.
--
-- The PostgreSQL backend uses DATETIME fields for timestamps,
-- and we will migrate the MySQL definitions at some point as
-- well.
--
--
-- The /*$wgDBprefix*/ comments in this and other files are
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
CREATE TABLE /*$wgDBprefix*/user (
  user_id int(5) unsigned NOT NULL auto_increment,
  
  -- Usernames must be unique, must not be in the form of
  -- an IP address. _Shouldn't_ allow slashes or case
  -- conflicts. Spaces are allowed, and are _not_ converted
  -- to underscores like titles. See the User::newFromName() for
  -- the specific tests that usernames have to pass.
  user_name varchar(255) binary NOT NULL default '',
  
  -- Optional 'real name' to be displayed in credit listings
  user_real_name varchar(255) binary NOT NULL default '',
  
  -- Password hashes, normally hashed like so:
  -- MD5(CONCAT(user_id,'-',MD5(plaintext_password))), see
  -- wfEncryptPassword() in GlobalFunctions.php
  user_password tinyblob NOT NULL default '',
  
  -- When using 'mail me a new password', a random
  -- password is generated and the hash stored here.
  -- The previous password is left in place until
  -- someone actually logs in with the new password,
  -- at which point the hash is moved to user_password
  -- and the old password is invalidated.
  user_newpassword tinyblob NOT NULL default '',
  
  -- Note: email should be restricted, not public info.
  -- Same with passwords.
  user_email tinytext NOT NULL default '',
  
  -- Newline-separated list of name=value defining the user
  -- preferences
  user_options blob NOT NULL default '',
  
  -- This is a timestamp which is updated when a user
  -- logs in, logs out, changes preferences, or performs
  -- some other action requiring HTML cache invalidation
  -- to ensure that the UI is updated.
  user_touched char(14) binary NOT NULL default '',
  
  -- A pseudorandomly generated value that is stored in
  -- a cookie when the "remember password" feature is
  -- used (previously, a hash of the password was used, but
  -- this was vulnerable to cookie-stealing attacks)
  user_token char(32) binary NOT NULL default '',
  
  -- Initially NULL; when a user's e-mail address has been
  -- validated by returning with a mailed token, this is
  -- set to the current timestamp.
  user_email_authenticated CHAR(14) BINARY,
  
  -- Randomly generated token created when the e-mail address
  -- is set and a confirmation test mail sent.
  user_email_token CHAR(32) BINARY,
  
  -- Expiration date for the user_email_token
  user_email_token_expires CHAR(14) BINARY,
  
  -- Timestamp of account registration.
  -- Accounts predating this schema addition may contain NULL.
  user_registration CHAR(14) BINARY,

  PRIMARY KEY user_id (user_id),
  UNIQUE INDEX user_name (user_name),
  INDEX (user_email_token)

) TYPE=InnoDB;

--
-- User permissions have been broken out to a separate table;
-- this allows sites with a shared user table to have different
-- permissions assigned to a user in each project.
--
-- This table replaces the old user_rights field which used a
-- comma-separated blob.
--
CREATE TABLE /*$wgDBprefix*/user_groups (
  -- Key to user_id
  ug_user int(5) unsigned NOT NULL default '0',
  
  -- Group names are short symbolic string keys.
  -- The set of group names is open-ended, though in practice
  -- only some predefined ones are likely to be used.
  --
  -- At runtime $wgGroupPermissions will associate group keys
  -- with particular permissions. A user will have the combined
  -- permissions of any group they're explicitly in, plus
  -- the implicit '*' and 'user' groups.
  ug_group char(16) NOT NULL default '',
  
  PRIMARY KEY (ug_user,ug_group),
  KEY (ug_group)
) TYPE=InnoDB;

-- Stores notifications of user talk page changes, for the display
-- of the "you have new messages" box
CREATE TABLE /*$wgDBprefix*/user_newtalk (
  -- Key to user.user_id
  user_id int(5) NOT NULL default '0',
  -- If the user is an anonymous user hir IP address is stored here
  -- since the user_id of 0 is ambiguous
  user_ip varchar(40) NOT NULL default '',
  INDEX user_id (user_id),
  INDEX user_ip (user_ip)
);


--
-- Core of the wiki: each page has an entry here which identifies
-- it by title and contains some essential metadata.
--
CREATE TABLE /*$wgDBprefix*/page (
  -- Unique identifier number. The page_id will be preserved across
  -- edits and rename operations, but not deletions and recreations.
  page_id int(8) unsigned NOT NULL auto_increment,
  
  -- A page name is broken into a namespace and a title.
  -- The namespace keys are UI-language-independent constants,
  -- defined in includes/Defines.php
  page_namespace int NOT NULL,
  
  -- The rest of the title, as text.
  -- Spaces are transformed into underscores in title storage.
  page_title varchar(255) binary NOT NULL,
  
  -- Comma-separated set of permission keys indicating who
  -- can move or edit the page.
  page_restrictions tinyblob NOT NULL default '',
  
  -- Number of times this page has been viewed.
  page_counter bigint(20) unsigned NOT NULL default '0',
  
  -- 1 indicates the article is a redirect.
  page_is_redirect tinyint(1) unsigned NOT NULL default '0',
  
  -- 1 indicates this is a new entry, with only one edit.
  -- Not all pages with one edit are new pages.
  page_is_new tinyint(1) unsigned NOT NULL default '0',
  
  -- Random value between 0 and 1, used for Special:Randompage
  page_random real unsigned NOT NULL,
  
  -- This timestamp is updated whenever the page changes in
  -- a way requiring it to be re-rendered, invalidating caches.
  -- Aside from editing this includes permission changes,
  -- creation or deletion of linked pages, and alteration
  -- of contained templates.
  page_touched char(14) binary NOT NULL default '',

  -- Handy key to revision.rev_id of the current revision.
  -- This may be 0 during page creation, but that shouldn't
  -- happen outside of a transaction... hopefully.
  page_latest int(8) unsigned NOT NULL,
  
  -- Uncompressed length in bytes of the page's current source text.
  page_len int(8) unsigned NOT NULL,

  PRIMARY KEY page_id (page_id),
  UNIQUE INDEX name_title (page_namespace,page_title),
  
  -- Special-purpose indexes
  INDEX (page_random),
  INDEX (page_len)

) TYPE=InnoDB;

--
-- Every edit of a page creates also a revision row.
-- This stores metadata about the revision, and a reference
-- to the text storage backend.
--
CREATE TABLE /*$wgDBprefix*/revision (
  rev_id int(8) unsigned NOT NULL auto_increment,
  
  -- Key to page_id. This should _never_ be invalid.
  rev_page int(8) unsigned NOT NULL,
  
  -- Key to text.old_id, where the actual bulk text is stored.
  -- It's possible for multiple revisions to use the same text,
  -- for instance revisions where only metadata is altered
  -- or a rollback to a previous version.
  rev_text_id int(8) unsigned NOT NULL,
  
  -- Text comment summarizing the change.
  -- This text is shown in the history and other changes lists,
  -- rendered in a subset of wiki markup by Linker::formatComment()
  rev_comment tinyblob NOT NULL default '',
  
  -- Key to user.user_id of the user who made this edit.
  -- Stores 0 for anonymous edits and for some mass imports.
  rev_user int(5) unsigned NOT NULL default '0',
  
  -- Text username or IP address of the editor.
  rev_user_text varchar(255) binary NOT NULL default '',
  
  -- Timestamp
  rev_timestamp char(14) binary NOT NULL default '',
  
  -- Records whether the user marked the 'minor edit' checkbox.
  -- Many automated edits are marked as minor.
  rev_minor_edit tinyint(1) unsigned NOT NULL default '0',
  
  -- Not yet used; reserved for future changes to the deletion system.
  rev_deleted tinyint(1) unsigned NOT NULL default '0',
  
  PRIMARY KEY rev_page_id (rev_page, rev_id),
  UNIQUE INDEX rev_id (rev_id),
  INDEX rev_timestamp (rev_timestamp),
  INDEX page_timestamp (rev_page,rev_timestamp),
  INDEX user_timestamp (rev_user,rev_timestamp),
  INDEX usertext_timestamp (rev_user_text,rev_timestamp)

) TYPE=InnoDB;


--
-- Holds text of individual page revisions.
--
-- Field names are a holdover from the 'old' revisions table in
-- MediaWiki 1.4 and earlier: an upgrade will transform that
-- table into the 'text' table to minimize unnecessary churning
-- and downtime. If upgrading, the other fields will be left unused.
--
CREATE TABLE /*$wgDBprefix*/text (
  -- Unique text storage key number.
  -- Note that the 'oldid' parameter used in URLs does *not*
  -- refer to this number anymore, but to rev_id.
  --
  -- revision.rev_text_id is a key to this column
  old_id int(8) unsigned NOT NULL auto_increment,
  
  -- Depending on the contents of the old_flags field, the text
  -- may be convenient plain text, or it may be funkily encoded.
  old_text mediumblob NOT NULL default '',
  
  -- Comma-separated list of flags:
  -- gzip: text is compressed with PHP's gzdeflate() function.
  -- utf8: text was stored as UTF-8.
  --       If $wgLegacyEncoding option is on, rows *without* this flag
  --       will be converted to UTF-8 transparently at load time.
  -- object: text field contained a serialized PHP object.
  --         The object either contains multiple versions compressed
  --         together to achieve a better compression ratio, or it refers
  --         to another row where the text can be found.
  old_flags tinyblob NOT NULL default '',
  
  PRIMARY KEY old_id (old_id)

) TYPE=InnoDB;

--
-- Holding area for deleted articles, which may be viewed
-- or restored by admins through the Special:Undelete interface.
-- The fields generally correspond to the page, revision, and text
-- fields, with several caveats.
--
CREATE TABLE /*$wgDBprefix*/archive (
  ar_namespace int NOT NULL default '0',
  ar_title varchar(255) binary NOT NULL default '',
  
  -- Newly deleted pages will not store text in this table,
  -- but will reference the separately existing text rows.
  -- This field is retained for backwards compatibility,
  -- so old archived pages will remain accessible after
  -- upgrading from 1.4 to 1.5.
  -- Text may be gzipped or otherwise funky.
  ar_text mediumblob NOT NULL default '',
  
  -- Basic revision stuff...
  ar_comment tinyblob NOT NULL default '',
  ar_user int(5) unsigned NOT NULL default '0',
  ar_user_text varchar(255) binary NOT NULL,
  ar_timestamp char(14) binary NOT NULL default '',
  ar_minor_edit tinyint(1) NOT NULL default '0',
  
  -- See ar_text note.
  ar_flags tinyblob NOT NULL default '',
  
  -- When revisions are deleted, their unique rev_id is stored
  -- here so it can be retained after undeletion. This is necessary
  -- to retain permalinks to given revisions after accidental delete
  -- cycles or messy operations like history merges.
  -- 
  -- Old entries from 1.4 will be NULL here, and a new rev_id will
  -- be created on undeletion for those revisions.
  ar_rev_id int(8) unsigned,
  
  -- For newly deleted revisions, this is the text.old_id key to the
  -- actual stored text. To avoid breaking the block-compression scheme
  -- and otherwise making storage changes harder, the actual text is
  -- *not* deleted from the text table, merely hidden by removal of the
  -- page and revision entries.
  --
  -- Old entries deleted under 1.2-1.4 will have NULL here, and their
  -- ar_text and ar_flags fields will be used to create a new text
  -- row upon undeletion.
  ar_text_id int(8) unsigned,
  
  KEY name_title_timestamp (ar_namespace,ar_title,ar_timestamp)

) TYPE=InnoDB;


--
-- Track page-to-page hyperlinks within the wiki.
--
CREATE TABLE /*$wgDBprefix*/pagelinks (
  -- Key to the page_id of the page containing the link.
  pl_from int(8) unsigned NOT NULL default '0',
  
  -- Key to page_namespace/page_title of the target page.
  -- The target page may or may not exist, and due to renames
  -- and deletions may refer to different page records as time
  -- goes by.
  pl_namespace int NOT NULL default '0',
  pl_title varchar(255) binary NOT NULL default '',
  
  UNIQUE KEY pl_from(pl_from,pl_namespace,pl_title),
  KEY (pl_namespace,pl_title)

) TYPE=InnoDB;


--
-- Track template inclusions.
--
CREATE TABLE /*$wgDBprefix*/templatelinks (
  -- Key to the page_id of the page containing the link.
  tl_from int(8) unsigned NOT NULL default '0',
  
  -- Key to page_namespace/page_title of the target page.
  -- The target page may or may not exist, and due to renames
  -- and deletions may refer to different page records as time
  -- goes by.
  tl_namespace int NOT NULL default '0',
  tl_title varchar(255) binary NOT NULL default '',
  
  UNIQUE KEY tl_from(tl_from,tl_namespace,tl_title),
  KEY (tl_namespace,tl_title)

) TYPE=InnoDB;

--
-- Track links to images *used inline*
-- We don't distinguish live from broken links here, so
-- they do not need to be changed on upload/removal.
--
CREATE TABLE /*$wgDBprefix*/imagelinks (
  -- Key to page_id of the page containing the image / media link.
  il_from int(8) unsigned NOT NULL default '0',
  
  -- Filename of target image.
  -- This is also the page_title of the file's description page;
  -- all such pages are in namespace 6 (NS_IMAGE).
  il_to varchar(255) binary NOT NULL default '',
  
  UNIQUE KEY il_from(il_from,il_to),
  KEY (il_to)

) TYPE=InnoDB;

--
-- Track category inclusions *used inline*
-- This tracks a single level of category membership
-- (folksonomic tagging, really).
--
CREATE TABLE /*$wgDBprefix*/categorylinks (
  -- Key to page_id of the page defined as a category member.
  cl_from int(8) unsigned NOT NULL default '0',
  
  -- Name of the category.
  -- This is also the page_title of the category's description page;
  -- all such pages are in namespace 14 (NS_CATEGORY).
  cl_to varchar(255) binary NOT NULL default '',
  
  -- The title of the linking page, or an optional override
  -- to determine sort order. Sorting is by binary order, which
  -- isn't always ideal, but collations seem to be an exciting
  -- and dangerous new world in MySQL... The sortkey is updated
  -- if no override exists and cl_from is renamed.
  --
  -- For MySQL 4.1+ with charset set to utf8, the sort key *index*
  -- needs cut to be smaller than 1024 bytes (at 3 bytes per char).
  -- To sort properly on the shorter key, this field needs to be
  -- the same shortness.
  cl_sortkey varchar(86) binary NOT NULL default '',
  
  -- This isn't really used at present. Provided for an optional
  -- sorting method by approximate addition time.
  cl_timestamp timestamp NOT NULL,
  
  UNIQUE KEY cl_from(cl_from,cl_to),
  
  -- We always sort within a given category...
  KEY cl_sortkey(cl_to,cl_sortkey),
  
  -- Not really used?
  KEY cl_timestamp(cl_to,cl_timestamp)

) TYPE=InnoDB;

--
-- Track links to external URLs
--
CREATE TABLE /*$wgDBprefix*/externallinks (
  -- page_id of the referring page
  el_from int(8) unsigned NOT NULL default '0',

  -- The URL
  el_to blob NOT NULL default '',

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
  el_index blob NOT NULL default '',
  
  KEY (el_from, el_to(40)),
  KEY (el_to(60), el_from),
  KEY (el_index(60))
) TYPE=InnoDB;

-- 
-- Track interlanguage links
--
CREATE TABLE /*$wgDBprefix*/langlinks (
  -- page_id of the referring page
  ll_from int(8) unsigned NOT NULL default '0',
  
  -- Language code of the target
  ll_lang varchar(10) binary NOT NULL default '',

  -- Title of the target, including namespace
  ll_title varchar(255) binary NOT NULL default '',

  UNIQUE KEY (ll_from, ll_lang),
  KEY (ll_lang, ll_title)
) ENGINE=InnoDB;

--
-- Contains a single row with some aggregate info
-- on the state of the site.
--
CREATE TABLE /*$wgDBprefix*/site_stats (
  -- The single row should contain 1 here.
  ss_row_id int(8) unsigned NOT NULL,
  
  -- Total number of page views, if hit counters are enabled.
  ss_total_views bigint(20) unsigned default '0',
  
  -- Total number of edits performed.
  ss_total_edits bigint(20) unsigned default '0',
  
  -- An approximate count of pages matching the following criteria:
  -- * in namespace 0
  -- * not a redirect
  -- * contains the text '[['
  -- See Article::isCountable() in includes/Article.php
  ss_good_articles bigint(20) unsigned default '0',
  
  -- Total pages, theoretically equal to SELECT COUNT(*) FROM page; except faster
  ss_total_pages bigint(20) default '-1',

  -- Number of users, theoretically equal to SELECT COUNT(*) FROM user;
  ss_users bigint(20) default '-1',

  -- Deprecated, no longer updated as of 1.5
  ss_admins int(10) default '-1',

  -- Number of images, equivalent to SELECT COUNT(*) FROM image
  ss_images int(10) default '0',

  UNIQUE KEY ss_row_id (ss_row_id)

) TYPE=InnoDB;

--
-- Stores an ID for every time any article is visited;
-- depending on $wgHitcounterUpdateFreq, it is
-- periodically cleared and the page_counter column
-- in the page table updated for the all articles
-- that have been visited.)
--
CREATE TABLE /*$wgDBprefix*/hitcounter (
  hc_id INTEGER UNSIGNED NOT NULL
) TYPE=HEAP MAX_ROWS=25000;


--
-- The internet is full of jerks, alas. Sometimes it's handy
-- to block a vandal or troll account.
--
CREATE TABLE /*$wgDBprefix*/ipblocks (
  -- Primary key, introduced for privacy.
  ipb_id int(8) NOT NULL auto_increment,
  
  -- Blocked IP address in dotted-quad form or user name.
  ipb_address varchar(40) binary NOT NULL default '',
  
  -- Blocked user ID or 0 for IP blocks.
  ipb_user int(8) unsigned NOT NULL default '0',
  
  -- User ID who made the block.
  ipb_by int(8) unsigned NOT NULL default '0',
  
  -- Text comment made by blocker.
  ipb_reason tinyblob NOT NULL default '',
  
  -- Creation (or refresh) date in standard YMDHMS form.
  -- IP blocks expire automatically.
  ipb_timestamp char(14) binary NOT NULL default '',
  
  -- Indicates that the IP address was banned because a banned
  -- user accessed a page through it. If this is 1, ipb_address
  -- will be hidden, and the block identified by block ID number.
  ipb_auto tinyint(1) NOT NULL default '0',
  
  -- Time at which the block will expire.
  ipb_expiry char(14) binary NOT NULL default '',
  
  -- Start and end of an address range, in hexadecimal
  -- Size chosen to allow IPv6
  ipb_range_start varchar(32) NOT NULL default '',
  ipb_range_end varchar(32) NOT NULL default '',
  
  PRIMARY KEY ipb_id (ipb_id),
  INDEX ipb_address (ipb_address),
  INDEX ipb_user (ipb_user),
  INDEX ipb_range (ipb_range_start(8), ipb_range_end(8))

) TYPE=InnoDB;


--
-- Uploaded images and other files.
--
CREATE TABLE /*$wgDBprefix*/image (
  -- Filename.
  -- This is also the title of the associated description page,
  -- which will be in namespace 6 (NS_IMAGE).
  img_name varchar(255) binary NOT NULL default '',
  
  -- File size in bytes.
  img_size int(8) unsigned NOT NULL default '0',
  
  -- For images, size in pixels.
  img_width int(5)  NOT NULL default '0',
  img_height int(5)  NOT NULL default '0',
  
  -- Extracted EXIF metadata stored as a serialized PHP array.
  img_metadata mediumblob NOT NULL,
  
  -- For images, bits per pixel if known.
  img_bits int(3)  NOT NULL default '0',
  
  -- Media type as defined by the MEDIATYPE_xxx constants
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  
  -- major part of a MIME media type as defined by IANA
  -- see http://www.iana.org/assignments/media-types/
  img_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart") NOT NULL default "unknown",
  
  -- minor part of a MIME media type as defined by IANA
  -- the minor parts are not required to adher to any standard
  -- but should be consistent throughout the database
  -- see http://www.iana.org/assignments/media-types/
  img_minor_mime varchar(32) NOT NULL default "unknown",
  
  -- Description field as entered by the uploader.
  -- This is displayed in image upload history and logs.
  img_description tinyblob NOT NULL default '',
  
  -- user_id and user_name of uploader.
  img_user int(5) unsigned NOT NULL default '0',
  img_user_text varchar(255) binary NOT NULL default '',
  
  -- Time of the upload.
  img_timestamp char(14) binary NOT NULL default '',
  
  PRIMARY KEY img_name (img_name),
  
  -- Used by Special:Imagelist for sort-by-size
  INDEX img_size (img_size),

  -- Used by Special:Newimages and Special:Imagelist
  INDEX img_timestamp (img_timestamp)

) TYPE=InnoDB;

--
-- Previous revisions of uploaded files.
-- Awkwardly, image rows have to be moved into
-- this table at re-upload time.
--
CREATE TABLE /*$wgDBprefix*/oldimage (
  -- Base filename: key to image.img_name
  oi_name varchar(255) binary NOT NULL default '',
  
  -- Filename of the archived file.
  -- This is generally a timestamp and '!' prepended to the base name.
  oi_archive_name varchar(255) binary NOT NULL default '',
  
  -- Other fields as in image...
  oi_size int(8) unsigned NOT NULL default 0,
  oi_width int(5) NOT NULL default 0,
  oi_height int(5) NOT NULL default 0,
  oi_bits int(3) NOT NULL default 0,
  oi_description tinyblob NOT NULL default '',
  oi_user int(5) unsigned NOT NULL default '0',
  oi_user_text varchar(255) binary NOT NULL default '',
  oi_timestamp char(14) binary NOT NULL default '',

  INDEX oi_name (oi_name(10))

) TYPE=InnoDB;

--
-- Record of deleted file data
--
CREATE TABLE /*$wgDBprefix*/filearchive (
  -- Unique row id
  fa_id int not null auto_increment,
  
  -- Original base filename; key to image.img_name, page.page_title, etc
  fa_name varchar(255) binary NOT NULL default '',
  
  -- Filename of archived file, if an old revision
  fa_archive_name varchar(255) binary default '',
  
  -- Which storage bin (directory tree or object store) the file data
  -- is stored in. Should be 'deleted' for files that have been deleted;
  -- any other bin is not yet in use.
  fa_storage_group varchar(16),
  
  -- SHA-1 of the file contents plus extension, used as a key for storage.
  -- eg 8f8a562add37052a1848ff7771a2c515db94baa9.jpg
  --
  -- If NULL, the file was missing at deletion time or has been purged
  -- from the archival storage.
  fa_storage_key varchar(64) binary default '',
  
  -- Deletion information, if this file is deleted.
  fa_deleted_user int,
  fa_deleted_timestamp char(14) binary default '',
  fa_deleted_reason text,
  
  -- Duped fields from image
  fa_size int(8) unsigned default '0',
  fa_width int(5)  default '0',
  fa_height int(5)  default '0',
  fa_metadata mediumblob,
  fa_bits int(3)  default '0',
  fa_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  fa_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart") default "unknown",
  fa_minor_mime varchar(32) default "unknown",
  fa_description tinyblob default '',
  fa_user int(5) unsigned default '0',
  fa_user_text varchar(255) binary default '',
  fa_timestamp char(14) binary default '',
  
  PRIMARY KEY (fa_id),
  INDEX (fa_name, fa_timestamp),             -- pick out by image name
  INDEX (fa_storage_group, fa_storage_key),  -- pick out dupe files
  INDEX (fa_deleted_timestamp),              -- sort by deletion time
  INDEX (fa_deleted_user)                    -- sort by deleter

) TYPE=InnoDB;

--
-- Primarily a summary table for Special:Recentchanges,
-- this table contains some additional info on edits from
-- the last few days, see Article::editUpdates()
--
CREATE TABLE /*$wgDBprefix*/recentchanges (
  rc_id int(8) NOT NULL auto_increment,
  rc_timestamp varchar(14) binary NOT NULL default '',
  rc_cur_time varchar(14) binary NOT NULL default '',
  
  -- As in revision
  rc_user int(10) unsigned NOT NULL default '0',
  rc_user_text varchar(255) binary NOT NULL default '',
  
  -- When pages are renamed, their RC entries do _not_ change.
  rc_namespace int NOT NULL default '0',
  rc_title varchar(255) binary NOT NULL default '',
  
  -- as in revision...
  rc_comment varchar(255) binary NOT NULL default '',
  rc_minor tinyint(3) unsigned NOT NULL default '0',
  
  -- Edits by user accounts with the 'bot' rights key are
  -- marked with a 1 here, and will be hidden from the
  -- default view.
  rc_bot tinyint(3) unsigned NOT NULL default '0',
  
  rc_new tinyint(3) unsigned NOT NULL default '0',
  
  -- Key to page_id (was cur_id prior to 1.5).
  -- This will keep links working after moves while
  -- retaining the at-the-time name in the changes list.
  rc_cur_id int(10) unsigned NOT NULL default '0',
  
  -- rev_id of the given revision
  rc_this_oldid int(10) unsigned NOT NULL default '0',
  
  -- rev_id of the prior revision, for generating diff links.
  rc_last_oldid int(10) unsigned NOT NULL default '0',
  
  -- These may no longer be used, with the new move log.
  rc_type tinyint(3) unsigned NOT NULL default '0',
  rc_moved_to_ns tinyint(3) unsigned NOT NULL default '0',
  rc_moved_to_title varchar(255) binary NOT NULL default '',
  
  -- If the Recent Changes Patrol option is enabled,
  -- users may mark edits as having been reviewed to
  -- remove a warning flag on the RC list.
  -- A value of 1 indicates the page has been reviewed.
  rc_patrolled tinyint(3) unsigned NOT NULL default '0',
  
  -- Recorded IP address the edit was made from, if the
  -- $wgPutIPinRC option is enabled.
  rc_ip char(15) NOT NULL default '',
  
  PRIMARY KEY rc_id (rc_id),
  INDEX rc_timestamp (rc_timestamp),
  INDEX rc_namespace_title (rc_namespace, rc_title),
  INDEX rc_cur_id (rc_cur_id),
  INDEX new_name_timestamp(rc_new,rc_namespace,rc_timestamp),
  INDEX rc_ip (rc_ip)

) TYPE=InnoDB;

CREATE TABLE /*$wgDBprefix*/watchlist (
  -- Key to user.user_id
  wl_user int(5) unsigned NOT NULL,
  
  -- Key to page_namespace/page_title
  -- Note that users may watch pages which do not exist yet,
  -- or existed in the past but have been deleted.
  wl_namespace int NOT NULL default '0',
  wl_title varchar(255) binary NOT NULL default '',
  
  -- Timestamp when user was last sent a notification e-mail;
  -- cleared when the user visits the page.
  wl_notificationtimestamp varchar(14) binary,
  
  UNIQUE KEY (wl_user, wl_namespace, wl_title),
  KEY namespace_title (wl_namespace,wl_title)

) TYPE=InnoDB;


--
-- Used by the math module to keep track
-- of previously-rendered items.
--
CREATE TABLE /*$wgDBprefix*/math (
  -- Binary MD5 hash of the latex fragment, used as an identifier key.
  math_inputhash varchar(16) NOT NULL,
  
  -- Not sure what this is, exactly...
  math_outputhash varchar(16) NOT NULL,
  
  -- texvc reports how well it thinks the HTML conversion worked;
  -- if it's a low level the PNG rendering may be preferred.
  math_html_conservativeness tinyint(1) NOT NULL,
  
  -- HTML output from texvc, if any
  math_html text,
  
  -- MathML output from texvc, if any
  math_mathml text,
  
  UNIQUE KEY math_inputhash (math_inputhash)

) TYPE=InnoDB;

--
-- When using the default MySQL search backend, page titles
-- and text are munged to strip markup, do Unicode case folding,
-- and prepare the result for MySQL's fulltext index.
--
-- This table must be MyISAM; InnoDB does not support the needed
-- fulltext index.
--
CREATE TABLE /*$wgDBprefix*/searchindex (
  -- Key to page_id
  si_page int(8) unsigned NOT NULL,
  
  -- Munged version of title
  si_title varchar(255) NOT NULL default '',
  
  -- Munged version of body text
  si_text mediumtext NOT NULL default '',
  
  UNIQUE KEY (si_page),
  FULLTEXT si_title (si_title),
  FULLTEXT si_text (si_text)

) TYPE=MyISAM;

--
-- Recognized interwiki link prefixes
--
CREATE TABLE /*$wgDBprefix*/interwiki (
  -- The interwiki prefix, (e.g. "Meatball", or the language prefix "de")
  iw_prefix char(32) NOT NULL,
  
  -- The URL of the wiki, with "$1" as a placeholder for an article name.
  -- Any spaces in the name will be transformed to underscores before
  -- insertion.
  iw_url char(127) NOT NULL,
  
  -- A boolean value indicating whether the wiki is in this project
  -- (used, for example, to detect redirect loops)
  iw_local BOOL NOT NULL,
  
  -- Boolean value indicating whether interwiki transclusions are allowed.
  iw_trans TINYINT(1) NOT NULL DEFAULT 0,
  
  UNIQUE KEY iw_prefix (iw_prefix)

) TYPE=InnoDB;

--
-- Used for caching expensive grouped queries
--
CREATE TABLE /*$wgDBprefix*/querycache (
  -- A key name, generally the base name of of the special page.
  qc_type char(32) NOT NULL,
  
  -- Some sort of stored value. Sizes, counts...
  qc_value int(5) unsigned NOT NULL default '0',
  
  -- Target namespace+title
  qc_namespace int NOT NULL default '0',
  qc_title char(255) binary NOT NULL default '',
  
  KEY (qc_type,qc_value)

) TYPE=InnoDB;

--
-- For a few generic cache operations if not using Memcached
--
CREATE TABLE /*$wgDBprefix*/objectcache (
  keyname char(255) binary not null default '',
  value mediumblob,
  exptime datetime,
  unique key (keyname),
  key (exptime)

) TYPE=InnoDB;

--
-- Cache of interwiki transclusion
--
CREATE TABLE /*$wgDBprefix*/transcache (
	tc_url		VARCHAR(255) NOT NULL,
	tc_contents	TEXT,
	tc_time		INT NOT NULL,
	UNIQUE INDEX tc_url_idx(tc_url)
) TYPE=InnoDB;

CREATE TABLE /*$wgDBprefix*/logging (
  -- Symbolic keys for the general log type and the action type
  -- within the log. The output format will be controlled by the
  -- action field, but only the type controls categorization.
  log_type char(10) NOT NULL default '',
  log_action char(10) NOT NULL default '',
  
  -- Timestamp. Duh.
  log_timestamp char(14) NOT NULL default '19700101000000',
  
  -- The user who performed this action; key to user_id
  log_user int unsigned NOT NULL default 0,
  
  -- Key to the page affected. Where a user is the target,
  -- this will point to the user page.
  log_namespace int NOT NULL default 0,
  log_title varchar(255) binary NOT NULL default '',
  
  -- Freeform text. Interpreted as edit history comments.
  log_comment varchar(255) NOT NULL default '',
  
  -- LF separated list of miscellaneous parameters
  log_params blob NOT NULL default '',

  KEY type_time (log_type, log_timestamp),
  KEY user_time (log_user, log_timestamp),
  KEY page_time (log_namespace, log_title, log_timestamp),
  KEY times (log_timestamp)

) TYPE=InnoDB;

CREATE TABLE /*$wgDBprefix*/trackbacks (
	tb_id integer AUTO_INCREMENT PRIMARY KEY,
	tb_page	integer REFERENCES page(page_id) ON DELETE CASCADE,
	tb_title varchar(255) NOT NULL,
	tb_url	varchar(255) NOT NULL,
	tb_ex text,
	tb_name varchar(255),

	INDEX (tb_page)
) TYPE=InnoDB;


-- Jobs performed by parallel apache threads or a command-line daemon
CREATE TABLE /*$wgDBprefix*/job (
  job_id int(9) unsigned NOT NULL auto_increment,
  
  -- Command name, currently only refreshLinks is defined
  job_cmd varchar(255) NOT NULL default '',

  -- Namespace and title to act on
  -- Should be 0 and '' if the command does not operate on a title
  job_namespace int NOT NULL,
  job_title varchar(255) binary NOT NULL,

  -- Any other parameters to the command
  -- Presently unused, format undefined
  job_params blob NOT NULL default '',

  PRIMARY KEY job_id (job_id),
  KEY (job_cmd, job_namespace, job_title)
) TYPE=InnoDB;


-- Details of updates to cached special pages
CREATE TABLE /*$wgDBprefix*/querycache_info (

	-- Special page name
	-- Corresponds to a qc_type value
	qci_type varchar(32) NOT NULL default '',

	-- Timestamp of last update
	qci_timestamp char(14) NOT NULL default '19700101000000',

	UNIQUE KEY ( qci_type )

) TYPE=InnoDB;
