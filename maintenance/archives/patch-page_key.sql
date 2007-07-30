ALTER TABLE /*$wgDBprefix*/page
	ADD page_key VARCHAR(255) BINARY NOT NULL;
ALTER TABLE /*$wgDBprefix*/page
	ADD INDEX name_key (page_namespace, page_key);

