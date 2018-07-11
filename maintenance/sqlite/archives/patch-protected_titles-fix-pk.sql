CREATE TABLE /*_*/protected_titles_tmp (
  pt_namespace int NOT NULL,
  pt_title varchar(255) binary NOT NULL,
  pt_user int unsigned NOT NULL,
  pt_reason varbinary(767) default '', -- Deprecated.
  pt_reason_id bigint unsigned NOT NULL DEFAULT 0, -- ("DEFAULT 0" is temporary, signaling that pt_reason should be used)
  pt_timestamp binary(14) NOT NULL,
  pt_expiry varbinary(14) NOT NULL default '',
  pt_create_perm varbinary(60) NOT NULL,

  PRIMARY KEY (pt_namespace,pt_title)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/protected_titles_tmp
	SELECT * FROM /*_*/protected_titles;

DROP TABLE /*_*/protected_titles;

ALTER TABLE /*_*/protected_titles_tmp RENAME TO /*_*/protected_titles;

CREATE INDEX /*i*/pt_timestamp ON /*_*/protected_titles (pt_timestamp);
