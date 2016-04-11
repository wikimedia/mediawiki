-- Makes rev_page_id index non-unique
ALTER TABLE /*_*/revision
DROP INDEX /*i*/rev_page_id;

CREATE INDEX /*i*/rev_page_id ON /*_*/revision (rev_page, rev_id);
