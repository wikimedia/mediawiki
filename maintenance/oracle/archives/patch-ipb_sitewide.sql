-- Adding ipb_sitewide for blocks
ALTER TABLE &mw_prefix.ipblocks
  ADD ipb_sitewide CHAR(1) DEFAULT '1' NOT NULL;
