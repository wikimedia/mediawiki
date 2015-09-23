--
-- Makes the iwl_prefix_title_from index for the iwlinks table non-unique
--
DROP INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks;
CREATE INDEX /*i*/iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);
