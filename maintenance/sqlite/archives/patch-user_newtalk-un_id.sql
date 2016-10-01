DROP TABLE IF EXISTS /*_*/user_newtalk_tmp;

CREATE TABLE /*$wgDBprefix*/user_newtalk_tmp (
  un_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id int unsigned NOT NULL default 0,
  user_ip varbinary(40) NOT NULL default '',
  user_last_timestamp varbinary(14) NULL default NULL
);

INSERT OR IGNORE INTO /*_*/user_newtalk_tmp (
    user_id, user_ip, user_last_timestamp )
    SELECT
    user_id, user_ip, user_last_timestamp
    FROM /*_*/user_newtalk;

DROP TABLE /*_*/user_newtalk;

ALTER TABLE /*_*/user_newtalk_tmp RENAME TO /*_*/user_newtalk;

CREATE INDEX /*i*/un_user_id ON /*_*/user_newtalk (user_id);
CREATE INDEX /*i*/un_user_ip ON /*_*/user_newtalk (user_ip);