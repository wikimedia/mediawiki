<?
# The main wiki script and things like database
# conversion and maintenance scripts all share a
# common setup of including lots of classes and
# setting up a few globals.
#

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

$fname = "Setup.php";
wfProfileIn( $fname );
global $wgUseDynamicDates;
wfProfileIn( "$fname-includes" );

include_once( "GlobalFunctions.php" );
include_once( "Namespace.php" );
include_once( "Skin.php" );
include_once( "OutputPage.php" );
include_once( "User.php" );
include_once( "LinkCache.php" );
include_once( "Title.php" );
include_once( "Article.php" );
include_once( "MagicWord.php" );
include_once( "MemCachedClient.inc.php" );
include_once( "Block.php" );
include_once( "SearchEngine.php" );
include_once( "DifferenceEngine.php" );
include_once( "MessageCache.php" );

wfProfileOut( "$fname-includes" );
wfProfileIn( "$fname-memcached" );
global $wgUser, $wgLang, $wgOut, $wgTitle;
global $wgArticle, $wgDeferredUpdateList, $wgLinkCache;
global $wgMemc, $wgMagicWords, $wgMwRedir, $wgDebugLogFile;
global $wgMessageCache, $wgUseMemCached, $wgUseDatabaseMessages;
global $wgMsgCacheExpiry, $wgDBname;

class MemCachedClientforWiki extends MemCachedClient {
	function _debug( $text ) {
		wfDebug( "memcached: $text\n" );
	}
}

$wgMemc = new MemCachedClientforWiki();
if( $wgUseMemCached ) {
	$wgMemc->set_servers( $wgMemCachedServers );
	$wgMemc->set_debug( $wgMemCachedDebug );

	# Test it to see if it's working
	# This is necessary because otherwise wfMsg would be extremely inefficient
	if ( !$wgMemc->set( "test", "", 0 ) ) {
		wfDebug( "Memcached error: " . $wgMemc->error_string() . "\n" );
		$wgUseMemCached = false;
	}
}

wfProfileOut( "$fname-memcached" );
wfProfileIn( "$fname-misc" );

include_once( "Language.php" );

$wgMessageCache = new MessageCache( $wgUseMemCached, $wgUseDatabaseMessages, $wgMsgCacheExpiry, $wgDBname );

$wgOut = new OutputPage();
wfDebug( "\n\n" );

$wgLangClass = "Language" . ucfirst( $wgLanguageCode );
if( ! class_exists( $wgLangClass ) ) {
	include_once( "LanguageUtf8.php" );
	$wgLangClass = "LanguageUtf8";
}
$wgLang = new $wgLangClass();

if ( $wgUseDynamicDates ) {
	include_once( "DateFormatter.php" );
	global $wgDateFormatter;
	$wgDateFormatter = new DateFormatter;
}

if( !$wgCommandLineMode ) {
	if( $wgSessionsInMemcached ) {
		include_once( "MemcachedSessions.php" );
	}
	session_set_cookie_params( 0, $wgCookiePath, $wgCookieDomain );
	session_cache_limiter( "private, must-revalidate" );
	session_start();
	session_register( "wsUserID" );
	session_register( "wsUserName" );
	session_register( "wsUserPassword" );
	session_register( "wsUploadFiles" );
}

$wgUser = User::loadFromSession();
$wgDeferredUpdateList = array();
$wgLinkCache = new LinkCache();
$wgMagicWords = array();
$wgMwRedir =& MagicWord::get( MAG_REDIRECT );

wfProfileOut( "$fname-misc" );
wfProfileOut( $fname );

?>
