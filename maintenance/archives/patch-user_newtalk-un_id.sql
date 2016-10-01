--
-- patch-user_newtalk-un_id.sql
--
-- Bug T146585. Add user_newtalk.un_id.

ALTER TABLE /*$wgDBprefix*/user_newtalk
    ADD COLUMN un_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (un_id);
