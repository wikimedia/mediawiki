-- For setting a password expiration date for users
ALTER TABLE /*$wgDBprefix*/user
  ADD COLUMN user_password_expires binary(14) DEFAULT NULL;
