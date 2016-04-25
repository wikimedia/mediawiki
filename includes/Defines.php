<?php
/**
 * A few constants that might be needed during LocalSettings.php.
 *
 * Note: these constants must all be resolvable at compile time by HipHop,
 * since this file will not be executed during request startup for a compiled
 * MediaWiki.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * @defgroup Constants MediaWiki constants
 */

/**
 * Version constants for the benefit of extensions
 */
define( 'MW_SPECIALPAGE_VERSION', 2 );

/**@{
 * Database related constants
 */
define( 'DBO_DEBUG', 1 );
define( 'DBO_NOBUFFER', 2 );
define( 'DBO_IGNORE', 4 );
define( 'DBO_TRX', 8 ); // automatically start transaction on first query
define( 'DBO_DEFAULT', 16 );
define( 'DBO_PERSISTENT', 32 );
define( 'DBO_SYSDBA', 64 ); //for oracle maintenance
define( 'DBO_DDLMODE', 128 ); // when using schema files: mostly for Oracle
define( 'DBO_SSL', 256 );
define( 'DBO_COMPRESS', 512 );
/**@}*/

/**@{
 * Valid database indexes
 * Operation-based indexes
 */
define( 'DB_SLAVE', -1 );     # Read from the slave (or only server)
define( 'DB_MASTER', -2 );    # Write to master (or only server)
/**@}*/

# Obsolete aliases
define( 'DB_READ', -1 );
define( 'DB_WRITE', -2 );
define( 'DB_LAST', -3 ); # deprecated since 2008, usage throws exception

/**@{
 * Virtual namespaces; don't appear in the page database
 */
define( 'NS_MEDIA', -2 );
define( 'NS_SPECIAL', -1 );
/**@}*/

/**@{
 * Real namespaces
 *
 * Number 100 and beyond are reserved for custom namespaces;
 * DO NOT assign standard namespaces at 100 or beyond.
 * DO NOT Change integer values as they are most probably hardcoded everywhere
 * see bug #696 which talked about that.
 */
define( 'NS_MAIN', 0 );
define( 'NS_TALK', 1 );
define( 'NS_USER', 2 );
define( 'NS_USER_TALK', 3 );
define( 'NS_PROJECT', 4 );
define( 'NS_PROJECT_TALK', 5 );
define( 'NS_FILE', 6 );
define( 'NS_FILE_TALK', 7 );
define( 'NS_MEDIAWIKI', 8 );
define( 'NS_MEDIAWIKI_TALK', 9 );
define( 'NS_TEMPLATE', 10 );
define( 'NS_TEMPLATE_TALK', 11 );
define( 'NS_HELP', 12 );
define( 'NS_HELP_TALK', 13 );
define( 'NS_CATEGORY', 14 );
define( 'NS_CATEGORY_TALK', 15 );

/**
 * NS_IMAGE and NS_IMAGE_TALK are the pre-v1.14 names for NS_FILE and
 * NS_FILE_TALK respectively, and are kept for compatibility.
 *
 * When writing code that should be compatible with older MediaWiki
 * versions, either stick to the old names or define the new constants
 * yourself, if they're not defined already.
 */
define( 'NS_IMAGE', NS_FILE );
define( 'NS_IMAGE_TALK', NS_FILE_TALK );
/**@}*/

/**@{
 * Cache type
 */
define( 'CACHE_ANYTHING', -1 );  // Use anything, as long as it works
define( 'CACHE_NONE', 0 );       // Do not cache
define( 'CACHE_DB', 1 );         // Store cache objects in the DB
define( 'CACHE_MEMCACHED', 2 );  // MemCached, must specify servers in $wgMemCacheServers
define( 'CACHE_ACCEL', 3 );      // APC, XCache or WinCache
/**@}*/

/**@{
 * Media types.
 * This defines constants for the value returned by File::getMediaType()
 */
