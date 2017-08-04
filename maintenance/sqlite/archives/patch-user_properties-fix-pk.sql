CREATE TABLE /*_*/user_properties_tmp (
  -- Foreign key to user.user_id
  up_user int NOT NULL,

  -- Name of the option being saved. This is indexed for bulk lookup.
  up_property varbinary(255) NOT NULL,

  -- Property value as a string.
  up_value blob,
  PRIMARY KEY (up_user,up_property)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/user_properties_tmp
	SELECT * FROM /*_*/user_properties;

DROP TABLE /*_*/user_properties;

ALTER TABLE /*_*/user_properties_tmp RENAME TO /*_*/user_properties;

CREATE INDEX /*i*/user_properties_property ON /*_*/user_properties (up_property);