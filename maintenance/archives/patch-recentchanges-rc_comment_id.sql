ALTER TABLE /*_*/recentchanges
  ADD COLUMN rc_comment_id bigint unsigned NOT NULL DEFAULT 0 AFTER rc_comment;
