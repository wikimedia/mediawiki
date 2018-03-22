--
-- patch-recentchanges-nttindex.sql
--
-- Per task T57377
--
-- Improve performance API queries to ask for a certain pages
--

ALTER TABLE /*_*/recentchanges
   DROP INDEX /*i*/rc_namespace_title,
   ADD INDEX /*i*/rc_namespace_title_timestamp (rc_namespace, rc_title, rc_timestamp);
