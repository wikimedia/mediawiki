DROP INDEX IF EXISTS /*i*/pt_namespace_title ON /*_*/protected_titles;
ALTER TABLE /*_*/protected_titles ADD CONSTRAINT PK_protected_titles PRIMARY KEY(pt_namespace,pt_title);