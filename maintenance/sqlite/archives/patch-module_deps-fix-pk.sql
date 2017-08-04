CREATE TABLE /*_*/module_deps_tmp (
  -- Module name
  md_module varbinary(255) NOT NULL,
  -- Module context vary (includes skin and language; called "md_skin" for legacy reasons)
  md_skin varbinary(32) NOT NULL,
  -- JSON blob with file dependencies
  md_deps mediumblob NOT NULL,
  PRIMARY KEY (md_module,md_skin)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/module_deps_tmp
	SELECT * FROM /*_*/module_deps;

DROP TABLE /*_*/module_deps;

ALTER TABLE /*_*/module_deps_tmp RENAME TO /*_*/module_deps;