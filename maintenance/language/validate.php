<?php
/**
 * Check language files for unrecognised variables.
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
 * @ingroup MaintenanceLanguage
 */

if ( !isset( $argv[1] ) ) {
	print "Usage: php {$argv[0]} <filename>\n";
	exit( 1 );
}
array_shift( $argv );

define( 'MEDIAWIKI', 1 );
define( 'NOT_REALLY_MEDIAWIKI', 1 );

$IP = __DIR__ . '/../..';

require_once( "$IP/includes/Defines.php" );
require_once( "$IP/languages/Language.php" );

$files = array();
foreach ( $argv as $arg ) {
	$files = array_merge( $files, glob( $arg ) );
}

foreach ( $files as $filename ) {
	print "$filename...";
	$vars = getVars( $filename );
	$keys = array_keys( $vars );
	$diff = array_diff( $keys, Language::$mLocalisationKeys );
	if ( $diff ) {
		print "\nWarning: unrecognised variable(s): " . implode( ', ', $diff ) . "\n";
	} else {
		print " ok\n";
	}
}

function getVars( $filename ) {
	require( $filename );
	$vars = get_defined_vars();
	unset( $vars['filename'] );
	return $vars;
}
