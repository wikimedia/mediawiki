--
-- Adds a default value to the rev_text_id field in the revision table.
-- This is to allow the Multi Content Revisions migration to happen where
-- rows will have to be added to the revision table with no rev_text_id.
--
-- 2018-03-12
--

ALTER TABLE /*_*/revision
  ADD CONSTRAINT DF_rev_text_id DEFAULT 0 FOR rev_text_id;