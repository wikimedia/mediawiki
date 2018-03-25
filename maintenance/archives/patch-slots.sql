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

  -- The revision ID of the revision that originated the slot's content.
  -- To find revisions that changed slots, look for slot_origin = slot_revision_id.
  slot_origin bigint unsigned NOT NULL,

  PRIMARY KEY ( slot_revision_id, slot_role_id )
) /*$wgDBTableOptions*/;

-- Index for finding revisions that modified a specific slot
CREATE INDEX /*i*/slot_revision_origin_role ON /*_*/slots (slot_revision_id, slot_origin, slot_role_id);
