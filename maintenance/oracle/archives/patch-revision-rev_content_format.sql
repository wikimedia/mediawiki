define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.revision ADD rev_content_format VARCHAR2(64);
