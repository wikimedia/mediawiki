-- Primary key in recentchanges

ALTER TABLE /*$wgDBprefix*/recentchanges 
  ADD rc_id int(8) NOT NULL auto_increment,
  ADD PRIMARY KEY rc_id (rc_id);


