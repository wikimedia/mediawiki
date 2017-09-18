CREATE TABLE slots (
  slot_revision_id INTEGER   NOT NULL,
  slot_role_id     SMALLINT  NOT NULL,
  slot_content_id  INTEGER   NOT NULL,
  slot_inherited   SMALLINT  NOT NULL  DEFAULT 0,
  PRIMARY KEY (slot_revision_id, slot_role_id)
);

CREATE INDEX slot_role_inherited ON slots (slot_revision_id, slot_role_id, slot_inherited);