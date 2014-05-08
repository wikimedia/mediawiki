-- jobs that temporarily should not be executed
CREATE TABLE /*_*/paused_job (
  -- Command name to pause
  paused_job_cmd varbinary(60) NOT NULL PRIMARY KEY
) /*$wgDBTableOptions*/;
