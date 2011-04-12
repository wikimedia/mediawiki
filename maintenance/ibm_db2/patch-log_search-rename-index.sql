CREATE TABLE log_search (
  -- The type of ID (rev ID, log ID, rev TIMESTAMP(3), username)
  ls_field VARCHAR(32) FOR BIT DATA NOT NULL,
  -- The value of the ID
  ls_value varchar(255) NOT NULL,
  -- Key to log_id
  ls_log_id BIGINT NOT NULL default 0
);
