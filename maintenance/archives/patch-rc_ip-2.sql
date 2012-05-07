-- Adding the rc_ip field for logging of IP addresses in recentchanges

ALTER TABLE /*$wgDBprefix*/recentchanges 
  MODIFY rc_ip varbinary(40) NOT NULL default '';

