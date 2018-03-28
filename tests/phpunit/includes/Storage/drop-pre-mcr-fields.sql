-- TODO: we can only stop writing to rev_text_id when T188741 is done
-- ALTER TABLE /*_*/revision DROP COLUMN rev_text_id;
ALTER TABLE /*_*/revision DROP COLUMN rev_content_model;
ALTER TABLE /*_*/revision DROP COLUMN rev_content_format;
