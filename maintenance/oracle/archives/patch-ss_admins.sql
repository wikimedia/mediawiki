define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.site_stats DROP COLUMN ss_admins;

