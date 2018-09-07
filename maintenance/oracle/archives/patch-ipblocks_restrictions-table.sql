-- For partial block restrictions --

CREATE TABLE &mw_prefix.ipblocks_restrictions (
  ir_ipb_id NUMBER NOT NULL,
  ir_type NUMBER NOT NULL,
  ir_value NUMBER NOT NULL
);

ALTER TABLE &mw_prefix.ipblocks_restrictions ADD CONSTRAINT ipblocks_restrictions_pk PRIMARY KEY (ir_ipb_id, ir_type, ir_value);

-- Index to query restrictions by the page or namespace.
CREATE INDEX &mw_prefix.ir_type_value ON &mw_prefix.ipblocks_restrictions (ir_type, ir_value);
