-- T182678: Make ar_rev_id not nullable

define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive MODIFY ar_rev_id NUMBER NOT NULL;
