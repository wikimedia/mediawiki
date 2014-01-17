ALTER TABLE recentchanges ADD column rc_moved_to_ns tinyint unsigned NOT NULL default 0 AFTER rc_type;
ALTER TABLE recentchanges ADD column rc_moved_to_title varchar(255) binary NOT NULL default '' AFTER rc_moved_to_ns;
