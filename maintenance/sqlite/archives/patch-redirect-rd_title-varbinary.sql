CREATE TABLE /*_*/redirect_tmp (
  rd_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  rd_namespace INTEGER DEFAULT 0 NOT NULL,
  rd_title BLOB DEFAULT '' NOT NULL,
  rd_interwiki VARCHAR(32) DEFAULT NULL,
  rd_fragment BLOB DEFAULT NULL,
  PRIMARY KEY(rd_from)
);
INSERT INTO /*_*/redirect_tmp
	SELECT rd_from, rd_namespace, rd_title, rd_interwiki, rd_fragment
		FROM /*_*/redirect;
DROP TABLE /*_*/redirect;
ALTER TABLE /*_*/redirect_tmp RENAME TO /*_*/redirect;

CREATE INDEX rd_ns_title ON /*_*/redirect (rd_namespace, rd_title, rd_from);
