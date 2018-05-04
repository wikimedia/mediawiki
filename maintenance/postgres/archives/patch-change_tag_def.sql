-- Table defining tag names for IDs. Also stores hit counts to avoid expensive queries on change_tag

CREATE SEQUENCE change_tag_def_ctd_id_seq;
CREATE TABLE change_tag_def (
    ctd_id int unsigned NOT NULL PRIMARY KEY DEFAULT nextval('change_tag_def_ctd_id_seq'),
    ctd_name TEXT NOT NULL,
    ctd_user_defined SMALLINT NOT NULL DEFAULT 0,
    ctd_count INTEGER NOT NULL DEFAULT 0,
    ctd_timestamp TIMESTAMPTZ
);

CREATE UNIQUE INDEX ctd_name ON change_tag_def (ctd_name);
CREATE INDEX ctd_count ON change_tag_def (ctd_count);