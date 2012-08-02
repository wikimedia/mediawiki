define mw_prefix='{$wgDBprefix}';

/*$mw$*/
BEGIN
	EXECUTE IMMEDIATE 'ALTER TABLE &mw_prefix.user_groups MODIFY ug_group VARCHAR2(32) NOT NULL';
EXCEPTION WHEN OTHERS THEN
	IF (SQLCODE = -01442) THEN NULL; ELSE RAISE; END IF;
END;
/*$mw$*/
