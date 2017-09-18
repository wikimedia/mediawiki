CREATE TABLE &mw_prefix.slots (
  slot_revision_id NUMBER NOT NULL,
  slot_role_id NUMBER NOT NULL,
  slot_content_id NUMBER NOT NULL,
  slot_inherited CHAR(1) DEFAULT '0' NOT NULL
);

ALTER TABLE &mw_prefix.slots ADD CONSTRAINT &mw_prefix.slots_pk PRIMARY KEY (slot_revision_id, slot_role_id);

CREATE INDEX &mw_prefix.slot_role_inherited ON &mw_prefix.slots (slot_revision_id, slot_role_id, slot_inherited);