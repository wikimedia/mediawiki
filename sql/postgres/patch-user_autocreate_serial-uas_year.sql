-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-user_autocreate_serial-uas_year.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
ALTER TABLE user_autocreate_serial
  DROP CONSTRAINT user_autocreate_serial_pkey;

ALTER TABLE user_autocreate_serial
  ADD uas_year SMALLINT NOT NULL;

ALTER TABLE user_autocreate_serial
  ADD PRIMARY KEY (uas_shard, uas_year);
