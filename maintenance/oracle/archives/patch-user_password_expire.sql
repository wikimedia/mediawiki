define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.mwuser ADD user_password_expires TIMESTAMP(6) WITH TIME ZONE;
