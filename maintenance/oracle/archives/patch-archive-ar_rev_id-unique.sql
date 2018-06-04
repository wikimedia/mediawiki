-- T193180: ar_rev_id should be unique

define mw_prefix='{$wgDBprefix}';

CREATE UNIQUE INDEX &mw_prefix.archive_i04 ON &mw_prefix.archive (ar_rev_id);
DROP INDEX &mw_prefix.archive_i03;
