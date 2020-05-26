<?php
/**
 * Display an error page when there is no LocalSettings.php file.
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
 */

# T32219 : can not use pathinfo() on URLs since slashes do not match
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

$templateParser = new TemplateParser();

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
