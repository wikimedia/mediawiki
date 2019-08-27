-- Blobs table for external storage

CREATE TABLE IF NOT EXISTS /*$wgDBprefix*/blobs (
	blob_id integer UNSIGNED NOT NULL AUTO_INCREMENT,
	blob_text longblob,
	PRIMARY KEY  (blob_id)
) ENGINE=InnoDB;
