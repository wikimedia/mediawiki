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

global $wgProfiling, $wgProfileSampleRate, $wgIP, $wgUseSquid, $IP;

if( !isset( $wgProfiling ) )
	$wgProfiling = false;

if ( $wgProfiling and (0 == rand() % $wgProfileSampleRate ) ) {        
	require_once( 'Profiling.php' );
} else {
	function wfProfileIn( $fn = '' ) {}
	function wfProfileOut( $fn = '' ) {}
	function wfGetProfilingOutput( $s, $e ) {}
	function wfProfileClose() {}
}

/* collect the originating ips */
if( $wgUseSquid && isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	# If the web server is behind a reverse proxy, we need to find
	# out where our requests are really coming from.
	$hopips = array_map( 'trim', explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] ) );

	$allsquids = array_merge($wgSquidServers, $wgSquidServersNoPurge);
	while(in_array(trim(end($hopips)), $allsquids)){
		array_pop($hopips);
	}
	$wgIP = trim(end($hopips));
} elseif( isset( $_SERVER['REMOTE_ADDR'] ) ) {
	$wgIP = $_SERVER['REMOTE_ADDR'];
} else {
	# Running on CLI?
	$wgIP = '127.0.0.1';
}

if ( $wgUseData ) {
	$wgExtraNamespaces[20] = 'Data' ;
	$wgExtraNamespaces[21] = 'Data_talk' ;
}

$fname = 'Setup.php';
wfProfileIn( $fname );
global $wgUseDynamicDates;
wfProfileIn( $fname.'-includes' );

require_once( 'GlobalFunctions.php' );
require_once( 'Hooks.php' );
require_once( 'Namespace.php' );
require_once( 'RecentChange.php' ); 
require_once( 'User.php' );
require_once( 'Skin.php' );
require_once( 'OutputPage.php' );
require_once( 'LinkCache.php' );
require_once( 'Title.php' );
require_once( 'Article.php' );
require_once( 'MagicWord.php' );
require_once( 'Block.php' );
require_once( 'MessageCache.php' );
require_once( 'BlockCache.php' );
require_once( 'Parser.php' );
require_once( 'ParserXML.php' );
require_once( 'ParserCache.php' );
require_once( 'WebRequest.php' );
require_once( 'LoadBalancer.php' );
require_once( 'HistoryBlob.php' );

$wgRequest = new WebRequest();

wfProfileOut( $fname.'-includes' );
wfProfileIn( $fname.'-misc1' );
global $wgUser, $wgLang, $wgContLang, $wgOut, $wgTitle;
global $wgLangClass, $wgContLangClass;
global $wgArticle, $wgDeferredUpdateList, $wgLinkCache;
global $wgMemc, $wgMagicWords, $wgMwRedir, $wgDebugLogFile;
global $wgMessageCache, $wgUseMemCached, $wgUseDatabaseMessages;
global $wgMsgCacheExpiry, $wgCommandLineMode;
global $wgBlockCache, $wgParserCache, $wgParser, $wgMsgParserOptions;
global $wgLoadBalancer, $wgDBservers, $wgDebugDumpSql;
global $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, $wgDBtype;
global $wgUseOldExistenceCheck, $wgEnablePersistentLC, $wgMasterWaitTimeout;

global $wgFullyInitialised;

# Useful debug output
if ( $wgCommandLineMode ) {
	# wfDebug( '"' . implode( '"  "', $argv ) . '"' );
} elseif ( function_exists( 'getallheaders' ) ) {
	wfDebug( "\nStart request\n" );
	wfDebug( $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . "\n" );
	$headers = getallheaders();
	foreach ($headers as $name => $value) {
		wfDebug( "$name: $value\n" );
	}
	wfDebug( "\n" );
} else {
	wfDebug( $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . "\n" );
}

# Disable linkscc except if the old existence check method is enabled
if (!$wgUseOldExistenceCheck) {
	$wgEnablePersistentLC = false;
}

wfProfileOut( $fname.'-misc1' );
wfProfileIn( $fname.'-memcached' );

# FakeMemCachedClient imitates the API of memcached-client v. 0.1.2.
# It acts as a memcached server with no RAM, that is, all objects are
# cleared the moment they are set. All set operations succeed and all
# get operations return null.

if( $wgUseMemCached ) {
	# Set up Memcached
	#
	require_once( 'memcached-client.php' );
	
	/**
	 *
	 * @package MediaWiki
	 */
	class MemCachedClientforWiki extends memcached {
		function _debugprint( $text ) {
			wfDebug( "memcached: $text\n" );
		}
	}

	$wgMemc = new MemCachedClientforWiki( array('persistant' => true) );
	$wgMemc->set_servers( $wgMemCachedServers );
	$wgMemc->set_debug( $wgMemCachedDebug );

	$messageMemc = &$wgMemc;
} elseif ( $wgUseTurckShm ) {
	# Turck shared memory
	#
	require_once( 'ObjectCache.php' );
	$wgMemc = new TurckBagOStuff;
	$messageMemc = &$wgMemc;
} else {
	/**
	 * No shared memory
	 * @package MediaWiki
	 */
	class FakeMemCachedClient {
		function add ($key, $val, $exp = 0) { return true; }
		function decr ($key, $amt=1) { return null; }
		function delete ($key, $time = 0) { return false; }
		function disconnect_all () { }
		function enable_compress ($enable) { }
		function forget_dead_hosts () { }
		function get ($key) { return null; }
		function get_multi ($keys) { return array_pad(array(), count($keys), null); }
		function incr ($key, $amt=1) { return null; }
		function replace ($key, $value, $exp=0) { return false; }
		function run_command ($sock, $cmd) { return null; }
		function set ($key, $value, $exp=0){ return true; }
		function set_compress_threshold ($thresh){ }
		function set_debug ($dbg) { }
		function set_servers ($list) { }
	}
	$wgMemc = new FakeMemCachedClient();
	
	# Give the message cache a separate cache in the DB.
	# This is a speedup over separately querying every message used
	require_once( 'ObjectCache.php' );
	$messageMemc = new MediaWikiBagOStuff('objectcache');
}

