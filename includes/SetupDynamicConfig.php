<?php
/**
 * This is included by Setup.php to adjust the values of globals before services are initialized.
 * It's split into a separate file so it can be tested.
 */

use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\ForeignAPIRepo;
use MediaWiki\FileRepo\ForeignDBRepo;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Logging\LogFormatter;
use MediaWiki\Logging\NewUsersLogFormatter;
use MediaWiki\Logging\PageLangLogFormatter;
use MediaWiki\MainConfigSchema;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\AtEase\AtEase;

// For backwards compatibility, the value of wgLogos is copied to wgLogo.
// This is because some extensions/skins may be using $config->get('Logo')
// to access the value.
if ( $wgLogos !== false && isset( $wgLogos['1x'] ) ) {
	$wgLogo = $wgLogos['1x'];
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

// Rate limits should have the same name as the corresponding permission
if ( isset( $wgRateLimits['emailuser'] ) ) {
	// If the deprecated field is set, use it.
	// Note that we can't know whether the new field has been set explicitly, since it has a default value.
	$wgSettings->warning(
		'RateLimit: The "emailuser" limit is deprecated, use "sendemail" instead.'
	);
	$wgRateLimits['sendemail'] = $wgRateLimits['emailuser'];
}

// Rate limits should have the same name as the corresponding permission
if ( isset( $wgRateLimits['changetag'] ) ) {
	// If the deprecated field is set, use it.
	// Note that we can't know whether the new field has been set explicitly, since it has a default value.
	$wgSettings->warning(
		'RateLimit: The "changetag" limit is deprecated, use "changetags" instead.'
	);
	$wgRateLimits['changetags'] = $wgRateLimits['changetag'];
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
	&& is_array( $wgFooterIcons['poweredby']['mediawiki'] )
	&& $wgFooterIcons['poweredby']['mediawiki']['src'] === null
) {
	$compactLogo = "$wgResourceBasePath/resources/assets/mediawiki_compact.svg";
	$wgFooterIcons['poweredby']['mediawiki']['sources'] = [
		[
			"media" => "(min-width: 500px)",
			"srcset" => "$wgResourceBasePath/resources/assets/poweredby_mediawiki.svg",
			"width" => 88,
			"height" => 31,
		]
	];
	$wgFooterIcons['poweredby']['mediawiki']['src'] = $compactLogo;
	$wgFooterIcons['poweredby']['mediawiki']['width'] = 25;
	$wgFooterIcons['poweredby']['mediawiki']['height'] = 25;
}

// Unconditional protection for NS_MEDIAWIKI since otherwise it's too easy for a
// sysadmin to set $wgNamespaceProtection incorrectly and leave the wiki insecure.
//
// Note that this is the definition of editinterface and it can be granted to
// all users if desired.
$wgNamespaceProtection[NS_MEDIAWIKI] = 'editinterface';

// Initialise $wgLockManagers to include basic FS version
$wgLockManagers[] = [
	'name' => 'fsLockManager',
	'class' => FSLockManager::class,
	'lockDirectory' => "{$wgUploadDirectory}/lockdir",
];
$wgLockManagers[] = [
	'name' => 'nullLockManager',
	'class' => NullLockManager::class,
];

// Default parameters for the "<gallery>" tag.
// See \MediaWiki\MainConfigSchema::GalleryOptions
$wgGalleryOptions += [
	'imagesPerRow' => 0,
	'imageWidth' => 120,
	'imageHeight' => 120,
	'captionLength' => true,
	'showBytes' => true,
	'showDimensions' => true,
	'mode' => 'traditional',
];

if ( isset( $wgLocalFileRepo['name'] ) && !isset( $wgLocalFileRepo['backend'] ) ) {
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

$wgCookiePrefix = strtr( $wgCookiePrefix, '=,; +."\'\\[', '__________' );

if ( !$wgEnableEmail ) {
	// Disable all other email settings automatically if $wgEnableEmail
	// is set to false. - T65678
	$wgAllowHTMLEmail = false;
	$wgEmailAuthentication = false; // do not require auth if you're not sending email anyway
	$wgEnableUserEmail = false;
	$wgEnotifFromEditor = false;
	$wgEnotifMinorEdits = false;
	$wgEnotifRevealEditorAddress = false;
	$wgEnotifUseRealName = false;
	$wgEnotifUserTalk = false;
	$wgEnotifWatchlist = false;
	unset( $wgGroupPermissions['user']['sendemail'] );
	$wgUserEmailUseReplyTo = false;
	$wgUsersNotifiedOnAllChanges = [];
}

if ( !$wgLocaltimezone ) {
	// NOTE: The automatic dynamic default only kicks in if $wgLocaltimezone is null,
	//       but the installer writes $wgLocaltimezone into LocalSettings, and may
	//       produce (or may have produced historically) an empty string for some
	//       reason. To be compatible with existing LocalSettings.php files, we need
	//       to gracefully handle the case of $wgLocaltimezone being the empty string.
	//       See T305093#8063451.
	$wgLocaltimezone = MainConfigSchema::getDefaultLocaltimezone();
	$wgSettings->warning(
		'The Localtimezone setting must a valid timezone string or null. '
		. 'It must not be an empty string or false.'
	);
}

// The part after the System| is ignored, but rest of MW fills it out as the local offset.
$wgDefaultUserOptions['timecorrection'] = "System|$wgLocalTZoffset";

/**
 * Definitions of the NS_ constants are in Defines.php
 * @internal
 */
$wgCanonicalNamespaceNames = NamespaceInfo::CANONICAL_NAMES;

// Hard-deprecate setting $wgDummyLanguageCodes in LocalSettings.php
if ( count( $wgDummyLanguageCodes ) !== 0 ) {
	$wgSettings->warning(
		'Do not add to DummyLanguageCodes directly, ' .
		'add to ExtraLanguageCodes instead.'
	);
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
if ( $wgUseXssLanguage ) {
	$wgDummyLanguageCodes['x-xss'] = 'x-xss'; // Used for testing
}

// Temporary backwards-compatibility reading of old replica lag settings as of MediaWiki 1.36,
// to support sysadmins who fail to update their settings immediately:

if ( isset( $wgSlaveLagWarning ) ) {
	// If the old value is set to something other than the default, use it.
	if ( $wgDatabaseReplicaLagWarning === 10 && $wgSlaveLagWarning !== 10 ) {
		$wgDatabaseReplicaLagWarning = $wgSlaveLagWarning;
		$wgSettings->warning( 'SlaveLagWarning is no longer supported, ' .
			'use DatabaseReplicaLagWarning instead!' );
	}
} else {
	// Backwards-compatibility for extensions that read this value.
	$wgSlaveLagWarning = $wgDatabaseReplicaLagWarning;
}

if ( isset( $wgSlaveLagCritical ) ) {
	// If the old value is set to something other than the default, use it.
	if ( $wgDatabaseReplicaLagCritical === 30 && $wgSlaveLagCritical !== 30 ) {
		$wgDatabaseReplicaLagCritical = $wgSlaveLagCritical;
		$wgSettings->warning( 'SlaveLagCritical is no longer supported, ' .
			'use DatabaseReplicaLagCritical instead!' );
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
	$wgLogActionsHandlers['newusers/newusers'] = [
		'class' => NewUsersLogFormatter::class,
		'services' => [
			'NamespaceInfo',
		]
	];
	$wgLogActionsHandlers['newusers/create'] = [
		'class' => NewUsersLogFormatter::class,
		'services' => [
			'NamespaceInfo',
		]
	];
	$wgLogActionsHandlers['newusers/create2'] = [
		'class' => NewUsersLogFormatter::class,
		'services' => [
			'NamespaceInfo',
		]
	];
	$wgLogActionsHandlers['newusers/byemail'] = [
		'class' => NewUsersLogFormatter::class,
		'services' => [
			'NamespaceInfo',
		]
	];
	$wgLogActionsHandlers['newusers/autocreate'] = [
		'class' => NewUsersLogFormatter::class,
		'services' => [
			'NamespaceInfo',
		]
	];
}

if ( $wgPageCreationLog ) {
	// Add page creation log type
	$wgLogTypes[] = 'create';
	$wgLogActionsHandlers['create/create'] = LogFormatter::class;
}

if ( $wgPageLanguageUseDB ) {
	$wgLogTypes[] = 'pagelang';
	$wgLogActionsHandlers['pagelang/pagelang'] = [
		'class' => PageLangLogFormatter::class,
		'services' => [
			'LanguageNameUtils',
		]
	];
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

// Backwards compatibility with old bot passwords storage configs
if ( !$wgVirtualDomainsMapping ) {
	$wgVirtualDomainsMapping = [];
}
if ( $wgBotPasswordsCluster ) {
	$wgVirtualDomainsMapping['virtual-botpasswords']['cluster'] = $wgBotPasswordsCluster;
}

if ( $wgBotPasswordsDatabase ) {
	$wgVirtualDomainsMapping['virtual-botpasswords']['db'] = $wgBotPasswordsDatabase;
}
