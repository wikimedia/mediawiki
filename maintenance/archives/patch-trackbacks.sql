CREATE TABLE /*$wgDBprefix*/trackbacks (
	tb_id		INTEGER REFERENCES page(page_id) ON DELETE CASCADE,
	tb_title	VARCHAR(255) NOT NULL,
	tb_url		VARCHAR(255) NOT NULL,
	tb_ex		TEXT,
	tb_name		VARCHAR(255),

	INDEX (tb_id)
);
