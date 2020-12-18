--
-- patch-drop-image-img_user_timestamp.sql
--

ALTER TABLE /*_*/image
  DROP INDEX /*i*/img_user_timestamp;
