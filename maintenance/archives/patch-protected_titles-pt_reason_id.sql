ALTER TABLE /*_*/protected_titles
  ALTER COLUMN pt_reason SET DEFAULT '',
  ADD COLUMN pt_reason_id bigint unsigned NOT NULL DEFAULT 0 AFTER pt_reason;
