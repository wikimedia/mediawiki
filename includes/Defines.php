<?php
/**
 * A few constants that might be needed during LocalSettings.php
 * @package MediaWiki
 */

/**#@+
 * Database related constants
 */
define( 'DBO_DEBUG', 1 );
define( 'DBO_NOBUFFER', 2 );
define( 'DBO_IGNORE', 4 );
define( 'DBO_TRX', 8 );
define( 'DBO_DEFAULT', 16 );
/**#@-*/

/**#@+
 * Virtual namespaces; don't appear in the page database
 */
define('NS_MEDIA', -2);
define('NS_SPECIAL', -1);
/**#@-*/

/**#@+
 * Real namespaces
 */
define('NS_MAIN', 0);
define('NS_TALK', 1);
define('NS_USER', 2);
define('NS_USER_TALK', 3);
define('NS_PROJECT', 4);
define('NS_PROJECT_TALK', 5);
define('NS_IMAGE', 6);
define('NS_IMAGE_TALK', 7);
define('NS_MEDIAWIKI', 8);
define('NS_MEDIAWIKI_TALK', 9);
define('NS_TEMPLATE', 10);
define('NS_TEMPLATE_TALK', 11);
define('NS_HELP', 12);
define('NS_HELP_TALK', 13);
define('NS_CATEGORY', 14);
define('NS_CATEGORY_TALK', 15);
/**#@-*/

/**#@+
 * Fix the code and remove these...
 * @todo Constants that need to be removed after code cleaning
 * @deprecated
 */
define('NS_WP', NS_PROJECT);
define('NS_WP_TALK', NS_PROJECT_TALK);
define('NS_WIKIPEDIA', NS_PROJECT);
define('NS_WIKIPEDIA_TALK', NS_PROJECT_TALK);
/**#@-*/

/**
 * Available feeds objects
 * Should probably only be defined when a page is syndicated ie when
 * $wgOut->isSyndicated() is true
 */
$wgFeedClasses = array(
	'rss' => 'RSSFeed',
	'atom' => 'AtomFeed',
	);

/** Maths constants */
define( 'MW_MATH_PNG',    0 );
define( 'MW_MATH_SIMPLE', 1 );
define( 'MW_MATH_HTML',   2 );
define( 'MW_MATH_SOURCE', 3 );
define( 'MW_MATH_MODERN', 4 );
define( 'MW_MATH_MATHML', 5 );

/**
 * User rights management
 * a big array of string defining a right, that's how they are saved in the
 * database.
 */
$wgAvailableRights = array('read', 'edit', 'move', 'delete', 'undelete',
'protect', 'block', 'userrights', 'createaccount', 'upload', 'asksql',
'rollback', 'patrol', 'editinterface', 'siteadmin', 'bot');

/**#@+
 * Edit Comments really needs its own separate rights.
 * Two separate rights are required; edit all comments, edit your own comment.
 */
define('EDIT_COMMENT_ALL', 'protect');

?>
