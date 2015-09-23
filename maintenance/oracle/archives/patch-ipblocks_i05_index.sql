define mw_prefix='{$wgDBprefix}';

CREATE INDEX &mw_prefix.ipblocks_i05 ON &mw_prefix.ipblocks (ipb_parent_block_id);

