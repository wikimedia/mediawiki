-- recentchanges improvements --

ALTER TABLE recentchanges
  ADD rc_type tinyint(3) unsigned NOT NULL default '0',
  ADD rc_moved_to_ns tinyint(3) unsigned NOT NULL default '0',
  ADD rc_moved_to_title varchar(255) binary NOT NULL default '';
