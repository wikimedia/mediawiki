-- Used for caching expensive grouped queries

CREATE TABLE /*$wgDBprefix*/querycache (
  -- A key name, generally the base name of of the special page.
  qc_type char(32) NOT NULL,
  
  -- Some sort of stored value. Sizes, counts...
  qc_value int(5) unsigned NOT NULL default '0',
  
  -- Target namespace+title
  qc_namespace int NOT NULL default '0',
  qc_title char(255) binary NOT NULL default '',
  
  KEY (qc_type,qc_value)

) TYPE=InnoDB;
