-- Generic key-value store
CREATE TABLE /*_*/store (
-- Key
		store_key varbinary(255) NOT NULL PRIMARY KEY,
-- Value
		store_value MEDIUMBLOB
) /*$wgDBTableOptions*/;