// unknown format
define( 'MEDIATYPE_UNKNOWN', 'UNKNOWN' );
// some bitmap image or image source (like psd, etc). Can't scale up.
define( 'MEDIATYPE_BITMAP', 'BITMAP' );
// some vector drawing (SVG, WMF, PS, ...) or image source (oo-draw, etc). Can scale up.
define( 'MEDIATYPE_DRAWING', 'DRAWING' );
// simple audio file (ogg, mp3, wav, midi, whatever)
define( 'MEDIATYPE_AUDIO', 'AUDIO' );
// simple video file (ogg, mpg, etc;
// no not include formats here that may contain executable sections or scripts!)
define( 'MEDIATYPE_VIDEO', 'VIDEO' );
// Scriptable Multimedia (flash, advanced video container formats, etc)
define( 'MEDIATYPE_MULTIMEDIA', 'MULTIMEDIA' );
// Office Documents, Spreadsheets (office formats possibly containing apples, scripts, etc)
define( 'MEDIATYPE_OFFICE', 'OFFICE' );
// Plain text (possibly containing program code or scripts)
define( 'MEDIATYPE_TEXT', 'TEXT' );
// binary executable
define( 'MEDIATYPE_EXECUTABLE', 'EXECUTABLE' );
// archive file (zip, tar, etc)
define( 'MEDIATYPE_ARCHIVE', 'ARCHIVE' );
/**@}*/

/**@{
 * Antivirus result codes, for use in $wgAntivirusSetup.
 */
define( 'AV_NO_VIRUS', 0 );  #scan ok, no virus found
define( 'AV_VIRUS_FOUND', 1 );  #virus found!
define( 'AV_SCAN_ABORTED', -1 );  #scan aborted, the file is probably immune
define( 'AV_SCAN_FAILED', false );  #scan failed (scanner not found or error in scanner)
/**@}*/

/**@{
 * Anti-lock flags
 * See DefaultSettings.php for a description
 */
define( 'ALF_PRELOAD_LINKS', 1 ); // unused
define( 'ALF_PRELOAD_EXISTENCE', 2 ); // unused
define( 'ALF_NO_LINK_LOCK', 4 );
define( 'ALF_NO_BLOCK_LOCK', 8 );
/**@}*/

/**@{
 * Date format selectors; used in user preference storage and by
 * Language::date() and co.
 */
define( 'MW_DATE_DEFAULT', 'default' );
define( 'MW_DATE_MDY', 'mdy' );
define( 'MW_DATE_DMY', 'dmy' );
define( 'MW_DATE_YMD', 'ymd' );
define( 'MW_DATE_ISO', 'ISO 8601' );
/**@}*/

/**@{
 * RecentChange type identifiers
 */
define( 'RC_EDIT', 0 );
define( 'RC_NEW', 1 );
define( 'RC_MOVE', 2 ); // obsolete
define( 'RC_LOG', 3 );
define( 'RC_MOVE_OVER_REDIRECT', 4 ); // obsolete
define( 'RC_EXTERNAL', 5 );
/**@}*/

/**@{
 * Article edit flags
 */
define( 'EDIT_NEW', 1 );
define( 'EDIT_UPDATE', 2 );
define( 'EDIT_MINOR', 4 );
define( 'EDIT_SUPPRESS_RC', 8 );
define( 'EDIT_FORCE_BOT', 16 );
define( 'EDIT_DEFER_UPDATES', 32 );
define( 'EDIT_AUTOSUMMARY', 64 );
/**@}*/

/**@{
 * Flags for Database::makeList()
 * These are also available as Database class constants
 */
define( 'LIST_COMMA', 0 );
define( 'LIST_AND', 1 );
define( 'LIST_SET', 2 );
define( 'LIST_NAMES', 3 );
define( 'LIST_OR', 4 );
/**@}*/

/**
 * Unicode and normalisation related
 */
require_once __DIR__ . '/normal/UtfNormalDefines.php';

/**@{
 * Hook support constants
 */
define( 'MW_SUPPORTS_EDITFILTERMERGED', 1 );
define( 'MW_SUPPORTS_PARSERFIRSTCALLINIT', 1 );
define( 'MW_SUPPORTS_LOCALISATIONCACHE', 1 );
define( 'MW_SUPPORTS_CONTENTHANDLER', 1 );
/**@}*/

