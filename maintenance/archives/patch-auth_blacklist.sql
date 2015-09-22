-- Track system users who are not allowed to log in

CREATE TABLE /*_*/auth_blacklist (
  -- Key to user_name
  ab_name varchar(255) binary NOT NULL default '',
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/auth_blacklist_name ON /*_*/auth_blacklist (ab_name);
