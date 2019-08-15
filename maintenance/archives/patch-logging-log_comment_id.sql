ALTER TABLE /*_*/logging
  ADD COLUMN log_comment_id bigint unsigned NOT NULL DEFAULT 0 AFTER log_comment;
