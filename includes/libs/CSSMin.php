<?php
/**
 * Minification of CSS stylesheets.
 *
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
 *
 * @file
 * @version 0.1.1 -- 2010-09-11
 * @author Trevor Parscal <tparscal@wikimedia.org>
 * @copyright Copyright 2010 Wikimedia Foundation
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * Transforms CSS data
 *
 * This class provides minification, URL remapping, URL extracting, and data-URL embedding.
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
	const URL_REGEX = 'url\(\s*[\'"]?(?P<file>[^\?\)\'"]*?)(?P<query>\?[^\)\'"]*?|)[\'"]?\s*\)';
	const EMBED_REGEX = '\/\*\s*\@embed\s*\*\/';

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
		'svg' => 'image/svg+xml',
	);

	/* Static Methods */

	/**
	 * Gets a list of local file paths which are referenced in a CSS style sheet
	 *
	 * This function will always return an empty array if the second parameter is not given or null
	 * for backwards-compatibility.
	 *
	 * @param string $source CSS data to remap
	 * @param string $path File path where the source was read from (optional)
	 * @return array List of local file references
	 */
	public static function getLocalFileReferences( $source, $path = null ) {
		if ( $path === null ) {
			return array();
		}

		$path = rtrim( $path, '/' ) . '/';
		$files = array();

		$rFlags = PREG_OFFSET_CAPTURE | PREG_SET_ORDER;
		if ( preg_match_all( '/' . self::URL_REGEX . '/', $source, $matches, $rFlags ) ) {
			foreach ( $matches as $match ) {
				$url = $match['file'][0];

				// Skip fully-qualified and protocol-relative URLs and data URIs
				if ( substr( $url, 0, 2 ) === '//' || parse_url( $url, PHP_URL_SCHEME ) ) {
					break;
				}

				$file = $path . $url;
				// Skip non-existent files
				if ( file_exists( $file ) ) {
					break;
				}

				$files[] = $file;
			}
		}
		return $files;
	}

	/**
	 * Encode an image file as a base64 data URI.
	 * If the image file has a suitable MIME type and size, encode it as a
	 * base64 data URI. Return false if the image type is unfamiliar or exceeds
	 * the size limit.
	 *
	 * @param string $file Image file to encode.
	 * @param string|null $type File's MIME type or null. If null, CSSMin will
	 *     try to autodetect the type.
	 * @param int|bool $sizeLimit If the size of the target file is greater than
	 *     this value, decline to encode the image file and return false
	 *     instead. If $sizeLimit is false, no limit is enforced.
	 * @return string|bool: Image contents encoded as a data URI or false.
	 */
	public static function encodeImageAsDataURI( $file, $type = null, $sizeLimit = self::EMBED_SIZE_LIMIT ) {
		if ( $sizeLimit !== false && filesize( $file ) >= $sizeLimit ) {
			return false;
		}
		if ( $type === null ) {
			$type = self::getMimeType( $file );
		}
		if ( !$type ) {
			return false;
		}
		$data = base64_encode( file_get_contents( $file ) );
		return 'data:' . $type . ';base64,' . $data;
	}

	/**
	 * @param $file string
	 * @return bool|string
	 */
	public static function getMimeType( $file ) {
		$realpath = realpath( $file );
		// Try a couple of different ways to get the mime-type of a file, in order of
		// preference
		if (
			$realpath
			&& function_exists( 'finfo_file' )
			&& function_exists( 'finfo_open' )
			&& defined( 'FILEINFO_MIME_TYPE' )
		) {
			// As of PHP 5.3, this is how you get the mime-type of a file; it uses the Fileinfo
			// PECL extension
			return finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $realpath );
		} elseif ( function_exists( 'mime_content_type' ) ) {
			// Before this was deprecated in PHP 5.3, this was how you got the mime-type of a file
			return mime_content_type( $file );
		} else {
			// Worst-case scenario has happened, use the file extension to infer the mime-type
			$ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
			if ( isset( self::$mimeTypes[$ext] ) ) {
				return self::$mimeTypes[$ext];
			}
		}
		return false;
	}

	/**
	 * Build a CSS 'url()' value for the given URL, quoting parentheses (and other funny characters)
	 * and escaping quotes as necessary.
	 *
	 * @param string $url URL to process
	 * @return string 'url()' value, usually just `"url($url)"`, quoted/escaped if necessary
	 */
	public static function buildUrlValue( $url ) {
		// The list below has been crafted to match URLs such as:
		//   scheme://user@domain:port/~user/fi%20le.png?query=yes&really=y+s
		//   data:image/png;base64,R0lGODlh/+==
		if ( preg_match( '!^[\w\d:@/~.%+;,?&=-]+$!', $url ) ) {
			return "url($url)";
		} else {
			return 'url("' . strtr( $url, array( '\\' => '\\\\', '"' => '\\"' ) ) . '")';
		}
	}

	/**
	 * Remaps CSS URL paths and automatically embeds data URIs for CSS rules or url() values
	 * preceded by an / * @embed * / comment.
	 *
	 * @param string $source CSS data to remap
	 * @param string $local File path where the source was read from
	 * @param string $remote URL path to the file
	 * @param bool $embedData If false, never do any data URI embedding, even if / * @embed * / is found
	 * @return string Remapped CSS data
	 */
	public static function remap( $source, $local, $remote, $embedData = true ) {
		// High-level overview:
		// * For each CSS rule in $source that includes at least one url() value:
		//   * Check for an @embed comment at the start indicating that all URIs should be embedded
		//   * For each url() value:
		//     * Check for an @embed comment directly preceding the value
		//     * If either @embed comment exists:
		//       * Embedding the URL as data: URI, if it's possible / allowed
		//       * Otherwise remap the URL to work in generated stylesheets

		// Guard against trailing slashes, because "some/remote/../foo.png"
		// resolves to "some/remote/foo.png" on (some?) clients (bug 27052).
		if ( substr( $remote, -1 ) == '/' ) {
			$remote = substr( $remote, 0, -1 );
		}

		// Note: This will not correctly handle cases where ';', '{' or '}' appears in the rule itself,
		// e.g. in a quoted string. You are advised not to use such characters in file names.
		// We also match start/end of the string to be consistent in edge-cases ('@import url(…)').
		$pattern = '/(?:^|[;{])\K[^;{}]*' . CSSMin::URL_REGEX . '[^;}]*(?=[;}]|$)/';
		return preg_replace_callback( $pattern, function ( $matchOuter ) use ( $local, $remote, $embedData ) {
			$rule = $matchOuter[0];

			// Check for global @embed comment and remove it
			$embedAll = false;
			$rule = preg_replace( '/^(\s*)' . CSSMin::EMBED_REGEX . '\s*/', '$1', $rule, 1, $embedAll );

			// Build two versions of current rule: with remapped URLs and with embedded data: URIs (where possible)
			$pattern = '/(?P<embed>' . CSSMin::EMBED_REGEX . '\s*|)' . CSSMin::URL_REGEX . '/';

			$ruleWithRemapped = preg_replace_callback( $pattern, function ( $match ) use ( $local, $remote ) {
				$remapped = CSSMin::remapOne( $match['file'], $match['query'], $local, $remote, false );
				return CSSMin::buildUrlValue( $remapped );
			}, $rule );

			if ( $embedData ) {
				$ruleWithEmbedded = preg_replace_callback( $pattern, function ( $match ) use ( $embedAll, $local, $remote ) {
					$embed = $embedAll || $match['embed'];
					$embedded = CSSMin::remapOne( $match['file'], $match['query'], $local, $remote, $embed );
					return CSSMin::buildUrlValue( $embedded );
				}, $rule );
			}

			if ( $embedData && $ruleWithEmbedded !== $ruleWithRemapped ) {
				// Build 2 CSS properties; one which uses a base64 encoded data URI in place
				// of the @embed comment to try and retain line-number integrity, and the
				// other with a remapped an versioned URL and an Internet Explorer hack
				// making it ignored in all browsers that support data URIs
				return "$ruleWithEmbedded;$ruleWithRemapped!ie";
			} else {
				// No reason to repeat twice
				return $ruleWithRemapped;
			}
		}, $source );
	}

	/**
	 * Remap or embed a CSS URL path.
	 *
	 * @param string $file URL to remap/embed
	 * @param string $query
	 * @param string $local File path where the source was read from
	 * @param string $remote URL path to the file
	 * @param bool $embed Whether to do any data URI embedding
	 * @return string Remapped/embedded URL data
	 */
	public static function remapOne( $file, $query, $local, $remote, $embed ) {
		// The full URL possibly with query, as passed to the 'url()' value in CSS
		$url = $file . $query;

		// Skip fully-qualified and protocol-relative URLs and data URIs
		if ( substr( $url, 0, 2 ) === '//' || parse_url( $url, PHP_URL_SCHEME ) ) {
			return $url;
		}

		// URLs with absolute paths like /w/index.php need to be expanded
		// to absolute URLs but otherwise left alone
		if ( $url !== '' && $url[0] === '/' ) {
			// Replace the file path with an expanded (possibly protocol-relative) URL
			// ...but only if wfExpandUrl() is even available.
			// This will not be the case if we're running outside of MW
			if ( function_exists( 'wfExpandUrl' ) ) {
				return wfExpandUrl( $url, PROTO_RELATIVE );
			} else {
				return $url;
			}
		}

		if ( $local === false ) {
			// Assume that all paths are relative to $remote, and make them absolute
			return $remote . '/' . $url;
		} else {
			// We drop the query part here and instead make the path relative to $remote
			$url = "{$remote}/{$file}";
			// Path to the actual file on the filesystem
			$localFile = "{$local}/{$file}";
			if ( file_exists( $localFile ) ) {
				// Add version parameter as a time-stamp in ISO 8601 format,
				// using Z for the timezone, meaning GMT
				$url .= '?' . gmdate( 'Y-m-d\TH:i:s\Z', round( filemtime( $localFile ), -2 ) );
				if ( $embed ) {
					$data = self::encodeImageAsDataURI( $localFile );
					if ( $data !== false ) {
						return $data;
					}
				}
			}
			// If any of these conditions failed (file missing, we don't want to embed it
			// or it's not embeddable), return the URL (possibly with ?timestamp part)
			return $url;
		}
	}

	/**
	 * Removes whitespace from CSS data
	 *
	 * @param string $css CSS data to minify
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
