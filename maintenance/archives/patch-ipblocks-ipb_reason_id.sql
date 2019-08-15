ALTER TABLE /*_*/ipblocks
  ALTER COLUMN ipb_reason SET DEFAULT '',
  ADD COLUMN ipb_reason_id bigint unsigned NOT NULL DEFAULT 0 AFTER ipb_reason;
