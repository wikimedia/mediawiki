--
-- Slots represent an n:m relation between revisions and content objects.
-- A content object can have a specific "role" in one or more revisions.
-- Each revision can have multiple content objects, each having a different role.
--
CREATE TABLE /*_*/slots (

  -- reference to rev_id
  slot_revision_id bigint unsigned NOT NULL,

  -- reference to role_id
  slot_role_id smallint unsigned NOT NULL CONSTRAINT FK_slots_slot_role FOREIGN KEY REFERENCES slot_roles(role_id),

  -- reference to content_id
  slot_content_id bigint unsigned NOT NULL CONSTRAINT FK_slots_content_id FOREIGN KEY REFERENCES content(content_id),

  -- whether the content is inherited (1) or new in this revision (0)
  slot_inherited tinyint unsigned NOT NULL CONSTRAINT DF_slot_inherited DEFAULT 0,

  CONSTRAINT PK_slots PRIMARY KEY (slot_revision_id, slot_role_id)
);

-- Index for finding revisions that modified a specific slot
CREATE INDEX /*i*/slot_role_inherited ON /*_*/slots (slot_revision_id, slot_role_id, slot_inherited);