-- T33223: Remove obsolete ar_text and ar_flags columns
-- (and make ar_text_id not nullable)

ALTER TABLE archive
  DROP COLUMN ar_text,
  DROP COLUMN ar_flags,
  ALTER COLUMN ar_text_id SET NOT NULL;
