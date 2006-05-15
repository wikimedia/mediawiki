<?php

/**
 * Support functions for the importImages script
 *
 * @package MediaWiki
 * @subpackage Maintenance
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
					list( $name, $ext ) = splitFilename( $dir . '/' . $file );
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
 * Given an image hash, check that the structure exists to save the image file
 * and create it if it doesn't
 *
 * @param $hash Part of an image hash, e.g. /f/fd/
 */
function makeHashPath( $hash ) {
	global $wgUploadDirectory;
	$parts = explode( '/', substr( $hash, 1, strlen( $hash ) - 2 ) );
	if( !is_dir( $wgUploadDirectory . '/' . $parts[0] ) )
		mkdir( $wgUploadDirectory . '/' . $parts[0] );
	if( !is_dir( $wgUploadDirectory . '/' . $hash ) )
		mkdir( $wgUploadDirectory . '/' . $hash );
}


?>