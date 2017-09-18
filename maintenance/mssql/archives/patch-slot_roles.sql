--
-- Normalization table for role names
--
CREATE TABLE /*_*/slot_roles (
  role_id smallint NOT NULL CONSTRAINT PK_slot_roles PRIMARY KEY IDENTITY,
  role_name nvarchar(64) NOT NULL
);

-- Index for looking of the internal ID of for a name
CREATE UNIQUE INDEX /*i*/role_name ON /*_*/slot_roles (role_name);