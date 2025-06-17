<?php
/**
 * A few constants that might be needed during LocalSettings.php.
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

require_once __DIR__ . '/libs/mime/defines.php';
require_once __DIR__ . '/libs/rdbms/defines.php';

use Wikimedia\Rdbms\IDatabase;

/**
 * The running version of MediaWiki.
 *
 * This replaces the $wgVersion global found in earlier versions. When updating
 * the XX part of 1.XX.YY, please remember to also bump the stand-alone duplicate
 * of this in PHPVersionCheck.
 *
 * @since 1.35 (also backported to 1.33.3 and 1.34.1)
 */
define( 'MW_VERSION', '1.45.0-wmf.6' );

/** @{
 * Obsolete IDatabase::makeList() constants
 * These are also available as Database class constants
 */
define( 'LIST_COMMA', IDatabase::LIST_COMMA );
define( 'LIST_AND', IDatabase::LIST_AND );
define( 'LIST_SET', IDatabase::LIST_SET );
define( 'LIST_NAMES', IDatabase::LIST_NAMES );
define( 'LIST_OR', IDatabase::LIST_OR );
/** @} */

/** @{
 * Virtual namespaces; don't appear in the page database
 */
define( 'NS_MEDIA', -2 );
define( 'NS_SPECIAL', -1 );
/** @} */

/** @{
 * Real namespaces
 *
 * Number 100 and beyond are reserved for custom namespaces;
 * DO NOT assign standard namespaces at 100 or beyond.
 * DO NOT Change integer values as they are most probably hardcoded everywhere
 * see T2696 which talked about that.
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
/** @} */

/** @{
 * Cache type
 */
define( 'CACHE_ANYTHING', -1 ); // Use anything, as long as it works
define( 'CACHE_NONE', 0 ); // Do not cache
define( 'CACHE_DB', 1 ); // Store cache objects in the DB
define( 'CACHE_MEMCACHED', 'memcached-php' ); // Backwards-compatability alias for Memcached
define( 'CACHE_ACCEL', 3 ); // APC or APCu
define( 'CACHE_HASH', 'hash' ); // A HashBagOStuff, mostly useful for testing. Not configurable
/** @} */

/** @{
 * Antivirus result codes, for use in $wgAntivirusSetup.
 */
define( 'AV_NO_VIRUS', 0 );  # scan ok, no virus found
define( 'AV_VIRUS_FOUND', 1 );  # virus found!
define( 'AV_SCAN_ABORTED', -1 );  # scan aborted, the file is probably immune
define( 'AV_SCAN_FAILED', false );  # scan failed (scanner not found or error in scanner)
/** @} */

/** @{
 * Date format selectors; used in user preference storage and by
 * Language::date() and co.
 */
define( 'MW_DATE_DEFAULT', 'default' );
define( 'MW_DATE_MDY', 'mdy' );
define( 'MW_DATE_DMY', 'dmy' );
define( 'MW_DATE_YMD', 'ymd' );
define( 'MW_DATE_ISO', 'ISO 8601' );
/** @} */

/** @{
 * RecentChange type identifiers
 */
define( 'RC_EDIT', 0 );
define( 'RC_NEW', 1 );
define( 'RC_LOG', 3 );
define( 'RC_EXTERNAL', 5 );
define( 'RC_CATEGORIZE', 6 );
/** @} */

/** @{
 * Article edit flags
 */
/** Article is assumed to be non-existent, fail if it exists. */
define( 'EDIT_NEW', 1 );

/** Article is assumed to be pre-existing, fail if it doesn't exist. */
define( 'EDIT_UPDATE', 2 );

/** Mark this edit minor, if the user is allowed to do so */
define( 'EDIT_MINOR', 4 );

/** Do not notify other users (e.g. via RecentChanges or watchlist) */
define( 'EDIT_SILENT', 8 );

/** @deprecated since 1.44, use EDIT_SILENT instead */
define( 'EDIT_SUPPRESS_RC', EDIT_SILENT );

/** Mark the edit a "bot" edit regardless of user rights */
define( 'EDIT_FORCE_BOT', 16 );

/** @deprecated since 1.27, updates are always deferred */
define( 'EDIT_DEFER_UPDATES', 32 );

/** Fill in blank summaries with generated text where possible */
define( 'EDIT_AUTOSUMMARY', 64 );

/** Signal that the page retrieve/save cycle happened entirely in this request. */
define( 'EDIT_INTERNAL', 128 );

/** The edit is a side effect and does not represent an active user contribution. */
define( 'EDIT_IMPLICIT', 256 );
/** @} */

/** @{
 * Hook support constants
 */
