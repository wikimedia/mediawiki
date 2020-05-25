ALTER TABLE /*$wgDBprefix*/langlinks
	MODIFY `ll_lang`
	VARBINARY(35) NOT NULL DEFAULT '';
