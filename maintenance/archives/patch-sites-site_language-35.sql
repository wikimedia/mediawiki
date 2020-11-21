ALTER TABLE /*$wgDBprefix*/sites
	MODIFY `site_language`
	VARBINARY(35) NOT NULL;
