<?php
/**
 * Serialize variables found in input file and store the result in the
 * specified file.
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

if ( !defined( 'MEDIAWIKI' ) ) {
	$wgNoDBParam = true;
	$optionsWithArgs = array( 'o' );
	require_once __DIR__ .'/../maintenance/commandLine.inc';

	$stderr = fopen( 'php://stderr', 'w' );
	if ( !isset( $args[0] ) ) {
		fwrite( $stderr, "No input file specified\n" );
		exit( 1 );
	}
	if ( wfIsWindows() ) {
		$files = array();
		foreach ( $args as $arg ) {
			$files = array_merge( $files, glob( $arg ) );
		}
		if ( !$files ) {
			fwrite( $stderr, "No files found\n" );
		}
	} else {
		$files = $args;
	}

	if ( isset( $options['o'] ) ) {
		$out = fopen( $options['o'], 'wb' );
		if ( !$out ) {
			fwrite( $stderr, "Unable to open file \"{$options['o']}\" for output\n" );
			exit( 1 );
		}
	} else {
		$out = fopen( 'php://stdout', 'wb' );
	}

	$vars = array();
	foreach ( $files as $inputFile ) {
		$vars = array_merge( $vars, getVars( $inputFile ) );
	}
	fwrite( $out, serialize( $vars ) );
	fclose( $out );
	exit( 0 );
}

//----------------------------------------------------------------------------

function getVars( $_gv_filename ) {
	require $_gv_filename;
	$vars = get_defined_vars();
	unset( $vars['_gv_filename'] );

	# Clean up line endings
	if ( wfIsWindows() ) {
		$vars = unixLineEndings( $vars );
	}
	return $vars;
}

function unixLineEndings( $var ) {
	static $recursionLevel = 0;
	if ( $recursionLevel > 50 ) {
		global $stderr;
		fwrite( $stderr, "Error: Recursion limit exceeded. Possible circular reference in array variable.\n" );
		exit( 2 );
	}

	if ( is_array( $var ) ) {
		++$recursionLevel;
		$var = array_map( 'unixLineEndings', $var );
		--$recursionLevel;
	} elseif ( is_string( $var ) ) {
		$var = str_replace( "\r\n", "\n", $var );
	}
	return $var;
}
