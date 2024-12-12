DROP INDEX IF EXISTS iwl_from;
DROP INDEX IF EXISTS iwl_prefix_title_from;
DROP INDEX IF EXISTS iwl_prefix_from_title;
ALTER TABLE iwlinks
 ADD PRIMARY KEY (iwl_from, iwl_prefix, iwl_title);

CREATE INDEX iwl_prefix_title_from ON iwlinks (iwl_prefix, iwl_title, iwl_from);
CREATE INDEX iwl_prefix_from_title ON iwlinks (iwl_prefix, iwl_from, iwl_title);
