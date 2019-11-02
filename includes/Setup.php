<?php
/**
 * Include most things that are needed to make MediaWiki work.
 *
 * This file is included by WebStart.php and doMaintenance.php so that both
 * web and maintenance scripts share a final set up phase to include necessary
 * files and create global object variables.
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
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\ChronologyProtector;

/**
 * This file is not a valid entry point, perform no further processing unless
 * MEDIAWIKI is defined
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

// Check to see if we are at the file scope
$wgScopeTest = 'MediaWiki Setup.php scope test';
if ( !isset( $GLOBALS['wgScopeTest'] ) || $GLOBALS['wgScopeTest'] !== $wgScopeTest ) {
	echo "Error, Setup.php must be included from the file scope.\n";
	die( 1 );
}
unset( $wgScopeTest );

/**
 * Pre-config setup: Before loading LocalSettings.php
 */

// Sanity check (T5782, T122807)
if ( ini_get( 'mbstring.func_overload' ) ) {
	die( 'MediaWiki does not support installations where mbstring.func_overload is non-zero.' );
}

// Define MW_ENTRY_POINT if it's not already, so that config code can check the
// value without using defined()
if ( !defined( 'MW_ENTRY_POINT' ) ) {
	/**
	 * The entry point, which may be either the script filename without the
	 * file extension, or "cli" for maintenance scripts, or "unknown" for any
	 * entry point that does not set the constant.
	 */
	define( 'MW_ENTRY_POINT', 'unknown' );
}

// Start the autoloader, so that extensions can derive classes from core files
require_once "$IP/includes/AutoLoader.php";

// Load global constants
require_once "$IP/includes/Defines.php";

// Load default settings
require_once "$IP/includes/DefaultSettings.php";

// Load global functions
require_once "$IP/includes/GlobalFunctions.php";

// Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
} elseif ( file_exists( "$IP/vendor/autoload.php" ) ) {
	die( "$IP/vendor/autoload.php exists but is not readable" );
}

// Assert that composer dependencies were successfully loaded
// Purposely no leading \ due to it breaking HHVM RepoAuthorative mode
// PHP works fine with both versions
// See https://github.com/facebook/hhvm/issues/5833
if ( !interface_exists( 'Psr\Log\LoggerInterface' ) ) {
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

/**
 * Changes to the PHP environment that don't vary on configuration.
 */

// Install a header callback
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
 * Main setup
 */

// Load queued extensions
ExtensionRegistry::getInstance()->loadFromQueue();
// Don't let any other extensions load
ExtensionRegistry::getInstance()->finish();

// Set the configured locale on all requests for consisteny
putenv( "LC_ALL=$wgShellLocale" );
setlocale( LC_ALL, $wgShellLocale );

// Set various default paths sensibly...
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
 * The canonical names of namespaces 6 and 7 are, as of v1.14, "File"
 * and "File_talk".  The old names "Image" and "Image_talk" are
 * retained as aliases for backwards compatibility.
 */
$wgNamespaceAliases['Image'] = NS_FILE;
$wgNamespaceAliases['Image_talk'] = NS_FILE_TALK;

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

if ( $wgSkipSkin ) {
	// Hard deprecated in 1.34.
	wfDeprecated( '$wgSkipSkin – use $wgSkipSkins instead', '1.23' );
	$wgSkipSkins[] = $wgSkipSkin;
}

$wgSkipSkins[] = 'fallback';
$wgSkipSkins[] = 'apioutput';

if ( $wgLocalInterwiki ) {
	// Hard deprecated in 1.34.
	wfDeprecated( '$wgLocalInterwiki – use $wgLocalInterwikis instead', '1.23' );
	// @phan-suppress-next-line PhanUndeclaredVariableDim
	array_unshift( $wgLocalInterwikis, $wgLocalInterwiki );
}

// Set default shared prefix
if ( $wgSharedPrefix === false ) {
	$wgSharedPrefix = $wgDBprefix;
}

// Set default shared schema
if ( $wgSharedSchema === false ) {
	$wgSharedSchema = $wgDBmwschema;
}

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

// $wgSysopEmailBans deprecated in 1.34
if ( isset( $wgSysopEmailBans ) && $wgSysopEmailBans === false ) {
	wfDeprecated( 'wgSysopEmailBans', '1.34' );
	foreach ( $wgGroupPermissions as $group => $_ ) {
		unset( $wgGroupPermissions[$group]['blockemail'] );
	}
}

if ( $wgMetaNamespace === false ) {
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );
}

