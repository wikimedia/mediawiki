-- Used for caching expensive grouped queries

CREATE TABLE querycache (
  qc_type char(32) NOT NULL,
  qc_value int(5) unsigned NOT NULL default '0',
  qc_namespace tinyint(2) unsigned NOT NULL default '0',
  qc_title char(255) binary NOT NULL default '',
  KEY (qc_type,qc_value)
);
