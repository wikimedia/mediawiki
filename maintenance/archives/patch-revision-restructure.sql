-- @TODO consider splitting this to three patch files: setup, migration, cleanup.

--
-- Setup:
-- Create the new tables, and add new columns to revision...
--

-- the table creations are in tables.sql, need to be copied here:
-- @TODO create comment
-- @TODO create actor
-- @TODO create content
-- @TODO create slots

-- @TODO create slot_role
-- @TODO populate slot_role
-- @TODO create content_format
-- @TODO populate content_format
-- @TODO create content_model
-- @TODO populate content_model

-- Add new fields to revision
ALTER TABLE revision
  CHANGE COLUMN rev_id rev_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ADD COLUMN rev_comment_id bigint
    AFTER rev_page,
  ADD COLUMN   rev_actor int unsigned NOT NULL default 0
    AFTER rev_comment_id,
;
CREATE INDEX /*i*/actor_timestamp ON /*_*/revision (rev_actor,rev_timestamp);
CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision (rev_page,rev_actor,rev_timestamp);

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
INSERT INTO comment (comment_id, comment_text)
  SELECT rev_id, rev_comment
    FROM revision
      ORDER BY rev_id;
UPDATE revision
  SET rev_comment_id=rev_id
  WHERE rev_comment_id IS NULL;

-- Migrate actors...
INSERT INTO actor (actor_user)
  SELECT rev_user FROM user
    WHERE rev_user > 0
    GROUP BY rev_user
    ORDER BY rev_user;
INSERT INTO actor (actor_text)
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

-- Migrate content...
INSERT INTO content (cont_id,cont_text_id,cont_len,cont_sha1,cont_model,cont_format)
  SELECT rev_id,rev_text_id,rev_len,rev_sha1,cm_id,cf_id
    FROM revision
      LEFT JOIN content_model ON rev_content_model=cm_model
      LEFT JOIN content_format ON rev_content_format=cm_format;

-- Migrate slot associations...
INSERT INTO slots (slot_revision,slot_content,slot_role)
  SELECT rev_id,rev_id,sr_id
    FROM revision
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
