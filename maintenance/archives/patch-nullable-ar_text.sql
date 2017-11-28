--
-- patch-nullable-ar_text.sql
--
-- This patch is provided as an example for people not using update.php.
-- You need to make a change like this before running a version of MediaWiki
-- containing Gerrit change 5ca2d4a551, then you can run maintenance/migrateArchiveText.php
-- and apply patch-drop-ar_text.sql at your leisure.
--
-- See also T33223.

ALTER TABLE /*_*/archive
  MODIFY COLUMN ar_text mediumblob NULL,
  MODIFY COLUMN ar_flags tinyblob NULL;
