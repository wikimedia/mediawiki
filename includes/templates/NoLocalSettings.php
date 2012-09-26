<?php
/**
 * Template used when there is no LocalSettings.php file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Templates
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "NoLocalSettings.php is not a valid MediaWiki entry point\n" );
}

if ( !isset( $wgVersion ) ) {
	$wgVersion = 'VERSION';
}

# bug 30219 : can not use pathinfo() on URLs since slashes do not match
$matches = array();
$ext = 'php';
$path = '/';
foreach( array_filter( explode( '/', $_SERVER['PHP_SELF'] ) ) as $part ) {
	if( !preg_match( '/\.(php5?)$/', $part, $matches ) ) {
		$path .= "$part/";
	} else {
		$ext = $matches[1] == 'php5' ? 'php5' : 'php';
	}
}

# Check to see if the installer is running
if ( !function_exists( 'session_name' ) ) {
	$installerStarted = false;
} else {
	session_name( 'mw_installer_session' );
	$oldReporting = error_reporting( E_ALL & ~E_NOTICE );
	$success = session_start();
	error_reporting( $oldReporting );
	$installerStarted = ( $success && isset( $_SESSION['installData'] ) );
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8" />
		<title>MediaWiki <?php echo htmlspecialchars( $wgVersion ) ?></title>
		<style media='screen'>
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
		<p>LocalSettings.php not found.</p>
		<p>
		<?php
		if ( $installerStarted ) {
			echo "Please <a href=\"" . htmlspecialchars( $path ) . "mw-config/index." . htmlspecialchars( $ext ) . "\"> complete the installation</a> and download LocalSettings.php.";
		} else {
			echo "Please <a href=\"" . htmlspecialchars( $path ) . "mw-config/index." . htmlspecialchars( $ext ) . "\"> set up the wiki</a> first.";
		}
		?>
		</p>

		</div>
	</body>
</html>
