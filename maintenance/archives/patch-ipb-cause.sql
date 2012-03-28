-- Adding ipb_cause to track the block that caused an autoblock
ALTER TABLE /*$wgDBprefix*/ipblocks
  ADD ipb_cause int DEFAULT NULL;
