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
 *     http://www.apache.org/licenses/LICENSE-2.0
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
 * @license Apache-2.0
 */

/**
 * Transforms CSS data
 *
 * This class provides minification, URL remapping, URL extracting, and data-URL embedding.
 */
class CSSMin {

	/** @var string Strip marker for comments. **/
	const PLACEHOLDER = "\x7fPLACEHOLDER\x7f";

	/**
	 * Internet Explorer data URI length limit. See encodeImageAsDataURI().
	 */
	const DATA_URI_SIZE_LIMIT = 32768;

	const EMBED_REGEX = '\/\*\s*\@embed\s*\*\/';
	const COMMENT_REGEX = '\/\*.*?\*\/';

	/** @var string[] List of common image files extensions and MIME-types */
	protected static $mimeTypes = [
		'gif' => 'image/gif',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'png' => 'image/png',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'xbm' => 'image/x-xbitmap',
		'svg' => 'image/svg+xml',
	];

	/**
	 * Get a list of local files referenced in a stylesheet (includes non-existent files).
	 *
	 * @param string $source CSS stylesheet source to process
	 * @param string $path File path where the source was read from
	 * @return string[] List of local file references
	 */
	public static function getLocalFileReferences( $source, $path ) {
		$stripped = preg_replace( '/' . self::COMMENT_REGEX . '/s', '', $source );
		$path = rtrim( $path, '/' ) . '/';
		$files = [];

		$rFlags = PREG_OFFSET_CAPTURE | PREG_SET_ORDER;
		if ( preg_match_all( '/' . self::getUrlRegex() . '/', $stripped, $matches, $rFlags ) ) {
			foreach ( $matches as $match ) {
				self::processUrlMatch( $match, $rFlags );
				$url = $match['file'][0];

				// Skip fully-qualified and protocol-relative URLs and data URIs
				if (
					substr( $url, 0, 2 ) === '//' ||
					parse_url( $url, PHP_URL_SCHEME )
				) {
					break;
				}

				// Strip trailing anchors - T115436
				$anchor = strpos( $url, '#' );
				if ( $anchor !== false ) {
					$url = substr( $url, 0, $anchor );

					// '#some-anchors' is not a file
					if ( $url === '' ) {
						break;
					}
				}

				$files[] = $path . $url;
			}
		}
		return $files;
	}

	/**
	 * Encode an image file as a data URI.
	 *
	 * If the image file has a suitable MIME type and size, encode it as a data URI, base64-encoded
	 * for binary files or just percent-encoded otherwise. Return false if the image type is
	 * unfamiliar or file exceeds the size limit.
	 *
	 * @param string $file Image file to encode.
	 * @param string|null $type File's MIME type or null. If null, CSSMin will
	 *     try to autodetect the type.
	 * @param bool $ie8Compat By default, a data URI will only be produced if it can be made short
	 *     enough to fit in Internet Explorer 8 (and earlier) URI length limit (32,768 bytes). Pass
	 *     `false` to remove this limitation.
	 * @return string|false Image contents encoded as a data URI or false.
	 */
	public static function encodeImageAsDataURI( $file, $type = null, $ie8Compat = true ) {
		// Fast-fail for files that definitely exceed the maximum data URI length
		if ( $ie8Compat && filesize( $file ) >= self::DATA_URI_SIZE_LIMIT ) {
			return false;
		}

		if ( $type === null ) {
			$type = self::getMimeType( $file );
		}
		if ( !$type ) {
			return false;
		}

		return self::encodeStringAsDataURI( file_get_contents( $file ), $type, $ie8Compat );
	}

