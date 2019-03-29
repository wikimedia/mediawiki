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
 * @file
 * @ingroup Maintenance
 */
use MediaWiki\MediaWikiServices;

if ( !defined( 'RUN_MAINTENANCE_IF_MAIN' ) ) {
	echo "This file must be included after Maintenance.php\n";
	exit( 1 );
}

// Wasn't included from the file scope, halt execution (probably wanted the class)
// If a class is using commandLine.inc (old school maintenance), they definitely
// cannot be included and will proceed with execution
if ( !Maintenance::shouldExecute() && $maintClass != CommandLineInc::class ) {
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

// Define how settings are loaded (e.g. LocalSettings.php)
if ( !defined( 'MW_CONFIG_CALLBACK' ) && !defined( 'MW_CONFIG_FILE' ) ) {
	define( 'MW_CONFIG_FILE', $maintenance->loadSettings() );
}

// Custom setup for Maintenance entry point
if ( !defined( 'MW_SETUP_CALLBACK' ) ) {
	function wfMaintenanceSetup() {
		// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
		global $maintenance, $wgLocalisationCacheConf, $wgCacheDirectory;
		if ( $maintenance->getDbType() === Maintenance::DB_NONE ) {
			if ( $wgLocalisationCacheConf['storeClass'] === false
				&& ( $wgLocalisationCacheConf['store'] == 'db'
					|| ( $wgLocalisationCacheConf['store'] == 'detect' && !$wgCacheDirectory ) )
			) {
				$wgLocalisationCacheConf['storeClass'] = LCStoreNull::class;
			}
		}

		$maintenance->finalSetup();
	}
	define( 'MW_SETUP_CALLBACK', 'wfMaintenanceSetup' );
}

require_once "$IP/includes/Setup.php";

// Initialize main config instance
$maintenance->setConfig( MediaWikiServices::getInstance()->getMainConfig() );

// Sanity-check required extensions are installed
$maintenance->checkRequiredExtensions();

// A good time when no DBs have writes pending is around lag checks.
// This avoids having long running scripts just OOM and lose all the updates.
$maintenance->setAgentAndTriggers();

$maintenance->validateParamsAndArgs();

// Do the work
$success = $maintenance->execute();

// Potentially debug globals
$maintenance->globals();

if ( $maintenance->getDbType() !== Maintenance::DB_NONE ) {
	// Perform deferred updates.
	$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
	$lbFactory->commitMasterChanges( $maintClass );
	DeferredUpdates::doUpdates();
}

// log profiling info
wfLogProfilingData();

if ( isset( $lbFactory ) ) {
	// Commit and close up!
	$lbFactory->commitMasterChanges( 'doMaintenance' );
	$lbFactory->shutdown( $lbFactory::SHUTDOWN_NO_CHRONPROT );
}

// Exit with an error status if execute() returned false
if ( $success === false ) {
	exit( 1 );
}
