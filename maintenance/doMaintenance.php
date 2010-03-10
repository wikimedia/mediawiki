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

if ( !defined( 'DO_MAINTENANCE' ) ) {
	echo "This file must be included after Maintenance.php\n";
	exit( 1 );
}

if( !$maintClass || !class_exists( $maintClass ) ) {
	echo "\$maintClass is not set or is set to a non-existent class.\n";
	exit( 1 );
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

// Some other requires
require_once( "$IP/includes/AutoLoader.php" );
require_once( "$IP/includes/Defines.php" );

// Load settings, using wikimedia-mode if needed
// Fixme: replace this hack with general farm-friendly code
if( file_exists( "$IP/wmf-config/wikimedia-mode" ) ) {
	# TODO FIXME! Wikimedia-specific stuff needs to go away to an ext
	# Maybe a hook?
	global $cluster;
	$wgWikiFarm = true;
	$cluster = 'pmtpa';
	require_once( "$IP/includes/SiteConfiguration.php" );
	require( "$IP/wmf-config/wgConf.php" );
	$maintenance->loadWikimediaSettings();
	require( $IP.'/wmf-config/CommonSettings.php' );
} else {
	require_once( $maintenance->loadSettings() );
}
if ( $maintenance->getDbType() === Maintenance::DB_ADMIN &&
		is_readable( "$IP/AdminSettings.php" ) )
{
	require( "$IP/AdminSettings.php" );
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

	// Potentially debug globals
	$maintenance->globals();
} catch( MWException $mwe ) {
	echo( $mwe->getText() );
	exit( 1 );
}

