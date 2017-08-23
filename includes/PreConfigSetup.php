<?php
/**
 * File-scope setup actions, loaded before LocalSettings.php, shared by
 * WebStart.php and doMaintenance.php
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
 *
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Not an entry point
	exit( 1 );
}

// Grab profiling functions
require_once "$IP/includes/profiler/ProfilerFunctions.php";

// Start the autoloader, so that extensions can derive classes from core files
require_once "$IP/includes/AutoLoader.php";

// Load up some global defines.
require_once "$IP/includes/Defines.php";

// Start the profiler
$wgProfiler = [];
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require "$IP/StartProfiler.php";
}

// Load default settings
require_once "$IP/includes/DefaultSettings.php";

// Load global functions
require_once "$IP/includes/GlobalFunctions.php";

// Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
}
