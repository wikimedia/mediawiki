define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive ADD (
ar_id NUMBER NOT NULL,
);
ALTER TABLE &mw_prefix.archive ADD CONSTRAINT &mw_prefix.archive_pk PRIMARY KEY (ar_id);
