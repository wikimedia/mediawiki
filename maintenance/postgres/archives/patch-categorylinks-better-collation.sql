CREATE TYPE link_type AS ENUM ('page', 'subcat', 'file');
DROP INDEX cl_sortkey;
ALTER TABLE categorylinks
	ADD COLUMN cl_raw_sortkey TEXT NULL DEFAULT NULL,
	ADD COLUMN cl_collation SMALLINT NOT NULL DEFAULT 0,
	ADD COLUMN cl_type link_type NOT NULL DEFAULT 'page';
CREATE INDEX cl_collation ON categorylinks ( cl_collation );
CREATE INDEX cl_sortkey ON categorylinks ( cl_to, cl_type, cl_sortkey, cl_from );
