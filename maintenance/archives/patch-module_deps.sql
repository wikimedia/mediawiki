-- Table for tracking which local files a module depends on that aren't
-- registered directly.
-- Currently only used for tracking images that CSS depends on
CREATE TABLE /*_*/module_deps (
  -- Module name
  md_module varbinary(255) NOT NULL,
  -- Skin name
  md_skin varbinary(32) NOT NULL,
  -- JSON blob with file dependencies
  md_deps mediumblob NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/md_module_skin ON /*_*/module_deps (md_module, md_skin);
