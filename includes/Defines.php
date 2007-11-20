<?php
/**
 * A few constants that might be needed during LocalSettings.php
 */

/**
 * Version constants for the benefit of extensions
 */
define( 'MW_SPECIALPAGE_VERSION', 2 );

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

# Valid database indexes
# Operation-based indexes
define( 'DB_SLAVE', -1 );     # Read from the slave (or only server)
define( 'DB_MASTER', -2 );    # Write to master (or only server)
define( 'DB_LAST', -3 );     # Whatever database was used last

# Obsolete aliases
define( 'DB_READ', -1 );
define( 'DB_WRITE', -2 );


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
 * User rights list
 * @deprecated
 */
$wgAvailableRights = array(
	'block',
	'bot',
	'createaccount',
	'delete',
	'edit',
	'editinterface',
	'import',
	'importupload',
	'move',
	'patrol',
	'protect',
	'read',
	'rollback',
	'siteadmin',
	'unwatchedpages',
	'upload',
	'userrights',
);

/**#@+
 * Cache type
 */
define( 'CACHE_ANYTHING', -1 );  // Use anything, as long as it works
define( 'CACHE_NONE', 0 );       // Do not cache
define( 'CACHE_DB', 1 );         // Store cache objects in the DB
define( 'CACHE_MEMCACHED', 2 );  // MemCached, must specify servers in $wgMemCacheServers
define( 'CACHE_ACCEL', 3 );      // eAccelerator or Turck, whichever is available
define( 'CACHE_DBA', 4 );        // Use PHP's DBA extension to store in a DBM-style database
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
define( 'ALF_NO_BLOCK_LOCK', 8 );
/**#@-*/

/**#@+
 * Date format selectors; used in user preference storage and by
 * Language::date() and co.
 */
/*define( 'MW_DATE_DEFAULT', '0' );
define( 'MW_DATE_MDY', '1' );
define( 'MW_DATE_DMY', '2' );
define( 'MW_DATE_YMD', '3' );
define( 'MW_DATE_ISO', 'ISO 8601' );*/
define( 'MW_DATE_DEFAULT', 'default' );
define( 'MW_DATE_MDY', 'mdy' );
define( 'MW_DATE_DMY', 'dmy' );
define( 'MW_DATE_YMD', 'ymd' );
define( 'MW_DATE_ISO', 'ISO 8601' );
/**#@-*/

/**#@+
 * RecentChange type identifiers
 * This may be obsolete; log items are now used for moves?
 */
define( 'RC_EDIT', 0);
define( 'RC_NEW', 1);
define( 'RC_MOVE', 2);
define( 'RC_LOG', 3);
define( 'RC_MOVE_OVER_REDIRECT', 4);
/**#@-*/

/**#@+
 * Article edit flags
 */
define( 'EDIT_NEW', 1 );
define( 'EDIT_UPDATE', 2 );
define( 'EDIT_MINOR', 4 ); 
define( 'EDIT_SUPPRESS_RC', 8 );
define( 'EDIT_FORCE_BOT', 16 );
define( 'EDIT_DEFER_UPDATES', 32 );
define( 'EDIT_AUTOSUMMARY', 64 );
/**#@-*/

/** 
 * Flags for Database::makeList() 
 * These are also available as Database class constants
 */
define( 'LIST_COMMA', 0 );
define( 'LIST_AND', 1 );
define( 'LIST_SET', 2 );
define( 'LIST_NAMES', 3);
define( 'LIST_OR', 4);

/**
 * Unicode and normalisation related
 */
define( 'UNICODE_HANGUL_FIRST', 0xac00 );
define( 'UNICODE_HANGUL_LAST',  0xd7a3 );

define( 'UNICODE_HANGUL_LBASE', 0x1100 );
define( 'UNICODE_HANGUL_VBASE', 0x1161 );
define( 'UNICODE_HANGUL_TBASE', 0x11a7 );

define( 'UNICODE_HANGUL_LCOUNT', 19 );
define( 'UNICODE_HANGUL_VCOUNT', 21 );
define( 'UNICODE_HANGUL_TCOUNT', 28 );
define( 'UNICODE_HANGUL_NCOUNT', UNICODE_HANGUL_VCOUNT * UNICODE_HANGUL_TCOUNT );

