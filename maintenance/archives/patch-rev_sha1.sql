-- Adding rev_sha1 field
ALTER TABLE /*$wgDBprefix*/revision
  ADD rev_sha1 varbinary(32) NOT NULL default '';
