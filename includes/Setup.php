<?php
/**
 * The setup for all MediaWiki processes (both web-based and CLI).
 *
 * This file must be included by all entry points (such as WebStart.php and doMaintenance.php).
 * - The entry point MUST do these:
 *   - define the 'MEDIAWIKI' constant.
 *   - define the $IP global variable.
 * - The entry point SHOULD do these:
 *    - define the 'MW_ENTRY_POINT' constant.
 *    - display an error if MW_CONFIG_CALLBACK is not defined and the
 *      the file specified in MW_CONFIG_FILE (or the $IP/LocalSettings.php default)
 *      does not exist. The error should either be sent before and instead
 *      of the Setup.php inclusion, or (if it needs classes and dependencies
 *      from core) the erorr can be displayed via a MW_CONFIG_CALLBACK,
 *      which must then abort the process to prevent the rest of Setup.php
 *      from executing.
 *
 * It does:
 * - run-time environment checks,
 * - load autoloaders, constants, default settings, and global functions,
 * - load the site configuration (e.g. LocalSettings.php),
 * - load the enabled extensions (via ExtensionRegistry),
 * - expand any dynamic site configuration defaults and shortcuts
 * - initialization of:
 *   - PHP run-time (setlocale, memory limit, default date timezone)
 *   - the debug logger (MWDebug)
 *   - the service container (MediaWikiServices)
 *   - the exception handler (MWExceptionHandler)
 *   - the session manager (SessionManager)
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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\LBFactory;

/**
 * Environment checks
 *
 * These are inline checks done before we include any source files,
 * and thus these conditions may be assumed by all source code.
 */

// This file must be included from a valid entry point (e.g. WebStart.php, Maintenance.php)
if ( !defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

// This file must have global scope.
$wgScopeTest = 'MediaWiki Setup.php scope test';
if ( !isset( $GLOBALS['wgScopeTest'] ) || $GLOBALS['wgScopeTest'] !== $wgScopeTest ) {
	echo "Error, Setup.php must be included from the file scope.\n";
	die( 1 );
}
unset( $wgScopeTest );

// PHP must not be configured to overload mbstring functions. (T5782, T122807)
// This was deprecated by upstream in PHP 7.2, likely to be removed in PHP 8.0.
if ( ini_get( 'mbstring.func_overload' ) ) {
	die( 'MediaWiki does not support installations where mbstring.func_overload is non-zero.' );
}

// The MW_ENTRY_POINT constant must always exists, to make it safe to access.
// For compat, we do support older and custom MW entryoints that don't set this,
// in which case we assign a default here.
if ( !defined( 'MW_ENTRY_POINT' ) ) {
	/**
	 * The entry point, which may be either the script filename without the
	 * file extension, or "cli" for maintenance scripts, or "unknown" for any
	 * entry point that does not set the constant.
	 */
	define( 'MW_ENTRY_POINT', 'unknown' );
}

/**
 * Pre-config setup: Before loading LocalSettings.php
 *
 * These are changes and additions to runtime that don't vary on site configuration.
 */

require_once "$IP/includes/AutoLoader.php";
require_once "$IP/includes/Defines.php";
require_once "$IP/includes/DefaultSettings.php";
require_once "$IP/includes/GlobalFunctions.php";

// Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
} elseif ( file_exists( "$IP/vendor/autoload.php" ) ) {
	die( "$IP/vendor/autoload.php exists but is not readable" );
}

// Assert that composer dependencies were successfully loaded
if ( !interface_exists( LoggerInterface::class ) ) {
	$message = (
		'MediaWiki requires the <a href="https://github.com/php-fig/log">PSR-3 logging ' .
		"library</a> to be present. This library is not embedded directly in MediaWiki's " .
		"git repository and must be installed separately by the end user.\n\n" .
		'Please see <a href="https://www.mediawiki.org/wiki/Download_from_Git' .
		'#Fetch_external_libraries">mediawiki.org</a> for help on installing ' .
		'the required components.'
	);
	echo $message;
	trigger_error( $message, E_USER_ERROR );
	die( 1 );
}

