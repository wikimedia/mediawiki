define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.externallinks MODIFY el_index_60 DEFAULT NULL;
