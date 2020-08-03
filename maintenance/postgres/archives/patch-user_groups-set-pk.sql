ALTER TABLE user_properties
 DROP INDEX  user_properties_user_property,
 ADD PRIMARY KEY (up_user, up_property);
