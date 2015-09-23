CREATE TABLE /*$wgDBprefix*/transcache (
	tc_url		varbinary(255) NOT NULL,
	tc_contents	TEXT,
	tc_time		binary(14) NOT NULL,
	UNIQUE INDEX tc_url_idx(tc_url)
) /*$wgDBTableOptions*/;

