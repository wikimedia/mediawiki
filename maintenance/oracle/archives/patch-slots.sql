CREATE TABLE &mw_prefix.slots (
  slot_revision_id NUMBER NOT NULL,
  slot_role_id NUMBER NOT NULL,
  slot_content_id NUMBER NOT NULL,
  slot_origin NUMBER NOT NULL
);

ALTER TABLE &mw_prefix.slots ADD CONSTRAINT &mw_prefix.slots_pk PRIMARY KEY (slot_revision_id, slot_role_id);

CREATE INDEX &mw_prefix.slot_revision_origin_role ON &mw_prefix.slots (slot_revision_id, slot_origin, slot_role_id);
