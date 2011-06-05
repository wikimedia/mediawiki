-- Hopefully temporary index.
-- For https://bugzilla.wikimedia.org/show_bug.cgi?id=21279
CREATE INDEX /*i*/ar_revid ON /*$wgDBprefix*/archive ( ar_rev_id );