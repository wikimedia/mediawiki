DROP INDEX  user_properties_user_property;
ALTER TABLE user_properties
 ADD PRIMARY KEY (up_user, up_property);
