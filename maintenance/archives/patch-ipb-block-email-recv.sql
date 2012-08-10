-- Adding ipb_block_email_recv to block a user from receiving emails.
ALTER TABLE /*$wgDBprefix*/ipblocks
  ADD ipb_block_email_recv bool NOT NULL DEFAULT 0;
