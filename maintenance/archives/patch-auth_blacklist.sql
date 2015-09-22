-- Track system users who are not allowed to log in

CREATE TABLE /*_*/auth_blacklist (
  -- Key to user_name
  ab_name int unsigned NOT NULL default 0,
) /*$wgDBTableOptions*/;
