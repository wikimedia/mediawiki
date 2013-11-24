ALTER TABLE /*$wgDBprefix*/langlinks
	MODIFY `ll_lang`
	VARBINARY(20) NOT NULL DEFAULT '';