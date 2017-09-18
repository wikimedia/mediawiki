--
-- Normalization table for content model names
--
CREATE TABLE /*_*/content_models (
  model_id smallint PRIMARY KEY AUTO_INCREMENT,
  model_name varbinary(64) NOT NULL
) /*$wgDBTableOptions*/;

-- Index for looking of the internal ID of for a name
CREATE UNIQUE INDEX /*i*/model_name ON /*_*/content_models (model_name);