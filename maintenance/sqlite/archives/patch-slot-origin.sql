--
-- Replace slot_inherited with slot_origin.
--
-- NOTE: There is no release that has slot_inherited. This is only needed to transition between
-- snapshot versions of 1.30.
--
-- NOTE: No code that writes to the slots table was merge yet, the table is assumed to be empty.
--
BEGIN TRANSACTION;

DROP TABLE /*_*/slots;

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

COMMIT TRANSACTION;