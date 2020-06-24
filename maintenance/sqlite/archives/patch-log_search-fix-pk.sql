CREATE TABLE /*_*/log_search_tmp (
  -- The type of ID (rev ID, log ID, rev timestamp, username)
  ls_field varbinary(32) NOT NULL,
  -- The value of the ID
  ls_value varchar(255) NOT NULL,
  -- Key to log_id
  ls_log_id int unsigned NOT NULL default 0,
  PRIMARY KEY (ls_field,ls_value,ls_log_id)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/log_search_tmp(ls_field, ls_value, ls_log_id)
	SELECT ls_field, ls_value, ls_log_id FROM /*_*/log_search;

DROP TABLE /*_*/log_search;

ALTER TABLE /*_*/log_search_tmp RENAME TO /*_*/log_search;

CREATE INDEX /*i*/ls_log_id ON /*_*/log_search (ls_log_id);
