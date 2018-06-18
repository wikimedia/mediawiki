-- Adding ipb_sitewide for blocks
ALTER TABLE /*$wgDBprefix*/ipblocks
  ADD ipb_sitewide bool NOT NULL default 1;
