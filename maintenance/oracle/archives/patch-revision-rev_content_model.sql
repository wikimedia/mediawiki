define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.revision ADD rev_content_model VARCHAR2(32);
