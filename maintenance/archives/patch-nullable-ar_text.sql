--
-- patch-nullable-ar_text.sql
--
-- This patch is provided as an example for people not using update.php.

ALTER TABLE /*_*/archive
  MODIFY COLUMN ar_text mediumblob NULL,
  MODIFY COLUMN ar_flags tinyblob NULL;