MediaWiki\HeaderCallback::register();

// Set the encoding used by PHP for reading HTTP input, and writing output.
// This is also the default for mbstring functions.
mb_internal_encoding( 'UTF-8' );

/**
 * Load LocalSettings.php
 */

if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
	call_user_func( MW_CONFIG_CALLBACK );
} else {
	if ( !defined( 'MW_CONFIG_FILE' ) ) {
		define( 'MW_CONFIG_FILE', "$IP/LocalSettings.php" );
	}
	require_once MW_CONFIG_FILE;
}

/**
 * Customization point after all loading (constants, functions, classes,
 * DefaultSettings, LocalSettings). Specifically, this is before usage of
 * settings, before instantiation of Profiler (and other singletons), and
 * before any setup functions or hooks run.
 */

if ( defined( 'MW_SETUP_CALLBACK' ) ) {
	call_user_func( MW_SETUP_CALLBACK );
}

/**
 * Load queued extensions
 */

ExtensionRegistry::getInstance()->loadFromQueue();
// Don't let any other extensions load
ExtensionRegistry::getInstance()->finish();

// Set the configured locale on all requests for consistency
// This must be after LocalSettings.php (and is informed by the installer).
putenv( "LC_ALL=$wgShellLocale" );
setlocale( LC_ALL, $wgShellLocale );

/**
 * Expand dynamic defaults and shortcuts
 */

if ( $wgScript === false ) {
	$wgScript = "$wgScriptPath/index.php";
}
if ( $wgLoadScript === false ) {
	$wgLoadScript = "$wgScriptPath/load.php";
}
if ( $wgRestPath === false ) {
	$wgRestPath = "$wgScriptPath/rest.php";
}
if ( $wgArticlePath === false ) {
	if ( $wgUsePathInfo ) {
		$wgArticlePath = "$wgScript/$1";
	} else {
		$wgArticlePath = "$wgScript?title=$1";
	}
}
if ( $wgResourceBasePath === null ) {
	$wgResourceBasePath = $wgScriptPath;
}
if ( $wgStylePath === false ) {
	$wgStylePath = "$wgResourceBasePath/skins";
}
if ( $wgLocalStylePath === false ) {
	// Avoid wgResourceBasePath here since that may point to a different domain (e.g. CDN)
	$wgLocalStylePath = "$wgScriptPath/skins";
}
if ( $wgExtensionAssetsPath === false ) {
	$wgExtensionAssetsPath = "$wgResourceBasePath/extensions";
}

// For backwards compatibility, the value of wgLogos is copied to wgLogo.
// This is because some extensions/skins may be using $config->get('Logo')
// to access the value.
if ( $wgLogos !== false && isset( $wgLogos['1x'] ) ) {
	$wgLogo = $wgLogos['1x'];
}
if ( $wgLogo === false ) {
	$wgLogo = "$wgResourceBasePath/resources/assets/wiki.png";
}

if ( $wgUploadPath === false ) {
	$wgUploadPath = "$wgScriptPath/images";
}
if ( $wgUploadDirectory === false ) {
	$wgUploadDirectory = "$IP/images";
}
if ( $wgReadOnlyFile === false ) {
	$wgReadOnlyFile = "{$wgUploadDirectory}/lock_yBgMBwiR";
}
if ( $wgFileCacheDirectory === false ) {
	$wgFileCacheDirectory = "{$wgUploadDirectory}/cache";
}
if ( $wgDeletedDirectory === false ) {
	$wgDeletedDirectory = "{$wgUploadDirectory}/deleted";
}
if ( $wgGitInfoCacheDirectory === false && $wgCacheDirectory !== false ) {
	$wgGitInfoCacheDirectory = "{$wgCacheDirectory}/gitinfo";
}
if ( $wgSharedPrefix === false ) {
	$wgSharedPrefix = $wgDBprefix;
}
if ( $wgSharedSchema === false ) {
	$wgSharedSchema = $wgDBmwschema;
}
if ( $wgMetaNamespace === false ) {
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );
}

// Blacklisted file extensions shouldn't appear on the "allowed" list
$wgFileExtensions = array_values( array_diff( $wgFileExtensions, $wgFileBlacklist ) );

