-- Blobs table for external storage

CREATE TABLE /*$wgDBprefix*/blobs (
	blob_id int(8) NOT NULL AUTO_INCREMENT,
	blob_text mediumtext,
	PRIMARY KEY  (blob_id)
) TYPE=InnoDB;

