define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.categorylinks MODIFY cl_sortkey_prefix DEFAULT NULL NULL;
ALTER TABLE &mw_prefix.categorylinks MODIFY cl_collation DEFAULT NULL NULL;
ALTER TABLE &mw_prefix.iwlinks MODIFY iwl_prefix DEFAULT NULL NULL;
ALTER TABLE &mw_prefix.iwlinks MODIFY iwl_title DEFAULT NULL NULL;
ALTER TABLE &mw_prefix.searchindex MODIFY si_title DEFAULT NULL NULL;
ALTER TABLE &mw_prefix.querycachetwo MODIFY qcc_title DEFAULT NULL NULL;
ALTER TABLE &mw_prefix.querycachetwo MODIFY qcc_titletwo DEFAULT NULL NULL;
