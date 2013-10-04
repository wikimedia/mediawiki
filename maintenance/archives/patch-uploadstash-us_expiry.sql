ALTER TABLE /*$wgDBprefix*/uploadstash
  ADD COLUMN us_expiry varbinary(14) NULL default NULL;
