-- first step of migrating recentchanges rc_type to rc_source
ALTER TABLE /*$wgDBprefix*/recentchanges
  ADD rc_source varchar(16) binary NOT NULL default '';

