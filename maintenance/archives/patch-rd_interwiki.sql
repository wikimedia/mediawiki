-- Add interwiki and fragment columns to redirect table

ALTER TABLE /*$wgDBprefix*/redirect
	ADD rd_interwiki varchar(32) default NULL,
	ADD rd_fragment varchar(255) binary default NULL;
