-- Add a sort-key to page_restrictions table.
-- First immediate use of this is as a sort-key for coming modifications
-- of Special:Protectedpages.
-- Andrew Garrett, February 2007

ALTER TABLE page_restrictions
	ADD COLUMN pr_id SERIAL,
	ADD UNIQUE KEY pr_id (pr_id);
