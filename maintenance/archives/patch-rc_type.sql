-- recentchanges improvements --

ALTER TABLE recentchanges
  ADD rc_type tinyint(3) unsigned NOT NULL default '0',
  ADD rc_moved_to_ns tinyint(3) unsigned NOT NULL default '0',
  ADD rc_moved_to_title varchar(255) binary NOT NULL default '';

UPDATE recentchanges SET rc_type=1 WHERE rc_new;
UPDATE recentchanges SET rc_type=3 WHERE rc_namespace=4 AND (rc_title='Deletion_log' OR rc_title='Upload_log');
