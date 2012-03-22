--
-- patch-categorylinks-multiple-collations.sql
--
-- Allows more than one collations to be used at the same time
--

ALTER TABLE /*_*/categorylinks
  DROP INDEX /*i*/cl_from,
  ADD UNIQUE INDEX /*i*/cl_from(cl_from,cl_to,cl_collation);

ALTER TABLE /*_*/categorylinks
  DROP INDEX /*i*/cl_sortkey,
  ADD INDEX /*i*/cl_sortkey(cl_to,cl_type,cl_collation,cl_sortkey,cl_from);
