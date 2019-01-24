CREATE TABLE /*_*/imagelinks_tmp (
  -- Key to page_id of the page containing the image / media link.
  il_from int unsigned NOT NULL default 0,
  -- Namespace for this page
  il_from_namespace int NOT NULL default 0,

  -- Filename of target image.
  -- This is also the page_title of the file's description page;
  -- all such pages are in namespace 6 (NS_FILE).
  il_to varchar(255) binary NOT NULL default '',
  PRIMARY KEY (il_from,il_to)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/imagelinks_tmp (il_from, il_from_namespace, il_to)
	SELECT il_from, il_from_namespace, il_to FROM /*_*/imagelinks;

DROP TABLE /*_*/imagelinks;

ALTER TABLE /*_*/imagelinks_tmp RENAME TO /*_*/imagelinks;

-- Reverse index, for Special:Whatlinkshere and file description page local usage
CREATE INDEX /*i*/il_to ON /*_*/imagelinks (il_to,il_from);

-- Index for Special:Whatlinkshere with namespace filter
CREATE INDEX /*i*/il_backlinks_namespace ON /*_*/imagelinks (il_from_namespace,il_to,il_from);