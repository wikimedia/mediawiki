CREATE TABLE user_properties(
  up_user BIGINT NOT NULL,
  up_property TEXT NOT NULL,
  up_value TEXT
);

CREATE UNIQUE INDEX user_properties_user_property on user_properties (up_user,up_property);
CREATE INDEX user_properties_property on user_properties (up_property);