define( 'UNICODE_HANGUL_LEND', UNICODE_HANGUL_LBASE + UNICODE_HANGUL_LCOUNT - 1 );
define( 'UNICODE_HANGUL_VEND', UNICODE_HANGUL_VBASE + UNICODE_HANGUL_VCOUNT - 1 );
define( 'UNICODE_HANGUL_TEND', UNICODE_HANGUL_TBASE + UNICODE_HANGUL_TCOUNT - 1 );

define( 'UNICODE_SURROGATE_FIRST', 0xd800 );
define( 'UNICODE_SURROGATE_LAST', 0xdfff );
define( 'UNICODE_MAX', 0x10ffff );
define( 'UNICODE_REPLACEMENT', 0xfffd );


define( 'UTF8_HANGUL_FIRST', "\xea\xb0\x80" /*codepointToUtf8( UNICODE_HANGUL_FIRST )*/ );
define( 'UTF8_HANGUL_LAST', "\xed\x9e\xa3" /*codepointToUtf8( UNICODE_HANGUL_LAST )*/ );

define( 'UTF8_HANGUL_LBASE', "\xe1\x84\x80" /*codepointToUtf8( UNICODE_HANGUL_LBASE )*/ );
define( 'UTF8_HANGUL_VBASE', "\xe1\x85\xa1" /*codepointToUtf8( UNICODE_HANGUL_VBASE )*/ );
define( 'UTF8_HANGUL_TBASE', "\xe1\x86\xa7" /*codepointToUtf8( UNICODE_HANGUL_TBASE )*/ );

define( 'UTF8_HANGUL_LEND', "\xe1\x84\x92" /*codepointToUtf8( UNICODE_HANGUL_LEND )*/ );
define( 'UTF8_HANGUL_VEND', "\xe1\x85\xb5" /*codepointToUtf8( UNICODE_HANGUL_VEND )*/ );
define( 'UTF8_HANGUL_TEND', "\xe1\x87\x82" /*codepointToUtf8( UNICODE_HANGUL_TEND )*/ );

define( 'UTF8_SURROGATE_FIRST', "\xed\xa0\x80" /*codepointToUtf8( UNICODE_SURROGATE_FIRST )*/ );
define( 'UTF8_SURROGATE_LAST', "\xed\xbf\xbf" /*codepointToUtf8( UNICODE_SURROGATE_LAST )*/ );
define( 'UTF8_MAX', "\xf4\x8f\xbf\xbf" /*codepointToUtf8( UNICODE_MAX )*/ );
define( 'UTF8_REPLACEMENT', "\xef\xbf\xbd" /*codepointToUtf8( UNICODE_REPLACEMENT )*/ );
#define( 'UTF8_REPLACEMENT', '!' );

define( 'UTF8_OVERLONG_A', "\xc1\xbf" );
define( 'UTF8_OVERLONG_B', "\xe0\x9f\xbf" );
define( 'UTF8_OVERLONG_C', "\xf0\x8f\xbf\xbf" );

# These two ranges are illegal
define( 'UTF8_FDD0', "\xef\xb7\x90" /*codepointToUtf8( 0xfdd0 )*/ );
define( 'UTF8_FDEF', "\xef\xb7\xaf" /*codepointToUtf8( 0xfdef )*/ );
define( 'UTF8_FFFE', "\xef\xbf\xbe" /*codepointToUtf8( 0xfffe )*/ );
define( 'UTF8_FFFF', "\xef\xbf\xbf" /*codepointToUtf8( 0xffff )*/ );

define( 'UTF8_HEAD', false );
define( 'UTF8_TAIL', true );

# Hook support constants
define( 'MW_SUPPORTS_EDITFILTERMERGED', 1 );

# Allowed values for Parser::$mOutputType
# Parameter to Parser::startExternalParse().
define( 'OT_HTML', 1 );
define( 'OT_WIKI', 2 );
define( 'OT_MSG' , 3 );
define( 'OT_PREPROCESS', 4 );

# Flags for Parser::setFunctionHook
define( 'SFH_NO_HASH', 1 );
define( 'SFH_OBJECT_ARGS', 2 );

# Flags for Parser::replaceLinkHolders
define( 'RLH_FOR_UPDATE', 1 );

