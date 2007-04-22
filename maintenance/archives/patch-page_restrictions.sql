--- Used for storing page restrictions (i.e. protection levels)
CREATE TABLE /*$wgDBprefix*/page_restrictions (
	-- Page to apply restrictions to (Foreign Key to page).
	pr_page int(8) NOT NULL,
	-- The protection type (edit, move, etc)
	pr_type varchar(255) NOT NULL,
	-- The protection level (Sysop, autoconfirmed, etc)
	pr_level varchar(255) NOT NULL,
	-- Whether or not to cascade the protection down to pages transcluded.
	pr_cascade tinyint(4) NOT NULL,
	-- Field for future support of per-user restriction.
	pr_user int(8) NULL,
	-- Field for time-limited protection.
	pr_expiry char(14) binary NULL,

	PRIMARY KEY pr_pagetype (pr_page,pr_type),

	KEY pr_page (pr_page),
	KEY pr_typelevel (pr_type,pr_level),
	KEY pr_level (pr_level),
	KEY pr_cascade (pr_cascade)
) /*$wgDBTableOptions*/;