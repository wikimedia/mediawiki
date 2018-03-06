--
-- Replace slot_inherited with slot_origin
--
DROP INDEX /*i*/slot_role_inherited on /*_*/slots;

ALTER TABLE /*_*/slots DROP COLUMN slot_inherited;

ALTER TABLE /*_*/slots ADD COLUMN
  slot_origin bigint unsigned NOT NULL;

CREATE INDEX /*i*/slot_origin_role ON /*_*/slots (slot_origin, slot_role_id);
