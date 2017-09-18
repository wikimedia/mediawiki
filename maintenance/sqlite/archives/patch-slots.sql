--
-- Slots represent an n:m relation between revisions and content objects.
-- A content object can have a specific "role" in one or more revisions.
-- Each revision can have multiple content objects, each having a different role.
--
CREATE TABLE /*_*/slots (

  -- reference to rev_id
  slot_revision_id bigint unsigned NOT NULL,

  -- reference to role_id
  slot_role_id smallint unsigned NOT NULL,

  -- reference to content_id
  slot_content_id bigint unsigned NOT NULL,

  -- whether the content is inherited (1) or new in this revision (0)
  slot_inherited tinyint unsigned NOT NULL DEFAULT 0,

  PRIMARY KEY ( slot_revision_id, slot_role_id )
) /*$wgDBTableOptions*/;

-- Index for finding revisions that modified a specific slot
CREATE INDEX /*i*/slot_role_inherited ON /*_*/slots (slot_revision_id, slot_role_id, slot_inherited);