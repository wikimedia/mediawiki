define mw_prefix='{$wgDBprefix}';

/*$mw$*/
BEGIN
	EXECUTE IMMEDIATE 'ALTER TABLE &mw_prefix.user_former_groups MODIFY ufg_group VARCHAR2(255) NOT NULL';
EXCEPTION WHEN OTHERS THEN
	IF (SQLCODE = -01442) THEN NULL; ELSE RAISE; END IF;
END;
/*$mw$*/
