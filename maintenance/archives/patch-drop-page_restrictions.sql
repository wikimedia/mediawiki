-- page_restrictions is superseded by the page_restriction table, delete it

ALTER TABLE /*$wgDBprefix*/page DROP COLUMN page_restrictions;
