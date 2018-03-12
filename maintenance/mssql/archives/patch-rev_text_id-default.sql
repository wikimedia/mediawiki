--
-- Adds a default value to the rev_text_id field in the revision table and
-- allows NULL values.
-- This is to allow the Multi Content Revisions migration to happen where
-- rows will have to be added to the revision table with no rev_text_id.
--
-- 2018-03-12
--

ALTER TABLE /*_*/revision
  ALTER COLUMN rev_text_id INT NULL;

ALTER TABLE /*_*/revision
  ADD CONSTRAINT DF_rev_text_id DEFAULT NULL FOR rev_text_id;