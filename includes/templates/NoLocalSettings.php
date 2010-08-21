<?php
/**
 * Template used when there is no LocalSettings.php file
 *
 * @file
 * @ingroup Templates
 */

if ( !isset( $wgVersion ) ) {
	$wgVersion = 'VERSION';
}
$script = $_SERVER['SCRIPT_NAME'];
$path = pathinfo( $script, PATHINFO_DIRNAME ) . '/';
$ext = pathinfo( $script, PATHINFO_EXTENSION );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
	<head>
		<title>MediaWiki <?php echo htmlspecialchars( $wgVersion ) ?></title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<style type='text/css' media='screen'>
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
		<img src="<?php echo htmlspecialchars( $path ) ?>skins/common/images/mediawiki.png" alt='The MediaWiki logo' />

		<h1>MediaWiki <?php echo htmlspecialchars( $wgVersion ) ?></h1>
		<div class='error'>
		<?php
		if ( file_exists( 'config/LocalSettings.php' ) ) {
			echo( 'To complete the installation, move <tt>config/LocalSettings.php</tt> to the parent directory.' );
		} else {
			echo( "Please <a href=\"" . htmlspecialchars( $path ) . "config/index." . htmlspecialchars( $ext ) . "\" title='setup'> set up the wiki</a> first." );
		}
		?>

		</div>
	</body>
</html>
