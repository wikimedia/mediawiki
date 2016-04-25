--
-- This table contains a user's bot passwords: passwords that allow access to
-- the account via the API with limited rights.
--
CREATE TABLE /*_*/bot_passwords (
	bp_user int NOT NULL REFERENCES /*_*/mwuser(user_id) ON DELETE CASCADE,
	bp_app_id nvarchar(32) NOT NULL,
	bp_password nvarchar(255) NOT NULL,
	bp_token nvarchar(255) NOT NULL,
	bp_restrictions nvarchar(max) NOT NULL,
	bp_grants nvarchar(max) NOT NULL,
	PRIMARY KEY (bp_user, bp_app_id)
);
