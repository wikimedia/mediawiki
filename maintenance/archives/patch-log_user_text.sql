ALTER TABLE /*$wgDBprefix*/logging 
	ADD log_user_text varchar(255) binary NOT NULL default '',
	ADD log_target_id int unsigned NULL,
	CHANGE log_type log_type varbinary(32) NOT NULL,
	CHANGE log_action log_action varbinary(32) NOT NULL;

CREATE INDEX /*i*/user_type_time ON /*_*/logging (log_user, log_type, log_timestamp);
