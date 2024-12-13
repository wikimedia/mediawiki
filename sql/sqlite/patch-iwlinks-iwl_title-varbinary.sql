CREATE TABLE /*_*/iwlinks_tmp (
  iwl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  iwl_prefix BLOB DEFAULT '' NOT NULL,
  iwl_title BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(iwl_from, iwl_prefix, iwl_title)
);

INSERT INTO /*_*/iwlinks_tmp
	SELECT iwl_from, iwl_prefix, iwl_title
		FROM /*_*/iwlinks;
DROP TABLE /*_*/iwlinks;
ALTER TABLE /*_*/iwlinks_tmp RENAME TO /*_*/iwlinks;

CREATE INDEX iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);

CREATE INDEX iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);
