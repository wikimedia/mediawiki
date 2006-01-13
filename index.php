<?php
/**
 * Main wiki script; see docs/design.txt
 * @package MediaWiki
 */

# In the beginning...
require_once( "./includes/Wiki.php" );
$wgRequestTime = microtime();
$wgRUstart = MediaWiki::getRUsage();
unset( $IP );
MediaWiki::ckeckGlobalsVulnerability();

# Valid web server entry point, enable includes.
# Please don't move this line to includes/Defines.php. This line essentially defines
# a valid entry point. If you put it in includes/Defines.php, then any script that includes
# it becomes an entry point, thereby defeating its purpose.
define( 'MEDIAWIKI', true );
require_once( './includes/Defines.php' );


# Initialize MediaWiki base class
$mediaWiki = new MediaWiki();
$mediaWiki->checkSetup();

# These can't be done in mdiaWiki.php for some weird reason
require_once( './LocalSettings.php' );
require_once( 'includes/Setup.php' );

OutputPage::setEncodings(); # Not really used yet

$mediaWiki->setVal( "Request", $wgRequest );

# Query string fields
$mediaWiki->initializeActionTitle();
$action = $mediaWiki->getVal( 'action' ); # Global might be needed somewhere, sadly...

# Run initial queries
$wgTitle = $mediaWiki->checkInitialQueries( $wgOut, $wgContLang );

# Is this necessary? Who knows...
if ($wgTitle == NULL) {
	unset( $wgTitle );
}

# Setting global variables in mediaWiki
$mediaWiki->setVal( "Server", $wgServer );
$mediaWiki->setVal( "DisableInternalSearch", $wgDisableInternalSearch );
$mediaWiki->setVal( "SquidMaxage", $wgSquidMaxage );
$mediaWiki->setVal( "EnableDublinCoreRdf", $wgEnableDublinCoreRdf );
$mediaWiki->setVal( "EnableCreativeCommonsRdf", $wgEnableCreativeCommonsRdf );
$mediaWiki->setVal( "CommandLineMode", $wgCommandLineMode );
$mediaWiki->setVal( "UseExternalEditor", $wgUseExternalEditor );
$mediaWiki->setVal( "DisabledActions", $wgDisabledActions );

$wgArticle = $mediaWiki->initialize ( $wgTitle, $wgOut, $wgUser );
$mediaWiki->finalCleanup ( $wgDeferredUpdateList, $wgLoadBalancer, $wgOut );
$mediaWiki->doUpdates( $wgPostCommitUpdateList );
$mediaWiki->restInPeace( $wgLoadBalancer );
?>
