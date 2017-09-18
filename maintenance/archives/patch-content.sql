--
-- The content table represents content objects. It's primary purpose is to provide the necessary
-- meta-data for loading and interpreting a serialized data blob to create a content object.
--
CREATE TABLE /*_*/content (

  -- ID of the content object
  content_id bigint unsigned PRIMARY KEY AUTO_INCREMENT,

  -- Nominal size of the content object (not necessarily of the serialized blob)
  content_size int unsigned NOT NULL,

  -- Nominal hash of the content object (not necessarily of the serialized blob)
  content_sha1 varbinary(32) NOT NULL,

  -- reference to model_id
  content_model smallint unsigned NOT NULL,

  -- URL-like address of the content blob
  content_address varbinary(255) NOT NULL
) /*$wgDBTableOptions*/;