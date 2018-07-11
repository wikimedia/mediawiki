DROP INDEX IF EXISTS /*i*/site_ids_type ON /*_*/site_identifiers;
ALTER TABLE /*_*/site_identifiers ADD CONSTRAINT PK_site_identifiers PRIMARY KEY(si_type, si_key);