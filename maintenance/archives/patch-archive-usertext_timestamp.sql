ALTER TABLE /*$wgDBprefix*/archive
	ADD INDEX usertext_timestamp ( ar_user_text , ar_timestamp );