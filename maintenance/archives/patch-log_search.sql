CREATE TABLE /*_*/log_search (
	-- The type of ID (rev ID, log ID, timestamp, other)
	ls_field varbinary(32) NOT NULL,
	-- The value of the ID
	ls_value varchar(255) NOT NULL,
	-- Key to log_id
	ls_log_id int unsigned NOT NULL default 0,
	PRIMARY KEY (ls_field,ls_value,ls_log_id)
);
CREATE INDEX /*i*/ls_log_id ON /*_*/log_search (ls_log_id);
