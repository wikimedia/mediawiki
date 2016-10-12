alter table /*$wgDBTableOptions*/recentchanges change rc_ip rc_ip varbinary(40) NOT NULL default '';
