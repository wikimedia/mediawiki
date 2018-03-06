--
-- Replace slot_inherited with slot_origin.
--
-- NOTE: There is no release that has slot_inherited. This is only needed to transition between
-- snapshot versions of 1.30.
--
-- NOTE: No code that writes to the slots table was merge yet, the table is assumed to be empty.
--
DROP INDEX /*i*/slot_role_inherited ON /*_*/slots;

ALTER TABLE /*_*/slots DROP CONSTRAINT DF_slot_inherited, COLUMN slot_inherited;
ALTER TABLE /*_*/slots ADD COLUMN slot_origin bigint NOT NULL;

CREATE INDEX /*i*/slot_revision_origin_role ON /*_*/slots (slot_revision_id, slot_origin, slot_role_id);
