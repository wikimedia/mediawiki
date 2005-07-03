ALTER TABLE /*$wgDBprefix*/interwiki
	ADD COLUMN iw_trans TINYINT(1) NOT NULL DEFAULT 0;
