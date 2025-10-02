<?php
/**
 * Display an error page when there is no LocalSettings.php file.
 *
 * @license GPL-2.0-or-later
 * @file
 */

# T32219 : can not use pathinfo() on URLs since slashes do not match
use MediaWiki\Html\TemplateParser;
use Wikimedia\ObjectCache\EmptyBagOStuff;

$matches = [];
$path = '/';
foreach ( array_filter( explode( '/', $_SERVER['PHP_SELF'] ) ) as $part ) {
	if ( !preg_match( '/\.(php)$/', $part, $matches ) ) {
		$path .= "$part/";
	} else {
		break;
	}
}

# Check to see if the installer is running
if ( !function_exists( 'session_name' ) ) {
	$installerStarted = false;
} else {
	if ( !wfIniGetBool( 'session.auto_start' ) ) {
		session_name( 'mw_installer_session' );
	}
	$oldReporting = error_reporting( E_ALL & ~E_NOTICE );
	$success = session_start();
	error_reporting( $oldReporting );
	$installerStarted = ( $success && isset( $_SESSION['installData'] ) );
}

$templateParser = new TemplateParser( null, new EmptyBagOStuff() );

# Render error page if no LocalSettings file can be found
try {
	echo $templateParser->processTemplate(
		'NoLocalSettings',
		[
			'version' => ( defined( 'MW_VERSION' ) ? MW_VERSION : 'VERSION' ),
			'path' => $path,
			'localSettingsExists' => file_exists( MW_CONFIG_FILE ),
			'installerStarted' => $installerStarted
		]
	);
} catch ( Exception $e ) {
	echo 'Error: ' . htmlspecialchars( $e->getMessage() );
}
