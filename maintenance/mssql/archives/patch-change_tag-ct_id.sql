-- Primary key in change_tag table

ALTER TABLE /*_*/change_tag ADD ct_id INT IDENTITY;
ALTER TABLE /*_*/change_tag ADD CONSTRAINT pk_change_tag PRIMARY KEY(ct_id)
