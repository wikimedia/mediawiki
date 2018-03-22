--
-- patch-recentchanges-nttindex.sql
--
-- Per task T57377
--
-- Improve performance API queries to ask for a certain pages
--

DROP INDEX IF EXISTS /*i*/rc_namespace_title;
CREATE INDEX IF NOT EXISTS /*i*/rc_namespace_title_timestamp ON /*_*/recentchanges (rc_namespace, rc_title, rc_timestamp);
