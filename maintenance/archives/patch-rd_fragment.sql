-- Add fragment (section link) column to redirect table

ALTER TABLE /*$wgDBprefix*/redirect
   ADD rd_fragment varchar(255) binary DEFAULT NULL;