CREATE TABLE transcache (
	tc_url		VARCHAR2(255) NOT NULL UNIQUE,
	tc_contents	CLOB,
	tc_time		TIMESTAMP NOT NULL
);
