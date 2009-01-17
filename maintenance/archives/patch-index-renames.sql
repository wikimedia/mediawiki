-- 
-- patch-index-renames.sql
-- 
-- Rename three indices because of naming conflicts

DROP INDEX user_id ON /*_*/user_newtalk;
DROP INDEX user_ip ON /*_*/user_newtalk;
DROP INDEX usertext_timestamp ON /*_*/archive;
CREATE INDEX un_user_id ON /*_*/user_newtalk (user_id);
CREATE INDEX un_user_ip ON /*_*/user_newtalk (user_ip);
CREATE INDEX ar_usertext_timestamp ON /*_*/archive (ar_user_text,ar_timestamp);