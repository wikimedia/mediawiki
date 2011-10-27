-- Adding ar_sha1 field
ALTER TABLE /*$wgDBprefix*/archive
  ADD ar_sha1 varbinary(32) NOT NULL default '';
