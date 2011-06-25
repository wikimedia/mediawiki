-- Stores the groups the user has once belonged to. 
-- The user may still belong these groups. Check user_groups.
CREATE TABLE /*_*/user_former_groups (
  -- Key to user_id
  ufg_user int unsigned NOT NULL default 0,
  ufg_group varbinary(16) NOT NULL default '',
  
  PRIMARY KEY (ufg_user,ufg_group),
  KEY (ufg_group)
) /*$wgDBTableOptions*/;
