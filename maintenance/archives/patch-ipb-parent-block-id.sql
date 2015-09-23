-- Adding ipb_parent_block_id to track the block that caused an autoblock
ALTER TABLE /*$wgDBprefix*/ipblocks
  ADD ipb_parent_block_id int DEFAULT NULL;
