-- Update pagelinks and categorylinks with null entries ('','') for
-- pages with no pagelinks or categorylinks
-- Change made 2007-09-11 by Andrew Garrett <firstname at epstone dot net>

INSERT INTO /*$wgDBprefix*/pagelinks (pl_from,pl_namespace,pl_title)
SELECT 	page_id,0,''
	FROM /*$wgDBprefix*/page
	LEFT JOIN /*$wgDBprefix*/pagelinks ON page_id=pl_from
	WHERE pl_from IS NULL;

INSERT INTO /*$wgDBprefix*/categorylinks (cl_from,cl_sortkey,cl_to)
SELECT 	page_id,'',0
	FROM /*$wgDBprefix*/page
	LEFT JOIN /*$wgDBprefix*/categorylinks ON page_id=cl_from
	WHERE cl_from IS NULL;
