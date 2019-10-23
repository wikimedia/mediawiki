-- Add interwiki and fragment columns to redirect table

ALTER TABLE /*$wgDBprefix*/redirect ADD COLUMN rd_interwiki TEXT default NULL;
ALTER TABLE /*$wgDBprefix*/redirect ADD COLUMN rd_fragment TEXT default NULL;
