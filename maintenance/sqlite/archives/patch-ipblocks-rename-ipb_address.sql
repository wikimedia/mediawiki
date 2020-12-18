DROP INDEX /*i*/ipb_address;
CREATE UNIQUE INDEX /*i*/ipb_address_unique ON /*_*/ipblocks (ipb_address(255), ipb_user, ipb_auto);
