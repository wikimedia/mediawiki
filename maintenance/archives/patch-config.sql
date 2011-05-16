-- Table for holding configuration changes
CREATE TABLE /*_*/config (
  -- Config var name
  cf_name varbinary(255) NOT NULL PRIMARY KEY,
  -- Config var value
  cf_value blob NOT NULL,
) /*$wgDBTableOptions*/;
-- Should cover *most* configuration - strings, ints, bools, etc.
CREATE INDEX /*i*/cf_name_value ON /*_*/config (cf_name,cf_value(255));
