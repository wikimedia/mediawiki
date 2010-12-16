<?php
/**
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

define( 'MEDIAWIKI', 1 );
define( 'NOT_REALLY_MEDIAWIKI', 1 );

class Language { }
foreach ( glob( 'Language*.php' ) as $file ) {
	if ( $file != 'Language.php' ) {
		require_once( $file );
	}
}

$removedFunctions = array( 'date', 'time', 'timeanddate', 'formatMonth', 'formatDay',
	'getMonthName', 'getMonthNameGen', 'getMonthAbbreviation', 'getWeekdayName',
	'userAdjust', 'dateFormat', 'timeSeparator', 'timeDateSeparator', 'timeBeforeDate',
	'monthByLatinNumber', 'getSpecialMonthName',

	'commafy'
);

$numRemoved = 0;
$total = 0;
$classes = get_declared_classes();
ksort( $classes );
foreach ( $classes as $class ) {
	if ( !preg_match( '/^Language/', $class ) || $class == 'Language' || $class == 'LanguageConverter' ) {
		continue;
	}

	print "$class\n";
	$methods = get_class_methods( $class );
	print_r( $methods );

	if ( !count( array_diff( $methods, $removedFunctions ) ) ) {
		print "removed\n";
		$numRemoved++;
	}
	$total++;
	print "\n";
}

print "$numRemoved will be removed out of $total\n";


