
-- Table for tracking blobs prior to recompression or similar maintenance operations

CREATE TABLE /*$wgDBprefix*/blob_tracking (
	-- page.page_id
	-- This may be zero for orphan or deleted text
	bt_page integer not null,

	-- revision.rev_id
	-- This may be zero for orphan or deleted text
	bt_rev_id integer not null,

	-- text.old_id
	bt_text_id integer not null,

	-- The ES cluster
	bt_cluster varbinary(255),

	-- The ES blob ID
	bt_blob_id integer not null,

	-- The CGZ content hash, or null
	bt_cgz_hash varbinary(255),

	PRIMARY KEY (bt_rev_id, bt_text_id),

	-- Sort by page for easy CGZ recompression
	KEY (bt_page, bt_rev_id),

	-- For fast orphan searches
	KEY (bt_text_id),

	-- Key for determining the revisions using a given blob
	KEY (bt_cluster, bt_blob_id, bt_cgz_hash)
) /*$wgDBTableOptions*/;

-- Tracking table for blob rows that aren't tracked by the text table
CREATE TABLE /*$wgDBprefix*/blob_orphans (
	bo_cluster varbinary(255),
	bo_blob_id integer not null,

	PRIMARY KEY (bo_cluster, bo_blob_id)
) /*$wgDBTableOptions*/;

