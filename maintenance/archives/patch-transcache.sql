CREATE TABLE /*$wgDBprefix*/transcache (
	tc_url		VARCHAR(255) NOT NULL,
	tc_contents	TEXT,
	tc_time		INT NOT NULL,
	UNIQUE INDEX tc_url_idx(tc_url)
) ENGINE=InnoDB;

