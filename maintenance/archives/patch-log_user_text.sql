ALTER TABLE /*$wgDBprefix*/logging
	ADD log_user_text varchar(255) binary NOT NULL default '',
	ADD log_page int unsigned NULL,
	CHANGE log_type log_type varbinary(32) NOT NULL,
	CHANGE log_action log_action varbinary(32) NOT NULL;

CREATE INDEX /*i*/log_user_type_time ON /*_*/logging (log_user, log_type, log_timestamp);
CREATE INDEX /*i*/log_page_id_time ON /*_*/logging (log_page,log_timestamp);
