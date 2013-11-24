define mw_prefix='{$wgDBprefix}';

CREATE SEQUENCE uploadstash_us_id_seq;
CREATE TABLE &mw_prefix.uploadstash (
	us_id                 NUMBER       NOT NULL,
  us_user               NUMBER          DEFAULT 0 NOT NULL,
	us_key								VARCHAR2(255) NOT NULL,
	us_orig_path 					VARCHAR2(255) NOT NULL,
	us_path								VARCHAR2(255) NOT NULL,
	us_source_type				VARCHAR2(50),
  us_timestamp          TIMESTAMP(6) WITH TIME ZONE,
	us_status							VARCHAR2(50) NOT NULL,
	us_size								NUMBER NOT NULL,
	us_sha1								VARCHAR2(32) NOT NULL,
	us_mime								VARCHAR2(255),
  us_media_type         VARCHAR2(32) DEFAULT NULL,
	us_image_width				NUMBER,
	us_image_height				NUMBER,
	us_image_bits					NUMBER
);
ALTER TABLE &mw_prefix.uploadstash ADD CONSTRAINT &mw_prefix.uploadstash_pk PRIMARY KEY (us_id);
ALTER TABLE &mw_prefix.uploadstash ADD CONSTRAINT &mw_prefix.uploadstash_fk1 FOREIGN KEY (us_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
CREATE INDEX &mw_prefix.uploadstash_i01 ON &mw_prefix.uploadstash (us_user);
CREATE INDEX &mw_prefix.uploadstash_i02 ON &mw_prefix.uploadstash (us_timestamp);
CREATE UNIQUE INDEX &mw_prefix.uploadstash_u01 ON &mw_prefix.uploadstash (us_key);
