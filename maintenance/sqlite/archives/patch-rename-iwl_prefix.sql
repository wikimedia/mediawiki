-- 
-- Recreates the iwl_prefix for the iwlinks table
--
DROP INDEX IF EXISTS /*i*/iwl_prefix;
CREATE INDEX IF NOT EXISTS /*i*/iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);