define( 'MW_SUPPORTS_PARSERFIRSTCALLINIT', 1 );
define( 'MW_SUPPORTS_LOCALISATIONCACHE', 1 );
define( 'MW_SUPPORTS_CONTENTHANDLER', 1 );
define( 'MW_EDITFILTERMERGED_SUPPORTS_API', 1 );
/** @} */

/** Support for $wgResourceModules */
define( 'MW_SUPPORTS_RESOURCE_MODULES', 1 );

/**
 * Indicate that the Interwiki extension should not be loaded (it is now
 * in core).
 */
define( 'MW_HAS_SPECIAL_INTERWIKI', 1 );

/** @{
 * Allowed values for Parser::$mOutputType
 * Parameter to Parser::startExternalParse().
 * Use of Parser consts is preferred:
 * - Parser::OT_HTML
 * - Parser::OT_WIKI
 * - Parser::OT_PREPROCESS
 * - Parser::OT_PLAIN
 */
define( 'OT_HTML', 1 );
define( 'OT_WIKI', 2 );
define( 'OT_PREPROCESS', 3 );
define( 'OT_PLAIN', 4 );
/** @} */

/** @{
 * Flags for Parser::setFunctionHook
 * Use of Parser consts is preferred:
 * - Parser::SFH_NO_HASH
 * - Parser::SFH_OBJECT_ARGS
 */
define( 'SFH_NO_HASH', 1 );
define( 'SFH_OBJECT_ARGS', 2 );
/** @} */

/** @{
 * Autopromote conditions
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
/** @} */

/** @{
 * Conditional user defaults conditions
 *
 * Strings are used to make the values easier to use in extension.json
 * @since 1.42
 */
define( 'CUDCOND_AFTER', 'registered-after' );
define( 'CUDCOND_ANON', 'anonymous-user' );
define( 'CUDCOND_NAMED', 'named-user' );
define( 'CUDCOND_USERGROUP', 'usergroup' );
/** @} */

/** @{
 * Protocol constants for UrlUtils::expand()
 * PROTO_FALLBACK is @since 1.39
 */
define( 'PROTO_HTTP', 'http://' );
define( 'PROTO_HTTPS', 'https://' );
define( 'PROTO_RELATIVE', '//' );
define( 'PROTO_FALLBACK', null );
// Legacy alias for PROTO_FALLBACK from when the current request's protocol was always the fallback
define( 'PROTO_CURRENT', PROTO_FALLBACK );
define( 'PROTO_CANONICAL', 1 );
define( 'PROTO_INTERNAL', 2 );
/** @} */

/** @{
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
define( 'CONTENT_MODEL_JSON', 'json' );
define( 'CONTENT_MODEL_UNKNOWN', 'unknown' );
/** @} */

/** @{
 * Content formats, used by Content and ContentHandler.
 * These should be MIME types, and will be exposed in the API and XML dumps.
 *
 * Extensions are free to use the below formats, or define their own.
 * It is recommended to stick with the conventions for MIME types.
 */
/** Wikitext */
define( 'CONTENT_FORMAT_WIKITEXT', 'text/x-wiki' );
/** For JS pages */
define( 'CONTENT_FORMAT_JAVASCRIPT', 'text/javascript' );
/** For CSS pages */
define( 'CONTENT_FORMAT_CSS', 'text/css' );
/** For future use, e.g. with some plain HTML messages. */
define( 'CONTENT_FORMAT_TEXT', 'text/plain' );
/** For future use, e.g. with some plain HTML messages. */
define( 'CONTENT_FORMAT_HTML', 'text/html' );
/** For future use with the API and for extensions */
define( 'CONTENT_FORMAT_SERIALIZED', 'application/vnd.php.serialized' );
/** For future use with the API, and for use by extensions */
define( 'CONTENT_FORMAT_JSON', 'application/json' );
/** For future use with the API, and for use by extensions */
define( 'CONTENT_FORMAT_XML', 'application/xml' );
/** @} */

/** @{
 * Max string length for shell invocations; based on binfmts.h
 */
define( 'SHELL_MAX_ARG_STRLEN', '100000' );
/** @} */

/** @{
 * Schema compatibility flags.
 *
 * Used as flags in a bit field that indicates whether the old or new schema (or both)
 * are read or written.
 *
 * - SCHEMA_COMPAT_WRITE_OLD: Whether information is written to the old schema.
 * - SCHEMA_COMPAT_READ_OLD: Whether information stored in the old schema is read.
 * - SCHEMA_COMPAT_WRITE_TEMP: Whether information is written to a temporary
 *   intermediate schema.
 * - SCHEMA_COMPAT_READ_TEMP: Whether information is read from the temporary
 *   intermediate schema.
 * - SCHEMA_COMPAT_WRITE_NEW: Whether information is written to the new schema
 * - SCHEMA_COMPAT_READ_NEW: Whether information is read from the new schema
 */
