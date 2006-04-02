<?php
/**
 * Include most things that's need to customize the site
 * @package MediaWiki
 */

/**
 * This file is not a valid entry point, perform no further processing unless
 * MEDIAWIKI is defined
 */
if( defined( 'MEDIAWIKI' ) ) {

# The main wiki script and things like database
# conversion and maintenance scripts all share a
# common setup of including lots of classes and
# setting up a few globals.
#

// Check to see if we are at the file scope
if ( !isset( $wgVersion ) ) {
	echo "Error, Setup.php must be included from the file scope, after DefaultSettings.php\n";
	die( -1 );
}

if( !isset( $wgProfiling ) )
	$wgProfiling = false;

if ( function_exists( 'wfProfileIn' ) ) {
	/* nada, everything should be done already */
} elseif ( $wgProfiling and (0 == rand() % $wgProfileSampleRate ) ) {
	require_once( 'Profiling.php' );
	$wgProfiling = true;
	if ($wgProfilerType == "") {
		$wgProfiler = new Profiler();
	} else {
		$prclass="Profiler{$wgProfilerType}";
		require_once( $prclass.".php" );
		$wgProfiler = new $prclass();
	}
} else {
	require_once( 'ProfilerStub.php' );
}

$fname = 'Setup.php';
wfProfileIn( $fname );
wfProfileIn( $fname.'-includes' );

require_once( 'GlobalFunctions.php' );
require_once( 'Hooks.php' );
require_once( 'Namespace.php' );
require_once( 'User.php' );
require_once( 'Skin.php' );
require_once( 'OutputPage.php' );
require_once( 'LinkCache.php' );
require_once( 'LinkBatch.php' );
require_once( 'Title.php' );
require_once( 'Article.php' );
require_once( 'MagicWord.php' );
require_once( 'Block.php' );
require_once( 'MessageCache.php' );
require_once( 'Parser.php' );
require_once( 'ParserCache.php' );
require_once( 'WebRequest.php' );
require_once( 'LoadBalancer.php' );
require_once( 'HistoryBlob.php' );
require_once( 'ProxyTools.php' );
require_once( 'ObjectCache.php' );
require_once( 'WikiError.php' );
require_once( 'SpecialPage.php' );

if ( $wgUseDynamicDates ) {
	require_once( 'DateFormatter.php' );
}

wfProfileOut( $fname.'-includes' );
wfProfileIn( $fname.'-misc1' );

$wgIP = false; # Load on demand
$wgRequest = new WebRequest();
if ( function_exists( 'posix_uname' ) ) {
	$wguname = posix_uname();
	$wgNodeName = $wguname['nodename'];
} else {
	$wgNodeName = '';
}

# Useful debug output
if ( $wgCommandLineMode ) {
	# wfDebug( '"' . implode( '"  "', $argv ) . '"' );
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
if (!ini_get('session.auto_start')) {
	session_name( $wgCookiePrefix . '_session' );
}

if( !$wgCommandLineMode && ( isset( $_COOKIE[session_name()] ) || isset( $_COOKIE[$wgCookiePrefix.'Token'] ) ) ) {
	wfIncrStats( 'request_with_session' );
	User::SetupSession();
	$wgSessionStarted = true;
} else {
	wfIncrStats( 'request_without_session' );
	$wgSessionStarted = false;
}

wfProfileOut( $fname.'-SetupSession' );
wfProfileIn( $fname.'-database' );

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
$wgLoadBalancer = LoadBalancer::newFromParams( $wgDBservers, false, $wgMasterWaitTimeout );
$wgLoadBalancer->loadMasterPos();

wfProfileOut( $fname.'-database' );
wfProfileIn( $fname.'-language1' );

require_once( "$IP/languages/Language.php" );

function setupLangObj($langclass) {
	global $IP;

	if( ! class_exists( $langclass ) ) {
		# Default to English/UTF-8
		$baseclass = 'LanguageUtf8';
		require_once( "$IP/languages/$baseclass.php" );
		$lc = strtolower(substr($langclass, 8));
		$snip = "
			class $langclass extends $baseclass {
				function getVariants() {
					return array(\"$lc\");
				}

			}";
		eval($snip);
	}

	$lang = new $langclass();

	return $lang;
}

# $wgLanguageCode may be changed later to fit with user preference.
# The content language will remain fixed as per the configuration,
# so let's keep it.
$wgContLanguageCode = $wgLanguageCode;
$wgContLangClass = 'Language' . str_replace( '-', '_', ucfirst( $wgContLanguageCode ) );

$wgContLang = setupLangObj( $wgContLangClass );
$wgContLang->initEncoding();

wfProfileOut( $fname.'-language1' );
wfProfileIn( $fname.'-User' );

# Skin setup functions
# Entries can be added to this variable during the inclusion
# of the extension file. Skins can then perform any necessary initialisation.
# 
# require_once is slow even on the second call, so this needs to be outside the loop
if ( count( $wgSkinExtensionFunctions ) ) {
	require_once( 'PersistentObject.php' );
}
foreach ( $wgSkinExtensionFunctions as $func ) {
	call_user_func( $func );
}

if( !is_object( $wgAuth ) ) {
	require_once( 'AuthPlugin.php' );
	$wgAuth = new AuthPlugin();
}

if( $wgCommandLineMode ) {
	# Used for some maintenance scripts; user session cookies can screw things up
	# when the database is in an in-between state.
	$wgUser = new User();
	# Prevent loading User settings from the DB.
	$wgUser->setLoaded( true );
} else {
	$wgUser = null;
	wfRunHooks('AutoAuthenticate',array(&$wgUser));
	if ($wgUser === null) {
		$wgUser = User::loadFromSession();
	}
}

wfProfileOut( $fname.'-User' );
wfProfileIn( $fname.'-language2' );

// wgLanguageCode now specifically means the UI language
$wgLanguageCode = $wgRequest->getText('uselang', '');
if ($wgLanguageCode == '')
	$wgLanguageCode = $wgUser->getOption('language');
# Validate $wgLanguageCode, which will soon be sent to an eval()
if( empty( $wgLanguageCode ) || !preg_match( '/^[a-z]+(-[a-z]+)?$/', $wgLanguageCode ) ) {
	$wgLanguageCode = $wgContLanguageCode;
}

$wgLangClass = 'Language'. str_replace( '-', '_', ucfirst( $wgLanguageCode ) );

if( $wgLangClass == $wgContLangClass ) {
	$wgLang = &$wgContLang;
} else {
	wfSuppressWarnings();
	// Preload base classes to work around APC/PHP5 bug
	include_once("$IP/languages/$wgLangClass.deps.php");
	include_once("$IP/languages/$wgLangClass.php");
	wfRestoreWarnings();

	$wgLang = setupLangObj( $wgLangClass );
}

wfProfileOut( $fname.'-language2' );
wfProfileIn( $fname.'-MessageCache' );

$wgMessageCache = new MessageCache;
$wgMessageCache->initialise( $parserMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry, $wgDBname);

wfProfileOut( $fname.'-MessageCache' );

#
# I guess the warning about UI switching might still apply...
#
# FIXME: THE ABOVE MIGHT BREAK NAMESPACES, VARIABLES,
# SEARCH INDEX UPDATES, AND MANY MANY THINGS.
# DO NOT USE THIS MODE EXCEPT FOR TESTING RIGHT NOW.
#
# To disable it, the easiest thing could be to uncomment the
# following; they should effectively disable the UI switch functionality
#
# $wgLangClass = $wgContLangClass;
# $wgLanguageCode = $wgContLanguageCode;
# $wgLang = $wgContLang;
#
# TODO: Need to change reference to $wgLang to $wgContLang at proper
#       places, including namespaces, dates in signatures, magic words,
#       and links
#
# TODO: Need to look at the issue of input/output encoding
#


wfProfileIn( $fname.'-OutputPage' );

$wgOut = new OutputPage();

wfProfileOut( $fname.'-OutputPage' );
wfProfileIn( $fname.'-misc2' );

$wgDeferredUpdateList = array();
$wgPostCommitUpdateList = array();

$wgMagicWords = array();
$wgMwRedir =& MagicWord::get( MAG_REDIRECT );

if ( $wgUseXMLparser ) {
	require_once( 'ParserXML.php' );
	$wgParser = new ParserXML();
} else {
	$wgParser = new Parser();
}
$wgOut->setParserOptions( ParserOptions::newFromUser( $wgUser ) );
$wgMsgParserOptions = ParserOptions::newFromUser($wgUser);
wfSeedRandom();

# Placeholders in case of DB error
$wgTitle = Title::makeTitle( NS_SPECIAL, 'Error' );
$wgArticle = new Article($wgTitle);

wfProfileOut( $fname.'-misc2' );
wfProfileIn( $fname.'-extensions' );

# Extension setup functions for extensions other than skins
# Entries should be added to this variable during the inclusion
# of the extension file. This allows the extension to perform
# any necessary initialisation in the fully initialised environment
if ( count( $wgExtensionFunctions ) ) {
	require_once( 'PersistentObject.php' );
}
foreach ( $wgExtensionFunctions as $func ) {
	call_user_func( $func );
}

wfDebug( "\n" );
$wgFullyInitialised = true;
wfProfileOut( $fname.'-extensions' );
wfProfileOut( $fname );

}
?>
