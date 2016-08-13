define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.change_tag ADD (
ct_id NUMBER NOT NULL,
);
ALTER TABLE &mw_prefix.change_tag ADD CONSTRAINT &mw_prefix.change_tag_pk PRIMARY KEY (ct_id);
