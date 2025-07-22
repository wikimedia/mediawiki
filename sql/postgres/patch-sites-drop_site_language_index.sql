-- This file was manually written, in order to make sure that this index (intended to be dropped on the upgrade to
-- MediaWiki 1.42) is still dropped for Postgres wikis whose first run of `update.php` on MW 1.42+ failed due to an
-- index-renaming typo from MW 1.36.
-- See https://phabricator.wikimedia.org/T374042; https://phabricator.wikimedia.org/T374042#11017896
DROP INDEX site_language;
