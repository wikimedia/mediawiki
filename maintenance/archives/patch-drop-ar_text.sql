-- T33223: Remove obsolete ar_text and ar_flags columns
-- (and make ar_text_id not nullable and default 0)

ALTER TABLE /*_*/archive
  DROP COLUMN ar_text,
  DROP COLUMN ar_flags,
  CHANGE COLUMN ar_text_id ar_text_id int unsigned NOT NULL DEFAULT 0;
