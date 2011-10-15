<?php
/**
 * Include most things that's need to customize the site
 *
 * @file
 */

/**
 * This file is not a valid entry point, perform no further processing unless
 * MEDIAWIKI is defined
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

# The main wiki script and things like database
# conversion and maintenance scripts all share a
# common setup of including lots of classes and
# setting up a few globals.
#

$fname = 'Setup.php';
wfProfileIn( $fname );

// Check to see if we are at the file scope
if ( !isset( $wgVersion ) ) {
	echo "Error, Setup.php must be included from the file scope, after DefaultSettings.php\n";
	die( 1 );
}

// Set various default paths sensibly...
if ( $wgScript === false ) $wgScript = "$wgScriptPath/index$wgScriptExtension";
if ( $wgRedirectScript === false ) $wgRedirectScript = "$wgScriptPath/redirect$wgScriptExtension";
if ( $wgLoadScript === false ) $wgLoadScript = "$wgScriptPath/load$wgScriptExtension";

if ( $wgArticlePath === false ) {
	if ( $wgUsePathInfo ) {
		$wgArticlePath      = "$wgScript/$1";
	} else {
		$wgArticlePath      = "$wgScript?title=$1";
	}
}

if ( !empty($wgActionPaths) && !isset($wgActionPaths['view']) ) {
	# 'view' is assumed the default action path everywhere in the code
	# but is rarely filled in $wgActionPaths
	$wgActionPaths['view'] = $wgArticlePath;
}

if ( !empty($wgActionPaths) && !isset($wgActionPaths['view']) ) {
	# 'view' is assumed the default action path everywhere in the code
	# but is rarely filled in $wgActionPaths 
	$wgActionPaths['view'] = $wgArticlePath ;
}

if ( $wgStylePath === false ) $wgStylePath = "$wgScriptPath/skins";
if ( $wgLocalStylePath === false ) $wgLocalStylePath = "$wgScriptPath/skins";
if ( $wgStyleDirectory === false ) $wgStyleDirectory   = "$IP/skins";
if ( $wgExtensionAssetsPath === false ) $wgExtensionAssetsPath = "$wgScriptPath/extensions";

if ( $wgLogo === false ) $wgLogo = "$wgStylePath/common/images/wiki.png";

if ( $wgUploadPath === false ) $wgUploadPath = "$wgScriptPath/images";
if ( $wgUploadDirectory === false ) $wgUploadDirectory = "$IP/images";

if ( $wgTmpDirectory === false ) $wgTmpDirectory = "{$wgUploadDirectory}/tmp";

if ( $wgReadOnlyFile === false ) $wgReadOnlyFile = "{$wgUploadDirectory}/lock_yBgMBwiR";
if ( $wgFileCacheDirectory === false ) $wgFileCacheDirectory = "{$wgUploadDirectory}/cache";
if ( $wgDeletedDirectory === false ) $wgDeletedDirectory = "{$wgUploadDirectory}/deleted";

if ( isset( $wgFileStore['deleted']['directory'] ) ) {
	$wgDeletedDirectory = $wgFileStore['deleted']['directory'];
}

if ( isset( $wgFooterIcons['copyright'] ) &&
	isset( $wgFooterIcons['copyright']['copyright'] ) &&
	$wgFooterIcons['copyright']['copyright'] === array() )
{
	if ( isset( $wgCopyrightIcon ) && $wgCopyrightIcon ) {
		$wgFooterIcons['copyright']['copyright'] = $wgCopyrightIcon;
	} elseif ( $wgRightsIcon || $wgRightsText ) {
		$wgFooterIcons['copyright']['copyright'] = array(
			'url' => $wgRightsUrl,
			'src' => $wgRightsIcon,
			'alt' => $wgRightsText,
		);
	} else {
		unset( $wgFooterIcons['copyright']['copyright'] );
	}
}

if ( isset( $wgFooterIcons['poweredby'] ) &&
	isset( $wgFooterIcons['poweredby']['mediawiki'] ) &&
	$wgFooterIcons['poweredby']['mediawiki']['src'] === null )
{
	$wgFooterIcons['poweredby']['mediawiki']['src'] = "$wgStylePath/common/images/poweredby_mediawiki_88x31.png";
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
 * Initialise $wgLocalFileRepo from backwards-compatible settings
 */
