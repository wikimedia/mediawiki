--
-- This table contains a user's bot passwords: passwords that allow access to
-- the account via the API with limited rights.
--
CREATE TABLE /*_*/bot_passwords (
  -- Foreign key to user.user_id
  bp_user int NOT NULL,

  -- Application identifier
  bp_app_id varbinary(32) NOT NULL,

  -- Password hashes, like user.user_password
  bp_password tinyblob NOT NULL,

  -- Like user.user_token
  bp_token binary(32) NOT NULL default '',

  -- JSON blob for MWRestrictions
  bp_restrictions blob NOT NULL,

  -- Grants allowed to the account when authenticated with this bot-password
  bp_grants blob NOT NULL,

  PRIMARY KEY ( bp_user, bp_app_id )
) /*$wgDBTableOptions*/;
