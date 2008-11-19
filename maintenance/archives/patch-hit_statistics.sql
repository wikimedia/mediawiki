-- Creates the hit_statistics table, for storing, as the name implies, hit statistics.

CREATE TABLE /*$wgDBprefix*/hit_statistics (
	hs_page bigint(20) NOT NULL, -- Maybe this should be a namespace/title tuple instead.
	hs_period_start binary(14) NOT NULL,
	hs_period_end binary(14) NOT NULL,
	hs_period_length bigint(20) NOT NULL,
	hs_count bigint(20) NOT NULL,

	PRIMARY KEY  (hs_page,hs_period_start),
	KEY hs_period_start (hs_period_start),
	KEY hs_period_length (hs_period_length),
	KEY hs_count (hs_count)
) /*$wgDBTableOptions*/;