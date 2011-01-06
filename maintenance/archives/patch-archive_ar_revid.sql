-- Hopefully temporary index.
-- For https://bugzilla.wikimedia.org/show_bug.cgi?id=21279
ALTER TABLE /*$wgDBprefix*/archive
	ADD INDEX ar_revid ( ar_rev_id );