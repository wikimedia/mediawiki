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
	unique key log_id (log_id)
);

CREATE TABLE vote (
	vote_key varchar(255) not null default '',
	vote_record blob not null default '',
	vote_contributing text not null default '',
	vote_volunteer text not null default '',
	unique key vote_key (vote_key)
);


