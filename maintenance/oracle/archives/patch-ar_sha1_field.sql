define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive ADD ar_sha1		  VARCHAR2(32);
