
--
-- Normalization table for content model names
--
CREATE TABLE /*_*/content_models (
  model_id smallint NOT NULL CONSTRAINT PK_content_models PRIMARY KEY IDENTITY,
  model_name nvarchar(64) NOT NULL
);

-- Index for looking of the internal ID of for a name
CREATE UNIQUE INDEX /*i*/model_name ON /*_*/content_models (model_name);