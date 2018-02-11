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

$CREDITS = 'CREDITS';
$START_CONTRIBUTORS = '<!-- BEGIN CONTRIBUTOR LIST -->';
$END_CONTRIBUTORS = '<!-- END CONTRIBUTOR LIST -->';
$START_TRANSLATORS = '<!-- BEGIN TRANSLATOR LIST -->';
$END_TRANSLATORS = '<!-- END TRANSLATOR LIST -->';

$section = 'header';
$header = [];
$contributors = [];
$mid = [];
$translators = [];
$footer = [];

if ( !file_exists( $CREDITS ) ) {
	exit( 'No CREDITS file found. Are you running this script in the right directory?' );
}

$lines = explode( "\n", file_get_contents( $CREDITS ) );
foreach ( $lines as $line ) {
	switch ( $section ) {
		case 'header':
			$header[] = $line;
			if ( $line === $START_CONTRIBUTORS ) {
				$section = 'contributors';
			}
			break;
		case 'contributors':
			if ( $line === $END_CONTRIBUTORS ) {
				$section = 'mid';
				$mid[] = $line;
				break;
			}
			$name = substr( $line, 2 );
			$contributors[$name] = true;
			break;
		case 'mid':
			$mid[] = $line;
			if ( $line === $START_TRANSLATORS ) {
				$section = 'translators';
			}
			break;
		case 'translators':
			if ( $line === $END_TRANSLATORS ) {
				$section = 'footer';
				$footer[] = $line;
				break;
			}
			$name = substr( $line, 2 );
			$translators[$name] = true;
			break;
		case 'footer':
			$footer[] = $line;
			break;
	}
}
unset( $lines );

// Get list of contributors
$lines = explode( "\n", shell_exec( 'git log --format="%aN"' ) );
foreach ( $lines as $line ) {
	if ( empty( $line ) ) {
		continue;
	}
	if ( substr( $line, 0, 5 ) === '[BOT]' ) {
		continue;
	}
	$contributors[$line] = true;
}

// Get list of translators
$files = glob( 'languages/i18n/*.json' );
foreach ( $files as $file ) {
	$contents = json_decode( file_get_contents( $file ), true );
	if ( isset( $contents['@metadata']['authors'] ) ) {
		foreach ( $contents['@metadata']['authors'] as $author ) {
			$translators[$author] = true;
		}
	}
}

function process( array $list ) {
	$list = array_keys( $list );
	$collator = Collator::create( 'root' );
	$collator->setAttribute( Collator::NUMERIC_COLLATION, Collator::ON );
	$collator->sort( $list );
	array_walk( $list, function ( &$v, $k ) {
		$v = "* {$v}";
	} );
	return $list;
}

$contributors = process( $contributors );
$translators = process( $translators );

file_put_contents( $CREDITS,
	implode( "\n", array_merge( $header, $contributors, $mid, $translators, $footer ) ) );
