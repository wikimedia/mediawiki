define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.filearchive ADD fa_sha1 VARCHAR2(32);
CREATE INDEX &mw_prefix.filearchive_i05 ON &mw_prefix.filearchive (fa_sha1);

