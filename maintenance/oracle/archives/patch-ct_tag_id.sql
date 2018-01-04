define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.change_tag ADD (
ct_tag_id NUMBER NOT NULL,
);
