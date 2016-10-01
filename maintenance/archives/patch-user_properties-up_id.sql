--
-- patch-user_properties-up_id.sql
--
-- Bug T146570. Add user_properties.up_id.

ALTER TABLE /*$wgDBprefix*/user_properties
    ADD COLUMN up_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (up_id);