if ( !$wgLocalFileRepo ) {
	if ( isset( $wgFileStore['deleted']['hash'] ) ) {
		$deletedHashLevel = $wgFileStore['deleted']['hash'];
	} else {
		$deletedHashLevel = $wgHashedUploadDirectory ? 3 : 0;
	}
	$wgLocalFileRepo = array(
		'class' => 'LocalRepo',
		'name' => 'local',
		'directory' => $wgUploadDirectory,
		'scriptDirUrl' => $wgScriptPath,
		'scriptExtension' => $wgScriptExtension,
		'url' => $wgUploadBaseUrl ? $wgUploadBaseUrl . $wgUploadPath : $wgUploadPath,
		'hashLevels' => $wgHashedUploadDirectory ? 2 : 0,
		'thumbScriptUrl' => $wgThumbnailScriptPath,
		'transformVia404' => !$wgGenerateThumbnailOnParse,
		'deletedDir' => $wgDeletedDirectory,
		'deletedHashLevels' => $deletedHashLevel
	);
}
/**
 * Initialise shared repo from backwards-compatible settings
 */
if ( $wgUseSharedUploads ) {
	if ( $wgSharedUploadDBname ) {
		$wgForeignFileRepos[] = array(
			'class' => 'ForeignDBRepo',
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
		);
	} else {
		$wgForeignFileRepos[] = array(
			'class' => 'FSRepo',
			'name' => 'shared',
			'directory' => $wgSharedUploadDirectory,
			'url' => $wgSharedUploadPath,
			'hashLevels' => $wgHashedSharedUploadDirectory ? 2 : 0,
			'thumbScriptUrl' => $wgSharedThumbnailScriptPath,
			'transformVia404' => !$wgGenerateThumbnailOnParse,
			'descBaseUrl' => $wgRepositoryBaseUrl,
			'fetchDescription' => $wgFetchCommonsDescriptions,
		);
	}
}
if ( $wgUseInstantCommons ) {
	$wgForeignFileRepos[] = array(
		'class'                   => 'ForeignAPIRepo',
		'name'                    => 'wikimediacommons',
		'apibase'                 => 'http://commons.wikimedia.org/w/api.php',
		'hashLevels'              => 2,
		'fetchDescription'        => true,
		'descriptionCacheExpiry'  => 43200,
		'apiThumbCacheExpiry'     => 86400,
	);
}

if ( is_null( $wgEnableAutoRotation ) ) {
	// Only enable auto-rotation when the bitmap handler can rotate
	$wgEnableAutoRotation = BitmapHandler::canRotate();
}

if ( $wgRCFilterByAge ) {
	# # Trim down $wgRCLinkDays so that it only lists links which are valid
	# # as determined by $wgRCMaxAge.
	# # Note that we allow 1 link higher than the max for things like 56 days but a 60 day link.
	sort( $wgRCLinkDays );
	for ( $i = 0; $i < count( $wgRCLinkDays ); $i++ ) {
		if ( $wgRCLinkDays[$i] >= $wgRCMaxAge / ( 3600 * 24 ) ) {
			$wgRCLinkDays = array_slice( $wgRCLinkDays, 0, $i + 1, false );
			break;
		}
	}
}

if ( $wgSkipSkin ) {
	$wgSkipSkins[] = $wgSkipSkin;
}

# Set default shared prefix
if ( $wgSharedPrefix === false ) {
	$wgSharedPrefix = $wgDBprefix;
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

$wgUseEnotif = $wgEnotifUserTalk || $wgEnotifWatchlist;

if ( $wgMetaNamespace === false ) {
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );
}

/**
 * Definitions of the NS_ constants are in Defines.php
 * @private
 */
$wgCanonicalNamespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	NS_PROJECT          => 'Project',
	NS_PROJECT_TALK     => 'Project_talk',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'File_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk',
);

/// @todo UGLY UGLY
if( is_array( $wgExtraNamespaces ) ) {
	$wgCanonicalNamespaceNames = $wgCanonicalNamespaceNames + $wgExtraNamespaces;
}

# These are now the same, always
# To determine the user language, use $wgLang->getCode()
$wgContLanguageCode = $wgLanguageCode;

