<?php
# The main wiki script and things like database
# conversion and maintenance scripts all share a
# common setup of including lots of classes and
# setting up a few globals.
#

global $wgProfiling, $wgProfileSampleRate, $wgIP, $wgUseSquid;

if( !isset( $wgProfiling ) )
	$wgProfiling = false;

if ( $wgProfiling and (0 == rand() % $wgProfileSampleRate ) ) {        
	require_once( "Profiling.php" );
} else {
	function wfProfileIn( $fn ) {}
	function wfProfileOut( $fn = "" ) {}
	function wfGetProfilingOutput( $s, $e ) {}
	function wfProfileClose() {}
}

/* collect the originating ips */
if( $wgUseSquid && isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
	# If the web server is behind a reverse proxy, we need to find
	# out where our requests are really coming from.
	$hopips = array_map( "trim", explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] ) );

	while(in_array(trim(end($hopips)), $wgSquidServers)){
		array_pop($hopips);
	}
	$wgIP = trim(end($hopips));
} else {
	$wgIP = getenv("REMOTE_ADDR");
}


$fname = "Setup.php";
wfProfileIn( $fname );
global $wgUseDynamicDates;
wfProfileIn( "$fname-includes" );

require_once( "GlobalFunctions.php" );
require_once( "Namespace.php" );
require_once( "RecentChange.php" ); 
require_once( "Skin.php" );
require_once( "OutputPage.php" );
require_once( "User.php" );
require_once( "LinkCache.php" );
require_once( "Title.php" );
require_once( "Article.php" );
require_once( "MagicWord.php" );
require_once( "memcached-client.php" );
require_once( "Block.php" );
require_once( "SearchEngine.php" );
require_once( "DifferenceEngine.php" );
require_once( "MessageCache.php" );
require_once( "BlockCache.php" );
require_once( "Parser.php" );
require_once( "ParserCache.php" );
require_once( "WebRequest.php" );
require_once( "SpecialPage.php" );

$wgRequest = new WebRequest();



wfProfileOut( "$fname-includes" );
wfProfileIn( "$fname-memcached" );
global $wgUser, $wgLang, $wgOut, $wgTitle;
global $wgArticle, $wgDeferredUpdateList, $wgLinkCache;
global $wgMemc, $wgMagicWords, $wgMwRedir, $wgDebugLogFile;
global $wgMessageCache, $wgUseMemCached, $wgUseDatabaseMessages;
global $wgMsgCacheExpiry, $wgDBname, $wgCommandLineMode;
global $wgBlockCache, $wgParserCache, $wgParser, $wgDontTrustMemcachedWithImportantStuff;

# Useful debug output
if ( $wgCommandLineMode ) {
	# wfDebug( '"' . implode( '"  "', $argv ) . '"' );
} elseif ( function_exists( "getallheaders" ) ) {
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

# Set up Memcached
#
class MemCachedClientforWiki extends memcached {
	function _debugprint( $text ) {
		wfDebug( "memcached: $text\n" );
	}
}

# FakeMemCachedClient imitates the API of memcached-client v. 0.1.2.
# It acts as a memcached server with no RAM, that is, all objects are
# cleared the moment they are set. All set operations succeed and all
# get operations return null.

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

if( $wgUseMemCached ) {
	$wgMemc = new MemCachedClientforWiki( array('persistant' => true) );
	$wgMemc->set_servers( $wgMemCachedServers );
	$wgMemc->set_debug( $wgMemCachedDebug );

	# Test it to see if it's working
	# This is necessary because otherwise wfMsg would be extremely inefficient
	if ( !$wgMemc->set( "test", "", 0 ) ) {
		wfDebug( "Memcached failed setup test - connection error?\n" );
		$wgUseMemCached = false;
		$wgMemc = new FakeMemCachedClient();
	}
	$messageMemc = &$wgMemc;
} else {
	$wgMemc = new FakeMemCachedClient();
	
	# Give the message cache a separate cache in the DB.
	# This is a speedup over separately querying every message used
	require_once( "ObjectCache.php" );
	$messageMemc = new MediaWikiBagOStuff("objectcache");
}

wfProfileOut( "$fname-memcached" );
wfProfileIn( "$fname-language" );
require_once( "languages/Language.php" );

$wgMessageCache = new MessageCache; 

$wgLangClass = "Language" . ucfirst( $wgLanguageCode );
if( ! class_exists( $wgLangClass ) || ($wgLanguageCode == "en" && strcasecmp( $wgInputEncoding, "utf-8" ) == 0 ) ) {
	require_once( "languages/LanguageUtf8.php" );
	$wgLangClass = "LanguageUtf8";
}

$wgLang = new $wgLangClass();
if ( !is_object($wgLang) ) {
	print "No language class ($wgLang)\N";
}
wfProfileOut( "$fname-language" );
wfProfileIn( "$fname-MessageCache" );

$wgMessageCache->initialise( $messageMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry, $wgDBname );

wfProfileOut( "$fname-MessageCache" );
wfProfileIn( "$fname-OutputPage" );

$wgOut = new OutputPage();
wfDebug( "\n\n" );

wfProfileOut( "$fname-OutputPage" );
wfProfileIn( "$fname-DateFormatter" );

if ( $wgUseDynamicDates ) {
	require_once( "DateFormatter.php" );
	global $wgDateFormatter;
	$wgDateFormatter = new DateFormatter;
}

wfProfileOut( "$fname-DateFormatter" );
wfProfileIn( "$fname-SetupSession" );

if( !$wgCommandLineMode && ( isset( $_COOKIE[ini_get("session.name")] ) || isset( $_COOKIE["{$wgDBname}Password"] ) ) ) {
	User::SetupSession();
}

wfProfileOut( "$fname-SetupSession" );
wfProfileIn( "$fname-BlockCache" );

$wgBlockCache = new BlockCache( true );

wfProfileOut( "$fname-BlockCache" );
wfProfileIn( "$fname-User" );

if( $wgCommandLineMode ) {
	# Used for some maintenance scripts; user session cookies can screw things up
	# when the database is in an in-between state.
	$wgUser = new User();
} else {
	$wgUser = User::loadFromSession();
}

wfProfileOut( "$fname-User" );
wfProfileIn( "$fname-misc" );

$wgDeferredUpdateList = array();
$wgLinkCache = new LinkCache();
$wgMagicWords = array();
$wgMwRedir =& MagicWord::get( MAG_REDIRECT );
$wgParserCache = new ParserCache();
$wgParser = new Parser();
$wgOut->setParserOptions( ParserOptions::newFromUser( $wgUser ) );

if ( !$wgAllowSysopQueries ) {
	SpecialPage::removePage( "Asksql" );
}

# Placeholders in case of DB error
$wgTitle = Title::newFromText( wfMsg( "badtitle" ) );
$wgArticle = new Article($wgTitle);

wfProfileOut( "$fname-misc" );
wfProfileIn( "$fname-extensions" );

# Extension setup functions
# Entries should be added to this variable during the inclusion 
# of the extension file. This allows the extension to perform 
# any necessary initialisation in the fully initialised environment
foreach ( $wgExtensionFunctions as $func ) {
	$func();
}

wfProfileOut( "$fname-extensions" );
wfProfileOut( $fname );


?>
