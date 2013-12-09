define mw_prefix='{$wgDBprefix}';

CREATE INDEX &mw_prefix.logging_i06 ON &mw_prefix.logging (log_user_text, log_type, log_timestamp);

