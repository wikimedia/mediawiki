-- Add interwiki column to redirect table

ALTER TABLE /*$wgDBprefix*/redirect
   ADD rd_interwiki varchar(32) default NULL;