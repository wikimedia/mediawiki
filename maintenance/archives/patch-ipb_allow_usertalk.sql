-- Adding ipb_allow_usertalk for blocks
ALTER TABLE /*$wgDBprefix*/ipblocks
  ADD ipb_allow_usertalk bool NOT NULL default 1;