/** Support for $wgResourceModules */
define( 'MW_SUPPORTS_RESOURCE_MODULES', 1 );

/**@{
 * Allowed values for Parser::$mOutputType
 * Parameter to Parser::startExternalParse().
 */
define( 'OT_HTML', 1 );
define( 'OT_WIKI', 2 );
define( 'OT_PREPROCESS', 3 );
define( 'OT_MSG', 3 );  // b/c alias for OT_PREPROCESS
define( 'OT_PLAIN', 4 );
/**@}*/

/**@{
 * Flags for Parser::setFunctionHook
 */
define( 'SFH_NO_HASH', 1 );
define( 'SFH_OBJECT_ARGS', 2 );
/**@}*/

/**
 * Flags for Parser::replaceLinkHolders
 */
define( 'RLH_FOR_UPDATE', 1 );

/**@{
 * Autopromote conditions (must be here and not in Autopromote.php, so that
 * they're loaded for DefaultSettings.php before AutoLoader.php)
 */
define( 'APCOND_EDITCOUNT', 1 );
define( 'APCOND_AGE', 2 );
define( 'APCOND_EMAILCONFIRMED', 3 );
define( 'APCOND_INGROUPS', 4 );
define( 'APCOND_ISIP', 5 );
define( 'APCOND_IPINRANGE', 6 );
define( 'APCOND_AGE_FROM_EDIT', 7 );
define( 'APCOND_BLOCKED', 8 );
define( 'APCOND_ISBOT', 9 );
/**@}*/

/** @{
 * Protocol constants for wfExpandUrl()
 */
define( 'PROTO_HTTP', 'http://' );
define( 'PROTO_HTTPS', 'https://' );
define( 'PROTO_RELATIVE', '//' );
define( 'PROTO_CURRENT', null );
define( 'PROTO_CANONICAL', 1 );
define( 'PROTO_INTERNAL', 2 );
/**@}*/

/**@{
 * Content model ids, used by Content and ContentHandler.
 * These IDs will be exposed in the API and XML dumps.
 *
 * Extensions that define their own content model IDs should take
 * care to avoid conflicts. Using the extension name as a prefix is recommended,
 * for example 'myextension-somecontent'.
 */
define( 'CONTENT_MODEL_WIKITEXT', 'wikitext' );
define( 'CONTENT_MODEL_JAVASCRIPT', 'javascript' );
define( 'CONTENT_MODEL_CSS', 'css' );
define( 'CONTENT_MODEL_TEXT', 'text' );
/**@}*/

/**@{
 * Content formats, used by Content and ContentHandler.
 * These should be MIME types, and will be exposed in the API and XML dumps.
 *
 * Extensions are free to use the below formats, or define their own.
 * It is recommended to stick with the conventions for MIME types.
 */
// wikitext
define( 'CONTENT_FORMAT_WIKITEXT', 'text/x-wiki' );
// for js pages
define( 'CONTENT_FORMAT_JAVASCRIPT', 'text/javascript' );
// for css pages
define( 'CONTENT_FORMAT_CSS', 'text/css' );
// for future use, e.g. with some plain-html messages.
define( 'CONTENT_FORMAT_TEXT', 'text/plain' );
// for future use, e.g. with some plain-html messages.
define( 'CONTENT_FORMAT_HTML', 'text/html' );
// for future use with the api and for extensions
define( 'CONTENT_FORMAT_SERIALIZED', 'application/vnd.php.serialized' );
// for future use with the api, and for use by extensions
define( 'CONTENT_FORMAT_JSON', 'application/json' );
// for future use with the api, and for use by extensions
define( 'CONTENT_FORMAT_XML', 'application/xml' );
/**@}*/

/**@{
 * Max string length for shell invocations; based on binfmts.h
 */
define( 'SHELL_MAX_ARG_STRLEN', '100000');
/**@}*/
