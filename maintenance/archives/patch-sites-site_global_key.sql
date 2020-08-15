-- T260468
ALTER TABLE /*$wgDBprefix*/sites
	MODIFY `site_global_key` varbinary(64) NOT NULL;
