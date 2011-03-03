-- For getting diff since last view
ALTER TABLE /*$wgDBprefix*/user_newtalk
  ADD user_last_timestamp varbinary(14) NULL default NULL;
