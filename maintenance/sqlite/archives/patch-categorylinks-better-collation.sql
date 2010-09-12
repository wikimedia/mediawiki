ALTER TABLE /*_*/categorylinks ADD COLUMN cl_sortkey_prefix TEXT binary NOT NULL default '';
ALTER TABLE /*_*/categorylinks ADD COLUMN cl_collation BLOB NOT NULL default '';
ALTER TABLE /*_*/categorylinks ADD COLUMN cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page';
CREATE INDEX cl_collation ON /*_*/categorylinks (cl_collation);
DROP INDEX cl_sortkey;
CREATE INDEX cl_sortkey ON /*_*/categorylinks (cl_to, cl_type, cl_sortkey, cl_from);
INSERT IGNORE INTO /*_*/updatelog (ul_key) VALUES ('cl_fields_update');
