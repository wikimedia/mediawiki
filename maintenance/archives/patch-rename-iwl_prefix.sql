--
-- Recreates the iwl_prefix index for the iwlinks table
--
CREATE UNIQUE INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);
