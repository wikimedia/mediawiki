CREATE TABLE slots (
  slot_revision_id INTEGER   NOT NULL,
  slot_role_id     SMALLINT  NOT NULL,
  slot_content_id  INTEGER   NOT NULL,
  slot_origin INTEGER   NOT NULL,
  PRIMARY KEY (slot_revision_id, slot_role_id)
);

CREATE INDEX slot_revision_origin_role ON slots (slot_revision_id, slot_origin, slot_role_id);
