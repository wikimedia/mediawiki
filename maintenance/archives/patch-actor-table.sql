--
-- Revision authorship is represented by an actor row.
-- This avoids duplicating common usernames/IP addresses
-- in the revision table, and makes rename processing quicker.
--
CREATE TABLE /*_*/actor (
  -- Unique ID to identify each entry
  actor_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Key to user.user_id of the user who made this edit.
  -- Stores NULL for anonymous edits and for some mass imports.
  actor_user int unsigned,

  -- Text username or IP address of the editor.
  actor_text varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;


CREATE UNIQUE INDEX /*i*/actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX /*i*/actor_text ON /*_*/actor (actor_text);




-- TODO: maybe do these two as additions with temp tables?

-- Add new fields to revision
--ALTER TABLE revision
--  ADD COLUMN rev_actor bigint unsigned NOT NULL;
--CREATE INDEX /*i*/actor_timestamp ON /*_*/revision (rev_actor,rev_timestamp);
--CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision (rev_page,rev_actor,rev_timestamp);

-- Add new fields to archive and update some bigints
--ALTER TABLE archive
--  ADD COLUMN ar_actor bigint unsigned
--    AFTER ar_comment;

-- TODO: image
-- TODO: oldimage
-- TODO: filearchive
-- TODO: recentchanges
-- TODO: logging
