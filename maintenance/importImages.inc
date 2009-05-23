<?php

/**
 * Support functions for the importImages script
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

/**
 * Search a directory for files with one of a set of extensions
 *
 * @param $dir Path to directory to search
 * @param $exts Array of extensions to search for
 * @return mixed Array of filenames on success, or false on failure
 */
function findFiles( $dir, $exts ) {
	if( is_dir( $dir ) ) {
		if( $dhl = opendir( $dir ) ) {
			while( ( $file = readdir( $dhl ) ) !== false ) {
				if( is_file( $dir . '/' . $file ) ) {
					list( /* $name */, $ext ) = splitFilename( $dir . '/' . $file );
					if( array_search( strtolower( $ext ), $exts ) !== false )
						$files[] = $dir . '/' . $file;
				}
			}
			return $files;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Split a filename into filename and extension
 *
 * @param $filename Filename
 * @return array
 */
function splitFilename( $filename ) {
	$parts = explode( '.', $filename );
	$ext = $parts[ count( $parts ) - 1 ];
	unset( $parts[ count( $parts ) - 1 ] );
	$fname = implode( '.', $parts );
	return array( $fname, $ext );
}

/**
 * Find an auxilliary file with the given extension, matching 
 * the give base file path. $maxStrip determines how many extensions 
 * may be stripped from the original file name before appending the
 * new extension. For example, with $maxStrip = 1 (the default), 
 * file files acme.foo.bar.txt and acme.foo.txt would be auxilliary
 * files for acme.foo.bar and the extension ".txt". With $maxStrip = 2,
 * acme.txt would also be acceptable.
 *
 * @param $file base path
 * @param $auxExtension the extension to be appended to the base path
 * @param $maxStrip the maximum number of extensions to strip from the base path (default: 1)
 * @return string or false
 */
function findAuxFile( $file, $auxExtension, $maxStrip = 1 ) {
	if ( strpos( $auxExtension, '.' ) !== 0 ) {
		$auxExtension = '.' . $auxExtension;
	}

	$d = dirname( $file );
	$n = basename( $file );

	while ( $maxStrip >= 0 ) {
		$f = $d . '/' . $n . $auxExtension;

		if ( file_exists( $f ) ) {
			return $f;
		}

		$idx = strrpos( $n, '.' );
		if ( !$idx ) break;

		$n = substr( $n, 0, $idx );
		$maxStrip -= 1;
	}

	return false;
}