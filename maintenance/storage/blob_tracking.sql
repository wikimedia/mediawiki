
-- Table for tracking blobs prior to recompression or similar maintenance operations

CREATE TABLE /*$wgDBprefix*/blob_tracking (
	-- page.page_id
	-- This may be zero for orphan or deleted text
	-- Note that this is for compression grouping only -- it doesn't need to be
	-- accurate at the time recompressTracked is run. Operations such as a
	-- delete/undelete cycle may make it inaccurate.
	bt_page integer not null,

	-- revision.rev_id
	-- This may be zero for orphan or deleted text
	-- Like bt_page, it does not need to be accurate when recompressTracked is run.
	bt_rev_id integer not null,

	-- text.old_id
	bt_text_id integer not null,

	-- The ES cluster
	bt_cluster varbinary(255),

	-- The ES blob ID
	bt_blob_id integer not null,

	-- The CGZ content hash, or null
	bt_cgz_hash varbinary(255),

	-- The URL this blob is to be moved to
	bt_new_url varbinary(255),

	-- True if the text table has been updated to point to bt_new_url
	bt_moved bool not null default 0,

	-- Primary key
	-- Note that text_id is not unique due to null edits (protection, move)
	-- moveTextRow(), commit(), trackOrphanText()
	PRIMARY KEY (bt_text_id, bt_rev_id),

	-- Sort by page for easy CGZ recompression
	-- doAllPages(), doAllOrphans(), doPage(), finishIncompleteMoves()
	KEY (bt_moved, bt_page, bt_text_id),

	-- Key for determining the revisions using a given blob
	-- Not used by any scripts yet
	KEY (bt_cluster, bt_blob_id, bt_cgz_hash)

) /*$wgDBTableOptions*/;

-- Tracking table for blob rows that aren't tracked by the text table
CREATE TABLE /*$wgDBprefix*/blob_orphans (
	bo_cluster varbinary(255),
	bo_blob_id integer not null,

	PRIMARY KEY (bo_cluster, bo_blob_id)
) /*$wgDBTableOptions*/;
