--
-- image-user-index-2.sql
--
-- Add user/timestamp index to current image versions
--

ALTER TABLE /*$wgDBprefix*/image
   ADD INDEX img_user_timestamp (img_user,img_timestamp);
