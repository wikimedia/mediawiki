-- Used for caching expensive grouped queries that need two links (for example double-redirects)

CREATE TABLE /*$wgDBprefix*/querycachetwo (
  -- A key name, generally the base name of of the special page.
  qcc_type char(32) NOT NULL,
  
  -- Some sort of stored value. Sizes, counts...
  qcc_value int(5) unsigned NOT NULL default '0',
  
  -- Target namespace+title
  qcc_namespace int NOT NULL default '0',
  qcc_title char(255) binary NOT NULL default '',
  
  -- Target namespace+title2
  qcc_namespacetwo int NOT NULL default '0',
  qcc_titletwo char(255) binary NOT NULL default '',

  KEY qcc_type (qcc_type,qcc_value),
  KEY qcc_title (qcc_type,qcc_namespace,qcc_title),
  KEY qcc_titletwo (qcc_type,qcc_namespacetwo,qcc_titletwo)

) /*$wgDBTableOptions*/;
