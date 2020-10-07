-- T184615: Drop old archive content fields obsoleted by MCR.

ALTER TABLE /*_*/archive
	DROP COLUMN ar_text_id,
	DROP COLUMN ar_content_model,
	DROP COLUMN ar_content_format;
