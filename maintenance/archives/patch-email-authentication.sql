-- Patch for email authentication T.Gries/M.Arndt 27.11.2004
-- A new column is added to the table 'user'.
ALTER TABLE /*$wgDBprefix*/user ADD (user_emailauthenticationtimestamp varchar(14) binary NOT NULL default '0');
