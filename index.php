<?php
/**
 * Main wiki script; see docs/design.txt
 * @package MediaWiki
 */
$wgRequestTime = microtime();

# getrusage() does not exist on the Window$ platform, catching this
if ( function_exists ( 'getrusage' ) ) {
	$wgRUstart = getrusage();
} else {
	$wgRUstart = array();
}

unset( $IP );
@ini_set( 'allow_url_fopen', 0 ); # For security...

if ( isset( $_REQUEST['GLOBALS'] ) ) {
	die( '<a href="http://www.hardened-php.net/index.76.html">$GLOBALS overwrite vulnerability</a>');
}

# Valid web server entry point, enable includes.
# Please don't move this line to includes/Defines.php. This line essentially defines
# a valid entry point. If you put it in includes/Defines.php, then any script that includes
# it becomes an entry point, thereby defeating its purpose.
define( 'MEDIAWIKI', true );
require_once( './includes/Defines.php' );
@include_once( './LocalSettings.php' ); # Will die later if not included anyway


# Initialize MediaWiki base class
require_once( "includes/Wiki.php" );
$mediaWiki = new MediaWiki();


$mediaWiki->checkSetup();
require_once( 'includes/Setup.php' ); # This can't be done in mdiaWiki.php for some weird reason

OutputPage::setEncodings(); # Not really used yet

# Query string fields
$action = $wgRequest->getVal( 'action', 'view' );
$title = $wgRequest->getVal( 'title' );

$wgTitle = $mediaWiki->checkInitialQueries( $title,$action,$wgOut, $wgRequest, $wgContLang );

# Is this necessary? Who knows...
if ($wgTitle == NULL) {
	unset( $wgTitle );
}

# Setting global variables in mediaWiki
$mediaWiki->setVal( "Server", $wgServer );
$mediaWiki->setVal( "DisableInternalSearch", $wgDisableInternalSearch );
$mediaWiki->setVal( "action", $action );
$mediaWiki->setVal( "SquidMaxage", $wgSquidMaxage );
$mediaWiki->setVal( "EnableDublinCoreRdf", $wgEnableDublinCoreRdf );
$mediaWiki->setVal( "EnableCreativeCommonsRdf", $wgEnableCreativeCommonsRdf );
$mediaWiki->setVal( "CommandLineMode", $wgCommandLineMode );
$mediaWiki->setVal( "UseExternalEditor", $wgUseExternalEditor );
$mediaWiki->setVal( "DisabledActions", $wgDisabledActions );

$wgArticle = $mediaWiki->initialize ( $wgTitle, $wgOut, $wgUser, $wgRequest );
$mediaWiki->finalCleanup ( $wgDeferredUpdateList, $wgLoadBalancer, $wgOut );

# Not sure when $wgPostCommitUpdateList gets set, so I keep this separate from finalCleanup
$mediaWiki->doUpdates( $wgPostCommitUpdateList );

$mediaWiki->restInPeace( $wgLoadBalancer );
?>
