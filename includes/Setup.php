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
	include_once( "Profiling.php" );
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

include_once( "GlobalFunctions.php" );
include_once( "Namespace.php" );
include_once( "RecentChange.php" ); 
include_once( "Skin.php" );
include_once( "OutputPage.php" );
include_once( "User.php" );
include_once( "LinkCache.php" );
include_once( "Title.php" );
include_once( "Article.php" );
include_once( "MagicWord.php" );
include_once( "memcached-client.php" );
include_once( "Block.php" );
include_once( "SearchEngine.php" );
include_once( "DifferenceEngine.php" );
include_once( "MessageCache.php" );
include_once( "BlockCache.php" );
include_once( "Parser.php" );
include_once( "ParserCache.php" );
include_once( "WebRequest.php" );
$wgRequest = new WebRequest();


wfProfileOut( "$fname-includes" );
wfProfileIn( "$fname-memcached" );
global $wgUser, $wgLang, $wgOut, $wgTitle;
global $wgArticle, $wgDeferredUpdateList, $wgLinkCache;
global $wgMemc, $wgMagicWords, $wgMwRedir, $wgDebugLogFile;
global $wgMessageCache, $wgUseMemCached, $wgUseDatabaseMessages;
global $wgMsgCacheExpiry, $wgDBname, $wgCommandLineMode;
global $wgBlockCache, $wgParserCache, $wgParser;

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
} else {
	$wgMemc = new FakeMemCachedClient();
}

wfProfileOut( "$fname-memcached" );
wfProfileIn( "$fname-misc" );

include_once( "Language.php" );

$wgMessageCache = new MessageCache; 

$wgLangClass = "Language" . ucfirst( $wgLanguageCode );
if( ! class_exists( $wgLangClass ) || ($wgLanguageCode == "en" && strcasecmp( $wgInputEncoding, "utf-8" ) == 0 ) ) {
	include_once( "LanguageUtf8.php" );
	$wgLangClass = "LanguageUtf8";
}

$wgLang = new $wgLangClass();
if ( !is_object($wgLang) ) {
	print "No language class ($wgLang)\N";
}
$wgMessageCache->initialise( $wgUseMemCached, $wgUseDatabaseMessages, $wgMsgCacheExpiry, $wgDBname );

$wgOut = new OutputPage();
wfDebug( "\n\n" );

if ( $wgUseDynamicDates ) {
	include_once( "DateFormatter.php" );
	global $wgDateFormatter;
	$wgDateFormatter = new DateFormatter;
}

if( !$wgCommandLineMode && ( isset( $_COOKIE[ini_get("session.name")] ) || isset( $_COOKIE["{$wgDBname}Password"] ) ) ) {
	User::SetupSession();
}

$wgBlockCache = new BlockCache( true );
$wgUser = User::loadFromSession();
$wgDeferredUpdateList = array();
$wgLinkCache = new LinkCache();
$wgMagicWords = array();
$wgMwRedir =& MagicWord::get( MAG_REDIRECT );
$wgParserCache = new ParserCache();
$wgParser = new Parser();
$wgOut->setParserOptions( ParserOptions::newFromUser( $wgUser ) );

wfProfileOut( "$fname-misc" );
wfProfileOut( $fname );

?>
