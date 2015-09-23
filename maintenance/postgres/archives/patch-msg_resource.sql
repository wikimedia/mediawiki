CREATE TABLE msg_resource (
	mr_resource TEXT NOT NULL,
	mr_lang		TEXT NOT NULL,
	mr_blob		TEXT NOT NULL,
	mr_timestamp	TIMESTAMPTZ NOT NULL
);

CREATE UNIQUE INDEX mr_resource_lang ON msg_resource (mr_resource, mr_lang);
