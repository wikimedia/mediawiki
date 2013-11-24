--
-- Recreates the iwl_prefix_from_title index for the iwlinks table
--
CREATE INDEX /*i*/iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);
