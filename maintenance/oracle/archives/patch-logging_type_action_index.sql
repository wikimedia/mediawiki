define mw_prefix='{$wgDBprefix}';

CREATE INDEX &mw_prefix.logging_i05 ON &mw_prefix.logging (log_type, log_action, log_timestamp);

