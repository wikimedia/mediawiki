-- Add fa_sha1 and related index
ALTER TABLE /*$wgDBprefix*/filearchive
  ADD COLUMN fa_sha1 varbinary(32) NOT NULL default '',
  ADD INDEX fa_sha1 (fa_sha1);
