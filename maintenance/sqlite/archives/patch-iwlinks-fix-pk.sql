CREATE TABLE /*_*/iwlinks_tmp (
  -- page_id of the referring page
  iwl_from int unsigned NOT NULL default 0,

  -- Interwiki prefix code of the target
  iwl_prefix varbinary(20) NOT NULL default '',

  -- Title of the target, including namespace
  iwl_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (iwl_from,iwl_prefix,iwl_title)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/iwlinks_tmp(iwl_from, iwl_prefix, iwl_title)
	SELECT iwl_from, iwl_prefix, iwl_title FROM /*_*/iwlinks;

DROP TABLE /*_*/iwlinks;

ALTER TABLE /*_*/iwlinks_tmp RENAME TO /*_*/iwlinks;

-- Index for ApiQueryIWBacklinks
CREATE INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);

-- Index for ApiQueryIWLinks
CREATE INDEX /*i*/iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);
