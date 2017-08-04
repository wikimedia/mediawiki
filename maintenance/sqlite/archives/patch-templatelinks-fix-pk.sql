CREATE TABLE /*_*/templatelinks_tmp (
  -- Key to the page_id of the page containing the link.
  tl_from int unsigned NOT NULL default 0,
  -- Namespace for this page
  tl_from_namespace int NOT NULL default 0,

  -- Key to page_namespace/page_title of the target page.
  -- The target page may or may not exist, and due to renames
  -- and deletions may refer to different page records as time
  -- goes by.
  tl_namespace int NOT NULL default 0,
  tl_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (tl_from,tl_namespace,tl_title)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/templatelinks_tmp
	SELECT * FROM /*_*/templatelinks;

DROP TABLE /*_*/templatelinks;

ALTER TABLE /*_*/templatelinks_tmp RENAME TO /*_*/templatelinks;

-- Reverse index, for Special:Whatlinkshere
CREATE INDEX /*i*/tl_namespace ON /*_*/templatelinks (tl_namespace,tl_title,tl_from);

-- Index for Special:Whatlinkshere with namespace filter
CREATE INDEX /*i*/tl_backlinks_namespace ON /*_*/templatelinks (tl_from_namespace,tl_namespace,tl_title,tl_from);
