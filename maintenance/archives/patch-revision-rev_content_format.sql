ALTER TABLE /*$wgDBprefix*/revision
  ADD rev_content_format varbinary(64) DEFAULT NULL;