// Fix path to icon images after they were moved in 1.24
if ( $wgRightsIcon ) {
	$wgRightsIcon = str_replace(
		"{$wgStylePath}/common/images/",
		"{$wgResourceBasePath}/resources/assets/licenses/",
		$wgRightsIcon
	);
}

if ( isset( $wgFooterIcons['copyright']['copyright'] )
	&& $wgFooterIcons['copyright']['copyright'] === []
) {
	if ( $wgRightsIcon || $wgRightsText ) {
		$wgFooterIcons['copyright']['copyright'] = [
			'url' => $wgRightsUrl,
			'src' => $wgRightsIcon,
			'alt' => $wgRightsText,
		];
	}
}

if ( isset( $wgFooterIcons['poweredby'] )
	&& isset( $wgFooterIcons['poweredby']['mediawiki'] )
	&& $wgFooterIcons['poweredby']['mediawiki']['src'] === null
) {
	$wgFooterIcons['poweredby']['mediawiki']['src'] =
		"$wgResourceBasePath/resources/assets/poweredby_mediawiki_88x31.png";
	$wgFooterIcons['poweredby']['mediawiki']['srcset'] =
		"$wgResourceBasePath/resources/assets/poweredby_mediawiki_132x47.png 1.5x, " .
		"$wgResourceBasePath/resources/assets/poweredby_mediawiki_176x62.png 2x";
}

/**
 * Unconditional protection for NS_MEDIAWIKI since otherwise it's too easy for a
 * sysadmin to set $wgNamespaceProtection incorrectly and leave the wiki insecure.
 *
 * Note that this is the definition of editinterface and it can be granted to
 * all users if desired.
 */
$wgNamespaceProtection[NS_MEDIAWIKI] = 'editinterface';

/**
 * Initialise $wgLockManagers to include basic FS version
 */
$wgLockManagers[] = [
	'name' => 'fsLockManager',
	'class' => FSLockManager::class,
	'lockDirectory' => "{$wgUploadDirectory}/lockdir",
];
$wgLockManagers[] = [
	'name' => 'nullLockManager',
	'class' => NullLockManager::class,
];

/**
 * Default parameters for the "<gallery>" tag.
 * @see DefaultSettings.php for description of the fields.
 */
$wgGalleryOptions += [
	'imagesPerRow' => 0,
	'imageWidth' => 120,
	'imageHeight' => 120,
	'captionLength' => true,
	'showBytes' => true,
	'showDimensions' => true,
	'mode' => 'traditional',
];

/**
 * Shortcuts for $wgLocalFileRepo
 */
if ( !$wgLocalFileRepo ) {
	$wgLocalFileRepo = [
		'class' => LocalRepo::class,
		'name' => 'local',
		'directory' => $wgUploadDirectory,
		'scriptDirUrl' => $wgScriptPath,
		'url' => $wgUploadBaseUrl ? $wgUploadBaseUrl . $wgUploadPath : $wgUploadPath,
		'hashLevels' => $wgHashedUploadDirectory ? 2 : 0,
		'thumbScriptUrl' => $wgThumbnailScriptPath,
		'transformVia404' => !$wgGenerateThumbnailOnParse,
		'deletedDir' => $wgDeletedDirectory,
		'deletedHashLevels' => $wgHashedUploadDirectory ? 3 : 0
	];
}

if ( !isset( $wgLocalFileRepo['backend'] ) ) {
	// Create a default FileBackend name.
	// FileBackendGroup will register a default, if absent from $wgFileBackends.
	$wgLocalFileRepo['backend'] = $wgLocalFileRepo['name'] . '-backend';
}

/**
 * Shortcuts for $wgForeignFileRepos
 */
