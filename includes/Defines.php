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
define( 'DBO_PERSISTENT', 32 );
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

/**#@+
 * Maths constants
 */
define( 'MW_MATH_PNG',    0 );
define( 'MW_MATH_SIMPLE', 1 );
define( 'MW_MATH_HTML',   2 );
define( 'MW_MATH_SOURCE', 3 );
define( 'MW_MATH_MODERN', 4 );
define( 'MW_MATH_MATHML', 5 );
/**#@-*/

/**
 * User rights management
 * a big array of string defining a right, that's how they are saved in the
 * database.
 */
$wgAvailableRights = array('read', 'edit', 'move', 'delete', 'undelete',
'protect', 'block', 'userrights', 'grouprights', 'createaccount', 'upload',
'asksql', 'rollback', 'patrol', 'editinterface', 'siteadmin', 'bot', 'validate', 
'import');

/**#@+
 * Cache type
 */
define( 'CACHE_ANYTHING', -1 );  // Use anything, as long as it works
define( 'CACHE_NONE', 0 );       // Do not cache
define( 'CACHE_DB', 1 );         // Store cache objects in the DB
define( 'CACHE_MEMCACHED', 2 );  // MemCached, must specify servers in $wgMemCacheServers
define( 'CACHE_ACCEL', 3 );      // eAccelerator or Turck, whichever is available
/**#@-*/



/**#@+
 * Media types. 
 * This defines constants for the value returned by Image::getMediaType()
 */

define( 'MEDIATYPE_UNKNOWN',    'UNKNOWN' );     // unknown format
define( 'MEDIATYPE_BITMAP',     'BITMAP' );      // some bitmap image or image source (like psd, etc). Can't scale up.
define( 'MEDIATYPE_DRAWING',    'DRAWING' );     // some vector drawing (SVG, WMF, PS, ...) or image source (oo-draw, etc). Can scale up.
define( 'MEDIATYPE_AUDIO',      'AUDIO' );       // simple audio file (ogg, mp3, wav, midi, whatever)
define( 'MEDIATYPE_VIDEO',      'VIDEO' );       // simple video file (ogg, mpg, etc; no not include formats here that may contain executable sections or scripts!)
define( 'MEDIATYPE_MULTIMEDIA', 'MULTIMEDIA' );  // Scriptable Multimedia (flash, advanced video container formats, etc)
define( 'MEDIATYPE_OFFICE',     'OFFICE' );      // Office Documents, Spreadsheets (office formats possibly containing apples, scripts, etc)
define( 'MEDIATYPE_TEXT',       'TEXT' );        // Plain text (possibly containing program code or scripts)
define( 'MEDIATYPE_EXECUTABLE', 'EXECUTABLE' );  // binary executable
define( 'MEDIATYPE_ARCHIVE',    'ARCHIVE' );     // archive file (zip, tar, etc)
/**#@-*/

/**#@+
 * Antivirus result codes, for use in $wgAntivirusSetup. 
 */

define( 'AV_NO_VIRUS', 0 );  #scan ok, no virus found
define( 'AV_VIRUS_FOUND', 1 );  #virus found!
define( 'AV_SCAN_ABORTED', -1 );  #scan aborted, the file is probably imune
define( 'AV_SCAN_FAILED', false );  #scan failed (scanner not found or error in scanner)
/**#@-*/

/**#@+
 * Anti-lock flags
 * See DefaultSettings.php for a description
 */
define( 'ALF_PRELOAD_LINKS', 1 );
define( 'ALF_PRELOAD_EXISTENCE', 2 );
define( 'ALF_NO_LINK_LOCK', 4 );
/**#@-*/

?>
