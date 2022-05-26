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

use MediaWiki\Maintenance\MaintenanceRunner;
use MediaWiki\Settings\SettingsBuilder;

// No AutoLoader yet
require_once __DIR__ . '/includes/MaintenanceRunner.php';
require_once __DIR__ . '/includes/MaintenanceParameters.php';

if ( !defined( 'RUN_MAINTENANCE_IF_MAIN' ) ) {
	echo "This file must be included after Maintenance.php\n";
	exit( 1 );
}

// Wasn't included from the file scope, halt execution (probably wanted the class)
// If a class is using CommandLineInc (old school maintenance), they definitely
// cannot be included and will proceed with execution
// @phan-suppress-next-line PhanSuspiciousValueComparisonInGlobalScope
if ( !MaintenanceRunner::shouldExecute() && $maintClass != CommandLineInc::class ) {
	return;
}

// @phan-suppress-next-line PhanImpossibleConditionInGlobalScope
if ( !$maintClass || !class_exists( $maintClass ) ) {
	echo "\$maintClass is not set or is set to a non-existent class.\n";
	exit( 1 );
}

// Define the MediaWiki entrypoint
define( 'MEDIAWIKI', true );

$IP = wfDetectInstallPath();

$runner = new MaintenanceRunner();
$runner->init( $maintClass );

// We used to call this variable $self, but it was moved
// to $maintenance->mSelf. Keep that here for b/c
$self = $runner->getName();

$runner->defineSettings();

// Custom setup for Maintenance entry point
if ( !defined( 'MW_SETUP_CALLBACK' ) ) {

	// Define a function, since we can't put a closure or object
	// reference into MW_SETUP_CALLBACK.
	function wfMaintenanceSetup( SettingsBuilder $settingsBuilder ) {
		global $runner;
		$runner->overrideConfig( $settingsBuilder );
	}

	define( 'MW_SETUP_CALLBACK', 'wfMaintenanceSetup' );
}

// Initialize MediaWiki (load settings, initialized session,
// enable MediaWikiServices)
require_once "$IP/includes/Setup.php";

$success = $runner->run();

// Exit with an error status if execute() returned false
if ( !$success ) {
	exit( 1 );
}