if ( $wgUseSharedUploads ) {
	if ( $wgSharedUploadDBname ) {
		$wgForeignFileRepos[] = [
			'class' => ForeignDBRepo::class,
			'name' => 'shared',
			'directory' => $wgSharedUploadDirectory,
			'url' => $wgSharedUploadPath,
			'hashLevels' => $wgHashedSharedUploadDirectory ? 2 : 0,
			'thumbScriptUrl' => $wgSharedThumbnailScriptPath,
			'transformVia404' => !$wgGenerateThumbnailOnParse,
			'dbType' => $wgDBtype,
			'dbServer' => $wgDBserver,
			'dbUser' => $wgDBuser,
			'dbPassword' => $wgDBpassword,
			'dbName' => $wgSharedUploadDBname,
			'dbFlags' => ( $wgDebugDumpSql ? DBO_DEBUG : 0 ) | DBO_DEFAULT,
			'tablePrefix' => $wgSharedUploadDBprefix,
			'hasSharedCache' => $wgCacheSharedUploads,
			'descBaseUrl' => $wgRepositoryBaseUrl,
			'fetchDescription' => $wgFetchCommonsDescriptions,
		];
	} else {
		$wgForeignFileRepos[] = [
			'class' => FileRepo::class,
			'name' => 'shared',
			'directory' => $wgSharedUploadDirectory,
			'url' => $wgSharedUploadPath,
			'hashLevels' => $wgHashedSharedUploadDirectory ? 2 : 0,
			'thumbScriptUrl' => $wgSharedThumbnailScriptPath,
			'transformVia404' => !$wgGenerateThumbnailOnParse,
			'descBaseUrl' => $wgRepositoryBaseUrl,
			'fetchDescription' => $wgFetchCommonsDescriptions,
		];
	}
}
if ( $wgUseInstantCommons ) {
	$wgForeignFileRepos[] = [
		'class' => ForeignAPIRepo::class,
		'name' => 'wikimediacommons',
		'apibase' => 'https://commons.wikimedia.org/w/api.php',
		'url' => 'https://upload.wikimedia.org/wikipedia/commons',
		'thumbUrl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb',
		'hashLevels' => 2,
		'transformVia404' => true,
		'fetchDescription' => true,
		'descriptionCacheExpiry' => 43200,
		'apiThumbCacheExpiry' => 0,
	];
}
foreach ( $wgForeignFileRepos as &$repo ) {
	if ( !isset( $repo['directory'] ) && $repo['class'] === ForeignAPIRepo::class ) {
		$repo['directory'] = $wgUploadDirectory; // b/c
	}
	if ( !isset( $repo['backend'] ) ) {
		$repo['backend'] = $repo['name'] . '-backend';
	}
}
unset( $repo ); // no global pollution; destroy reference

$rcMaxAgeDays = $wgRCMaxAge / ( 3600 * 24 );
// Ensure that default user options are not invalid, since that breaks Special:Preferences
$wgDefaultUserOptions['rcdays'] = min(
	$wgDefaultUserOptions['rcdays'],
	ceil( $rcMaxAgeDays )
);
$wgDefaultUserOptions['watchlistdays'] = min(
	$wgDefaultUserOptions['watchlistdays'],
	ceil( $rcMaxAgeDays )
);
unset( $rcMaxAgeDays );

if ( !$wgCookiePrefix ) {
	if ( $wgSharedDB && $wgSharedPrefix && in_array( 'user', $wgSharedTables ) ) {
		$wgCookiePrefix = $wgSharedDB . '_' . $wgSharedPrefix;
	} elseif ( $wgSharedDB && in_array( 'user', $wgSharedTables ) ) {
		$wgCookiePrefix = $wgSharedDB;
	} elseif ( $wgDBprefix ) {
		$wgCookiePrefix = $wgDBname . '_' . $wgDBprefix;
	} else {
		$wgCookiePrefix = $wgDBname;
	}
}
$wgCookiePrefix = strtr( $wgCookiePrefix, '=,; +."\'\\[', '__________' );

