<?php
/**
 * @file
 * @ingroup Templates
 */

if( !defined( 'MW_PHP4' ) ) {
	die( "Not an entry point.");
}

if( isset( $_SERVER['SCRIPT_NAME'] ) ) {
	// Probably IIS; doesn't set REQUEST_URI
	$scriptUrl = $_SERVER['SCRIPT_NAME'];
} elseif( isset( $_SERVER['REQUEST_URI'] ) ) {
	// We're trying SCRIPT_NAME first because it won't include PATH_INFO... hopefully
	$scriptUrl = $_SERVER['REQUEST_URI'];
} else {
	$scriptUrl = '';
}
if ( preg_match( '!^(.*)/config/[^/]*.php$!', $scriptUrl, $m ) ) {
	$baseUrl = $m[1];
} elseif ( preg_match( '!^(.*)/[^/]*.php$!', $scriptUrl, $m ) ) {
	$baseUrl = $m[1];
} else {
	$baseUrl = dirname( $scriptUrl );
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
	<head>
		<title>MediaWiki <?php echo htmlspecialchars( $wgVersion ); ?></title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<style type='text/css' media='screen, projection'>
			html, body {
				color: #000;
				background-color: #fff;
				font-family: sans-serif;
				text-align: center;
			}

			p {
				text-align: left;
				margin-left: 2em;
				margin-right: 2em;
			}

			h1 {
				font-size: 150%;
			}
		</style>
	</head>
	<body>
		<img src="<?php echo htmlspecialchars( $baseUrl ) ?>/skins/common/images/mediawiki.png" alt='The MediaWiki logo' />

		<h1>MediaWiki <?php echo htmlspecialchars( $wgVersion ); ?></h1>
		<div class='error'>
<p>
			MediaWiki requires PHP 5.0.0 or higher. You are running PHP
			<?php echo htmlspecialchars( phpversion() ); ?>.
</p>
<?php
flush();
/**
 * Test the *.php5 extension
 */
$downloadOther = true;
if ( $baseUrl ) {
	$testUrl = "$wgServer$baseUrl/php5.php5";
	if( function_exists( 'file_get_contents' ) ) {
		$errorLevel = error_reporting();
		error_reporting( $errorLevel & !E_WARNING );
		
		ini_set( 'allow_url_fopen', '1' );
		$s = file_get_contents( $testUrl );
		
		error_reporting( $errorLevel );
	}

	if ( strpos( $s, 'yes' ) !== false ) {
		$encUrl = htmlspecialchars( str_replace( '.php', '.php5', $scriptUrl ) );
		echo "<p>You may be able to use MediaWiki using a <a href=\"$encUrl\">.php5</a> file extension.</p>";
		$downloadOther = false;
	}
}
if ( $downloadOther ) {
?>
<p>Please consider
<a href="http://www.php.net/downloads.php">upgrading your copy of PHP</a>.
PHP 4 is at the end of its lifecycle and will not receive further security updates.</p>
<p>If for some reason you really really need to run MediaWiki on PHP 4, you will need to
<a href="http://www.mediawiki.org/wiki/Download">download version 1.6.x</a>
from our website. </p>
<?php
}
?>

		</div>
	</body>
</html>