# Easy to forget to falsify $wgShowIPinHeader for static caches.
# If file cache or squid cache is on, just disable this (DWIMD).
if ( $wgUseFileCache || $wgUseSquid ) {
	$wgShowIPinHeader = false;
}

# $wgAllowRealName and $wgAllowUserSkin were removed in 1.16
# in favor of $wgHiddenPrefs, handle b/c here
if ( !$wgAllowRealName ) {
	$wgHiddenPrefs[] = 'realname';
}

# Doesn't make sense to have if disabled.
if ( !$wgEnotifMinorEdits ) {
	$wgHiddenPrefs[] = 'enotifminoredits';
}

# $wgDisabledActions is deprecated as of 1.18
foreach( $wgDisabledActions as $action ){
	$wgActions[$action] = false;
}
if( !$wgAllowPageInfo ){
	$wgActions['info'] = false;
}

if ( !$wgHtml5Version && $wgHtml5 && $wgAllowRdfaAttributes ) {
	# see http://www.w3.org/TR/rdfa-in-html/#document-conformance
	if ( $wgMimeType == 'application/xhtml+xml' ) {
		$wgHtml5Version = 'XHTML+RDFa 1.0';
	} else {
		$wgHtml5Version = 'HTML+RDFa 1.0';
	}
}

# Blacklisted file extensions shouldn't appear on the "allowed" list
$wgFileExtensions = array_diff ( $wgFileExtensions, $wgFileBlacklist );

if ( $wgArticleCountMethod === null ) {
	$wgArticleCountMethod = $wgUseCommaCount ? 'comma' : 'link';
}

if ( $wgInvalidateCacheOnLocalSettingsChange ) {
	$wgCacheEpoch = max( $wgCacheEpoch, gmdate( 'YmdHis', @filemtime( "$IP/LocalSettings.php" ) ) );
}

if ( $wgAjaxUploadDestCheck ) {
	$wgAjaxExportList[] = 'SpecialUpload::ajaxGetExistsWarning';
}

if ( $wgNewUserLog ) {
	# Add a new log type
	$wgLogTypes[]                        = 'newusers';
	$wgLogNames['newusers']              = 'newuserlogpage';
	$wgLogHeaders['newusers']            = 'newuserlogpagetext';
	# newusers, create, create2, autocreate
	$wgLogActionsHandlers['newusers/*']  = 'NewUsersLogFormatter';
}

if ( $wgCookieSecure === 'detect' ) {
	$wgCookieSecure = ( substr( $wgServer, 0, 6 ) === 'https:' );
}

if ( !defined( 'MW_COMPILED' ) ) {
	if ( !MWInit::classExists( 'AutoLoader' ) ) {
		require_once( "$IP/includes/AutoLoader.php" );
	}

	wfProfileIn( $fname . '-exception' );
	MWExceptionHandler::installHandler();
	wfProfileOut( $fname . '-exception' );

	wfProfileIn( $fname . '-includes' );
	require_once( "$IP/includes/normal/UtfNormalUtil.php" );
	require_once( "$IP/includes/GlobalFunctions.php" );
	require_once( "$IP/includes/ProxyTools.php" );
	require_once( "$IP/includes/ImageFunctions.php" );
	require_once( "$IP/includes/normal/UtfNormalDefines.php" );
	wfProfileOut( $fname . '-includes' );
}

# Now that GlobalFunctions is loaded, set the default for $wgCanonicalServer
if ( $wgCanonicalServer === false ) {
	$wgCanonicalServer = wfExpandUrl( $wgServer, PROTO_HTTP );
}

wfProfileIn( $fname . '-misc1' );

# Raise the memory limit if it's too low
wfMemoryLimit();

/**
 * Set up the timezone, suppressing the pseudo-security warning in PHP 5.1+
 * that happens whenever you use a date function without the timezone being
 * explicitly set. Inspired by phpMyAdmin's treatment of the problem.
 */
if ( is_null( $wgLocaltimezone) ) {
	wfSuppressWarnings();
	$wgLocaltimezone = date_default_timezone_get();
	wfRestoreWarnings();
}

date_default_timezone_set( $wgLocaltimezone );
if( is_null( $wgLocalTZoffset ) ) {
	$wgLocalTZoffset = date( 'Z' ) / 60;
}