if ( $wgEnableEmail ) {
	$wgUseEnotif = $wgEnotifUserTalk || $wgEnotifWatchlist;
} else {
	// Disable all other email settings automatically if $wgEnableEmail
	// is set to false. - T65678
	$wgAllowHTMLEmail = false;
	$wgEmailAuthentication = false; // do not require auth if you're not sending email anyway
	$wgEnableUserEmail = false;
	$wgEnotifFromEditor = false;
	$wgEnotifImpersonal = false;
	$wgEnotifMaxRecips = 0;
	$wgEnotifMinorEdits = false;
	$wgEnotifRevealEditorAddress = false;
	$wgEnotifUseRealName = false;
	$wgEnotifUserTalk = false;
	$wgEnotifWatchlist = false;
	unset( $wgGroupPermissions['user']['sendemail'] );
	$wgUseEnotif = false;
	$wgUserEmailUseReplyTo = false;
	$wgUsersNotifiedOnAllChanges = [];
}

/**
 * Definitions of the NS_ constants are in Defines.php
 * @internal
 */
$wgCanonicalNamespaceNames = NamespaceInfo::CANONICAL_NAMES;

/// @todo UGLY UGLY
if ( is_array( $wgExtraNamespaces ) ) {
	$wgCanonicalNamespaceNames += $wgExtraNamespaces;
}

// Hard-deprecate setting $wgDummyLanguageCodes in LocalSettings.php
if ( count( $wgDummyLanguageCodes ) !== 0 ) {
	wfDeprecated( '$wgDummyLanguageCodes', '1.29' );
}
// Merge in the legacy language codes, incorporating overrides from the config
$wgDummyLanguageCodes += [
	// Internal language codes of the private-use area which get mapped to
	// themselves.
	'qqq' => 'qqq', // Used for message documentation
	'qqx' => 'qqx', // Used for viewing message keys
] + $wgExtraLanguageCodes + LanguageCode::getDeprecatedCodeMapping();
// Merge in (inverted) BCP 47 mappings
foreach ( LanguageCode::getNonstandardLanguageCodeMapping() as $code => $bcp47 ) {
	$bcp47 = strtolower( $bcp47 ); // force case-insensitivity
	if ( !isset( $wgDummyLanguageCodes[$bcp47] ) ) {
		$wgDummyLanguageCodes[$bcp47] = $wgDummyLanguageCodes[$code] ?? $code;
	}
}

if ( $wgInvalidateCacheOnLocalSettingsChange ) {
	Wikimedia\suppressWarnings();
	$wgCacheEpoch = max( $wgCacheEpoch, gmdate( 'YmdHis', filemtime( "$IP/LocalSettings.php" ) ) );
	Wikimedia\restoreWarnings();
}

if ( $wgNewUserLog ) {
	// Add new user log type
	$wgLogTypes[] = 'newusers';
	$wgLogNames['newusers'] = 'newuserlogpage';
	$wgLogHeaders['newusers'] = 'newuserlogpagetext';
	$wgLogActionsHandlers['newusers/newusers'] = NewUsersLogFormatter::class;
	$wgLogActionsHandlers['newusers/create'] = NewUsersLogFormatter::class;
	$wgLogActionsHandlers['newusers/create2'] = NewUsersLogFormatter::class;
	$wgLogActionsHandlers['newusers/byemail'] = NewUsersLogFormatter::class;
	$wgLogActionsHandlers['newusers/autocreate'] = NewUsersLogFormatter::class;
}

if ( $wgPageCreationLog ) {
	// Add page creation log type
	$wgLogTypes[] = 'create';
	$wgLogActionsHandlers['create/create'] = LogFormatter::class;
}

if ( $wgPageLanguageUseDB ) {
	$wgLogTypes[] = 'pagelang';
	$wgLogActionsHandlers['pagelang/pagelang'] = PageLangLogFormatter::class;
}

if ( $wgCookieSecure === 'detect' ) {
	$wgCookieSecure = $wgForceHTTPS || ( WebRequest::detectProtocol() === 'https' );
}

// Backwards compatibility with old password limits
if ( $wgMinimalPasswordLength !== false ) {
	$wgPasswordPolicy['policies']['default']['MinimalPasswordLength'] = $wgMinimalPasswordLength;
}

if ( $wgMaximalPasswordLength !== false ) {
	$wgPasswordPolicy['policies']['default']['MaximalPasswordLength'] = $wgMaximalPasswordLength;
}

