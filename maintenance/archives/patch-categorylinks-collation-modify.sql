--
-- patch-categorylinks-collation-modify.sql
--
-- Bugs T158724, T146341. This patch increases the maximum size of cl_collation.
ALTER TABLE /*_*/categorylinks
	MODIFY COLUMN cl_collation varbinary(50) NOT NULL default '';
