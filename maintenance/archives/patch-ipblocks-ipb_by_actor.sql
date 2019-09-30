ALTER TABLE /*_*/ipblocks
  ADD COLUMN ipb_by_actor bigint unsigned NOT NULL DEFAULT 0 AFTER ipb_by_text;
