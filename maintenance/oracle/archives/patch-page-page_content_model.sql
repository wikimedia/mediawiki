define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.page ADD page_content_model VARCHAR2(32);
