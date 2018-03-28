ALTER TABLE /*_*/revision ADD rev_text_id INTEGER DEFAULT 0;
ALTER TABLE /*_*/revision  ADD rev_content_model VARCHAR DEFAULT NULL;
ALTER TABLE /*_*/revision ADD rev_content_format VARCHAR DEFAULT NULL;
