-- For partial block restrictions --

CREATE TABLE /*_*/ipblocks_restrictions (
  ir_ipb_id int NOT NULL,
  ir_type tinyint NOT NULL,
  ir_value int NOT NULL,
  PRIMARY KEY (ir_ipb_id, ir_type, ir_value)
) /*$wgDBTableOptions*/;

-- Index to query restrictions by the page or namespace.
CREATE INDEX /*i*/ir_type_value ON /*_*/ipblocks_restrictions (ir_type, ir_value);
