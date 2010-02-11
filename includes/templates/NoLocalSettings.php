<?php
/**
 * @file
 * @ingroup Templates
 */

if ( !isset( $wgVersion ) ) {
	$wgVersion = 'VERSION';
}

$scriptName = $_SERVER['SCRIPT_NAME'];
$ext = substr( $scriptName, strrpos( $scriptName, "." ) + 1 );
$path = '';
# Add any directories in the main folder that could contain an entrypoint (even possibly).
# We cannot just do a dir listing here, as we do not know where it is yet
# These must not also be the names of subfolders that may contain an entrypoint
$topdirs = array( 'extensions', 'includes' );
foreach( $topdirs as $dir ){
	# Check whether a directory by this name is in the path
	if( strrpos( $scriptName, "/" . $dir . "/" ) ){
		# If so, check whether it is the right folder
		# First, get the number of directories up it is (to generate path)
		$numToGoUp = substr_count( substr( $scriptName, strrpos( $scriptName, "/" . $dir . "/" ) + 1 ), "/" );
		# And generate the path using ..'s
		for( $i = 0; $i < $numToGoUp; $i++ ){
			$realPath = "../" . $realPath;
		}
		# Checking existance (using the image here as it is something not likely to change, and to always be here)
		if( file_exists( $realPath . "skins/common/images/mediawiki.png" ) ) {
			# If so, get the path that we can use in this file, and stop looking
			$path = substr( $scriptName, 0, strrpos( $scriptName, "/" . $dir . "/" ) + 1 );
			break;
		}
	}
}
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
