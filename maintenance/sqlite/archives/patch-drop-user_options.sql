-- Remove user_options field from user table

CREATE TABLE /*_*/user_tmp (
  user_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_name varchar(255) binary NOT NULL default '',
  user_real_name varchar(255) binary NOT NULL default '',
  user_password tinyblob NOT NULL,
  user_newpassword tinyblob NOT NULL,
  user_newpass_time binary(14),
  user_email tinytext NOT NULL,
  user_touched binary(14) NOT NULL default '',
  user_token binary(32) NOT NULL default '',
  user_email_authenticated binary(14),
  user_email_token binary(32),
  user_email_token_expires binary(14),
  user_registration binary(14),
  user_editcount int
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/user_tmp
	SELECT user_id, user_name, user_real_name, user_password, user_newpassword, user_newpass_time, user_email, user_touched,
		user_token, user_email_authenticated, user_email_token, user_email_token_expires, user_registration, user_editcount
		FROM /*_*/user;

DROP TABLE /*_*/user;

ALTER TABLE /*_*/user_tmp RENAME TO /*_*/user;

CREATE UNIQUE INDEX /*i*/user_name ON /*_*/user (user_name);
CREATE INDEX /*i*/user_email_token ON /*_*/user (user_email_token);
CREATE INDEX /*i*/user_email ON /*_*/user (user_email(50));
