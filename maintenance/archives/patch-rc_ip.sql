-- Adding the rc_ip field for logging of IP addresses in recentchanges

ALTER TABLE recentchanges 
  ADD rc_ip char(15) NOT NULL default '',
  ADD INDEX rc_ip (rc_ip);


