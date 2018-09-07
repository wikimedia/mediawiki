-- For partial block restrictions --

CREATE TABLE ipblocks_restrictions (
  ir_ipb_id INTEGER  NOT NULL REFERENCES ipblocks(ipb_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  ir_type   SMALLINT NOT NULL,
  ir_value  INTEGER  NOT NULL,
  PRIMARY KEY (ir_ipb_id, ir_type, ir_value)
);

-- Index to query restrictions by the page or namespace.
CREATE INDEX /*i*/ir_type_value ON /*_*/ipblocks_restrictions (ir_type, ir_value);
