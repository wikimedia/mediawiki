ALTER TABLE /*_*/categorylinks ADD COLUMN cl_sortkey_prefix TEXT NOT NULL default '';
ALTER TABLE /*_*/categorylinks ADD COLUMN cl_collation BLOB NOT NULL default '';
ALTER TABLE /*_*/categorylinks ADD COLUMN cl_type TEXT NOT NULL default 'page';
CREATE INDEX cl_collation ON /*_*/categorylinks (cl_collation);
DROP INDEX cl_sortkey;
CREATE INDEX cl_sortkey ON /*_*/categorylinks (cl_to, cl_type, cl_sortkey, cl_from);
INSERT OR IGNORE INTO /*_*/updatelog (ul_key) VALUES ('cl_fields_update');
