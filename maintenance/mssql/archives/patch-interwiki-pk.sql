DROP INDEX IF EXISTS /*i*/iw_prefix ON /*_*/interwiki;
ALTER TABLE /*_*/interwiki ADD CONSTRAINT PK_interwiki PRIMARY KEY(iw_prefix);