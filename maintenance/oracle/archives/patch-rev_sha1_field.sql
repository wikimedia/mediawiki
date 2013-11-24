define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.revision ADD rev_sha1		  VARCHAR2(32);

