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
 *
 * Number 100 and beyond are reserved for custom namespaces;
 * DO NOT assign standard namespaces at 100 or beyond.
 * DO NOT Change integer values as they are most probably hardcoded everywhere
 * see bug #696 which talked about that.
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
'protect', 'block', 'userrights', 'grouprights', 'createaccount', 'upload',
'asksql', 'rollback', 'patrol', 'editinterface', 'siteadmin', 'bot', 'validate', 
'import');

/**
 * Cache type
 */
define( 'CACHE_ANYTHING', -1 );  // Use anything, as long as it works
define( 'CACHE_NONE', 0 );       // Do not cache
define( 'CACHE_DB', 1 );         // Store cache objects in the DB
define( 'CACHE_MEMCACHED', 2 );  // MemCached, must specify servers in $wgMemCacheServers
define( 'CACHE_ACCEL', 3 );      // eAccelerator or Turck, whichever is available

?>
