-- Adding ipb_sitewide for blocks
ALTER TABLE /*$wgDBprefix*/ipblocks
  ADD ipb_sitewide bit NOT NULL CONSTRAINT DF_ipb_sitewide DEFAULT 1;
