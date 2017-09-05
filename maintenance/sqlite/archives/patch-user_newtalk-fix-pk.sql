CREATE TABLE /*_*/user_newtalk_tmp (
  -- Key to user.user_id
  user_id int unsigned NOT NULL default 0,
  -- If the user is an anonymous user their IP address is stored here
  -- since the user_id of 0 is ambiguous
  user_ip varbinary(40) NOT NULL default '',
  -- The highest timestamp of revisions of the talk page viewed
  -- by this user
  user_last_timestamp varbinary(14) NULL default NULL,
  PRIMARY KEY (user_id, user_ip)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/user_newtalk_tmp
	SELECT * FROM /*_*/user_newtalk;

DROP TABLE /*_*/user_newtalk;

ALTER TABLE /*_*/user_newtalk_tmp RENAME TO /*_*/user_newtalk;

CREATE INDEX /*i*/un_user_ip ON /*_*/user_newtalk (user_ip);