	/**
	 * Encode file contents as a data URI with chosen MIME type.
	 *
	 * The URI will be base64-encoded for binary files or just percent-encoded otherwise.
	 *
	 * @since 1.25
	 *
	 * @param string $contents File contents to encode.
	 * @param string $type File's MIME type.
	 * @param bool $ie8Compat See encodeImageAsDataURI().
	 * @return string|false Image contents encoded as a data URI or false.
	 */
	public static function encodeStringAsDataURI( $contents, $type, $ie8Compat = true ) {
		// Try #1: Non-encoded data URI

		// Remove XML declaration, it's not needed with data URI usage
		$contents = preg_replace( "/<\\?xml.*?\\?>/", '', $contents );
		// The regular expression matches ASCII whitespace and printable characters.
		if ( preg_match( '/^[\r\n\t\x20-\x7e]+$/', $contents ) ) {
			// Do not base64-encode non-binary files (sane SVGs).
			// (This often produces longer URLs, but they compress better, yielding a net smaller size.)
			$encoded = rawurlencode( $contents );
			// Unencode some things that don't need to be encoded, to make the encoding smaller
			$encoded = strtr( $encoded, [
				'%20' => ' ', // Unencode spaces
				'%2F' => '/', // Unencode slashes
				'%3A' => ':', // Unencode colons
				'%3D' => '=', // Unencode equals signs
				'%0A' => ' ', // Change newlines to spaces
				'%0D' => ' ', // Change carriage returns to spaces
				'%09' => ' ', // Change tabs to spaces
			] );
			// Consolidate runs of multiple spaces in a row
			$encoded = preg_replace( '/ {2,}/', ' ', $encoded );
			// Remove leading and trailing spaces
			$encoded = preg_replace( '/^ | $/', '', $encoded );

			$uri = 'data:' . $type . ',' . $encoded;
			if ( !$ie8Compat || strlen( $uri ) < self::DATA_URI_SIZE_LIMIT ) {
				return $uri;
			}
		}

		// Try #2: Encoded data URI
		$uri = 'data:' . $type . ';base64,' . base64_encode( $contents );
		if ( !$ie8Compat || strlen( $uri ) < self::DATA_URI_SIZE_LIMIT ) {
			return $uri;
		}

		// A data URI couldn't be produced
		return false;
	}

	/**
	 * Serialize a string (escape and quote) for use as a CSS string value.
	 * https://drafts.csswg.org/cssom/#serialize-a-string
	 *
	 * @param string $value
	 * @return string
	 */
	public static function serializeStringValue( $value ) {
		$value = strtr( $value, [ "\0" => "\u{FFFD}", '\\' => '\\\\', '"' => '\\"' ] );
		$value = preg_replace_callback( '/[\x01-\x1f\x7f]/', function ( $match ) {
			return '\\' . base_convert( ord( $match[0] ), 10, 16 ) . ' ';
		}, $value );
		return '"' . $value . '"';
	}

	/**
	 * @param string $file
	 * @return bool|string
	 */
	public static function getMimeType( $file ) {
		// Infer the MIME-type from the file extension
		$ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
		if ( isset( self::$mimeTypes[$ext] ) ) {
			return self::$mimeTypes[$ext];
		}

		return mime_content_type( realpath( $file ) );
	}