if ( $wgPHPSessionHandling !== 'enable' &&
	$wgPHPSessionHandling !== 'warn' &&
	$wgPHPSessionHandling !== 'disable'
) {
	$wgPHPSessionHandling = 'warn';
}
if ( defined( 'MW_NO_SESSION' ) ) {
	// If the entry point wants no session, force 'disable' here unless they
	// specifically set it to the (undocumented) 'warn'.
	$wgPHPSessionHandling = MW_NO_SESSION === 'warn' ? 'warn' : 'disable';
}

MWDebug::setup();

// Reset the global service locator, so any services that have already been created will be
// re-created while taking into account any custom settings and extensions.
MediaWikiServices::resetGlobalInstance( new GlobalVarConfig(), 'quick' );

// Define a constant that indicates that the bootstrapping of the service locator
// is complete.
define( 'MW_SERVICE_BOOTSTRAP_COMPLETE', 1 );

MWExceptionHandler::installHandler();

// T30798: $wgServer must be explicitly set
// @phan-suppress-next-line PhanSuspiciousValueComparisonInGlobalScope
if ( $wgServer === false ) {
	throw new FatalError(
		'$wgServer must be set in LocalSettings.php. ' .
		'See <a href="https://www.mediawiki.org/wiki/Manual:$wgServer">' .
		'https://www.mediawiki.org/wiki/Manual:$wgServer</a>.'
	);
}

if ( $wgCanonicalServer === false ) {
	$wgCanonicalServer = wfExpandUrl( $wgServer, PROTO_HTTP );
}

// Set server name
$serverParts = wfParseUrl( $wgCanonicalServer );
if ( $wgServerName !== false ) {
	wfWarn( '$wgServerName should be derived from $wgCanonicalServer, '
		. 'not customized. Overwriting $wgServerName.' );
}
$wgServerName = $serverParts['host'];
unset( $serverParts );

// Set defaults for configuration variables
// that are derived from the server name by default
// Note: $wgEmergencyContact and $wgPasswordSender may be false or empty string (T104142)
if ( !$wgEmergencyContact ) {
	$wgEmergencyContact = 'wikiadmin@' . $wgServerName;
}
if ( !$wgPasswordSender ) {
	$wgPasswordSender = 'apache@' . $wgServerName;
}
if ( !$wgNoReplyAddress ) {
	$wgNoReplyAddress = $wgPasswordSender;
}

if ( $wgSecureLogin && substr( $wgServer, 0, 2 ) !== '//' ) {
	$wgSecureLogin = false;
	wfWarn( 'Secure login was enabled on a server that only supports '
		. 'HTTP or HTTPS. Disabling secure login.' );
}

$wgVirtualRestConfig['global']['domain'] = $wgCanonicalServer;

// Now that GlobalFunctions is loaded, set defaults that depend on it.
if ( $wgTmpDirectory === false ) {
	$wgTmpDirectory = wfTempDir();
}

if ( $wgMainWANCache === false ) {
	// Setup a WAN cache from $wgMainCacheType
	$wgMainWANCache = 'mediawiki-main-default';
	$wgWANObjectCaches[$wgMainWANCache] = [
		'class'    => WANObjectCache::class,
		'cacheId'  => $wgMainCacheType,
	];
}

if ( $wgSharedDB && $wgSharedTables ) {
	// Apply $wgSharedDB table aliases for the local LB (all non-foreign DB connections)
	MediaWikiServices::getInstance()->getDBLoadBalancer()->setTableAliases(
		array_fill_keys(
			$wgSharedTables,
			[
				'dbname' => $wgSharedDB,
				'schema' => $wgSharedSchema,
				'prefix' => $wgSharedPrefix
			]
		)
	);
}

// Raise the memory limit if it's too low
// Note, this makes use of wfDebug, and thus should not be before
// MWDebug::init() is called.
wfMemoryLimit( $wgMemoryLimit );

/**
 * Set up the timezone, suppressing the pseudo-security warning in PHP 5.1+
 * that happens whenever you use a date function without the timezone being
 * explicitly set. Inspired by phpMyAdmin's treatment of the problem.
 */
