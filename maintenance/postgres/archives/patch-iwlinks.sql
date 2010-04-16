
CREATE TABLE iwlinks (
	iwl_from INTEGER NOT NULL DEFAULT 0,
	iwl_prefix TEXT NOT NULL DEFAULT '',
	iwl_title TEXT NOT NULL DEFAULT ''
);
CREATE UNIQUE INDEX iwl_from ON iwlinks (iwl_from, iwl_prefix, iwl_title);
CREATE INDEX iwl_prefix ON iwlinks (iwl_prefix, iwl_title);
