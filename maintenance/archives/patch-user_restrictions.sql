-- Allows admins to block user from editing certain namespaces or pages

CREATE TABLE /*$wgDBprefix*/user_restrictions (
	-- ID of the restriction
	ur_id int NOT NULL auto_increment,

	-- Restriction type. Block from either editing namespace or page
	ur_type varbinary(255) NOT NULL,
	-- Namespace to restrict if ur_type = namespace
	ur_namespace int default NULL,
	-- Page to restrict if ur_type = page
	ur_page_namespace int default NULL,
	ur_page_title varchar(255) binary default '',

	-- User that is restricted
	ur_user int unsigned NOT NULL,
	ur_user_text tinyblob NOT NULL,

	-- User who has done this restriction
	ur_by int unsigned NOT NULL,
	ur_by_text varchar(255) binary NOT NULL default '',
	-- Reason for this restriction
	ur_reason tinyblob NOT NULL,

	-- Time when this restriction was made
	ur_timestamp varbinary(14) NOT NULL default '',
	-- Expiry or "infinity"
	ur_expiry varbinary(14) NOT NULL default '',

	PRIMARY KEY ur_id (ur_id),
	-- For looking up restrictions for user
	INDEX ur_user (ur_user,ur_user_text(255)),
	-- For Special:ListUserRestrictions
	INDEX ur_type (ur_type(255),ur_timestamp),
	INDEX ur_namespace (ur_namespace,ur_timestamp),
	INDEX ur_page (ur_page_namespace,ur_page_title,ur_timestamp),
	INDEX ur_timestamp (ur_timestamp),
	-- For quick removal of expired restrictions
	INDEX ur_expiry (ur_expiry)
) /*$wgDBTableOptions*/;
