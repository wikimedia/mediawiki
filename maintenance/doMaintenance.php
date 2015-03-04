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

if ( !defined( 'RUN_MAINTENANCE_IF_MAIN' ) ) {
	echo "This file must be included after Maintenance.php\n";
	exit( 1 );
}

// Wasn't included from the file scope, halt execution (probably wanted the class)
// If a class is using commandLine.inc (old school maintenance), they definitely
// cannot be included and will proceed with execution
if ( !Maintenance::shouldExecute() && $maintClass != 'CommandLineInc' ) {
	return;
}

if ( !$maintClass || !class_exists( $maintClass ) ) {
	echo "\$maintClass is not set or is set to a non-existent class.\n";
	exit( 1 );
}

// Get an object to start us off
/** @var Maintenance $maintenance */
$maintenance = new $maintClass();

// Basic sanity checks and such
$maintenance->setup();

// We used to call this variable $self, but it was moved
// to $maintenance->mSelf. Keep that here for b/c
$self = $maintenance->getName();

# Start the autoloader, so that extensions can derive classes from core files
require_once "$IP/includes/AutoLoader.php";
# Grab profiling functions
require_once "$IP/includes/profiler/ProfilerFunctions.php";

# Start the profiler
$wgProfiler = array();
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require "$IP/StartProfiler.php";
}

// Some other requires
require_once "$IP/includes/Defines.php";
require_once "$IP/includes/DefaultSettings.php";
require_once "$IP/includes/GlobalFunctions.php";

# Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
}

if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
	# Use a callback function to configure MediaWiki
	call_user_func( MW_CONFIG_CALLBACK );
} else {
	// Require the configuration (probably LocalSettings.php)
	require $maintenance->loadSettings();
}

if ( $maintenance->getDbType() === Maintenance::DB_NONE ) {
	if ( $wgLocalisationCacheConf['storeClass'] === false
		&& ( $wgLocalisationCacheConf['store'] == 'db'
			|| ( $wgLocalisationCacheConf['store'] == 'detect' && !$wgCacheDirectory ) )
	) {
		$wgLocalisationCacheConf['storeClass'] = 'LCStoreNull';
	}
}

$maintenance->finalSetup();
// Some last includes
require_once "$IP/includes/Setup.php";

// Initialize main config instance
$maintenance->setConfig( ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );

// Do the work
$maintenance->execute();

// Potentially debug globals
$maintenance->globals();

// Perform deferred updates.
DeferredUpdates::doUpdates( 'commit' );

// log profiling info
wfLogProfilingData();

// Commit and close up!
$factory = wfGetLBFactory();
$factory->commitMasterChanges();
$factory->shutdown();
