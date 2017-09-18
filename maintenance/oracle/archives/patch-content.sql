CREATE SEQUENCE content_content_id_seq;
CREATE TABLE &mw_prefix.content (
  content_id NUMBER NOT NULL,
  content_size NUMBER NOT NULL,
  content_sha1 VARBINARY(32),
  content_model NUMBER NOT NULL,
  content_address VARBINARY(255) NOT NULL
);

ALTER TABLE &mw_prefix.content ADD CONSTRAINT &mw_prefix.content_pk PRIMARY KEY (content_id);

/*$mw$*/
CREATE TRIGGER &mw_prefix.content_default_content_id BEFORE INSERT ON &mw_prefix.content
	FOR EACH ROW WHEN (new.content_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(content_content_id_seq.nextval, :new.content_id);
END;
/*$mw$*/