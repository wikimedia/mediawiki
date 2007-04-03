-- Adding rc_deleted field for revisiondelete
-- Add rc_logid to match log_id
ALTER TABLE /*$wgDBprefix*/recentchanges 
  ADD rc_deleted tinyint(1) unsigned NOT NULL default '0',
  ADD rc_logid int(10) unsigned NOT NULL default '0',
  ADD rc_log_type varchar(255) binary NULL default NULL,
  ADD rc_log_action varchar(255) binary NULL default NULL,
  ADD rc_params blob NOT NULL default '';
