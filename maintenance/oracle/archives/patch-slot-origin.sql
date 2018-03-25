--
-- Replace slot_inherited with slot_origin.
--
-- NOTE: There is no release that has slot_inherited. This is only needed to transition between
-- snapshot versions of 1.30.
--
-- NOTE: No code that writes to the slots table was merge yet, the table is assumed to be empty.
--
DROP INDEX &mw_prefix.slot_role_inherited;

ALTER TABLE &mw_prefix.slots DROP COLUMN slot_inherited;
ALTER TABLE &mw_prefix.slots ADD ( slot_origin NUMBER NOT NULL );

CREATE INDEX &mw_prefix.slot_revision_origin_role ON &mw_prefix.slots (slot_revision_id, slot_origin, slot_role_id);
