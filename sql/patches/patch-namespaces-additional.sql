ALTER TABLE /*$wgDBprefix*/mw_namespaces
  ADD COLUMN ns_additional LONGTEXT AFTER ns_core;
