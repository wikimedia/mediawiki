-- Add timestamp of protection, performer and reason field
ALTER TABLE /*$wgDBprefix*/page_restrictions
  ADD COLUMN pr_timestamp binary(14) NULL,
  ADD COLUMN pr_performer int unsigned NULL,
  ADD COLUMN pr_reason tinyblob NULL;
