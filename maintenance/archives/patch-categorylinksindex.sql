--
-- patch-categorylinksindex.sql
--
-- Per task T12280 / https://phabricator.wikimedia.org/T12280
--
-- Improve enum continuation performance of the what pages belong to a category query
--

ALTER TABLE /*$wgDBprefix*/categorylinks
   DROP INDEX cl_sortkey,
   ADD INDEX cl_sortkey(cl_to, cl_sortkey, cl_from);
