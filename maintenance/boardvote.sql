-- Board of Trustees vote

CREATE TABLE log (
	log_id int(5) not null auto_increment,
	log_user int(5) not null default 0,
	log_user_text varchar(255) binary not null default '',
	log_user_key varchar(255) binary not null default '',
	log_wiki char(32) not null default '',
	log_edits int(5) not null default 0,
	log_days int(5) not null default 0,
	log_record blob not null default '',
	log_ip char(16) not null default '',
	log_xff varchar(255) not null default '',
	log_ua varchar(255) not null default '',
	log_timestamp char(14) not null default '',
	log_current tinyint(1) not null default 0,
	unique index log_id (log_id),
	index log_user_key (log_user_key)
);


