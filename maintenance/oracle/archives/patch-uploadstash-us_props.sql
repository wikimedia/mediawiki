define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.uploadstash ADD us_props BLOB;