// Ensure the minimum chunk size is less than PHP upload limits or the maximum
// upload size.
$wgMinUploadChunkSize = min(
	$wgMinUploadChunkSize,
	UploadBase::getMaxUploadSize( 'file' ),
	UploadBase::getMaxPhpUploadSize(),
	( wfShorthandToInteger(
		ini_get( 'post_max_size' ) ?: ini_get( 'hhvm.server.max_post_size' ),
		PHP_INT_MAX
	) ?: PHP_INT_MAX ) - 1024 // Leave some room for other POST parameters
);

/**
 * Definitions of the NS_ constants are in Defines.php
 * @private
 */
$wgCanonicalNamespaceNames = NamespaceInfo::$canonicalNames;

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

// These are now the same, always
// To determine the user language, use $wgLang->getCode()
$wgContLanguageCode = $wgLanguageCode;

// Temporary backwards-compatibility reading of old Squid-named CDN settings as of MediaWiki 1.34,
// to support sysadmins who fail to update their settings immediately:

if ( isset( $wgUseSquid ) ) {
	// If the sysadmin is still setting a value of $wgUseSquid to true but $wgUseCdn is the default of
	// false, to be safe, assume they do want this still, so enable it.
	if ( !$wgUseCdn && $wgUseSquid ) {
		$wgUseCdn = $wgUseSquid;
		wfDeprecated( '$wgUseSquid enabled but $wgUseCdn disabled; enabling CDN functions', '1.34' );
	}
} else {
	// Backwards-compatibility for extensions that read this value.
	$wgUseSquid = $wgUseCdn;
}

if ( isset( $wgSquidServers ) ) {
	// If the sysadmin is still setting a value of $wgSquidServers but $wgCdnServers is the default of
	// empty, to be safe, assume they do want these servers to be still used, so use them.
	if ( !empty( $wgSquidServers ) && empty( $wgCdnServers ) ) {
		$wgCdnServers = $wgSquidServers;
		wfDeprecated( '$wgSquidServers set, $wgCdnServers empty; using them', '1.34' );
	}
} else {
	// Backwards-compatibility for extensions that read this value.
	$wgSquidServers = $wgCdnServers;
}

if ( isset( $wgSquidServersNoPurge ) ) {
	// If the sysadmin is still setting values in $wgSquidServersNoPurge but $wgCdnServersNoPurge is
	// the default of empty, to be safe, assume they do want these servers to be still used, so use
	// them.
	if ( !empty( $wgSquidServersNoPurge ) && empty( $wgCdnServersNoPurge ) ) {
		$wgCdnServersNoPurge = $wgSquidServersNoPurge;
		wfDeprecated( '$wgSquidServersNoPurge set, $wgCdnServersNoPurge empty; using them', '1.34' );
	}
} else {
	// Backwards-compatibility for extensions that read this value.
	$wgSquidServersNoPurge = $wgCdnServersNoPurge;
}

if ( isset( $wgSquidMaxage ) ) {
	// If the sysadmin is still setting a value of $wgSquidMaxage and it's higher than $wgCdnMaxAge,
	// to be safe, assume they want the higher (lower performance requirement) value, so use that.
	if ( $wgCdnMaxAge < $wgSquidMaxage ) {
		$wgCdnMaxAge = $wgSquidMaxage;
		wfDeprecated( '$wgSquidMaxage set higher than $wgCdnMaxAge; using the higher value', '1.34' );
	}
} else {
	// Backwards-compatibility for extensions that read this value.
	$wgSquidMaxage = $wgCdnMaxAge;
}

