<?php
/*
 * Copyright 2010 Wikimedia Foundation
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * 		http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License. 
 */

/**
 * Transforms CSS data
 * 
 * This class provides minification, URL remapping, URL extracting, and data-URL embedding.
 * 
 * @file
 * @version 0.1.1 -- 2010-09-11
 * @author Trevor Parscal <tparscal@wikimedia.org>
 * @copyright Copyright 2010 Wikimedia Foundation
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
class CSSMin {
	
	/* Constants */
	
	/**
	 * Maximum file size to still qualify for in-line embedding as a data-URI
	 *
	 * 24,576 is used because Internet Explorer has a 32,768 byte limit for data URIs,
	 * which when base64 encoded will result in a 1/3 increase in size.
	 */
	const EMBED_SIZE_LIMIT = 24576;
	const URL_REGEX = 'url\([\'"]?(?P<file>[^\?\)\:\'"]*)\??[^\)\'"]*[\'"]?\)';
	
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
		$files = array();
		$rFlags = PREG_OFFSET_CAPTURE | PREG_SET_ORDER;
		if ( preg_match_all( '/' . self::URL_REGEX . '/', $source, $matches, $rFlags ) ) {
			foreach ( $matches as $match ) {
				$file = ( isset( $path )
					? rtrim( $path, '/' ) . '/'
					: '' ) . "{$match['file'][0]}";

				// Only proceed if we can access the file
				if ( !is_null( $path ) && file_exists( $file ) ) {
					$files[] = $file;
				}
			}
		}
		return $files;
	}
	
	/**
	 * Remaps CSS URL paths and automatically embeds data URIs for URL rules
	 * preceded by an /* @embed * / comment
	 *
	 * @param $source string CSS data to remap
	 * @param $local string File path where the source was read from
	 * @param $remote string URL path to the file
	 * @param $embed ???
	 * @return string Remapped CSS data
	 */
	public static function remap( $source, $local, $remote, $embed = true ) {
		$pattern = '/((?P<embed>\s*\/\*\s*\@embed\s*\*\/)(?P<pre>[^\;\}]*))?' .
			self::URL_REGEX . '(?P<post>[^;]*)[\;]?/';
		$offset = 0;
		while ( preg_match( $pattern, $source, $match, PREG_OFFSET_CAPTURE, $offset ) ) {
			// Shortcuts
			$embed = $match['embed'][0];
			$pre = $match['pre'][0];
			$post = $match['post'][0];
			$file = "{$local}/{$match['file'][0]}";
			$url = "{$remote}/{$match['file'][0]}";
			// Only proceed if we can access the file
			if ( file_exists( $file ) ) {
				// Add version parameter as a time-stamp in ISO 8601 format,
				// using Z for the timezone, meaning GMT
				$url .= '?' . gmdate( 'Y-m-d\TH:i:s\Z', round( filemtime( $file ), -2 ) );
				// If we the mime-type can't be determined, no embedding will take place
				$type = false;
				$realpath = realpath( $file );
				// Try a couple of different ways to get the mime-type of a file,
				// in order of preference
				if ( $realpath
					&& function_exists( 'finfo_file' ) && function_exists( 'finfo_open' ) )
				{
					// As of PHP 5.3, this is how you get the mime-type of a file;
					// it uses the Fileinfo PECL extension
					$type = finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $realpath );
				} else if ( function_exists( 'mime_content_type' ) ) {
					// Before this was deprecated in PHP 5.3,
					// this used to be how you get the mime-type of a file
					$type = mime_content_type( $file );
				} else {
					// Worst-case scenario has happend,
					// use the file extension to infer the mime-type
					$ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
					if ( isset( self::$mimeTypes[$ext] ) ) {
						$type = self::$mimeTypes[$ext];
					}
				}
				// Detect when URLs were preceeded with embed tags,
				// and also verify file size is below the limit
				if ( $embed && $type && $match['embed'][1] > 0
					&& filesize( $file ) < self::EMBED_SIZE_LIMIT )
				{
					// Strip off any trailing = symbols (makes browsers freak out)
					$data = base64_encode( file_get_contents( $file ) );
					// Build 2 CSS properties; one which uses a base64 encoded data URI
					// in place of the @embed comment to try and retain line-number integrity,
					// and the other with a remapped an versioned URL and an Internet Explorer
					// hack making it ignored in all browsers that support data URIs
					$replacement = "{$pre}url(data:{$type};base64,{$data}){$post};";
					$replacement .= "{$pre}url({$url}){$post}!ie;";
				} else {
					// Build a CSS property with a remapped and versioned URL,
					// preserving comment for debug mode
					$replacement = "{$embed}{$pre}url({$url}){$post};";
				}

				// Perform replacement on the source
				$source = substr_replace( $source,
					$replacement, $match[0][1], strlen( $match[0][0] ) );
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
	 * @param $css string CSS data to minify
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
