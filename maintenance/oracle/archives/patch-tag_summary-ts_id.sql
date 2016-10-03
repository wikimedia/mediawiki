define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.tag_summary ADD (
ts_id NUMBER NOT NULL,
);
ALTER TABLE &mw_prefix.tag_summary ADD CONSTRAINT &mw_prefix.tag_summary_pk PRIMARY KEY (ts_id);
