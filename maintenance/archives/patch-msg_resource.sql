-- Table for storing JSON message blobs for the resource loader
CREATE TABLE /*_*/msg_resource (
  -- Resource name
  mr_resource varbinary(255) NOT NULL,
  -- Language code
  mr_lang varbinary(32) NOT NULL,
  -- JSON blob. This is an incomplete JSON object, i.e. without the wrapping {}
  mr_blob mediumblob NOT NULL,
  -- Timestamp of last update
  mr_timestamp binary(14) NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/mr_resource_lang ON /*_*/msg_resource(mr_resource, mr_lang);

-- Table for administering which message is contained in which resource
CREATE TABLE /*_*/msg_resource_links (
  mrl_resource varbinary(255) NOT NULL,
  -- Message key
  mrl_message varbinary(255) NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/mrl_message_resource ON /*_*/msg_resource_links (mrl_message, mrl_resource);
