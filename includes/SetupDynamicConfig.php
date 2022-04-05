<?php
/**
 * This is included by Setup.php to adjust the values of globals before services are initialized.
 * It's split into a separate file so it can be tested.
 */
use Wikimedia\AtEase\AtEase;

if ( $wgScript === false ) {
	$wgScript = "$wgScriptPath/index.php";
}
if ( $wgLoadScript === false ) {
	$wgLoadScript = "$wgScriptPath/load.php";
}
if ( $wgRestPath === false ) {
	$wgRestPath = "$wgScriptPath/rest.php";
}
if ( $wgUsePathInfo === null ) {
	// These often break when PHP is set up in CGI mode.
	// PATH_INFO *may* be correct if cgi.fix_pathinfo is set, but then again it may not;
	// lighttpd converts incoming path data to lowercase on systems
	// with case-insensitive filesystems, and there have been reports of
	// problems on Apache as well.
	$wgUsePathInfo = ( strpos( PHP_SAPI, 'cgi' ) === false ) &&
		( strpos( PHP_SAPI, 'apache2filter' ) === false ) &&
		( strpos( PHP_SAPI, 'isapi' ) === false );
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
	$wgLogo = "$wgResourceBasePath/resources/assets/change-your-logo.svg";
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
if ( $wgSharedPrefix === false ) {
	$wgSharedPrefix = $wgDBprefix;
}
if ( $wgSharedSchema === false ) {
	$wgSharedSchema = $wgDBmwschema;
}
if ( $wgMetaNamespace === false ) {
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );
}

if ( $wgMainWANCache === false ) {
	// Create a WAN cache from $wgMainCacheType
	$wgMainWANCache = 'mediawiki-main-default';
	$wgWANObjectCaches[$wgMainWANCache] = [
		'class'    => WANObjectCache::class,
		'cacheId'  => $wgMainCacheType,
	];
}

// Back-compat
if ( isset( $wgFileBlacklist ) ) {
	$wgProhibitedFileExtensions = array_merge( $wgProhibitedFileExtensions, $wgFileBlacklist );
} else {
	$wgFileBlacklist = $wgProhibitedFileExtensions;
}
if ( isset( $wgMimeTypeBlacklist ) ) {
	$wgMimeTypeExclusions = array_merge( $wgMimeTypeExclusions, $wgMimeTypeBlacklist );
} else {
	$wgMimeTypeBlacklist = $wgMimeTypeExclusions;
}
if ( isset( $wgEnableUserEmailBlacklist ) ) {
	$wgEnableUserEmailMuteList = $wgEnableUserEmailBlacklist;
} else {
	$wgEnableUserEmailBlacklist = $wgEnableUserEmailMuteList;
}
if ( isset( $wgShortPagesNamespaceBlacklist ) ) {
	$wgShortPagesNamespaceExclusions = $wgShortPagesNamespaceBlacklist;
} else {
	$wgShortPagesNamespaceBlacklist = $wgShortPagesNamespaceExclusions;
}

// Prohibited file extensions shouldn't appear on the "allowed" list
// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal False positive
$wgFileExtensions = array_values( array_diff( $wgFileExtensions, $wgProhibitedFileExtensions ) );

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
 * Determine whether EXIF info can be shown
 */
if ( $wgShowEXIF === null ) {
	$wgShowEXIF = function_exists( 'exif_read_data' );
}

/**
 * Default parameters for the "<gallery>" tag.
 * @see docs/Configuration.md for description of the fields.
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
		'favicon' => $wgFavicon,
		'url' => $wgUploadBaseUrl ? $wgUploadBaseUrl . $wgUploadPath : $wgUploadPath,
		'hashLevels' => $wgHashedUploadDirectory ? 2 : 0,
		'thumbScriptUrl' => $wgThumbnailScriptPath,
		'transformVia404' => !$wgGenerateThumbnailOnParse,
		'deletedDir' => $wgDeletedDirectory,
		'deletedHashLevels' => $wgHashedUploadDirectory ? 3 : 0,
		'updateCompatibleMetadata' => $wgUpdateCompatibleMetadata,
		'reserializeMetadata' => $wgUpdateCompatibleMetadata,
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

if ( $wgLocaltimezone === null ) {
	// This defaults to the `date.timezone` value of the PHP INI option. If this option is not set,
	// it falls back to UTC. Prior to PHP 7.0, this fallback produced a warning.
	$wgLocaltimezone = date_default_timezone_get();
}
date_default_timezone_set( $wgLocaltimezone );
if ( $wgLocalTZoffset === null ) {
	$wgLocalTZoffset = (int)date( 'Z' ) / 60;
}
// The part after the System| is ignored, but rest of MW fills it out as the local offset.
$wgDefaultUserOptions['timecorrection'] = "System|$wgLocalTZoffset";

if ( !$wgDBerrorLogTZ ) {
	$wgDBerrorLogTZ = $wgLocaltimezone;
}

/**
 * Definitions of the NS_ constants are in Defines.php
 * @internal
 */
$wgCanonicalNamespaceNames = NamespaceInfo::CANONICAL_NAMES;

// @todo UGLY UGLY
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
unset( $code ); // no global pollution; destroy reference
unset( $bcp47 ); // no global pollution; destroy reference

// Temporary backwards-compatibility reading of old replica lag settings as of MediaWiki 1.36,
// to support sysadmins who fail to update their settings immediately:

if ( isset( $wgSlaveLagWarning ) ) {
	// If the old value is set to something other than the default, use it.
	if ( $wgDatabaseReplicaLagWarning === 10 && $wgSlaveLagWarning !== 10 ) {
		$wgDatabaseReplicaLagWarning = $wgSlaveLagWarning;
		wfDeprecated(
			'$wgSlaveLagWarning set but $wgDatabaseReplicaLagWarning unchanged; using $wgSlaveLagWarning',
			'1.36'
		);
	}
} else {
	// Backwards-compatibility for extensions that read this value.
	$wgSlaveLagWarning = $wgDatabaseReplicaLagWarning;
}

if ( isset( $wgSlaveLagCritical ) ) {
	// If the old value is set to something other than the default, use it.
	if ( $wgDatabaseReplicaLagCritical === 30 && $wgSlaveLagCritical !== 30 ) {
		$wgDatabaseReplicaLagCritical = $wgSlaveLagCritical;
		wfDeprecated(
			'$wgSlaveLagCritical set but $wgDatabaseReplicaLagCritical unchanged; using $wgSlaveLagCritical',
			'1.36'
		);
	}
} else {
	// Backwards-compatibility for extensions that read this value.
	$wgSlaveLagCritical = $wgDatabaseReplicaLagCritical;
}

if ( $wgInvalidateCacheOnLocalSettingsChange && defined( 'MW_CONFIG_FILE' ) ) {
	AtEase::suppressWarnings();
	$wgCacheEpoch = max( $wgCacheEpoch, gmdate( 'YmdHis', filemtime( MW_CONFIG_FILE ) ) );
	AtEase::restoreWarnings();
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
