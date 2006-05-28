<?php
/**
 * Main wiki script; see docs/design.txt
 * @package MediaWiki
 */
$wgRequestTime = microtime(true);

# getrusage() does not exist on the Microsoft Windows platforms, catching this
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
# Please don't move this line to includes/Defines.php. This line essentially
# defines a valid entry point. If you put it in includes/Defines.php, then
# any script that includes it becomes an entry point, thereby defeating
# its purpose.
define( 'MEDIAWIKI', true );

# Load up some global defines.
require_once( './includes/Defines.php' );

# LocalSettings.php is the per site customization file. If it does not exit
# the wiki installer need to be launched or the generated file moved from
# ./config/ to ./
if( !file_exists( 'LocalSettings.php' ) ) {
	$IP = '.';
	require_once( 'includes/DefaultSettings.php' ); # used for printing the version
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
	<head>
		<title>MediaWiki <?php echo $wgVersion ?></title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<style type='text/css' media='screen, projection'>
			html, body {
				color: #000;
				background-color: #fff;
				font-family: sans-serif;
				text-align: center;
			}

			h1 {
				font-size: 150%;
			}
		</style>
	</head>
	<body>
		<img src='skins/common/images/mediawiki.png' alt='The MediaWiki logo' />

		<h1>MediaWiki <?php echo $wgVersion ?></h1>
		<div class='error'>
		<?php
		if ( file_exists( 'config/LocalSettings.php' ) ) {
			echo( 'To complete the installation, move <tt>config/LocalSettings.php</tt> to the parent directory.' );
		} else {
			echo( 'Please <a href="config/index.php" title="setup">setup the wiki</a> first.' );
		}
		?>

		</div>
	</body>
</html>
<?php
	die();
}

# Include this site setttings
require_once( './LocalSettings.php' );
# Prepare MediaWiki
require_once( 'includes/Setup.php' );

# Initialize MediaWiki base class
require_once( "includes/Wiki.php" );
$mediaWiki = new MediaWiki();

wfProfileIn( 'main-misc-setup' );
OutputPage::setEncodings(); # Not really used yet

# Query string fields
$action = $wgRequest->getVal( 'action', 'view' );
$title = $wgRequest->getVal( 'title' );

#
# Send Ajax requests to the Ajax dispatcher.
#
if ( $wgUseAjax && $action == 'ajax' ) {
	require_once( 'AjaxDispatcher.php' );

	$dispatcher = new AjaxDispatcher();
	$dispatcher->performAction();

	exit;
}

$wgTitle = $mediaWiki->checkInitialQueries( $title,$action,$wgOut, $wgRequest, $wgContLang );
if ($wgTitle == NULL) {
	unset( $wgTitle );
}

wfProfileOut( 'main-misc-setup' );

# Setting global variables in mediaWiki
$mediaWiki->setVal( 'Server', $wgServer );
$mediaWiki->setVal( 'DisableInternalSearch', $wgDisableInternalSearch );
$mediaWiki->setVal( 'action', $action );
$mediaWiki->setVal( 'SquidMaxage', $wgSquidMaxage );
$mediaWiki->setVal( 'EnableDublinCoreRdf', $wgEnableDublinCoreRdf );
$mediaWiki->setVal( 'EnableCreativeCommonsRdf', $wgEnableCreativeCommonsRdf );
$mediaWiki->setVal( 'CommandLineMode', $wgCommandLineMode );
$mediaWiki->setVal( 'UseExternalEditor', $wgUseExternalEditor );
$mediaWiki->setVal( 'DisabledActions', $wgDisabledActions );

$wgArticle = $mediaWiki->initialize ( $wgTitle, $wgOut, $wgUser, $wgRequest );
$mediaWiki->finalCleanup ( $wgDeferredUpdateList, $wgLoadBalancer, $wgOut );

# Not sure when $wgPostCommitUpdateList gets set, so I keep this separate from finalCleanup
$mediaWiki->doUpdates( $wgPostCommitUpdateList );

$mediaWiki->restInPeace( $wgLoadBalancer );
?>
