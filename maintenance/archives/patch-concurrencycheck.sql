--
-- Store atomic locking information for web resources, to permit
-- UI that warns users when concurrently editing things that aren't
-- concurrently editable.
--
CREATE TABLE /*_*/concurrencycheck (
	-- a string describing the resource or application being checked out.
	cc_resource_type varchar(255) NOT NULL,

	-- the (numeric) ID of the record that's being checked out.
	cc_record int unsigned NOT NULL,

	-- the user who has control of the resource
	cc_user int unsigned NOT NULL,

	-- the date/time on which this record expires
	cc_expiration varbinary(14) not null

) /*$wgDBTableOptions*/;
-- composite pk.
CREATE UNIQUE INDEX /*i*/cc_resource_record ON /*_*/concurrencycheck (cc_resource_type, cc_record);
-- sometimes there's a delete based on userid.
CREATE INDEX /*i*/cc_user ON /*_*/concurrencycheck (cc_user);
-- sometimes there's a delete based on expiration
CREATE INDEX /*i*/cc_expiration ON /*_*/concurrencycheck (cc_expiration);
