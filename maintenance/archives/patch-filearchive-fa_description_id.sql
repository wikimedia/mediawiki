ALTER TABLE /*_*/filearchive
  ADD COLUMN fa_deleted_reason_id bigint unsigned NOT NULL DEFAULT 0 AFTER fa_deleted_reason,
  ALTER COLUMN fa_description SET DEFAULT '',
  ADD COLUMN fa_description_id bigint unsigned NOT NULL DEFAULT 0 AFTER fa_description;
