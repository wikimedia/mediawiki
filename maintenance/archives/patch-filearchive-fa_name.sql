ALTER TABLE /*_*/filearchive
    MODIFY fa_name VARBINARY(255) DEFAULT '' NOT NULL,
    MODIFY fa_archive_name VARBINARY(255) DEFAULT '';
