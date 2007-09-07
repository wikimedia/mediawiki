-- Add rev_bot column.  We don't need an index if all we want to be able to do
-- is, e.g., hide bot edits: most edits are non-bot, so for normal use it's
-- better to just look at 20% more rows or whatever than to add a new index.
ALTER TABLE /*$wgDBprefix*/revision
  ADD COLUMN rev_bot tinyint unsigned NOT NULL default 0 AFTER rev_deleted;
