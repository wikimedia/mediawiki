--
-- patch-rc_new_len.sql
-- Adds a row to recentchanges to hold the text size after the edit
-- 2006-12-03
--

ALTER TABLE /*$wgDBprefix*/recentchanges
	ADD COLUMN rc_new_len int(10) default 0;

