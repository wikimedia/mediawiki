-- Jobs performed by parallel apache threads or a command-line daemon
CREATE TABLE /*_*/job (
  job_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  
  -- Command name
  -- Limited to 60 to prevent key length overflow
  job_cmd varbinary(60) NOT NULL default '',

  -- Namespace and title to act on
  -- Should be 0 and '' if the command does not operate on a title
  job_namespace int NOT NULL,
  job_title varbinary(255) NOT NULL,

  -- Any other parameters to the command
  -- Stored as a PHP serialized array, or an empty string if there are no parameters
  job_params blob NOT NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/job_cmd ON /*_*/job (job_cmd, job_namespace, job_title, job_params(128));

