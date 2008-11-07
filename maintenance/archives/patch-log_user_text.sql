ALTER TABLE /*$wgDBprefix*/logging 
	ADD log_user_text varchar(255) binary NOT NULL default '',
	ADD log_target_id int unsigned NULL,
	CHANGE `log_type` `log_type` VARBINARY( 15 ) NOT NULL,
	CHANGE `log_action` `log_action` VARBINARY( 15 ) NOT NULL;