if ( $wgLocaltimezone === null ) {
	Wikimedia\suppressWarnings();
	$wgLocaltimezone = date_default_timezone_get();
	Wikimedia\restoreWarnings();
}

date_default_timezone_set( $wgLocaltimezone );
if ( $wgLocalTZoffset === null ) {
	$wgLocalTZoffset = (int)date( 'Z' ) / 60;
}
// The part after the System| is ignored, but rest of MW fills it
// out as the local offset.
$wgDefaultUserOptions['timecorrection'] = "System|$wgLocalTZoffset";

if ( !$wgDBerrorLogTZ ) {
	$wgDBerrorLogTZ = $wgLocaltimezone;
}

// Initialize the request object in $wgRequest
$wgRequest = RequestContext::getMain()->getRequest(); // BackCompat
// Set user IP/agent information for agent session consistency purposes
$cpPosInfo = LBFactory::getCPInfoFromCookieValue(
	// The cookie has no prefix and is set by MediaWiki::preOutputCommit()
	$wgRequest->getCookie( 'cpPosIndex', '' ),
	// Mitigate broken client-side cookie expiration handling (T190082)
	time() - ChronologyProtector::POSITION_COOKIE_TTL
);
MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->setRequestInfo( [
	'IPAddress' => $wgRequest->getIP(),
	'UserAgent' => $wgRequest->getHeader( 'User-Agent' ),
	'ChronologyProtection' => $wgRequest->getHeader( 'MediaWiki-Chronology-Protection' ),
	'ChronologyPositionIndex' => $wgRequest->getInt( 'cpPosIndex', $cpPosInfo['index'] ),
	'ChronologyClientId' => $cpPosInfo['clientId']
		?? $wgRequest->getHeader( 'MediaWiki-Chronology-Client-Id' )
] );
unset( $cpPosInfo );
// Make sure that object caching does not undermine the ChronologyProtector improvements
if ( $wgRequest->getCookie( 'UseDC', '' ) === 'master' ) {
	// The user is pinned to the primary DC, meaning that they made recent changes which should
	// be reflected in their subsequent web requests. Avoid the use of interim cache keys because
	// they use a blind TTL and could be stale if an object changes twice in a short time span.
	MediaWikiServices::getInstance()->getMainWANObjectCache()->useInterimHoldOffCaching( false );
}

// Useful debug output
( function () {
	global $wgCommandLineMode, $wgRequest;
	$logger = LoggerFactory::getInstance( 'wfDebug' );
	if ( $wgCommandLineMode ) {
		$self = $_SERVER['PHP_SELF'] ?? '';
		$logger->debug( "\n\nStart command line script $self" );
	} else {
		$debug = "\n\nStart request {$wgRequest->getMethod()} {$wgRequest->getRequestURL()}\n";
		$debug .= "IP: " . $wgRequest->getIP() . "\n";
		$debug .= "HTTP HEADERS:\n";
		foreach ( $wgRequest->getAllHeaders() as $name => $value ) {
			$debug .= "$name: $value\n";
		}
		$debug .= "(end headers)";
		$logger->debug( $debug );
	}
} )();

/**
 * @var BagOStuff $wgMemc
 * @deprecated since 1.35, use the LocalServerObjectCache service instead
 */
$wgMemc = ObjectCache::getLocalClusterInstance();

// Most of the config is out, some might want to run hooks here.
Hooks::runner()->onSetupAfterCache();

/**
 * @var Language $wgContLang
 * @deprecated since 1.32, use the ContentLanguage service directly
 */
$wgContLang = MediaWikiServices::getInstance()->getContentLanguage();

// Now that variant lists may be available...
$wgRequest->interpolateTitle();

/**
 * @var MediaWiki\Session\SessionId|null The persistent session ID (if any) loaded at startup
 */
