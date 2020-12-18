ALTER TABLE /*_*/ipblocks DROP INDEX /*i*/ipb_address_unique, ADD UNIQUE INDEX /*i*/ipb_address_unique (ipb_address(255), ipb_user, ipb_auto);
