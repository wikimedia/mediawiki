<?php
/**
 * Include most things that's need to customize the site
 * @package MediaWiki
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
if( $wgScript === false ) $wgScript = "$wgScriptPath/index.php";
if( $wgRedirectScript === false ) $wgRedirectScript = "$wgScriptPath/redirect.php";

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

require_once( "$IP/includes/AutoLoader.php" );

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


$wgIP = false; # Load on demand
# Can't stub this one, it sets up $_GET and $_REQUEST in its constructor
$wgRequest = new WebRequest;
if ( function_exists( 'posix_uname' ) ) {
	$wguname = posix_uname();
	$wgNodeName = $wguname['nodename'];
} else {
	$wgNodeName = '';
}

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

wfProfileOut( $fname.'-misc1' );
wfProfileIn( $fname.'-memcached' );

$wgMemc =& wfGetMainCache();
$messageMemc =& wfGetMessageCacheStorage();
$parserMemc =& wfGetParserCacheStorage();

wfDebug( 'Main cache: ' . get_class( $wgMemc ) .
       "\nMessage cache: " . get_class( $messageMemc ) .
	   "\nParser cache: " . get_class( $parserMemc ) . "\n" );

wfProfileOut( $fname.'-memcached' );
wfProfileIn( $fname.'-SetupSession' );

if ( $wgDBprefix ) {
	$wgCookiePrefix = $wgDBname . '_' . $wgDBprefix;
} elseif ( $wgSharedDB ) {
	$wgCookiePrefix = $wgSharedDB;
} else {
	$wgCookiePrefix = $wgDBname;
}

# If session.auto_start is there, we can't touch session name
#
if( !ini_get( 'session.auto_start' ) )
	session_name( $wgSessionName ? $wgSessionName : $wgCookiePrefix . '_session' );

if( !$wgCommandLineMode && ( isset( $_COOKIE[session_name()] ) || isset( $_COOKIE[$wgCookiePrefix.'Token'] ) ) ) {
	wfIncrStats( 'request_with_session' );
	wfSetupSession();
	$wgSessionStarted = true;
} else {
	wfIncrStats( 'request_without_session' );
	$wgSessionStarted = false;
}

wfProfileOut( $fname.'-SetupSession' );
wfProfileIn( $fname.'-globals' );

if ( !$wgDBservers ) {
	$wgDBservers = array(array(
		'host' => $wgDBserver,
		'user' => $wgDBuser,
		'password' => $wgDBpassword,
		'dbname' => $wgDBname,
		'type' => $wgDBtype,
		'load' => 1,
		'flags' => ($wgDebugDumpSql ? DBO_DEBUG : 0) | DBO_DEFAULT
	));
}

$wgLoadBalancer = new StubObject( 'wgLoadBalancer', 'LoadBalancer', 
	array( $wgDBservers, false, $wgMasterWaitTimeout, true ) );
$wgContLang = new StubContLang;
$wgUser = new StubUser;
$wgLang = new StubUserLang;
$wgOut = new StubObject( 'wgOut', 'OutputPage' );
$wgParser = new StubObject( 'wgParser', 'Parser' );
$wgMessageCache = new StubObject( 'wgMessageCache', 'MessageCache', 
	array( $parserMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry, wfWikiID() ) );

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

if ( $wgAjaxSearch ) $wgAjaxExportList[] = 'wfSajaxSearch';
if ( $wgAjaxWatch ) $wgAjaxExportList[] = 'wfAjaxWatch';

wfSeedRandom();

# Placeholders in case of DB error
$wgTitle = null;
$wgArticle = null;

wfProfileOut( $fname.'-misc2' );
wfProfileIn( $fname.'-extensions' );

# Extension setup functions for extensions other than skins
# Entries should be added to this variable during the inclusion
# of the extension file. This allows the extension to perform
# any necessary initialisation in the fully initialised environment
foreach ( $wgExtensionFunctions as $func ) {
	$profName = $fname.'-extensions-'.strval( $func );
	wfProfileIn( $profName );
	call_user_func( $func );
	wfProfileOut( $profName );
}

// For compatibility
wfRunHooks( 'LogPageValidTypes', array( &$wgLogTypes ) );
wfRunHooks( 'LogPageLogName', array( &$wgLogNames ) );
wfRunHooks( 'LogPageLogHeader', array( &$wgLogHeaders ) );
wfRunHooks( 'LogPageActionText', array( &$wgLogActions ) );


wfDebug( "Fully initialised\n" );
$wgFullyInitialised = true;
wfProfileOut( $fname.'-extensions' );
wfProfileOut( $fname );

?>
