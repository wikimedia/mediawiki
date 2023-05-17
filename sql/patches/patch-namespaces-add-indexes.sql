-- Convert namespaces table to use indexes for the db column
ALTER TABLE /*$wgDBprefix*/mw_namespaces
  ADD INDEX ns_dbname (ns_dbname);
