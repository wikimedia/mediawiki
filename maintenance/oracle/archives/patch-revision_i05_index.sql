define mw_prefix='{$wgDBprefix}';

CREATE INDEX &mw_prefix.revision_i05 ON &mw_prefix.revision (rev_page,rev_user,rev_timestamp);

