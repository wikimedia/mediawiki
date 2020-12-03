ALTER TABLE /*_*/categorylinks MODIFY cl_to VARBINARY(255) DEFAULT '' NOT NULL;
ALTER TABLE /*_*/categorylinks MODIFY cl_sortkey_prefix VARBINARY(255) DEFAULT '' NOT NULL;
