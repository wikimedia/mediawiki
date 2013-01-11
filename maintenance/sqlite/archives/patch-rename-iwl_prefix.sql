--
-- Recreates the iwl_prefix for the iwlinks table
--
DROP INDEX IF EXISTS /*i*/iwl_prefix;
CREATE INDEX IF NOT EXISTS /*i*/iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);