wfProfileOut( $fname.'-memcached' );
wfProfileIn( $fname.'-SetupSession' );

if( !$wgCommandLineMode && ( isset( $_COOKIE[ini_get('session.name')] ) || isset( $_COOKIE[$wgDBname.'Token'] ) ) ) {
	User::SetupSession();
	$wgSessionStarted = true;
} else {
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

wfProfileOut( $fname.'-language1' );
wfProfileIn( $fname.'-User' );

# Skin setup functions
# Entries can be added to this variable during the inclusion 
# of the extension file. Skins can then perform any necessary initialisation.
foreach ( $wgSkinExtensionFunctions as $func ) {
	$func();
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
	$wgUser = User::loadFromSession();
}

wfProfileOut( $fname.'-User' );
wfProfileIn( $fname.'-language2' );

function setupLangObj(&$langclass) {
	global $wgUseLatin1, $IP;

	if( ! class_exists( $langclass ) ) {
		# Default to English/UTF-8, or for non-UTF-8, to latin-1
		$baseclass = 'LanguageUtf8';
		if( $wgUseLatin1 )
			$baseclass = 'LanguageLatin1';
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

// set default user option from content language
if( !$wgUser->mDataLoaded ) {
	$wgUser->loadDefaultFromLanguage();
}

// wgLanguageCode now specifically means the UI language
$wgLanguageCode = $wgUser->getOption('language');
# Validate $wgLanguageCode, which will soon be sent to an eval()
if( empty( $wgLanguageCode ) || !preg_match( '/^[a-z\-]*$/', $wgLanguageCode ) ) {
	$wgLanguageCode = $wgContLanguageCode;
}

$wgLangClass = 'Language'. str_replace( '-', '_', ucfirst( $wgLanguageCode ) );

if( $wgLangClass == $wgContLangClass ) {
	$wgLang = &$wgContLang;
} else {
	wfSuppressWarnings();
	include_once("$IP/languages/$wgLangClass.php");
	wfRestoreWarnings();

	$wgLang = setupLangObj( $wgLangClass );
}

wfProfileOut( $fname.'-language' );
wfProfileIn( $fname.'-MessageCache' );

$wgMessageCache = new MessageCache;
$wgMessageCache->initialise( $messageMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry, $wgDBname);

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
wfDebug( "\n\n" );

wfProfileOut( $fname.'-OutputPage' );
wfProfileIn( $fname.'-DateFormatter' );

if ( $wgUseDynamicDates ) {
	require_once( 'DateFormatter.php' );
	global $wgDateFormatter;
	$wgDateFormatter = new DateFormatter;
}

wfProfileOut( $fname.'-DateFormatter' );
wfProfileIn( $fname.'-BlockCache' );

$wgBlockCache = new BlockCache( true );

wfProfileOut( $fname.'-BlockCache' );
wfProfileIn( $fname.'-misc2' );

$wgDeferredUpdateList = array();
$wgLinkCache = new LinkCache();
$wgMagicWords = array();
$wgMwRedir =& MagicWord::get( MAG_REDIRECT );
$wgParserCache = new ParserCache( $messageMemc );

if ( $wgUseXMLparser ) $wgParser = new ParserXML();
else $wgParser = new Parser();
$wgOut->setParserOptions( ParserOptions::newFromUser( $wgUser ) );
$wgMsgParserOptions = ParserOptions::newFromUser($wgUser);
wfSeedRandom();

# Placeholders in case of DB error
$wgTitle = Title::makeTitle( NS_SPECIAL, 'Error' );
$wgArticle = new Article($wgTitle);

# Site notice

$notice = wfMsg( 'sitenotice' );
if($notice == '&lt;sitenotice&gt;') $notice = '';
# Allow individual wikis to turn it off
if ( $notice == '-' ) {
	$wgSiteNotice = '';
} else {
	# if($wgSiteNotice) $notice .= $wgSiteNotice;
	if ($notice == '') {
		$notice = $wgSiteNotice;
	}
	if($notice != '-' && $notice != '') {
		$specialparser = new Parser();
		$parserOutput = $specialparser->parse( $notice, $wgTitle, $wgOut->mParserOptions, false );
		$wgSiteNotice = $parserOutput->getText();
	}
}

wfProfileOut( $fname.'-misc2' );
wfProfileIn( $fname.'-extensions' );

# Extension setup functions for extensions other than skins
# Entries should be added to this variable during the inclusion 
# of the extension file. This allows the extension to perform 
# any necessary initialisation in the fully initialised environment
foreach ( $wgExtensionFunctions as $func ) {
	$func();
}

$wgFullyInitialised = true;
wfProfileOut( $fname.'-extensions' );
wfProfileOut( $fname );

}
?>
