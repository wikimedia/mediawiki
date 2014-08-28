<?php
/**
 * Helper file to initialize the rest of MediaWiki.
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
// Valid web server entry point, enable includes.
// Please don't move this line to includes/Defines.php. This line essentially
// defines a valid entry point. If you put it in includes/Defines.php, then
// any script that includes it becomes an entry point, thereby defeating
// its purpose.
define( 'MEDIAWIKI', true );

$wgRequestTime = microtime( true );

// Start the autoloader, so that extensions can derive classes from core files
require_once "$IP/includes/AutoLoader.php";
// Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
}

// Load the profiler
require_once "$IP/includes/profiler/Profiler.php";
// Start the profiler
$wgProfiler = array();
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require "$IP/StartProfiler.php";
}

// Load up some global defines.
require_once "$IP/includes/Defines.php";
// Load default settings
require_once "$IP/includes/DefaultSettings.php";
