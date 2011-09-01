define mw_prefix='{$wgDBprefix}';

CREATE TABLE &mw_prefix.globalnamespaces (
	gn_wiki						VARCHAR2(64) NOT NULL,
	gn_namespace			NUMBER NOT NULL,
	gn_namespacetext	VARCHAR2(255) NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.globalnamespaces_u01 ON &mw_prefix.globalnamespaces (gn_wiki, gn_namespace, gn_namespacetext);

