CREATE TABLE /*_*/pagelinks_tmp (
  -- Key to the page_id of the page containing the link.
  pl_from int unsigned NOT NULL default 0,
  -- Namespace for this page
  pl_from_namespace int NOT NULL default 0,

  -- Key to page_namespace/page_title of the target page.
  -- The target page may or may not exist, and due to renames
  -- and deletions may refer to different page records as time
  -- goes by.
  pl_namespace int NOT NULL default 0,
  pl_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (pl_from,pl_namespace,pl_title)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/pagelinks_tmp
	SELECT * FROM /*_*/pagelinks;

DROP TABLE /*_*/pagelinks;

ALTER TABLE /*_*/pagelinks_tmp RENAME TO /*_*/pagelinks;

-- Reverse index, for Special:Whatlinkshere
CREATE INDEX /*i*/pl_namespace ON /*_*/pagelinks (pl_namespace,pl_title,pl_from);

-- Index for Special:Whatlinkshere with namespace filter
CREATE INDEX /*i*/pl_backlinks_namespace ON /*_*/pagelinks (pl_from_namespace,pl_namespace,pl_title,pl_from);
