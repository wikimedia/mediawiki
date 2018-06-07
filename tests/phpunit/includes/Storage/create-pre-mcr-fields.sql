ALTER TABLE /*_*/revision ADD rev_text_id INTEGER DEFAULT 0;
ALTER TABLE /*_*/revision  ADD rev_content_model VARBINARY(32) DEFAULT NULL;
ALTER TABLE /*_*/revision ADD rev_content_format VARBINARY(64) DEFAULT NULL;