	/**
	 * Build a CSS 'url()' value for the given URL, quoting parentheses (and other funny characters)
	 * and escaping quotes as necessary.
	 *
	 * See http://www.w3.org/TR/css-syntax-3/#consume-a-url-token
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
			return 'url("' . strtr( $url, [ '\\' => '\\\\', '"' => '\\"' ] ) . '")';
		}
	}

	/**
	 * Remaps CSS URL paths and automatically embeds data URIs for CSS rules
	 * or url() values preceded by an / * @embed * / comment.
	 *
	 * @param string $source CSS data to remap
	 * @param string $local File path where the source was read from
	 * @param string $remote URL path to the file
	 * @param bool $embedData If false, never do any data URI embedding,
	 *   even if / * @embed * / is found.
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
		// resolves to "some/remote/foo.png" on (some?) clients (T29052).
		if ( substr( $remote, -1 ) == '/' ) {
			$remote = substr( $remote, 0, -1 );
		}

		// Disallow U+007F DELETE, which is illegal anyway, and which
		// we use for comment placeholders.
		$source = str_replace( "\x7f", "?", $source );

		// Replace all comments by a placeholder so they will not interfere with the remapping.
		// Warning: This will also catch on anything looking like the start of a comment between
		// quotation marks (e.g. "foo /* bar").
		$comments = [];

		$pattern = '/(?!' . self::EMBED_REGEX . ')(' . self::COMMENT_REGEX . ')/s';

		$source = preg_replace_callback(
			$pattern,
			function ( $match ) use ( &$comments ) {
				$comments[] = $match[ 0 ];
				return CSSMin::PLACEHOLDER . ( count( $comments ) - 1 ) . 'x';
			},
			$source
		);

		// Note: This will not correctly handle cases where ';', '{' or '}'
		// appears in the rule itself, e.g. in a quoted string. You are advised
		// not to use such characters in file names. We also match start/end of
		// the string to be consistent in edge-cases ('@import url(…)').
		$pattern = '/(?:^|[;{])\K[^;{}]*' . self::getUrlRegex() . '[^;}]*(?=[;}]|$)/';

		$source = preg_replace_callback(
			$pattern,
			function ( $matchOuter ) use ( $local, $remote, $embedData ) {
				$rule = $matchOuter[0];

				// Check for global @embed comment and remove it. Allow other comments to be present
				// before @embed (they have been replaced with placeholders at this point).
				$embedAll = false;
				$rule = preg_replace(
					'/^((?:\s+|' .
						CSSMin::PLACEHOLDER .
						'(\d+)x)*)' .
						CSSMin::EMBED_REGEX .
						'\s*/',
					'$1',
					$rule,
					1,
					$embedAll
				);

				// Build two versions of current rule: with remapped URLs
				// and with embedded data: URIs (where possible).
				$pattern = '/(?P<embed>' . CSSMin::EMBED_REGEX . '\s*|)' . self::getUrlRegex() . '/';

				$ruleWithRemapped = preg_replace_callback(
					$pattern,
					function ( $match ) use ( $local, $remote ) {
						self::processUrlMatch( $match );

						$remapped = CSSMin::remapOne( $match['file'], $match['query'], $local, $remote, false );
						return CSSMin::buildUrlValue( $remapped );
					},
					$rule
				);

				if ( $embedData ) {
					// Remember the occurring MIME types to avoid fallbacks when embedding some files.
					$mimeTypes = [];

					$ruleWithEmbedded = preg_replace_callback(
						$pattern,
						function ( $match ) use ( $embedAll, $local, $remote, &$mimeTypes ) {
							self::processUrlMatch( $match );

							$embed = $embedAll || $match['embed'];
							$embedded = CSSMin::remapOne(
								$match['file'],
								$match['query'],
								$local,
								$remote,
								$embed
							);

							$url = $match['file'] . $match['query'];
							$file = "{$local}/{$match['file']}";
							if (
								!self::isRemoteUrl( $url ) && !self::isLocalUrl( $url )
								&& file_exists( $file )
							) {
								$mimeTypes[ CSSMin::getMimeType( $file ) ] = true;
							}

							return CSSMin::buildUrlValue( $embedded );
						},
						$rule
					);

					// Are all referenced images SVGs?
					$needsEmbedFallback = $mimeTypes !== [ 'image/svg+xml' => true ];
				}

				if ( !$embedData || $ruleWithEmbedded === $ruleWithRemapped ) {
					// We're not embedding anything, or we tried to but the file is not embeddable
					return $ruleWithRemapped;
				} elseif ( $embedData && $needsEmbedFallback ) {
					// Build 2 CSS properties; one which uses a data URI in place of the @embed comment, and
					// the other with a remapped and versioned URL with an Internet Explorer 6 and 7 hack
					// making it ignored in all browsers that support data URIs
					return "$ruleWithEmbedded;$ruleWithRemapped!ie";
				} else {
					// Look ma, no fallbacks! This is for files which IE 6 and 7 don't support anyway: SVG.
					return $ruleWithEmbedded;
				}
			}, $source );

		// Re-insert comments
		$pattern = '/' . self::PLACEHOLDER . '(\d+)x/';
		$source = preg_replace_callback( $pattern, function ( $match ) use ( &$comments ) {
			return $comments[ $match[1] ];
		}, $source );

		return $source;
	}

	/**
	 * Is this CSS rule referencing a remote URL?
	 *
	 * @param string $maybeUrl
	 * @return bool
	 */
	protected static function isRemoteUrl( $maybeUrl ) {
		if ( substr( $maybeUrl, 0, 2 ) === '//' || parse_url( $maybeUrl, PHP_URL_SCHEME ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Is this CSS rule referencing a local URL?
	 *
	 * @param string $maybeUrl
	 * @return bool
	 */
	protected static function isLocalUrl( $maybeUrl ) {
		return isset( $maybeUrl[1] ) && $maybeUrl[0] === '/' && $maybeUrl[1] !== '/';
	}

	/**
	 * @codeCoverageIgnore
	 */
	private static function getUrlRegex() {
		static $urlRegex;
		if ( $urlRegex === null ) {
			// Match these three variants separately to avoid broken urls when
			// e.g. a double quoted url contains a parenthesis, or when a
			// single quoted url contains a double quote, etc.
			// FIXME: Simplify now we only support PHP 7.0.0+
			// Note: PCRE doesn't support multiple capture groups with the same name by default.
			// - PCRE 6.7 introduced the "J" modifier (PCRE_INFO_JCHANGED for PCRE_DUPNAMES).
			//   https://secure.php.net/manual/en/reference.pcre.pattern.modifiers.php
			//   However this isn't useful since it just ignores all but the first one.
			//   Also, while the modifier was introduced in PCRE 6.7 (PHP 5.2+) it was
			//   not exposed to public preg_* functions until PHP 5.6.0.
			// - PCRE 8.36 fixed this to work as expected (e.g. merge conceptually to
			//   only return the one matched in the part that actually matched).
			//   However MediaWiki supports 5.5.9, which has PCRE 8.32
			//   Per https://secure.php.net/manual/en/pcre.installation.php:
			//   - PCRE 8.32 (PHP 5.5.0)
			//   - PCRE 8.34 (PHP 5.5.10, PHP 5.6.0)
			//   - PCRE 8.37 (PHP 5.5.26, PHP 5.6.9, PHP 7.0.0)
			//   Workaround by using different groups and merge via processUrlMatch().
			// - Using string concatenation for class constant or member assignments
			//   is only supported in PHP 5.6. Use a getter method for now.
			$urlRegex = '(' .
				// Unquoted url
				'url\(\s*(?P<file0>[^\s\'"][^\?\)]+?)(?P<query0>\?[^\)]*?|)\s*\)' .
				// Single quoted url
				'|url\(\s*\'(?P<file1>[^\?\']+?)(?P<query1>\?[^\']*?|)\'\s*\)' .
				// Double quoted url
				'|url\(\s*"(?P<file2>[^\?"]+?)(?P<query2>\?[^"]*?|)"\s*\)' .
				')';
		}
		return $urlRegex;
	}

	private static function processUrlMatch( array &$match, $flags = 0 ) {
		if ( $flags & PREG_SET_ORDER ) {
			// preg_match_all with PREG_SET_ORDER will return each group in each
			// match array, and if it didn't match, instead of the sub array
			// being an empty array it is `[ '', -1 ]`...
			if ( isset( $match['file0'] ) && $match['file0'][1] !== -1 ) {
				$match['file'] = $match['file0'];
				$match['query'] = $match['query0'];
			} elseif ( isset( $match['file1'] ) && $match['file1'][1] !== -1 ) {
				$match['file'] = $match['file1'];
				$match['query'] = $match['query1'];
			} else {
				if ( !isset( $match['file2'] ) || $match['file2'][1] === -1 ) {
					throw new Exception( 'URL must be non-empty' );
				}
				$match['file'] = $match['file2'];
				$match['query'] = $match['query2'];
			}
		} else {
			if ( isset( $match['file0'] ) && $match['file0'] !== '' ) {
				$match['file'] = $match['file0'];
				$match['query'] = $match['query0'];
			} elseif ( isset( $match['file1'] ) && $match['file1'] !== '' ) {
				$match['file'] = $match['file1'];
				$match['query'] = $match['query1'];
			} else {
				if ( !isset( $match['file2'] ) || $match['file2'] === '' ) {
					throw new Exception( 'URL must be non-empty' );
				}
				$match['file'] = $match['file2'];
				$match['query'] = $match['query2'];
			}
		}
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

		// Expand local URLs with absolute paths like /w/index.php to possibly protocol-relative URL, if
		// wfExpandUrl() is available. (This will not be the case if we're running outside of MW.)
		if ( self::isLocalUrl( $url ) && function_exists( 'wfExpandUrl' ) ) {
			return wfExpandUrl( $url, PROTO_RELATIVE );
		}

		// Pass thru fully-qualified and protocol-relative URLs and data URIs, as well as local URLs if
		// we can't expand them.
		// Also skips anchors or the rare `behavior` property specifying application's default behavior
		if (
			self::isRemoteUrl( $url ) ||
			self::isLocalUrl( $url ) ||
			substr( $url, 0, 1 ) === '#'
		) {
			return $url;
		}

		if ( $local === false ) {
			// Assume that all paths are relative to $remote, and make them absolute
			$url = $remote . '/' . $url;
		} else {
			// We drop the query part here and instead make the path relative to $remote
			$url = "{$remote}/{$file}";
			// Path to the actual file on the filesystem
			$localFile = "{$local}/{$file}";
			if ( file_exists( $localFile ) ) {
				if ( $embed ) {
					$data = self::encodeImageAsDataURI( $localFile );
					if ( $data !== false ) {
						return $data;
					}
				}
				if ( class_exists( OutputPage::class ) ) {
					$url = OutputPage::transformFilePath( $remote, $local, $file );
				} else {
					// Add version parameter as the first five hex digits
					// of the MD5 hash of the file's contents.
					$url .= '?' . substr( md5_file( $localFile ), 0, 5 );
				}
			}
			// If any of these conditions failed (file missing, we don't want to embed it
			// or it's not embeddable), return the URL (possibly with ?timestamp part)
		}
		if ( function_exists( 'wfRemoveDotSegments' ) ) {
			$url = wfRemoveDotSegments( $url );
		}
		return $url;
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
				[ '; ', ': ', ' {', '{ ', ', ', '} ', ';}', '( ', ' )', '[ ', ' ]' ],
				[ ';', ':', '{', '{', ',', '}', '}', '(', ')', '[', ']' ],
				preg_replace( [ '/\s+/', '/\/\*.*?\*\//s' ], [ ' ', '' ], $css )
			)
		);
	}
}
