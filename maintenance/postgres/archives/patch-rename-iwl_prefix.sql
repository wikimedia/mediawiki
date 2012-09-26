DROP INDEX iwl_prefix;
CREATE UNIQUE INDEX iwl_prefix_title_from ON iwlinks (iwl_prefix, iwl_title, iwl_from);
