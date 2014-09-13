define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.page ADD page_links_updated TIMESTAMP(6) WITH TIME ZONE;

