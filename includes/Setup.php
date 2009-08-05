<?php
/**
 * Include most things that's need to customize the site
 */

/**
 * This file is not a valid entry point, perform no further processing unless
 * MEDIAWIKI is defined
 */
if( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is part of MediaWiki, it is not a valid entry point.\n";
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
if( $wgScript === false ) $wgScript = "$wgScriptPath/index$wgScriptExtension";
if( $wgRedirectScript === false ) $wgRedirectScript = "$wgScriptPath/redirect$wgScriptExtension";

if( $wgArticlePath === false ) {
	if( $wgUsePathInfo ) {
		$wgArticlePath      = "$wgScript/$1";
	} else {
		$wgArticlePath      = "$wgScript?title=$1";
	}
}

if( $wgStylePath === false ) $wgStylePath = "$wgScriptPath/skins";
if( $wgStyleDirectory === false) $wgStyleDirectory   = "$IP/skins";

if( $wgLogo === false ) $wgLogo = "$wgStylePath/common/images/wiki.png";

if( $wgUploadPath === false ) $wgUploadPath = "$wgScriptPath/images";
if( $wgUploadDirectory === false ) $wgUploadDirectory = "$IP/images";

if( $wgMathPath === false ) $wgMathPath = "{$wgUploadPath}/math";
if( $wgMathDirectory === false ) $wgMathDirectory = "{$wgUploadDirectory}/math";
if( $wgTmpDirectory === false ) $wgTmpDirectory = "{$wgUploadDirectory}/tmp";

if( $wgReadOnlyFile === false ) $wgReadOnlyFile = "{$wgUploadDirectory}/lock_yBgMBwiR";
if( $wgFileCacheDirectory === false ) $wgFileCacheDirectory = "{$wgUploadDirectory}/cache";

if ( empty( $wgFileStore['deleted']['directory'] ) ) {
	$wgFileStore['deleted']['directory'] = "{$wgUploadDirectory}/deleted";
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
	$wgLocalFileRepo = array(
		'class' => 'LocalRepo',
		'name' => 'local',
		'directory' => $wgUploadDirectory,
		'url' => $wgUploadBaseUrl ? $wgUploadBaseUrl . $wgUploadPath : $wgUploadPath,
		'hashLevels' => $wgHashedUploadDirectory ? 2 : 0,
		'thumbScriptUrl' => $wgThumbnailScriptPath,
		'transformVia404' => !$wgGenerateThumbnailOnParse,
		'initialCapital' => $wgCapitalLinks,
		'deletedDir' => $wgFileStore['deleted']['directory'],
		'deletedHashLevels' => $wgFileStore['deleted']['hash']
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
			'dbFlags' => ($wgDebugDumpSql ? DBO_DEBUG : 0) | DBO_DEFAULT,
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
if ( !class_exists( 'AutoLoader' ) ) {
	require_once( "$IP/includes/AutoLoader.php" );
}

wfProfileIn( $fname.'-exception' );
require_once( "$IP/includes/Exception.php" );
wfInstallExceptionHandler();
wfProfileOut( $fname.'-exception' );

wfProfileIn( $fname.'-includes' );
require_once( "$IP/includes/GlobalFunctions.php" );
require_once( "$IP/includes/Hooks.php" );
require_once( "$IP/includes/Namespace.php" );
require_once( "$IP/includes/ProxyTools.php" );
require_once( "$IP/includes/ObjectCache.php" );
require_once( "$IP/includes/ImageFunctions.php" );
require_once( "$IP/includes/StubObject.php" );
wfProfileOut( $fname.'-includes' );
wfProfileIn( $fname.'-misc1' );
# Raise the memory limit if it's too low
global $wgMemoryLimit;
$memlimit = ini_get( "memory_limit" );
if( !( empty( $memlimit ) || $memlimit == -1 ) ) {
	if( wfParseMemoryLimit( $memlimit ) < wfParseMemoryLimit( $wgMemoryLimit ) ) {
		wfDebug( "\n\nRaise PHP's memory limit from $memlimit to $wgMemoryLimit\n" );
		wfDisableWarnings();
		ini_set( "memory_limit", $wgMemoryLimit );
		wfEnableWarnings();
	}
}

$wgIP = false; # Load on demand
# Can't stub this one, it sets up $_GET and $_REQUEST in its constructor
$wgRequest = new WebRequest;

# Useful debug output
if ( $wgCommandLineMode ) {
	wfDebug( "\n\nStart command line script $self\n" );
} elseif ( function_exists( 'getallheaders' ) ) {
	wfDebug( "\n\nStart request\n" );
	wfDebug( $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . "\n" );
	$headers = getallheaders();
	foreach ($headers as $name => $value) {
		wfDebug( "$name: $value\n" );
	}
	wfDebug( "\n" );
} elseif( isset( $_SERVER['REQUEST_URI'] ) ) {
	wfDebug( $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . "\n" );
}

if( $wgRCFilterByAge ) {
	## Trim down $wgRCLinkDays so that it only lists links which are valid
	## as determined by $wgRCMaxAge.
	## Note that we allow 1 link higher than the max for things like 56 days but a 60 day link.
	sort($wgRCLinkDays);
	for( $i = 0; $i < count($wgRCLinkDays); $i++ ) {
		if( $wgRCLinkDays[$i] >= $wgRCMaxAge / ( 3600 * 24 ) ) {
			$wgRCLinkDays = array_slice( $wgRCLinkDays, 0, $i+1, false );
			break;
		}
	}
}

if ( $wgSkipSkin ) {
	$wgSkipSkins[] = $wgSkipSkin;
}

$wgUseEnotif = $wgEnotifUserTalk || $wgEnotifWatchlist;

if($wgMetaNamespace === FALSE) {
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );
}

# These are now the same, always
# To determine the user language, use $wgLang->getCode()
$wgContLanguageCode = $wgLanguageCode;

# Easy to forget to falsify $wgShowIPinHeader for static caches.
# If file cache or squid cache is on, just disable this (DWIMD).
if( $wgUseFileCache || $wgUseSquid ) $wgShowIPinHeader = false;

# $wgAllowRealName and $wgAllowUserSkin were removed in 1.16
# in favor of $wgHiddenPrefs, handle b/c here
if( !$wgAllowRealName ) {
	$wgHiddenPrefs[] = 'realname';
}

if( !$wgAllowUserSkin ) {
	$wgHiddenPrefs[] = 'skin';
}

wfProfileOut( $fname.'-misc1' );
wfProfileIn( $fname.'-memcached' );

$wgMemc =& wfGetMainCache();
$messageMemc =& wfGetMessageCacheStorage();
$parserMemc =& wfGetParserCacheStorage();

wfDebug( 'Main cache: ' . get_class( $wgMemc ) .
	"\nMessage cache: " . get_class( $messageMemc ) .
	"\nParser cache: " . get_class( $parserMemc ) . "\n" );

wfProfileOut( $fname.'-memcached' );

## Most of the config is out, some might want to run hooks here.
wfRunHooks( 'SetupAfterCache' );

wfProfileIn( $fname.'-SetupSession' );

# Set default shared prefix
if( $wgSharedPrefix === false ) $wgSharedPrefix = $wgDBprefix;

if( !$wgCookiePrefix ) {
	if ( $wgSharedDB && $wgSharedPrefix && in_array('user',$wgSharedTables) ) {
		$wgCookiePrefix = $wgSharedDB . '_' . $wgSharedPrefix;
	} elseif ( $wgSharedDB && in_array('user',$wgSharedTables) ) {
		$wgCookiePrefix = $wgSharedDB;
	} elseif ( $wgDBprefix ) {
		$wgCookiePrefix = $wgDBname . '_' . $wgDBprefix;
	} else {
		$wgCookiePrefix = $wgDBname;
	}
}
$wgCookiePrefix = strtr($wgCookiePrefix, "=,; +.\"'\\[", "__________");

# If session.auto_start is there, we can't touch session name
#
if( !wfIniGetBool( 'session.auto_start' ) )
	session_name( $wgSessionName ? $wgSessionName : $wgCookiePrefix . '_session' );

if( !$wgCommandLineMode && ( $wgRequest->checkSessionCookie() || isset( $_COOKIE[$wgCookiePrefix.'Token'] ) ) ) {
	wfIncrStats( 'request_with_session' );
	wfSetupSession();
	$wgSessionStarted = true;
} else {
	wfIncrStats( 'request_without_session' );
	$wgSessionStarted = false;
}

wfProfileOut( $fname.'-SetupSession' );
wfProfileIn( $fname.'-globals' );

$wgContLang = new StubContLang;

// Now that variant lists may be available...
$wgRequest->interpolateTitle();

$wgUser = new StubUser;
$wgLang = new StubUserLang;
$wgVariant = new StubUserVariant;
$wgOut = new StubObject( 'wgOut', 'OutputPage' );
$wgParser = new StubObject( 'wgParser', $wgParserConf['class'], array( $wgParserConf ) );

$wgMessageCache = new StubObject( 'wgMessageCache', 'MessageCache',
	array( $messageMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry, wfWikiID() ) );

wfProfileOut( $fname.'-globals' );
wfProfileIn( $fname.'-User' );

# Skin setup functions
# Entries can be added to this variable during the inclusion
# of the extension file. Skins can then perform any necessary initialisation.
#
foreach ( $wgSkinExtensionFunctions as $func ) {
	call_user_func( $func );
}

if( !is_object( $wgAuth ) ) {
	$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
	wfRunHooks( 'AuthPluginSetup', array( &$wgAuth ) );
}

wfProfileOut( $fname.'-User' );

wfProfileIn( $fname.'-misc2' );

$wgDeferredUpdateList = array();
$wgPostCommitUpdateList = array();

if ( $wgAjaxWatch ) $wgAjaxExportList[] = 'wfAjaxWatch';
if ( $wgAjaxUploadDestCheck ) $wgAjaxExportList[] = 'UploadForm::ajaxGetExistsWarning';
if( $wgAjaxLicensePreview )
	$wgAjaxExportList[] = 'UploadForm::ajaxGetLicensePreview';

# Placeholders in case of DB error
$wgTitle = null;
$wgArticle = null;

wfProfileOut( $fname.'-misc2' );
wfProfileIn( $fname.'-extensions' );

/*
 * load the $wgExtensionMessagesFiles for the script loader
 * this can't be done in a normal extension type way
 * since the script-loader is an entry point
 */
if( $wgEnableScriptLoader && strpos( wfGetScriptUrl(), "mwScriptLoader.php" ) !== false ){
	$wgExtensionMessagesFiles['mwEmbed'] = "{$IP}/js2/mwEmbed/php/languages/mwEmbed.i18n.php";
}


# Extension setup functions for extensions other than skins
# Entries should be added to this variable during the inclusion
# of the extension file. This allows the extension to perform
# any necessary initialisation in the fully initialised environment
foreach ( $wgExtensionFunctions as $func ) {
	# Allow closures in PHP 5.3+
	if ( is_object( $func ) && $func instanceof Closure ) {
		$profName = $fname.'-extensions-closure';
	} elseif( is_array( $func ) ) {
		if ( is_object( $func[0] ) )
			$profName = $fname.'-extensions-'.get_class( $func[0] ).'::'.$func[1];
		else
			$profName = $fname.'-extensions-'.implode( '::', $func );
	} else {
		$profName = $fname.'-extensions-'.strval( $func );
	}

	wfProfileIn( $profName );
	call_user_func( $func );
	wfProfileOut( $profName );
}

// For compatibility
wfRunHooks( 'LogPageValidTypes', array( &$wgLogTypes ) );
wfRunHooks( 'LogPageLogName', array( &$wgLogNames ) );
wfRunHooks( 'LogPageLogHeader', array( &$wgLogHeaders ) );
wfRunHooks( 'LogPageActionText', array( &$wgLogActions ) );

if( !empty($wgNewUserLog) ) {
	# Add a new log type
	$wgLogTypes[]                        = 'newusers';
	$wgLogNames['newusers']              = 'newuserlogpage';
	$wgLogHeaders['newusers']            = 'newuserlogpagetext';
	$wgLogActions['newusers/newusers']   = 'newuserlogentry'; // For compatibility with older log entries
	$wgLogActions['newusers/create']     = 'newuserlog-create-entry';
	$wgLogActions['newusers/create2']    = 'newuserlog-create2-entry';
	$wgLogActions['newusers/autocreate'] = 'newuserlog-autocreate-entry';
}

wfDebug( "Fully initialised\n" );
$wgFullyInitialised = true;
wfProfileOut( $fname.'-extensions' );
wfProfileOut( $fname );
