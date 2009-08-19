<?php
/**
 * We want to make this whole thing as seamless as possible to the
 * end-user. Unfortunately, we can't do _all_ of the work in the class
 * because A) included files are not in global scope, but in the scope 
 * of their caller, and B) MediaWiki has way too many globals. So instead
 * we'll kinda fake it, and do the requires() inline. <3 PHP
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
 * @author Chad Horohoe <chad@anyonecanedit.org>
 * @file
 * @ingroup Maintenance
 */

error_reporting( E_ALL | E_STRICT );

if( !isset( $maintClass ) || !class_exists( $maintClass ) ) {
	echo "\$maintClass is not set or is set to a non-existent class.";
	die();
}

if( defined( 'MW_NO_SETUP' ) ) {
	return;
}

// Get an object to start us off
$maintenance = new $maintClass();

// Basic sanity checks and such
$maintenance->setup();

// We used to call this variable $self, but it was moved
// to $maintenance->mSelf. Keep that here for b/c
$self = $maintenance->getName();

# Setup the profiler
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require_once( "$IP/StartProfiler.php" );
} else {
	require_once( "$IP/includes/ProfilerStub.php" );
}

// Load settings, using wikimedia-mode if needed
if( file_exists( dirname(__FILE__).'/wikimedia-mode' ) ) {
	# TODO FIXME! Wikimedia-specific stuff needs to go away to an ext
	# Maybe a hook?
	global $cluster;
	$wgWikiFarm = true;
	$cluster = 'pmtma';
	require_once( "$IP/includes/AutoLoader.php" );
	require_once( "$IP/includes/SiteConfiguration.php" );
	require( "$IP/wgConf.php" );
	$maintenance->loadWikimediaSettings();
	require( $IP.'/includes/Defines.php' );
	require( $IP.'/CommonSettings.php' );
} else {
	require_once( "$IP/includes/AutoLoader.php" );
	require_once( "$IP/includes/Defines.php" );
	require_once( $maintenance->loadSettings() );
}
$maintenance->finalSetup();
// Some last includes
require_once( "$IP/includes/Setup.php" );
require_once( "$IP/maintenance/install-utils.inc" );

// Much much faster startup than creating a title object
$wgTitle = null; 

// Do the work
try {
	$maintenance->execute();
} catch( MWException $mwe ) {
	echo( $mwe->getText() );
}

// Potentially debug globals
$maintenance->globals();
