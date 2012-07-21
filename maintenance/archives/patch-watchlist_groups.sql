CREATE TABLE watchlist_groups (
  wg_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  wg_name varchar(255) binary NOT NULL,

  -- Key to user.user_id for owner of the watchlist
  wg_user int unsigned NOT NULL,

  -- Permissions bool
  wg_perm tinyint NOT NULL default 0

) /*$wgDBTableOptions*/;