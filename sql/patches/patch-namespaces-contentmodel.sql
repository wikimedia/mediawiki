ALTER TABLE /*$wgDBprefix*/mw_namespaces
  ADD COLUMN ns_content_model VARCHAR(32) AFTER ns_content;
