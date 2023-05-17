CREATE TABLE /*_*/mw_namespaces (
  `ns_dbname` VARCHAR(64) NOT NULL,
  `ns_namespace_id` INT(4) NOT NULL,
  `ns_namespace_name` VARCHAR(32) NOT NULL,
  `ns_searchable` TINYINT NOT NULL,
  `ns_subpages` TINYINT NOT NULL,
  `ns_content` TINYINT NOT NULL,
  `ns_content_model` VARCHAR(32) NOT NULL,
  `ns_protection` VARCHAR(32) NOT NULL,
  `ns_aliases` LONGTEXT NOT NULL,
  `ns_core` INT(1) NOT NULL DEFAULT 0,
  `ns_additional` LONGTEXT NOT NULL,
  UNIQUE KEY `uniquens`(ns_dbname,ns_namespace_id)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/ns_dbname ON /*_*/mw_namespaces (ns_dbname);
