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
 */

/**
 * Transforms CSS data
 * 
 * This class provides minification, URL remapping, URL extracting, and data-URL embedding.
 * 
 * @author Trevor Parscal
 */
class CSSMin {
	
	/* Constants */
	
	/**
	 * Maximum file size to still qualify for in-line embedding as a data-URI
	 *
	 * 24,576 is used because Internet Explorer has a 32,768 byte limit for data URIs, which when base64 encoded will
	 * result in a 1/3 increase in size.
	 */
	const EMBED_SIZE_LIMIT = 24576;
	
	/* Protected Static Members */
	
	/** @var array List of common image files extensions and mime-types */
	protected static $mimeTypes = array(
		'gif' => 'image/gif',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'png' => 'image/png',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'xbm' => 'image/x-xbitmap',
	);
	
	/* Static Methods */
	
	/**
	 * Gets a list of local file paths which are referenced in a CSS style sheet
	 *
	 * @param $source string CSS data to remap
	 * @param $path string File path where the source was read from (optional)
	 * @return array List of local file references
	 */
	public static function getLocalFileReferences( $source, $path = null ) {
		$pattern = '/url\([\'"]?(?<file>[^\?\)\:]*)\??[^\)]*[\'"]?\)/';
		$files = array();
		if ( preg_match_all( $pattern, $source, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER ) ) {
			foreach ( $matches as $match ) {
				$file = ( isset( $path ) ? rtrim( $path, '/' ) . '/' : '' ) . "{$match['file'][0]}";

				// Only proceed if we can access the file
				if ( file_exists( $file ) ) {
					$files[] = $file;
				}
			}
		}
		return $files;
	}
	
	/**
	 * Remaps CSS URL paths and automatically embeds data URIs for URL rules preceded by an /* @embed * / comment
	 *
	 * @param $source string CSS data to remap
	 * @param $path string File path where the source was read from
	 * @return string Remapped CSS data
	 */
	public static function remap( $source, $path, $embed = true ) {
		$pattern = '/((?<embed>\s*\/\*\s*\@embed\s*\*\/)(?<rule>[^\;\}]*))?url\([\'"]?(?<file>[^\?\)\:\'"]*)\??[^\)\'"]*[\'"]?\)(?<extra>[^;]*)[\;]?/';
		$offset = 0;
		while ( preg_match( $pattern, $source, $match, PREG_OFFSET_CAPTURE, $offset ) ) {
			// Shortcuts
			$embed = $match['embed'][0];
			$rule = $match['rule'][0];
			$extra = $match['extra'][0];
			$file = "{$path}/{$match['file'][0]}";
			// Only proceed if we can access the file
			if ( file_exists( $file ) ) {
				// Add version parameter as a time-stamp in ISO 8601 format, using Z for the timezone, meaning GMT
				$url = "{$file}?" . gmdate( 'Y-m-d\TH:i:s\Z', round( filemtime( $file ), -2 ) );
				// If we the mime-type can't be determined, no embedding will take place
				$type = false;
				// Try a couple of different ways to get the mime-type of a file, in order of preference
				if ( function_exists( 'finfo_file' ) && function_exists( 'finfo_open' ) ) {
					// As of PHP 5.3, this is how you get the mime-type of a file; it uses the Fileinfo PECL extension
					$type = finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $file );
				} else if ( function_exists( 'mime_content_type' ) ) {
					// Before this was deprecated in PHP 5.3, this used to be how you get the mime-type of a file
					$type = mime_content_type( $file );
				} else {
					// Worst-case scenario has happend, use the file extension to infer the mime-type
					$ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
					if ( isset( self::$mimeTypes[$ext] ) ) {
						$type = self::$mimeTypes[$ext];
					}
				}
				// Detect when URLs were preceeded with embed tags, and also verify file size is below the limit
				if ( $embed && $type && $match['embed'][1] > 0 && filesize( $file ) < self::EMBED_SIZE_LIMIT ) {
					// Strip off any trailing = symbols (makes browsers freak out)
					$data = base64_encode( file_get_contents( $file ) );
					// Build 2 CSS properties; one which uses a base64 encoded data URI in place of the @embed
					// comment to try and retain line-number integrity , and the other with a remapped an versioned
					// URL and an Internet Explorer hack making it ignored in all browsers that support data URIs
					$replacement = "{$rule}url(data:{$type};base64,{$data}){$extra};{$rule}url({$url}){$extra}!ie;";
				} else {
					// Build a CSS property with a remapped and versioned URL
					$replacement = "{$embed}{$rule}url({$url}){$extra};";
				}

				// Perform replacement on the source
				$source = substr_replace( $source, $replacement, $match[0][1], strlen( $match[0][0] ) );
				// Move the offset to the end of the replacement in the source
				$offset = $match[0][1] + strlen( $replacement );
				continue;
			}
			// Move the offset to the end of the match, leaving it alone
			$offset = $match[0][1] + strlen( $match[0][0] );
		}
		return $source;
	}
	
	/**
	 * Removes whitespace from CSS data
	 *
	 * @param $source string CSS data to minify
	 * @return string Minified CSS data
	 */
	public static function minify( $css ) {
		return trim(
			str_replace(
				array( '; ', ': ', ' {', '{ ', ', ', '} ', ';}' ),
				array( ';', ':', '{', '{', ',', '}', '}' ),
				preg_replace( array( '/\s+/', '/\/\*.*?\*\//s' ), array( ' ', '' ), $css )
			)
		);
	}
}
