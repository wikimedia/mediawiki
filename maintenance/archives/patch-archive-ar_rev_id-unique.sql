-- T193180: ar_rev_id should be unique

CREATE UNIQUE INDEX /*i*/ar_revid_uniq ON /*_*/archive (ar_rev_id);
DROP INDEX /*i*/ar_revid ON /*_*/archive;
