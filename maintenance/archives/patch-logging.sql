-- Add the logging table and adjust recentchanges to accomodate special pages
-- 2004-08-24

CREATE TABLE logging (
  -- Symbolic keys for the general log type and the action type
  -- within the log. The output format will be controlled by the
  -- action field, but only the type controls categorization.
  log_type char(10) NOT NULL default '',
  log_action char(10) NOT NULL default '',
  
  -- Timestamp. Duh.
  log_timestamp char(14) NOT NULL default '19700101000000',
  
  -- The user who performed this action; key to user_id
  log_user int unsigned NOT NULL default 0,
  
  -- Key to the page affected. Where a user is the target,
  -- this will point to the user page.
  log_namespace tinyint unsigned NOT NULL default 0,
  log_title varchar(255) NOT NULL default '',
  
  -- Freeform text. Interpreted as edit history comments.
  log_comment varchar(255) NOT NULL default '',
  
  KEY type_time (log_type, log_timestamp),
  KEY user_time (log_user, log_timestamp),
  KEY page_time (log_namespace, log_title, log_timestamp)
);

-- Change from unsigned to signed so we can store special pages
ALTER TABLE recentchanges
  MODIFY rc_namespace tinyint(3) NOT NULL default '0';
