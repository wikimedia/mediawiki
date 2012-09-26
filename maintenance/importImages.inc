<?php
/**
 * Support functions for the importImages.php script
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
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 * @author Mij <mij@bitchx.it>
 */

/**
 * Search a directory for files with one of a set of extensions
 *
 * @param $dir string Path to directory to search
 * @param $exts Array of extensions to search for
 * @param $recurse Bool Search subdirectories recursively
 * @return mixed Array of filenames on success, or false on failure
 */
function findFiles( $dir, $exts, $recurse = false ) {
	if ( is_dir( $dir ) ) {
		$dhl = opendir( $dir );
		if ( $dhl ) {
			$files = array();
			while ( ( $file = readdir( $dhl ) ) !== false ) {
				if ( is_file( $dir . '/' . $file ) ) {
					list( /* $name */, $ext ) = splitFilename( $dir . '/' . $file );
					if ( array_search( strtolower( $ext ), $exts ) !== false ) {
						$files[] = $dir . '/' . $file;
					}
				} elseif ( $recurse && is_dir( $dir . '/' . $file ) && $file !== '..' && $file !== '.' ) {
					$files = array_merge( $files, findFiles( $dir . '/' . $file, $exts, true ) );
				}
			}
			return $files;
		} else {
			return array();
		}
	} else {
		return array();
	}
}

/**
 * Split a filename into filename and extension
 *
 * @param $filename string Filename
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
 * @param $file string base path
 * @param $auxExtension string the extension to be appended to the base path
 * @param $maxStrip int the maximum number of extensions to strip from the base path (default: 1)
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
		if ( !$idx ) {
			break;
		}

		$n = substr( $n, 0, $idx );
		$maxStrip -= 1;
	}

	return false;
}

# FIXME: Access the api in a saner way and performing just one query (preferably batching files too).
function getFileCommentFromSourceWiki( $wiki_host, $file ) {
	$url = $wiki_host . '/api.php?action=query&format=xml&titles=File:' . rawurlencode( $file ) . '&prop=imageinfo&&iiprop=comment';
	$body = Http::get( $url );
	if ( preg_match( '#<ii comment="([^"]*)" />#', $body, $matches ) == 0 ) {
		return false;
	}

	return html_entity_decode( $matches[1] );
}

function getFileUserFromSourceWiki( $wiki_host, $file ) {
	$url = $wiki_host . '/api.php?action=query&format=xml&titles=File:' . rawurlencode( $file ) . '&prop=imageinfo&&iiprop=user';
	$body = Http::get( $url );
	if ( preg_match( '#<ii user="([^"]*)" />#', $body, $matches ) == 0 ) {
		return false;
	}

	return html_entity_decode( $matches[1] );
}

