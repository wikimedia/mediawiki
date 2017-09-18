--
-- The content table represents content objects. It's primary purpose is to provide the necessary
-- meta-data for loading and interpreting a serialized data blob to create a content object.
--
CREATE TABLE /*_*/content (

  -- ID of the content object
  content_id bigint unsigned NOT NULL CONSTRAINT PK_content PRIMARY KEY IDENTITY,

  -- Nominal size of the content object (not necessarily of the serialized blob)
  content_size int unsigned NOT NULL,

  -- Nominal hash of the content object (not necessarily of the serialized blob)
  -- NOTE: nullable so we can stop storing the hash without changing the schema.
  content_sha1 varchar(32),

  -- reference to model_id
  -- XXX: we may want an index for this for statistics
  content_model smallint unsigned NOT NULL CONSTRAINT FK_content_content_models FOREIGN KEY REFERENCES /*_*/content_models(model_id),

  -- URL-like address of the content blob
  -- XXX: we may want an index on this so we can find orphan blobs
  content_address nvarchar(255) NOT NULL
);