ALTER TABLE /*_*/archive
  ALTER COLUMN ar_comment SET DEFAULT '',
  ADD COLUMN ar_comment_id bigint unsigned NOT NULL DEFAULT 0 AFTER ar_comment;
