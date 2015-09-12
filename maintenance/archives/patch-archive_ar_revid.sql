-- Hopefully temporary index.
-- For https://phabricator.wikimedia.org/T23279
CREATE INDEX /*i*/ar_revid ON /*$wgDBprefix*/archive ( ar_rev_id );