# Useful debug output
global $wgCommandLineMode;
if ( $wgCommandLineMode ) {
	$wgRequest = new FauxRequest( array() );

	wfDebug( "\n\nStart command line script $self\n" );
} else {
	# Can't stub this one, it sets up $_GET and $_REQUEST in its constructor
	$wgRequest = new WebRequest;

	$debug = "Start request\n\n{$_SERVER['REQUEST_METHOD']} {$wgRequest->getRequestURL()}";

	if ( $wgDebugPrintHttpHeaders ) {
		$debug .= "\nHTTP HEADERS:\n";

		foreach ( $wgRequest->getAllHeaders() as $name => $value ) {
			$debug .= "$name: $value\n";
		}
	}
	wfDebug( "$debug\n" );
}

wfProfileOut( $fname . '-misc1' );
wfProfileIn( $fname . '-memcached' );

$wgMemc = wfGetMainCache();
$messageMemc = wfGetMessageCacheStorage();
$parserMemc = wfGetParserCacheStorage();

wfDebug( 'CACHES: ' . get_class( $wgMemc ) . '[main] ' .
	get_class( $messageMemc ) . '[message] ' .
	get_class( $parserMemc ) . "[parser]\n" );

wfProfileOut( $fname . '-memcached' );

# # Most of the config is out, some might want to run hooks here.
wfRunHooks( 'SetupAfterCache' );

wfProfileIn( $fname . '-session' );

# If session.auto_start is there, we can't touch session name
if ( !wfIniGetBool( 'session.auto_start' ) ) {
	session_name( $wgSessionName ? $wgSessionName : $wgCookiePrefix . '_session' );
}

if ( !defined( 'MW_NO_SESSION' ) && !$wgCommandLineMode ) {
	if ( $wgRequest->checkSessionCookie() || isset( $_COOKIE[$wgCookiePrefix . 'Token'] ) ) {
		wfIncrStats( 'request_with_session' );
		wfSetupSession();
		$wgSessionStarted = true;
	} else {
		wfIncrStats( 'request_without_session' );
		$wgSessionStarted = false;
	}
}

wfProfileOut( $fname . '-session' );
wfProfileIn( $fname . '-globals' );

$wgContLang = Language::factory( $wgLanguageCode );
$wgContLang->initEncoding();
$wgContLang->initContLang();

// Now that variant lists may be available...
$wgRequest->interpolateTitle();
$wgUser = RequestContext::getMain()->getUser(); # BackCompat

/**
 * @var Language
 */
$wgLang = new StubUserLang;

/**
 * @var OutputPage
 */
$wgOut = RequestContext::getMain()->getOutput(); # BackCompat

/**
 * @var Parser
 */
$wgParser = new StubObject( 'wgParser', $wgParserConf['class'], array( $wgParserConf ) );

if ( !is_object( $wgAuth ) ) {
	$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
	wfRunHooks( 'AuthPluginSetup', array( &$wgAuth ) );
}

# Placeholders in case of DB error
$wgTitle = null;

$wgDeferredUpdateList = array();

// We need to check for safe_mode, because mail() will throw an E_NOTICE
// on additional parameters
if( !is_null($wgAdditionalMailParams) && wfIniGetBool('safe_mode') ) {
	$wgAdditionalMailParams = null;
}

wfProfileOut( $fname . '-globals' );
wfProfileIn( $fname . '-extensions' );

# Extension setup functions for extensions other than skins
# Entries should be added to this variable during the inclusion
# of the extension file. This allows the extension to perform
# any necessary initialisation in the fully initialised environment
foreach ( $wgExtensionFunctions as $func ) {
	# Allow closures in PHP 5.3+
	if ( is_object( $func ) && $func instanceof Closure ) {
		$profName = $fname . '-extensions-closure';
	} elseif ( is_array( $func ) ) {
		if ( is_object( $func[0] ) )
			$profName = $fname . '-extensions-' . get_class( $func[0] ) . '::' . $func[1];
		else
			$profName = $fname . '-extensions-' . implode( '::', $func );
	} else {
		$profName = $fname . '-extensions-' . strval( $func );
	}

	wfProfileIn( $profName );
	call_user_func( $func );
	wfProfileOut( $profName );
}

wfDebug( "Fully initialised\n" );
$wgFullyInitialised = true;

wfProfileOut( $fname . '-extensions' );
wfProfileOut( $fname );
