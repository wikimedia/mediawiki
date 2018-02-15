--
-- Normalization table for role names
--
CREATE TABLE /*_*/slot_roles (
  role_id smallint PRIMARY KEY AUTO_INCREMENT,
  role_name varbinary(64) NOT NULL
) /*$wgDBTableOptions*/;

-- Index for looking of the internal ID of for a name
CREATE UNIQUE INDEX /*i*/role_name ON /*_*/slot_roles (role_name);