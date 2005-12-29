<?php
/**
 * Main wiki script; see docs/design.txt
 * @package MediaWiki
 */
$wgRequestTime = microtime();

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

if( !file_exists( 'LocalSettings.php' ) ) {
	$IP = "." ;
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
			echo( "To complete the installation, move <tt>config/LocalSettings.php</tt> to the parent directory." );
		} else {
			echo( "Please <a href='config/index.php' title='setup'>setup the wiki</a> first." );
		}
		?>

		</div>
	</body>
</html>
<?php
	die();
}

require_once( './LocalSettings.php' );
require_once( 'includes/Setup.php' );

# The wiki action class
require_once ( "includes/Wiki.php" ) ;
$wgTheWiki = new MediaWikiType ; 

logProfilingData();
$wgLoadBalancer->closeAll();
wfDebug( "Request ended normally\n" );
?>
