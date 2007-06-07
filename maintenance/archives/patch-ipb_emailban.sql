-- Add row for email blocks --

ALTER TABLE /*$wgDBprefix*/ipblocks
	ADD ipb_block_email tinyint(1) NOT NULL default '0';
