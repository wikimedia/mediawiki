ALTER TABLE /*$wgDBprefix*/revision
  ADD rev_content_model varbinary(32) DEFAULT NULL;
