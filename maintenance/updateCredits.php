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

if ( PHP_SAPI != 'cli' ) {
	die( "This script can only be run from the command line.\n" );
}

chdir( dirname( __DIR__ ) );

$CREDITS = 'CREDITS';
$START_CONTRIBUTORS = '<!-- BEGIN CONTRIBUTOR LIST -->';
$END_CONTRIBUTORS = '<!-- END CONTRIBUTOR LIST -->';

$inHeader = true;
$inFooter = false;
$header = [];
$contributors = [];
$footer = [];

$lines = explode( "\n", file_get_contents( $CREDITS ) );
foreach ( $lines as $line ) {
	if ( $inHeader ) {
		$header[] = $line;
		$inHeader = $line !== $START_CONTRIBUTORS;
	} elseif ( $inFooter ) {
		$footer[] = $line;
	} elseif ( $line == $END_CONTRIBUTORS ) {
		$inFooter = true;
		$footer[] = $line;
	} else {
		$name = substr( $line, 2 );
		$contributors[$name] = true;
	}
}
unset( $lines );

$lines = explode( "\n", shell_exec( 'git log --format="%aN"' ) );
foreach ( $lines as $line ) {
	if ( empty( $line ) )  {
		continue;
	}
	if ( substr( $line, 0, 5 ) === '[BOT]' ) {
		continue;
	}
	$contributors[$line] = true;
}

$contributors = array_keys( $contributors );
$collator = Collator::create( 'uca-default-u-kn' );
$collator->sort( $contributors );
array_walk( $contributors, function ( &$v, $k ) {
	$v = "* {$v}";
} );

file_put_contents( $CREDITS,
	implode( "\n", array_merge( $header, $contributors, $footer ) ) );
