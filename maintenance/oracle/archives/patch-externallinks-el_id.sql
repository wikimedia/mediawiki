define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.externallinks ADD el_id NUMBER NOT NULL;
ALTER TABLE &mw_prefix.externallinks ADD CONSTRAINT &mw_prefix.externallinks_pk PRIMARY KEY (el_id);