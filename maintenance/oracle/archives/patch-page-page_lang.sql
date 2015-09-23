define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.page ADD page_lang VARCHAR2(35);