define( 'SCHEMA_COMPAT_WRITE_OLD', 0x01 );
define( 'SCHEMA_COMPAT_READ_OLD', 0x02 );
define( 'SCHEMA_COMPAT_WRITE_TEMP', 0x10 );
define( 'SCHEMA_COMPAT_READ_TEMP', 0x20 );
define( 'SCHEMA_COMPAT_WRITE_NEW', 0x100 );
define( 'SCHEMA_COMPAT_READ_NEW', 0x200 );
define( 'SCHEMA_COMPAT_WRITE_MASK',
	SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_WRITE_TEMP | SCHEMA_COMPAT_WRITE_NEW );
define( 'SCHEMA_COMPAT_READ_MASK',
	SCHEMA_COMPAT_READ_OLD | SCHEMA_COMPAT_READ_TEMP | SCHEMA_COMPAT_READ_NEW );
define( 'SCHEMA_COMPAT_WRITE_BOTH', SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_WRITE_NEW );
define( 'SCHEMA_COMPAT_WRITE_OLD_AND_TEMP', SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_WRITE_TEMP );
define( 'SCHEMA_COMPAT_WRITE_TEMP_AND_NEW', SCHEMA_COMPAT_WRITE_TEMP | SCHEMA_COMPAT_WRITE_NEW );
define( 'SCHEMA_COMPAT_READ_BOTH', SCHEMA_COMPAT_READ_OLD | SCHEMA_COMPAT_READ_NEW );
define( 'SCHEMA_COMPAT_OLD', SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_OLD );
define( 'SCHEMA_COMPAT_TEMP', SCHEMA_COMPAT_WRITE_TEMP | SCHEMA_COMPAT_READ_TEMP );
define( 'SCHEMA_COMPAT_NEW', SCHEMA_COMPAT_WRITE_NEW | SCHEMA_COMPAT_READ_NEW );
/** @} */

/** @{
 * Schema change migration flags.
 *
 * Used as values of a feature flag for an orderly transition from an old
 * schema to a new schema. The numeric values of these constants are compatible with the
 * SCHEMA_COMPAT_XXX bitfield semantics. High bits are used to ensure that the numeric
 * ordering follows the order in which the migration stages should be used.
 *
 * Do not use these constants to query the feature flag.  If you wish to check if your
 * code should perform a particular kind of read or write operation, use the appropriate
 * SCHEMA_COMPAT_XXX flag.  It is generally an error to use MIGRATION_XXX constants in a bitwise operation.
 *
 * - MIGRATION_OLD: Only read and write the old schema. The new schema need not
 *   even exist. This is used from when the patch is merged until the schema
 *   change is actually applied to the database.
 * - MIGRATION_WRITE_BOTH: Write both the old and new schema. Read the new
 *   schema preferentially, falling back to the old. This is used while the
 *   change is being tested, allowing easy roll-back to the old schema.
 * - MIGRATION_WRITE_NEW: Write only the new schema. Read the new schema
 *   preferentially, falling back to the old. This is used while running the
 *   maintenance script to migrate existing entries in the old schema to the
 *   new schema.
 * - MIGRATION_NEW: Only read and write the new schema. The old schema (and the
 *   feature flag) may now be removed.
 */
define( 'MIGRATION_OLD', 0x00000000 | SCHEMA_COMPAT_OLD );
define( 'MIGRATION_WRITE_BOTH', 0x10000000 | SCHEMA_COMPAT_READ_BOTH | SCHEMA_COMPAT_WRITE_BOTH );
define( 'MIGRATION_WRITE_NEW', 0x20000000 | SCHEMA_COMPAT_READ_BOTH | SCHEMA_COMPAT_WRITE_NEW );
define( 'MIGRATION_NEW', 0x30000000 | SCHEMA_COMPAT_NEW );
/** @} */

/** @{
 * XML dump schema versions, for use with XmlDumpWriter.
 * See also the corresponding export-nnnn.xsd files in the docs directory,
 * which are also listed at <https://www.mediawiki.org/xml/>.
 * Note that not all old schema versions are represented here, as several
 * were already unsupported at the time these constants were introduced.
 */
define( 'XML_DUMP_SCHEMA_VERSION_10', '0.10' );
define( 'XML_DUMP_SCHEMA_VERSION_11', '0.11' );
/** @} */
