--
-- Every time an edit by a logged out user is saved,
-- a row is created in ip_changes. This stores
-- the IP as a hex representation so that we can more
-- easily find edits within an IP range.
--
CREATE TABLE /*_*/ip_changes (
  -- Foreign key to the revision table, also serves as the unique primary key
  ipc_rev_id int unsigned NOT NULL PRIMARY KEY DEFAULT '0',

  -- The timestamp of the revision
  ipc_rev_timestamp binary(14) NOT NULL DEFAULT '',

  -- Hex representation of the IP address, as returned by IPUtils::toHex()
  -- For IPv4 it will resemble: ABCD1234
  -- For IPv6: v6-ABCD1234000000000000000000000000
  -- BETWEEN is then used to identify revisions within a given range
  ipc_hex varbinary(35) NOT NULL DEFAULT ''

) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/ipc_rev_timestamp ON /*_*/ip_changes (ipc_rev_timestamp);
CREATE INDEX /*i*/ipc_hex_time ON /*_*/ip_changes (ipc_hex,ipc_rev_timestamp);
