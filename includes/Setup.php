<?
# The main wiki script and things like database
# conversion and maintenance scripts all share a
# common setup of including lots of classes and
# setting up a few globals.
#

global $IP;
include_once( "$IP/GlobalFunctions.php" );
include_once( "$IP/Language.php" );
include_once( "$IP/Namespace.php" );
include_once( "$IP/Skin.php" );
include_once( "$IP/OutputPage.php" );
include_once( "$IP/DifferenceEngine.php" );
include_once( "$IP/SearchEngine.php" );
include_once( "$IP/User.php" );
include_once( "$IP/LinkCache.php" );
include_once( "$IP/Title.php" );
include_once( "$IP/Article.php" );
require( "$IP/MemCachedClient.inc.php" );

wfDebug( "\n\n" );

global $wgUser, $wgLang, $wgOut, $wgTitle;
global $wgArticle, $wgDeferredUpdateList, $wgLinkCache;
global $wgMemc;

class MemCachedClientforWiki extends MemCachedClient {
	function _debug( $text ) {
		wfDebug( "memcached: $text\n" );
	}
}

$wgMemc = new MemCachedClientforWiki();
if( $wgUseMemCached ) {
	$wgMemc->set_servers( $wgMemCachedServers );
	$wgMemc->set_debug( $wgMemCachedDebug );
}

$wgOut = new OutputPage();
$wgLangClass = "Language" . ucfirst( $wgLanguageCode );
if( ! class_exists( $wgLangClass ) ) {
	include_once( "$IP/Utf8Case.php" );
	$wgLangClass = "LanguageUtf8";
}
$wgLang = new $wgLangClass();

$wgUser = User::loadFromSession();
$wgDeferredUpdateList = array();
$wgLinkCache = new LinkCache();

?>
