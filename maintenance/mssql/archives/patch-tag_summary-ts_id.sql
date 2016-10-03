-- Primary key in tag_summary table

ALTER TABLE /*_*/tag_summary ADD ts_id INT IDENTITY;
ALTER TABLE /*_*/tag_summary ADD CONSTRAINT pk_tag_summary PRIMARY KEY(ts_id)
