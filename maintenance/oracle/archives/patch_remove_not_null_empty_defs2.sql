define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.ipblocks MODIFY ipb_by_text DEFAULT NULL NULL;
