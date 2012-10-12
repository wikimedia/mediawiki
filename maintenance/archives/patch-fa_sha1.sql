-- Add fa_sha1 and related index
ALTER TABLE /*$wgDBprefix*/filearchive
  ADD COLUMN fa_sha1 varbinary(32) NOT NULL default '';
CREATE INDEX /*i*/fa_sha1 ON /*$wgDBprefix*/filearchive (fa_sha1(10));
