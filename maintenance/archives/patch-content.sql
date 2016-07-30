CREATE TABLE /*$wgDBprefix*/content (
  cnt_id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  cnt_revision     INT NOT NULL, -- the revision that originated this slot
  cnt_role         INT NOT NULL, -- content role aka slot name -> content_roles.
  -- cnt_subrole      VARCHAR(255) NOT NULL DEFAULT '', -- further specification of the role. Can be considered as a siffix to cnt_role.
  -- cnt_type         INT NOT NULL DEFAULT 1, -- flag field indicating whether this content is primary or derived
  cnt_address      VARCHAR(255) NOT NULL, -- factor out common prefixes -> content_stores?
  cnt_timestamp    CHAR(14) DEFAULT NULL, -- only relevant for updatable derived data, can be NULL otherwise; could also be a Timestamp-UUID
  cnt_blob_length  INT NOT NULL, -- serialized size. NEEDED?
  cnt_model        INT NOT NULL, -- -> content_models
  -- cnt_language     INT NOT NULL, -- -> content_languages
  cnt_format       INT NOT NULL, -- serialization format -> content_formats
  cnt_hash         VARCHAR(32) NOT NULL, -- from Content::getHash.
  cnt_logical_size INT NOT NULL, -- bogo-bytes from Content::getSize
  cnt_deleted      INT NOT NULL DEFAULT 0 -- for per-slot suppression. NEEDED?
) /*$wgDBTableOptions*/;

-- for read and write operations
CREATE UNIQUE INDEX /*i*/cnt_revision_role ON /*_*/content ( cnt_revision, cnt_role );

-- for analysis and maintenance:
CREATE INDEX /*i*/cnt_hash ON /*_*/content ( cnt_hash );
CREATE INDEX /*i*/cnt_model ON /*_*/content ( cnt_model );
CREATE INDEX /*i*/cnt_address ON /*_*/content ( cnt_address );

CREATE TABLE /*$wgDBprefix*/revision_content (
  -- do we need a row id here as a primary key?
  revc_revision INT NOT NULL,
  revc_content INT NOT NULL,
  PRIMARY KEY( revc_revision, revc_content )
);

-- for analysis and maintenance:
CREATE INDEX /*i*/revc_content ON /*_*/revision_content ( revc_content );

CREATE TABLE /*$wgDBprefix*/content_roles (
  -- do we need a row id here as a primary key?
  role_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  role_name VARCHAR(255) NOT NULL
);

-- for analysis and maintenance:
CREATE UNIQUE INDEX /*i*/role_name ON /*_*/content_roles ( role_name );

CREATE TABLE /*$wgDBprefix*/content_models (
  -- do we need a row id here as a primary key?
  model_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  model_name VARCHAR(255) NOT NULL,
  model_format INT NOT NULL -- -> content_formats
);

-- for analysis and maintenance:
CREATE UNIQUE INDEX /*i*/model_name ON /*_*/content_models ( model_name );


CREATE TABLE /*$wgDBprefix*/content_formats (
  -- do we need a row id here as a primary key?
  format_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  format_name VARCHAR(255) NOT NULL
);

-- for analysis and maintenance:
CREATE UNIQUE INDEX /*i*/format_name ON /*_*/content_formats ( format_name );

