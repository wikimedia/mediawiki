-- 
-- Recreates the iwl_prefix for the iwlinks table
--
DROP INDEX /*i*/iwl_prefix ON /*_*/iwlinks;
CREATE INDEX /*i*/iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);