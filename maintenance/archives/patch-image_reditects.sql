-- Image redirects. Only existent images are put in this table.
CREATE TABLE /*$wgDBprefix*/imageredirects (
	ir_from varchar(255) binary NOT NULL default '',
	ir_to   varchar(255) binary NOT NULL default '',
	PRIMARY KEY ir_from (ir_from)
) /*$wgDBTableOptions*/;