-- Used for killing the wrong index added during SVN for 1.17
-- Won't affect most people, but it doesn't need to exist
ALTER TABLE /*$wgDBprefix*/archive
	DROP INDEX ar_page_revid;