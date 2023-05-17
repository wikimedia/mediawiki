ALTER TABLE /*$wgDBprefix*/mw_namespaces
  MODIFY COLUMN ns_core INT(1) NOT NULL DEFAULT 0;
