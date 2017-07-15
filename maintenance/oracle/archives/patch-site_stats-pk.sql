define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.site_stats DROP CONSTRAINT &mw_prefix.site_stats_u01;
ALTER TABLE &mw_prefix.site_stats ADD CONSTRAINT &mw_prefix.site_stats_pk PRIMARY KEY(ss_row_id);
