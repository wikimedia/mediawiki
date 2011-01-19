-- Add interwiki and fragment columns to redirect table

ALTER TABLE /*$wgDBprefix*/redirect
	ADD rd_interwiki varbinary(32) default NULL,
	ADD rd_fragment varbinary(255) default NULL;

