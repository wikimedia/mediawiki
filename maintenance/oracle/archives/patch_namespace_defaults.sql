define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.page MODIFY page_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.archive MODIFY ar_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.pagelinks MODIFY pl_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.templatelinks MODIFY tl_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.recentchanges MODIFY rc_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.querycache MODIFY qc_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.logging MODIFY log_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.job MODIFY job_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.redirect MODIFY rd_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.protected_titles MODIFY pt_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.archive MODIFY ar_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.archive MODIFY ar_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.archive MODIFY ar_namespace DEFAULT 0;
ALTER TABLE &mw_prefix.archive MODIFY ar_namespace DEFAULT 0;

