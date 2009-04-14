CREATE TABLE /*_*/log_search (
	-- Key to log_type
	ls_log_type varbinary(32) NOT NULL,
	-- Key to log_action
	ls_log_action varbinary(32) NOT NULL,
	-- The type of ID (rev ID, timestamp, other)
	ls_field varbinary(32) NOT NULL,
	-- The value of the ID
	ls_value varchar(255) NOT NULL,
	-- Key to log_id
	ls_log_id int unsigned NOT NULL default 0
);
CREATE INDEX /*i*/log_field_value ON /*_*/log_search (ls_field,ls_value,ls_log_id);
