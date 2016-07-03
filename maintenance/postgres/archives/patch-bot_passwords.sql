CREATE TABLE bot_passwords (
  bp_user INTEGER NOT NULL,
  bp_app_id TEXT NOT NULL,
  bp_password TEXT NOT NULL,
  bp_token TEXT NOT NULL,
  bp_restrictions TEXT NOT NULL,
  bp_grants TEXT NOT NULL,
  PRIMARY KEY ( bp_user, bp_app_id )
);
