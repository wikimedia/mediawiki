ALTER TABLE /*_*/redirect MODIFY rd_title VARBINARY(255) NOT NULL default '',
	MODIFY rd_fragment VARBINARY(255) default NULL;
