-- Add an index to archive on ar_user_text, ar_timestamp
--
-- Added 2006-11-27
--

     ALTER TABLE /*$wgDBprefix*/archive
ADD INDEX usertext_timestamp (ar_user_text, ar_timestamp);