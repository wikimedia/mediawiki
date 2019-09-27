ALTER TABLE /*_*/oldimage
  ALTER COLUMN oi_description SET DEFAULT '',
  ADD COLUMN oi_description_id bigint unsigned NOT NULL DEFAULT 0 AFTER oi_description;
