-- Increase the length of up_property from 32 -> 255 bytes. Bug 19408

ALTER TABLE /*_*/user_properties
	MODIFY up_property varbinary(255);
