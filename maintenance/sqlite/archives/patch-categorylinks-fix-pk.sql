CREATE TABLE /*_*/categorylinks_tmp (
  -- Key to page_id of the page defined as a category member.
  cl_from int unsigned NOT NULL default 0,

  -- Name of the category.
  -- This is also the page_title of the category's description page;
  -- all such pages are in namespace 14 (NS_CATEGORY).
  cl_to varchar(255) binary NOT NULL default '',

  -- A binary string obtained by applying a sortkey generation algorithm
  -- (Collation::getSortKey()) to page_title, or cl_sortkey_prefix . "\n"
  -- . page_title if cl_sortkey_prefix is nonempty.
  cl_sortkey varbinary(230) NOT NULL default '',

  -- A prefix for the raw sortkey manually specified by the user, either via
  -- [[Category:Foo|prefix]] or {{defaultsort:prefix}}.  If nonempty, it's
  -- concatenated with a line break followed by the page title before the sortkey
  -- conversion algorithm is run.  We store this so that we can update
  -- collations without reparsing all pages.
  -- Note: If you change the length of this field, you also need to change
  -- code in LinksUpdate.php. See T27254.
  cl_sortkey_prefix varchar(255) binary NOT NULL default '',

  -- This isn't really used at present. Provided for an optional
  -- sorting method by approximate addition time.
  cl_timestamp timestamp NOT NULL,

  -- Stores $wgCategoryCollation at the time cl_sortkey was generated.  This
  -- can be used to install new collation versions, tracking which rows are not
  -- yet updated.  '' means no collation, this is a legacy row that needs to be
  -- updated by updateCollation.php.  In the future, it might be possible to
  -- specify different collations per category.
  cl_collation varbinary(32) NOT NULL default '',

  -- Stores whether cl_from is a category, file, or other page, so we can
  -- paginate the three categories separately.  This only has to be updated
  -- when moving pages into or out of the category namespace, since file pages
  -- cannot be moved to other namespaces, nor can non-files be moved into the
  -- file namespace.
  cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page',
  PRIMARY KEY (cl_from,cl_to)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/categorylinks_tmp(cl_from, cl_to, cl_sortkey, cl_sortkey_prefix, cl_timestamp, cl_collation, cl_type)
	SELECT cl_from, cl_to, cl_sortkey, cl_sortkey_prefix, cl_timestamp, cl_collation, cl_type
		FROM /*_*/categorylinks;

DROP TABLE /*_*/categorylinks;

ALTER TABLE /*_*/categorylinks_tmp RENAME TO /*_*/categorylinks;

-- We always sort within a given category, and within a given type.  FIXME:
-- Formerly this index didn't cover cl_type (since that didn't exist), so old
-- callers won't be using an index: fix this?
CREATE INDEX /*i*/cl_sortkey ON /*_*/categorylinks (cl_to,cl_type,cl_sortkey,cl_from);

-- Used by the API (and some extensions)
CREATE INDEX /*i*/cl_timestamp ON /*_*/categorylinks (cl_to,cl_timestamp);

-- Used when updating collation (e.g. updateCollation.php)
CREATE INDEX /*i*/cl_collation_ext ON /*_*/categorylinks (cl_collation, cl_to, cl_type, cl_from);
