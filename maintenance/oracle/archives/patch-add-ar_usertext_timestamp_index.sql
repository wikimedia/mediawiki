define mw_prefix='{$wgDBprefix}';

CREATE INDEX &mw_prefix.ar_usertext_timestamp ON &mw_prefix.archive (ar_user_text,ar_timestamp);
