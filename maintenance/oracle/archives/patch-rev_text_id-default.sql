--
-- Adds a default value to the rev_text_id field in the revision table and
-- allows NULL values.
-- This is to allow the Multi Content Revisions migration to happen where
-- rows will have to be added to the revision table with no rev_text_id.
--
-- 2018-03-12
--

ALTER TABLE &mw_prefix.revision MODIFY rev_text_id default NULL;