$wgInitialSessionId = null;
if ( !defined( 'MW_NO_SESSION' ) && !$wgCommandLineMode ) {
	// If session.auto_start is there, we can't touch session name
	if ( $wgPHPSessionHandling !== 'disable' && !wfIniGetBool( 'session.auto_start' ) ) {
		session_name( $wgSessionName ?: $wgCookiePrefix . '_session' );
	}

	// Create the SessionManager singleton and set up our session handler,
	// unless we're specifically asked not to.
	if ( !defined( 'MW_NO_SESSION_HANDLER' ) ) {
		MediaWiki\Session\PHPSessionHandler::install(
			MediaWiki\Session\SessionManager::singleton()
		);
	}

	// Initialize the session
	try {
		$session = MediaWiki\Session\SessionManager::getGlobalSession();
	} catch ( MediaWiki\Session\SessionOverflowException $ex ) {
		// The exception is because the request had multiple possible
		// sessions tied for top priority. Report this to the user.
		$list = [];
		foreach ( $ex->getSessionInfos() as $info ) {
			$list[] = $info->getProvider()->describe( $wgContLang );
		}
		$list = $wgContLang->listToText( $list );
		throw new HttpError( 400,
			Message::newFromKey( 'sessionmanager-tie', $list )->inLanguage( $wgContLang )->plain()
		);
	}

	if ( $session->isPersistent() ) {
		$wgInitialSessionId = $session->getSessionId();
	}

	$session->renew();
	if ( MediaWiki\Session\PHPSessionHandler::isEnabled() &&
		( $session->isPersistent() || $session->shouldRememberUser() ) &&
		session_id() !== $session->getId()
	) {
		// Start the PHP-session for backwards compatibility
		if ( session_id() !== '' ) {
			wfDebugLog( 'session', 'PHP session {old_id} was already started, changing to {new_id}', 'all', [
				'old_id' => session_id(),
				'new_id' => $session->getId(),
			] );
			session_write_close();
		}
		session_id( $session->getId() );
		session_start();
	}

	unset( $session );
} else {
	// Even if we didn't set up a global Session, still install our session
	// handler unless specifically requested not to.
	if ( !defined( 'MW_NO_SESSION_HANDLER' ) ) {
		MediaWiki\Session\PHPSessionHandler::install(
			MediaWiki\Session\SessionManager::singleton()
		);
	}
}

/**
 * @var User $wgUser
 * @deprecated since 1.35, use an available context source when possible, or, as a backup,
 * RequestContext::getMain()
 */
$wgUser = RequestContext::getMain()->getUser(); // BackCompat

/**
 * @var Language $wgLang
 */
$wgLang = new StubUserLang;

/**
 * @var OutputPage $wgOut
 */
$wgOut = RequestContext::getMain()->getOutput(); // BackCompat

/**
 * @var Parser $wgParser
 * @deprecated since 1.32, use MediaWikiServices::getInstance()->getParser() instead
 */
$wgParser = new DeprecatedGlobal( 'wgParser', function () {
	return MediaWikiServices::getInstance()->getParser();
}, '1.32' );

/**
 * @var Title $wgTitle
 */
$wgTitle = null;

// Extension setup functions
// Entries should be added to this variable during the inclusion
// of the extension file. This allows the extension to perform
// any necessary initialisation in the fully initialised environment
foreach ( $wgExtensionFunctions as $func ) {
	call_user_func( $func );
}

// If the session user has a 0 id but a valid name, that means we need to
// autocreate it.
if ( !defined( 'MW_NO_SESSION' ) && !$wgCommandLineMode ) {
	$sessionUser = MediaWiki\Session\SessionManager::getGlobalSession()->getUser();
	if ( $sessionUser->getId() === 0 && User::isValidUserName( $sessionUser->getName() ) ) {
		$res = MediaWikiServices::getInstance()->getAuthManager()->autoCreateUser(
			$sessionUser,
			MediaWiki\Auth\AuthManager::AUTOCREATE_SOURCE_SESSION,
			true
		);
		\MediaWiki\Logger\LoggerFactory::getInstance( 'authevents' )->info( 'Autocreation attempt', [
			'event' => 'autocreate',
			'status' => $res,
		] );
		unset( $res );
	}
	unset( $sessionUser );
}

if ( !$wgCommandLineMode ) {
	Pingback::schedulePingback();
}

$wgFullyInitialised = true;
