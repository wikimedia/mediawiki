CREATE TABLE msg_resource_links (
	mrl_resource TEXT NOT NULL,
	mrl_message TEXT NOT NULL
);

CREATE UNIQUE INDEX mrl_message_resource ON msg_resource_links (mrl_message, mrl_resource);
