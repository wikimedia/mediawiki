define mw_prefix='{$wgDBprefix}';

CREATE TABLE &mw_prefix.globaltemplatelinks (
	gtl_from_wiki 				VARCHAR2(64) NOT NULL,
	gtl_from_page					NUMBER NOT NULL,
	gtl_from_namespace		NUMBER NOT NULL,
	gtl_from_title				VARCHAR2(255) NOT NULL,
	gtl_to_prefix					VARCHAR2(32) NOT NULL,
	gtl_to_namespace			NUMBER NOT NULL,
	gtl_to_namespacetext	VARCHAR2(255) NOT NULL,
	gtl_to_title					VARCHAR2(255) NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.globaltemplatelinks_u01 ON &mw_prefix.globaltemplatelinks (gtl_to_prefix, gtl_to_namespace, gtl_to_title, gtl_from_wiki, gtl_from_page);
CREATE UNIQUE INDEX &mw_prefix.globaltemplatelinks_u02 ON &mw_prefix.globaltemplatelinks (gtl_from_wiki, gtl_from_page, gtl_to_prefix, gtl_to_namespace, gtl_to_title);

