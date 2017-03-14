--
-- image-user-index.sql
--
-- Add user_text/timestamp index to current image versions
--

ALTER TABLE /*$wgDBprefix*/image
   ADD INDEX img_usertext_timestamp (img_user_text,img_timestamp);
