-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-drop-cl_collation_ext.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TEMPORARY TABLE /*_*/__temp__categorylinks AS
SELECT
  cl_from,
  cl_to,
  cl_sortkey,
  cl_sortkey_prefix,
  cl_timestamp,
  cl_collation,
  cl_type
FROM /*_*/categorylinks;

DROP TABLE /*_*/categorylinks;


CREATE TABLE /*_*/categorylinks (
  cl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  cl_to BLOB DEFAULT '' NOT NULL,
  cl_sortkey BLOB DEFAULT '' NOT NULL,
  cl_sortkey_prefix BLOB DEFAULT '' NOT NULL,
  cl_timestamp DATETIME NOT NULL,
  cl_collation BLOB DEFAULT '' NOT NULL,
  cl_type TEXT DEFAULT 'page' NOT NULL,
  PRIMARY KEY(cl_from, cl_to)
);

INSERT INTO /*_*/categorylinks (
  cl_from, cl_to, cl_sortkey, cl_sortkey_prefix,
  cl_timestamp, cl_collation, cl_type
)
SELECT
  cl_from,
  cl_to,
  cl_sortkey,
  cl_sortkey_prefix,
  cl_timestamp,
  cl_collation,
  cl_type
FROM
  /*_*/__temp__categorylinks;

DROP TABLE /*_*/__temp__categorylinks;

CREATE INDEX cl_sortkey ON /*_*/categorylinks (
  cl_to, cl_type, cl_sortkey, cl_from
);

CREATE INDEX cl_timestamp ON /*_*/categorylinks (cl_to, cl_timestamp);
