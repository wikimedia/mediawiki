<?php
/**
 * Update the CREDITS list by merging in the list of git commit authors.
 *
 * The contents of the existing contributors list will be preserved. If a name
 * needs to be removed for some reason that must be done manually before or
 * after running this script.
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
 */

// NO_AUTOLOAD -- file-scope code

if ( PHP_SAPI != 'cli' ) {
	die( "This script can only be run from the command line.\n" );
}

const CREDITS = 'CREDITS';
const START_CONTRIBUTORS = '<!-- BEGIN CONTRIBUTOR LIST -->';
const END_CONTRIBUTORS = '<!-- END CONTRIBUTOR LIST -->';

$inHeader = true;
$inFooter = false;
$header = [];
$contributors = [];
$footer = [];

if ( !file_exists( CREDITS ) ) {
	exit( 'No CREDITS file found. Are you running this script in the right directory?' );
}

$lines = explode( "\n", file_get_contents( CREDITS ) );
foreach ( $lines as $line ) {
	if ( $inHeader ) {
		$header[] = $line;
		$inHeader = $line !== START_CONTRIBUTORS;
	} elseif ( $inFooter ) {
		$footer[] = $line;
	} elseif ( $line == END_CONTRIBUTORS ) {
		$inFooter = true;
		$footer[] = $line;
	} else {
		$name = substr( $line, 2 );
		$contributors[$name] = true;
	}
}
unset( $lines );

$lines = explode( "\n", (string)shell_exec( 'git log --format="%aN"' ) );
foreach ( $lines as $line ) {
	if ( empty( $line ) ) {
		continue;
	}
	if ( str_starts_with( $line, '[BOT]' ) ) {
		continue;
	}
	$contributors[$line] = true;
}

$contributors = array_keys( $contributors );
$collator = Collator::create( 'root' );
$collator->setAttribute( Collator::NUMERIC_COLLATION, Collator::ON );
$collator->sort( $contributors );
array_walk( $contributors, static function ( &$v, $k ) {
	$v = "* {$v}";
} );

file_put_contents(
	CREDITS,
	implode( "\n", array_merge( $header, $contributors, $footer ) )
);
