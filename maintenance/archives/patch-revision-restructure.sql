-- @TODO consider splitting this to three patch files: setup, migration, cleanup.

--
-- Setup:
-- Create the new tables, and add new columns to revision...
--

-- the table creations are in tables.sql, need to be copied here:
-- @TODO create comment
-- @TODO create actor
-- @TODO create content
-- @TODO create slot

-- @TODO create content_role
-- @TODO populate content_role
-- @TODO create content_format
-- @TODO populate content_format
-- @TODO create content_model
-- @TODO populate content_model

-- Update page table to match new rev_id bigint size
ALTER TABLE page
  CHANGE COLUMN rev_latest rev_latest bigint unsigned NOT NULL;

-- Add new fields to revision and update some bigints
ALTER TABLE revision
  CHANGE COLUMN rev_id rev_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  CHANGE COLUMN rev_parent_id rev_parent_id bigint unsigned default NULL,
  ADD COLUMN rev_comment_id bigint
    AFTER rev_page,
  ADD COLUMN rev_actor bigint unsigned NOT NULL
    AFTER rev_comment_id,
;
CREATE INDEX /*i*/actor_timestamp ON /*_*/revision (rev_actor,rev_timestamp);
CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision (rev_page,rev_actor,rev_timestamp);

-- Add new fields to archive and update some bigints
ALTER TABLE archive
  CHANGE COLUMN ar_id ar_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  CHANGE COLUMN ar_rev_id ar_rev_id bigint unsigned,
  CHANGE COLUMN ar_parent_id ar_parent_id bigint unsigned default NULL,
ADD COLUMN ar_comment_id bigint
  AFTER ar_title,
ADD COLUMN ar_actor bigint unsigned
  AFTER ar_comment_id,
  ;

--
-- Migration:
-- Move data from the old fields to the new tables, brute-force style.
--
-- Production upgrade would not run this, instead migrating data in small bits
-- through a batch process.
--
-- If this is run on a small wiki via update.php it should work, but requires
-- the wiki to be offline and won't provide any feedback about how long it
-- takes.
--
-- A smarter updater plugin could do the migration in the same way we'd do it
-- in production, which might be safer.
--

-- Migrate comments...
-- @TODO we may change how comment is associated and do it earlier.
-- As a migration hack, the original rev_id is used as the initial comment_id.
INSERT INTO comment (comment_id, comment_text)
  SELECT rev_id, rev_comment
    FROM revision
      ORDER BY rev_id;
UPDATE revision
  SET rev_comment_id=rev_id
  WHERE rev_comment_id IS NULL;

-- @TODO this requires ar_rev_id to already be fully filled in archive!
INSERT INTO comment (comment_id, comment_text)
  SELECT ar_rev_id, ar_comment
    FROM archive
      ORDER BY ar_rev_id;
UPDATE archive
  SET ar_comment_id=ar_rev_id
  WHERE ar_comment_id IS NULL;

-- Migrate actors...
-- Start with all existing users.
INSERT INTO actor (actor_user,actor_text)
  SELECT user_id,user_name
    FROM user;

-- Pull any non-user entries from revision, such as IP edits and imports.
-- @TODO due to import fun, it's possible to have rows with user 'Foo' and also
-- rows with a non-user 'Foo'. This potentially won't transition cleanly, as
-- actor has a unique index on names.
-- The INSERT IGNORE avoids breaking on this case, but means the later updates
-- will change the reference from the non-user one to the user one.
-- That's probably what we want, though.
INSERT IGNORE INTO actor (actor_text)
  SELECT rev_user_text FROM revision
    WHERE rev_user = 0
    GROUP BY rev_user_text
    ORDER BY rev_user_text;
UPDATE revision
  SET rev_actor = (SELECT actor_id FROM actor WHERE actor_user=rev_user)
  WHERE rev_user > 0 AND rev_actor IS NULL;
UPDATE revision
  SET rev_actor = (SELECT actor_id FROM actor WHERE actor_text=rev_user_text)
  WHERE rev_user == 0 AND rev_actor IS NULL;

-- Migrate non-user actors from archive
INSERT IGNORE INTO actor (actor_text)
  SELECT ar_user_text FROM archive
    WHERE ar_user = 0
    GROUP BY ar_user_text
    ORDER BY ar_user_text;
UPDATE archive
  SET ar_actor = (SELECT actor_id FROM actor WHERE actor_user=ar_user)
  WHERE ar_user > 0 AND ar_actor IS NULL;
UPDATE archive
  SET ar_actor = (SELECT actor_id FROM actor WHERE actor_text=ar_user_text)
  WHERE ar_user == 0 AND ar_actor IS NULL;

-- Migrate content...
-- As a migration hack, rev_id is reused as the content_id for initial entries.
INSERT INTO content (cont_id,cont_text_id,cont_len,cont_sha1,cont_model,cont_format)
  SELECT rev_id,rev_text_id,rev_len,rev_sha1,cm_id,cf_id
    FROM revision
      LEFT JOIN content_model ON rev_content_model=cm_model
      LEFT JOIN content_format ON rev_content_format=cm_format;
-- Migrate deleted content...
-- @TODO requires ar_rev_id to have been filled out already
INSERT INTO content (cont_id,cont_text_id,cont_len,cont_sha1,cont_model,cont_format)
  SELECT ar_rev_id,ar_text_id,ar_len,ar_sha1,cm_id,cf_id
    FROM archive
      LEFT JOIN content_model ON rev_content_model=cm_model
      LEFT JOIN content_format ON rev_content_format=cm_format;

-- Migrate slot associations...
-- @TODO what's the 'classic' default slot ID string? is it in fact 'default'?
INSERT INTO slot (slot_rev_id,slot_content,slot_role)
  SELECT rev_id,rev_id,sr_id
    FROM revision
      LEFT JOIN slot_role ON sr_role='default';
INSERT INTO slot (slot_rev_id,slot_content,slot_role)
  SELECT ar_rev_id,ar_rev_id,sr_id
    FROM archive
      LEFT JOIN slot_role ON sr_role='default';

--
-- Cleanup:
-- Once everything is migrated, we can remove the obsolete fields from
-- the revision table and their indexes.
--
-- Production upgrade would run this on replicas when they're out of rotation.
--

-- Drop old fields
ALTER TABLE revision
  DROP KEY user_timestamp,
  DROP KEY usertext_timestamp,
  DROP KEY page_user_timestamp,

  DROP COLUMN rev_comment,
  DROP COLUMN rev_text_id,
  DROP COLUMN rev_user,
  DROP COLUMN rev_user_text,
  DROP COLUMN rev_len,
  DROP COLUMN rev_sha1,
  DROP COLUMN rev_content_model,
  DROP COLUMN rev_content_format
;

ALTER TABLE archive
  DROP COLUMN ar_text,
  DROP COLUMN ar_comment,
  DROP COLUMN ar_user,
  DROP COLUMN ar_user_text,
  DROP COLUMN ar_flags,
  DROP COLUMN ar_text_id,
  DROP COLUMN ar_len,
  DROP COLUMN ar_sha1,
  DROP COLUMN ar_content_model,
  DROP COLUMN ar_content_format
;
