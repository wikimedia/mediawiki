-- Table to handle resource locking (EX) with row-level locking
CREATE TABLE /*_*/filelocks_exclusive (
	fle_key binary(31) NOT NULL PRIMARY KEY
) ENGINE=InnoDB, CHECKSUM=0;

-- Table to handle resource locking (SH) with row-level locking
CREATE TABLE /*_*/filelocks_shared (
	fls_key binary(31) NOT NULL,
	fls_session binary(31) NOT NULL,
	PRIMARY KEY (fls_key,fls_session)
) ENGINE=InnoDB, CHECKSUM=0;