// Blacklisted file extensions shouldn't appear on the "allowed" list
$wgFileExtensions = array_values( array_diff( $wgFileExtensions, $wgFileBlacklist ) );

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
	$wgCookieSecure = ( WebRequest::detectProtocol() === 'https' );
}

if ( $wgProfileOnly ) {
	// Hard deprecated in 1.34.
	wfDeprecated(
		'$wgProfileOnly set the log file in $wgDebugLogGroups[\'profileoutput\'] instead',
		'1.23'
	);
	$wgDebugLogGroups['profileoutput'] = $wgDebugLogFile;
	$wgDebugLogFile = '';
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
	// @phan-suppress-next-line PhanUndeclaredConstant
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
if ( $wgServer === false ) {
	throw new FatalError(
		'$wgServer must be set in LocalSettings.php. ' .
		'See <a href="https://www.mediawiki.org/wiki/Manual:$wgServer">' .
		'https://www.mediawiki.org/wiki/Manual:$wgServer</a>.'
	);
}

// T48998: Bail out early if $wgArticlePath is non-absolute
foreach ( [ 'wgArticlePath', 'wgVariantArticlePath' ] as $varName ) {
	if ( $$varName && !preg_match( '/^(https?:\/\/|\/)/', $$varName ) ) {
		throw new FatalError(
			"If you use a relative URL for \$$varName, it must start " .
			'with a slash (<code>/</code>).<br><br>See ' .
			"<a href=\"https://www.mediawiki.org/wiki/Manual:\$$varName\">" .
			"https://www.mediawiki.org/wiki/Manual:\$$varName</a>."
		);
	}
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

// We don't use counters anymore. Left here for extensions still
// expecting this to exist. Should be removed sometime 1.26 or later.
if ( !isset( $wgDisableCounters ) ) {
	$wgDisableCounters = true;
}

if ( $wgMainWANCache === false ) {
	// Setup a WAN cache from $wgMainCacheType with no relayer.
	// Sites using multiple datacenters can configure a relayer.
	$wgMainWANCache = 'mediawiki-main-default';
	$wgWANObjectCaches[$wgMainWANCache] = [
		'class'    => WANObjectCache::class,
		'cacheId'  => $wgMainCacheType
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
if ( is_null( $wgLocaltimezone ) ) {
	Wikimedia\suppressWarnings();
	$wgLocaltimezone = date_default_timezone_get();
	Wikimedia\restoreWarnings();
}

date_default_timezone_set( $wgLocaltimezone );
if ( is_null( $wgLocalTZoffset ) ) {
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
if ( $wgCommandLineMode ) {
	if ( isset( $self ) ) {
		wfDebug( "\n\nStart command line script $self\n" );
	}
} else {
	$debug = "\n\nStart request {$wgRequest->getMethod()} {$wgRequest->getRequestURL()}\n";
	$debug .= "HTTP HEADERS:\n";
	foreach ( $wgRequest->getAllHeaders() as $name => $value ) {
		$debug .= "$name: $value\n";
	}
	wfDebug( $debug );
}

$wgMemc = ObjectCache::getLocalClusterInstance();
$messageMemc = wfGetMessageCacheStorage();

// Most of the config is out, some might want to run hooks here.
Hooks::run( 'SetupAfterCache' );

/**
 * @var Language $wgContLang
 * @deprecated since 1.32, use the ContentLanguage service directly
 */
$wgContLang = MediaWikiServices::getInstance()->getContentLanguage();

// Now that variant lists may be available...
$wgRequest->interpolateTitle();

/**
 * @var MediaWiki\Session\SessionId|null $wgInitialSessionId The persistent
 * session ID (if any) loaded at startup
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
$wgParser = new StubObject( 'wgParser', function () {
	return MediaWikiServices::getInstance()->getParser();
} );

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
		$res = MediaWiki\Auth\AuthManager::singleton()->autoCreateUser(
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
