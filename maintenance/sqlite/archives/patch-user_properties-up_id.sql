DROP TABLE IF EXISTS /*_*/user_properties_tmp;

CREATE TABLE /*$wgDBprefix*/user_properties_tmp (
  up_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  up_user int NOT NULL,
  up_property varbinary(255) NOT NULL,
  up_value blob
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/user_properties_tmp (
    up_user, up_property, up_value )
    SELECT
    up_user, up_property, up_value
    FROM /*_*/user_properties;

DROP TABLE /*_*/user_properties;

ALTER TABLE /*_*/user_properties_tmp RENAME TO /*_*/user_properties;

CREATE UNIQUE INDEX /*i*/user_properties_user_property ON /*_*/user_properties (up_user,up_property);
CREATE INDEX /*i*/user_properties_property ON /*_*/user_properties (up_property);