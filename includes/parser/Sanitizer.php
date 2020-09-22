<?php
/**
 * HTML sanitizer for %MediaWiki.
 *
 * Copyright © 2002-2005 Brion Vibber <brion@pobox.com> et al
 * https://www.mediawiki.org/
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
 * @ingroup Parser
 */

use MediaWiki\MediaWikiServices;
use RemexHtml\HTMLData;

/**
 * HTML sanitizer for MediaWiki
 * @ingroup Parser
 */
class Sanitizer {
	/**
	 * Regular expression to match various types of character references in
	 * Sanitizer::normalizeCharReferences and Sanitizer::decodeCharReferences.
	 * Note that HTML5 allows some named entities to omit the trailing
	 * semicolon; wikitext entities *must* have a trailing semicolon.
	 */
	private const CHAR_REFS_REGEX =
		'/&([A-Za-z0-9\x80-\xff]+;)
		 |&\#([0-9]+);
		 |&\#[xX]([0-9A-Fa-f]+);
		 |(&)/x';

	/**
	 * Acceptable tag name charset from HTML5 parsing spec
	 * https://www.w3.org/TR/html5/syntax.html#tag-open-state
	 */
	private const ELEMENT_BITS_REGEX = '!^(/?)([A-Za-z][^\t\n\v />\0]*+)([^>]*?)(/?>)([^<]*)$!';

	/**
	 * Pattern matching evil uris like javascript:
	 * WARNING: DO NOT use this in any place that actually requires denying
	 * certain URIs for security reasons. There are NUMEROUS[1] ways to bypass
	 * pattern-based deny lists; the only way to be secure from javascript:
	 * uri based xss vectors is to allow only things that you know are safe
	 * and deny everything else.
	 * [1]: http://ha.ckers.org/xss.html
	 */
	private const EVIL_URI_PATTERN = '!(^|\s|\*/\s*)(javascript|vbscript)([^\w]|$)!i';
	private const XMLNS_ATTRIBUTE_PATTERN = "/^xmlns:[:A-Z_a-z-.0-9]+$/";

	/**
	 * Tells escapeUrlForHtml() to encode the ID using the wiki's primary encoding.
	 *
	 * @since 1.30
	 */
	public const ID_PRIMARY = 0;

	/**
	 * Tells escapeUrlForHtml() to encode the ID using the fallback encoding, or return false
	 * if no fallback is configured.
	 *
	 * @since 1.30
	 */
	public const ID_FALLBACK = 1;

	/**
	 * Character entity aliases accepted by MediaWiki in wikitext.
	 * These are not part of the HTML standard.
	 */
	private const MW_ENTITY_ALIASES = [
		'רלמ;' => 'rlm;',
		'رلم;' => 'rlm;',
	];

	/**
	 * Lazy-initialised attributes regex, see getAttribsRegex()
	 */
	private static $attribsRegex;

	/**
	 * Regular expression to match HTML/XML attribute pairs within a tag.
	 * Based on https://www.w3.org/TR/html5/syntax.html#before-attribute-name-state
	 * Used in Sanitizer::decodeTagAttributes
	 * @return string
	 */
	private static function getAttribsRegex() {
		if ( self::$attribsRegex === null ) {
			$spaceChars = '\x09\x0a\x0c\x0d\x20';
			$space = "[{$spaceChars}]";
			$attrib = "[^{$spaceChars}\/>=]";
			$attribFirst = "(?:{$attrib}|=)";
			self::$attribsRegex =
				"/({$attribFirst}{$attrib}*)
					($space*=$space*
					(?:
						# The attribute value: quoted or alone
						\"([^\"]*)(?:\"|\$)
						| '([^']*)(?:'|\$)
						| (((?!$space|>).)*)
					)
				)?/sxu";
		}
		return self::$attribsRegex;
	}

	/**
	 * Lazy-initialised attribute name regex, see getAttribNameRegex()
	 */
	private static $attribNameRegex;

	/**
	 * Used in Sanitizer::decodeTagAttributes to filter attributes.
	 * @return string
	 */
	private static function getAttribNameRegex() {
		if ( self::$attribNameRegex === null ) {
			$attribFirst = "[:_\p{L}\p{N}]";
			$attrib = "[:_\.\-\p{L}\p{N}]";
			self::$attribNameRegex = "/^({$attribFirst}{$attrib}*)$/sxu";
		}
		return self::$attribNameRegex;
	}

	/**
	 * Return the various lists of recognized tags
	 * @param array $extratags For any extra tags to include
	 * @param array $removetags For any tags (default or extra) to exclude
	 * @return array
	 */
	public static function getRecognizedTagData( $extratags = [], $removetags = [] ) {
		global $wgAllowImageTag;

		static $htmlpairsStatic, $htmlsingle, $htmlsingleonly, $htmlnest, $tabletags,
			$htmllist, $listtags, $htmlsingleallowed, $htmlelementsStatic, $staticInitialised;

		// Base our staticInitialised variable off of the global config state so that if the globals
		// are changed (like in the screwed up test system) we will re-initialise the settings.
		$globalContext = $wgAllowImageTag;
		if ( !$staticInitialised || $staticInitialised != $globalContext ) {
			$htmlpairsStatic = [ # Tags that must be closed
				'b', 'bdi', 'del', 'i', 'ins', 'u', 'font', 'big', 'small', 'sub', 'sup', 'h1',
				'h2', 'h3', 'h4', 'h5', 'h6', 'cite', 'code', 'em', 's',
				'strike', 'strong', 'tt', 'var', 'div', 'center',
				'blockquote', 'ol', 'ul', 'dl', 'table', 'caption', 'pre',
				'ruby', 'rb', 'rp', 'rt', 'rtc', 'p', 'span', 'abbr', 'dfn',
				'kbd', 'samp', 'data', 'time', 'mark'
			];
			$htmlsingle = [
				'br', 'wbr', 'hr', 'li', 'dt', 'dd', 'meta', 'link'
			];

			# Elements that cannot have close tags. This is (not coincidentally)
			# also the list of tags for which the HTML 5 parsing algorithm
			# requires you to "acknowledge the token's self-closing flag", i.e.
			# a self-closing tag like <br/> is not an HTML 5 parse error only
			# for this list.
			$htmlsingleonly = [
				'br', 'wbr', 'hr', 'meta', 'link'
			];

			$htmlnest = [ # Tags that can be nested--??
				'table', 'tr', 'td', 'th', 'div', 'blockquote', 'ol', 'ul',
				'li', 'dl', 'dt', 'dd', 'font', 'big', 'small', 'sub', 'sup', 'span',
				'var', 'kbd', 'samp', 'em', 'strong', 'q', 'ruby', 'bdo'
			];
			$tabletags = [ # Can only appear inside table, we will close them
				'td', 'th', 'tr',
			];
			$htmllist = [ # Tags used by list
				'ul', 'ol',
			];
			$listtags = [ # Tags that can appear in a list
				'li',
			];

			if ( $wgAllowImageTag ) {
				wfDeprecatedMsg( 'Setting $wgAllowImageTag to true ' .
					'is deprecated since MediaWiki 1.35', '1.35', false, false );
				$htmlsingle[] = 'img';
				$htmlsingleonly[] = 'img';
			}

			$htmlsingleallowed = array_unique( array_merge( $htmlsingle, $tabletags ) );
			$htmlelementsStatic = array_unique( array_merge( $htmlsingle, $htmlpairsStatic, $htmlnest ) );

			# Convert them all to hashtables for faster lookup
			$vars = [ 'htmlpairsStatic', 'htmlsingle', 'htmlsingleonly', 'htmlnest', 'tabletags',
				'htmllist', 'listtags', 'htmlsingleallowed', 'htmlelementsStatic' ];
			foreach ( $vars as $var ) {
				$$var = array_flip( $$var );
			}
			$staticInitialised = $globalContext;
		}

		# Populate $htmlpairs and $htmlelements with the $extratags and $removetags arrays
		$extratags = array_flip( $extratags );
		$removetags = array_flip( $removetags );
		$htmlpairs = array_merge( $extratags, $htmlpairsStatic );
		$htmlelements = array_diff_key( array_merge( $extratags, $htmlelementsStatic ), $removetags );

		return [
			'htmlpairs' => $htmlpairs,
			'htmlsingle' => $htmlsingle,
			'htmlsingleonly' => $htmlsingleonly,
			'htmlnest' => $htmlnest,
			'tabletags' => $tabletags,
			'htmllist' => $htmllist,
			'listtags' => $listtags,
			'htmlsingleallowed' => $htmlsingleallowed,
			'htmlelements' => $htmlelements,
		];
	}

	/**
	 * Cleans up HTML, removes dangerous tags and attributes, and
	 * removes HTML comments
	 * @param string $text
	 * @param callable|null $processCallback Callback to do any variable or parameter
	 *   replacements in HTML attribute values
	 * @param array|bool $args Arguments for the processing callback
	 * @param array $extratags For any extra tags to include
	 * @param array $removetags For any tags (default or extra) to exclude
	 * @return string
	 */
	public static function removeHTMLtags( $text, $processCallback = null,
		$args = [], $extratags = [], $removetags = []
	) {
		$tagData = self::getRecognizedTagData( $extratags, $removetags );
		$htmlpairs = $tagData['htmlpairs'];
		$htmlsingle = $tagData['htmlsingle'];
		$htmlsingleonly = $tagData['htmlsingleonly'];
		$htmlnest = $tagData['htmlnest'];
		$tabletags = $tagData['tabletags'];
		$htmllist = $tagData['htmllist'];
		$listtags = $tagData['listtags'];
		$htmlsingleallowed = $tagData['htmlsingleallowed'];
		$htmlelements = $tagData['htmlelements'];

		# Remove HTML comments
		$text = self::removeHTMLcomments( $text );
		$bits = explode( '<', $text );
		$text = str_replace( '>', '&gt;', array_shift( $bits ) );

		# this might be possible using remex tidy itself
		foreach ( $bits as $x ) {
			if ( preg_match( self::ELEMENT_BITS_REGEX, $x, $regs ) ) {
				list( /* $qbar */, $slash, $t, $params, $brace, $rest ) = $regs;

				$badtag = false;
				$t = strtolower( $t );
				if ( isset( $htmlelements[$t] ) ) {
					if ( is_callable( $processCallback ) ) {
						call_user_func_array( $processCallback, [ &$params, $args ] );
					}

					if ( $brace == '/>' && !( isset( $htmlsingle[$t] ) || isset( $htmlsingleonly[$t] ) ) ) {
						// Remove the self-closing slash, to be consistent
						// with HTML5 semantics. T134423
						$brace = '>';
					}
					if ( !self::validateTag( $params, $t ) ) {
						$badtag = true;
					}

					$newparams = self::fixTagAttributes( $params, $t );
					if ( !$badtag ) {
						if ( $brace === '/>' && !isset( $htmlsingleonly[$t] ) ) {
							# Interpret self-closing tags as empty tags even when
							# HTML 5 would interpret them as start tags. Such input
							# is commonly seen on Wikimedia wikis with this intention.
							$brace = "></$t>";
						}

						$rest = str_replace( '>', '&gt;', $rest );
						$text .= "<$slash$t$newparams$brace$rest";
						continue;
					}
				}
			}
			$text .= '&lt;' . str_replace( '>', '&gt;', $x );
		}
		return $text;
	}

	/**
	 * Remove '<!--', '-->', and everything between.
	 * To avoid leaving blank lines, when a comment is both preceded
	 * and followed by a newline (ignoring spaces), trim leading and
	 * trailing spaces and one of the newlines.
	 *
	 * @param string $text
	 * @return string
	 */
	public static function removeHTMLcomments( $text ) {
		while ( ( $start = strpos( $text, '<!--' ) ) !== false ) {
			$end = strpos( $text, '-->', $start + 4 );
			if ( $end === false ) {
				# Unterminated comment; bail out
				break;
			}

			$end += 3;

			# Trim space and newline if the comment is both
			# preceded and followed by a newline
			$spaceStart = max( $start - 1, 0 );
			$spaceLen = $end - $spaceStart;
			while ( substr( $text, $spaceStart, 1 ) === ' ' && $spaceStart > 0 ) {
				$spaceStart--;
				$spaceLen++;
			}
			while ( substr( $text, $spaceStart + $spaceLen, 1 ) === ' ' ) {
				$spaceLen++;
			}
			if ( substr( $text, $spaceStart, 1 ) === "\n"
				&& substr( $text, $spaceStart + $spaceLen, 1 ) === "\n" ) {
				# Remove the comment, leading and trailing
				# spaces, and leave only one newline.
				$text = substr_replace( $text, "\n", $spaceStart, $spaceLen + 1 );
			} else {
				# Remove just the comment.
				$text = substr_replace( $text, '', $start, $end - $start );
			}
		}
		return $text;
	}

	/**
	 * Takes attribute names and values for a tag and the tag name and
	 * validates that the tag is allowed to be present.
	 * This DOES NOT validate the attributes, nor does it validate the
	 * tags themselves. This method only handles the special circumstances
	 * where we may want to allow a tag within content but ONLY when it has
	 * specific attributes set.
	 *
	 * @param string $params
	 * @param string $element
	 * @return bool
	 */
	private static function validateTag( $params, $element ) {
		$params = self::decodeTagAttributes( $params );

		if ( $element == 'meta' || $element == 'link' ) {
			if ( !isset( $params['itemprop'] ) ) {
				// <meta> and <link> must have an itemprop="" otherwise they are not valid or safe in content
				return false;
			}
			if ( $element == 'meta' && !isset( $params['content'] ) ) {
				// <meta> must have a content="" for the itemprop
				return false;
			}
			if ( $element == 'link' && !isset( $params['href'] ) ) {
				// <link> must have an associated href=""
				return false;
			}
		}

		return true;
	}

	/**
	 * Take an array of attribute names and values and normalize or discard
	 * illegal values for the given element type.
	 *
	 * - Discards attributes not allowed for the given element
	 * - Unsafe style attributes are discarded
	 * - Invalid id attributes are re-encoded
	 *
	 * @param array $attribs
	 * @param string $element
	 * @return array
	 *
	 * @todo Check for legal values where the DTD limits things.
	 * @todo Check for unique id attribute :P
	 */
	public static function validateTagAttributes( $attribs, $element ) {
		return self::validateAttributes( $attribs,
			self::attributesAllowedInternal( $element ) );
	}

	/**
	 * Take an array of attribute names and values and normalize or discard
	 * illegal values.
	 *
	 * - Discards attributes not on the given list
	 * - Unsafe style attributes are discarded
	 * - Invalid id attributes are re-encoded
	 *
	 * @param array $attribs
	 * @param array $allowed List of allowed attribute names,
	 *   as an associative array where keys give valid attribute names
	 *   (since 1.34).  Before 1.35, passing a sequential array of
	 *   valid attribute names was permitted but that is now deprecated.
	 * @return array
	 *
	 * @todo Check for legal values where the DTD limits things.
	 * @todo Check for unique id attribute :P
	 */
	public static function validateAttributes( $attribs, $allowed ) {
		if ( isset( $allowed[0] ) ) {
			// Calling this function with a sequential array is
			// deprecated.  For now just convert it.
			wfDeprecated( __METHOD__ . ' with sequential array', '1.35' );
			$allowed = array_flip( $allowed );
		}
		$hrefExp = '/^(' . wfUrlProtocols() . ')[^\s]+$/';

		$out = [];
		foreach ( $attribs as $attribute => $value ) {
			# Allow XML namespace declaration to allow RDFa
			if ( preg_match( self::XMLNS_ATTRIBUTE_PATTERN, $attribute ) ) {
				if ( !preg_match( self::EVIL_URI_PATTERN, $value ) ) {
					$out[$attribute] = $value;
				}

				continue;
			}

			# Allow any attribute beginning with "data-"
			# However:
			# * Disallow data attributes used by MediaWiki code
			# * Ensure that the attribute is not namespaced by banning
			#   colons.
			if ( (
				!preg_match( '/^data-[^:]*$/i', $attribute ) &&
				!array_key_exists( $attribute, $allowed )
			) || self::isReservedDataAttribute( $attribute ) ) {
				continue;
			}

			# Strip javascript "expression" from stylesheets.
			# https://msdn.microsoft.com/en-us/library/ms537634.aspx
			if ( $attribute == 'style' ) {
				$value = self::checkCss( $value );
			}

			# Escape HTML id attributes
			if ( $attribute === 'id' ) {
				$value = self::escapeIdForAttribute( $value, self::ID_PRIMARY );
			}

			# Escape HTML id reference lists
			if ( $attribute === 'aria-describedby'
				|| $attribute === 'aria-flowto'
				|| $attribute === 'aria-labelledby'
				|| $attribute === 'aria-owns'
			) {
				$value = self::escapeIdReferenceListInternal( $value );
			}

			// RDFa and microdata properties allow URLs, URIs and/or CURIs.
			// Check them for sanity.
			if ( $attribute === 'rel' || $attribute === 'rev'
				# RDFa
				|| $attribute === 'about' || $attribute === 'property'
				|| $attribute === 'resource' || $attribute === 'datatype'
				|| $attribute === 'typeof'
				# HTML5 microdata
				|| $attribute === 'itemid' || $attribute === 'itemprop'
				|| $attribute === 'itemref' || $attribute === 'itemscope'
				|| $attribute === 'itemtype'
			) {
				// Paranoia. Allow "simple" values but suppress javascript
				if ( preg_match( self::EVIL_URI_PATTERN, $value ) ) {
					continue;
				}
			}

			# NOTE: even though elements using href/src are not allowed directly, supply
			#       validation code that can be used by tag hook handlers, etc
			if ( $attribute === 'href' || $attribute === 'src' || $attribute === 'poster' ) {
				if ( !preg_match( $hrefExp, $value ) ) {
					continue; // drop any href or src attributes not using an allowed protocol.
					// NOTE: this also drops all relative URLs
				}
			}

			if ( $attribute === 'tabindex' && $value !== '0' ) {
				// Only allow tabindex of 0, which is useful for accessibility.
				continue;
			}

			// If this attribute was previously set, override it.
			// Output should only have one attribute of each name.
			$out[$attribute] = $value;
		}

		# itemtype, itemid, itemref don't make sense without itemscope
		if ( !array_key_exists( 'itemscope', $out ) ) {
			unset( $out['itemtype'] );
			unset( $out['itemid'] );
			unset( $out['itemref'] );
		}
		# TODO: Strip itemprop if we aren't descendants of an itemscope or pointed to by an itemref.

		return $out;
	}

	/**
	 * Given an attribute name, checks whether it is a reserved data attribute
	 * (such as data-mw-foo) which is unavailable to user-generated HTML so MediaWiki
	 * core and extension code can safely use it to communicate with frontend code.
	 * @param string $attr Attribute name.
	 * @return bool
	 */
	public static function isReservedDataAttribute( $attr ) {
		// data-ooui is reserved for ooui.
		// data-mw and data-parsoid are reserved for parsoid.
		// data-mw-<name here> is reserved for extensions (or core) if
		// they need to communicate some data to the client and want to be
		// sure that it isn't coming from an untrusted user.
		// We ignore the possibility of namespaces since user-generated HTML
		// can't use them anymore.
		return (bool)preg_match( '/^data-(ooui|mw|parsoid)/i', $attr );
	}

	/**
	 * Merge two sets of HTML attributes.  Conflicting items in the second set
	 * will override those in the first, except for 'class' attributes which
	 * will be combined (if they're both strings).
	 *
	 * @todo implement merging for other attributes such as style
	 * @param array $a
	 * @param array $b
	 * @return array
	 */
	public static function mergeAttributes( $a, $b ) {
		$out = array_merge( $a, $b );
		if ( isset( $a['class'] ) && isset( $b['class'] )
			&& is_string( $a['class'] ) && is_string( $b['class'] )
			&& $a['class'] !== $b['class']
		) {
			$classes = preg_split( '/\s+/', "{$a['class']} {$b['class']}",
				-1, PREG_SPLIT_NO_EMPTY );
			$out['class'] = implode( ' ', array_unique( $classes ) );
		}
		return $out;
	}

	/**
	 * Normalize CSS into a format we can easily search for hostile input
	 *  - decode character references
	 *  - decode escape sequences
	 *  - remove comments, unless the entire value is one single comment
	 * @param string $value the css string
	 * @return string normalized css
	 */
	public static function normalizeCss( $value ) {
		// Decode character references like &#123;
		$value = self::decodeCharReferences( $value );

		// Decode escape sequences and line continuation
		// See the grammar in the CSS 2 spec, appendix D.
		// This has to be done AFTER decoding character references.
		// This means it isn't possible for this function to return
		// unsanitized escape sequences. It is possible to manufacture
		// input that contains character references that decode to
		// escape sequences that decode to character references, but
		// it's OK for the return value to contain character references
		// because the caller is supposed to escape those anyway.
		static $decodeRegex;
		if ( !$decodeRegex ) {
			$space = '[\\x20\\t\\r\\n\\f]';
			$nl = '(?:\\n|\\r\\n|\\r|\\f)';
			$backslash = '\\\\';
			$decodeRegex = "/ $backslash
				(?:
					($nl) |  # 1. Line continuation
					([0-9A-Fa-f]{1,6})$space? |  # 2. character number
					(.) | # 3. backslash cancelling special meaning
					() | # 4. backslash at end of string
				)/xu";
		}
		$value = preg_replace_callback( $decodeRegex,
			[ __CLASS__, 'cssDecodeCallback' ], $value );

		// Let the value through if it's nothing but a single comment, to
		// allow other functions which may reject it to pass some error
		// message through.
		if ( !preg_match( '! ^ \s* /\* [^*\\/]* \*/ \s* $ !x', $value ) ) {
			// Remove any comments; IE gets token splitting wrong
			// This must be done AFTER decoding character references and
			// escape sequences, because those steps can introduce comments
			// This step cannot introduce character references or escape
			// sequences, because it replaces comments with spaces rather
			// than removing them completely.
			$value = StringUtils::delimiterReplace( '/*', '*/', ' ', $value );

			// Remove anything after a comment-start token, to guard against
			// incorrect client implementations.
			$commentPos = strpos( $value, '/*' );
			if ( $commentPos !== false ) {
				$value = substr( $value, 0, $commentPos );
			}
		}

		return $value;
	}

	/**
	 * Pick apart some CSS and check it for forbidden or unsafe structures.
	 * Returns a sanitized string. This sanitized string will have
	 * character references and escape sequences decoded and comments
	 * stripped (unless it is itself one valid comment, in which case the value
	 * will be passed through). If the input is just too evil, only a comment
	 * complaining about evilness will be returned.
	 *
	 * Currently URL references, 'expression', 'tps' are forbidden.
	 *
	 * NOTE: Despite the fact that character references are decoded, the
	 * returned string may contain character references given certain
	 * clever input strings. These character references must
	 * be escaped before the return value is embedded in HTML.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function checkCss( $value ) {
		$value = self::normalizeCss( $value );

		// Reject problematic keywords and control characters
		if ( preg_match( '/[\000-\010\013\016-\037\177]/', $value ) ||
			strpos( $value, UtfNormal\Constants::UTF8_REPLACEMENT ) !== false ) {
			return '/* invalid control char */';
		} elseif ( preg_match(
			'! expression
				| filter\s*:
				| accelerator\s*:
				| -o-link\s*:
				| -o-link-source\s*:
				| -o-replace\s*:
				| url\s*\(
				| image\s*\(
				| image-set\s*\(
				| attr\s*\([^)]+[\s,]+url
				| var\s*\(
			!ix', $value ) ) {
			return '/* insecure input */';
		}
		return $value;
	}

	/**
	 * @param array $matches
	 * @return string
	 */
	private static function cssDecodeCallback( $matches ) {
		if ( $matches[1] !== '' ) {
			// Line continuation
			return '';
		} elseif ( $matches[2] !== '' ) {
			$char = UtfNormal\Utils::codepointToUtf8( hexdec( $matches[2] ) );
		} elseif ( $matches[3] !== '' ) {
			$char = $matches[3];
		} else {
			$char = '\\';
		}
		if ( $char == "\n" || $char == '"' || $char == "'" || $char == '\\' ) {
			// These characters need to be escaped in strings
			// Clean up the escape sequence to avoid parsing errors by clients
			return '\\' . dechex( ord( $char ) ) . ' ';
		} else {
			// Decode unnecessary escape
			return $char;
		}
	}

	/**
	 * Take a tag soup fragment listing an HTML element's attributes
	 * and normalize it to well-formed XML, discarding unwanted attributes.
	 * Output is safe for further wikitext processing, with escaping of
	 * values that could trigger problems.
	 *
	 * - Normalizes attribute names to lowercase
	 * - Discards attributes not allowed for the given element
	 * - Turns broken or invalid entities into plaintext
	 * - Double-quotes all attribute values
	 * - Attributes without values are given the name as attribute
	 * - Double attributes are discarded
	 * - Unsafe style attributes are discarded
	 * - Prepends space if there are attributes.
	 * - (Optionally) Sorts attributes by name.
	 *
	 * @param string $text
	 * @param string $element
	 * @param bool $sorted Whether to sort the attributes (default: false)
	 * @return string
	 */
	public static function fixTagAttributes( $text, $element, $sorted = false ) {
		if ( trim( $text ) == '' ) {
			return '';
		}

		$decoded = self::decodeTagAttributes( $text );
		$stripped = self::validateTagAttributes( $decoded, $element );

		if ( $sorted ) {
			ksort( $stripped );
		}

		return self::safeEncodeTagAttributes( $stripped );
	}

	/**
	 * Encode an attribute value for HTML output.
	 * @param string $text
	 * @return string HTML-encoded text fragment
	 */
	public static function encodeAttribute( $text ) {
		$encValue = htmlspecialchars( $text, ENT_QUOTES );

		// Whitespace is normalized during attribute decoding,
		// so if we've been passed non-spaces we must encode them
		// ahead of time or they won't be preserved.
		$encValue = strtr( $encValue, [
			"\n" => '&#10;',
			"\r" => '&#13;',
			"\t" => '&#9;',
		] );

		return $encValue;
	}

	/**
	 * Armor French spaces with a replacement character
	 *
	 * @since 1.32
	 * @param string $text Text to armor
	 * @param string $space Space character for the French spaces, defaults to '&#160;'
	 * @return string Armored text
	 */
	public static function armorFrenchSpaces( $text, $space = '&#160;' ) {
		// Replace $ with \$ and \ with \\
		$space = preg_replace( '#(?<!\\\\)(\\$|\\\\)#', '\\\\$1', $space );
		$fixtags = [
			# French spaces, last one Guillemet-left
			# only if there is something before the space
			# and a non-word character after the punctuation.
			'/(?<=\S) (?=[?:;!%»›](?!\w))/u' => "$space",
			# French spaces, Guillemet-right
			'/([«‹]) /u' => "\\1$space",
		];
		return preg_replace( array_keys( $fixtags ), array_values( $fixtags ), $text );
	}

	/**
	 * Encode an attribute value for HTML tags, with extra armoring
	 * against further wiki processing.
	 * @param string $text
	 * @return string HTML-encoded text fragment
	 */
	public static function safeEncodeAttribute( $text ) {
		$encValue = self::encodeAttribute( $text );

		# Templates and links may be expanded in later parsing,
		# creating invalid or dangerous output. Suppress this.
		$encValue = strtr( $encValue, [
			'<'    => '&lt;',   // This should never happen,
			'>'    => '&gt;',   // we've received invalid input
			'"'    => '&quot;', // which should have been escaped.
			'{'    => '&#123;',
			'}'    => '&#125;', // prevent unpaired language conversion syntax
			'['    => '&#91;',
			']'    => '&#93;',
			"''"   => '&#39;&#39;',
			'ISBN' => '&#73;SBN',
			'RFC'  => '&#82;FC',
			'PMID' => '&#80;MID',
			'|'    => '&#124;',
			'__'   => '&#95;_',
		] );

		# Armor against French spaces detection (T5158)
		$encValue = self::armorFrenchSpaces( $encValue, '&#32;' );

		# Stupid hack
		$encValue = preg_replace_callback(
			'/((?i)' . wfUrlProtocols() . ')/',
			function ( $matches ) {
				return str_replace( ':', '&#58;', $matches[1] );
			},
			$encValue );
		return $encValue;
	}

	/**
	 * Given a section name or other user-generated or otherwise unsafe string, escapes it to be
	 * a valid HTML id attribute.
	 *
	 * WARNING: The output of this function is not guaranteed to be HTML safe, so be sure to use
	 * proper escaping.
	 *
	 * @param string $id String to escape
	 * @param int $mode One of ID_* constants, specifying whether the primary or fallback encoding
	 *     should be used.
	 * @return string|bool Escaped ID or false if fallback encoding is requested but it's not
	 *     configured.
	 *
	 * @since 1.30
	 */
	public static function escapeIdForAttribute( $id, $mode = self::ID_PRIMARY ) {
		global $wgFragmentMode;

		if ( !isset( $wgFragmentMode[$mode] ) ) {
			if ( $mode === self::ID_PRIMARY ) {
				throw new UnexpectedValueException( '$wgFragmentMode is configured with no primary mode' );
			}
			return false;
		}

		$internalMode = $wgFragmentMode[$mode];

		return self::escapeIdInternal( $id, $internalMode );
	}

	/**
	 * Given a section name or other user-generated or otherwise unsafe string, escapes it to be
	 * a valid URL fragment.
	 *
	 * WARNING: The output of this function is not guaranteed to be HTML safe, so be sure to use
	 * proper escaping.
	 *
	 * @param string $id String to escape
	 * @return string Escaped ID
	 *
	 * @since 1.30
	 */
	public static function escapeIdForLink( $id ) {
		global $wgFragmentMode;

		if ( !isset( $wgFragmentMode[self::ID_PRIMARY] ) ) {
			throw new UnexpectedValueException( '$wgFragmentMode is configured with no primary mode' );
		}

		$mode = $wgFragmentMode[self::ID_PRIMARY];

		$id = self::escapeIdInternalUrl( $id, $mode );

		return $id;
	}

	/**
	 * Given a section name or other user-generated or otherwise unsafe string, escapes it to be
	 * a valid URL fragment for external interwikis.
	 *
	 * @param string $id String to escape
	 * @return string Escaped ID
	 *
	 * @since 1.30
	 */
	public static function escapeIdForExternalInterwiki( $id ) {
		global $wgExternalInterwikiFragmentMode;

		$id = self::escapeIdInternalUrl( $id, $wgExternalInterwikiFragmentMode );

		return $id;
	}

	/**
	 * Do percent encoding of percent signs for href (but not id) attributes
	 *
	 * @since 1.35
	 * @see https://phabricator.wikimedia.org/T238385
	 * @param string $id String to escape
	 * @param string $mode One of modes from $wgFragmentMode
	 * @return string
	 */
	private static function escapeIdInternalUrl( $id, $mode ) {
		$id = self::escapeIdInternal( $id, $mode );
		if ( $mode === 'html5' ) {
			$id = preg_replace( '/%([a-fA-F0-9]{2})/', '%25$1', $id );
		}
		return $id;
	}

	/**
	 * Helper for escapeIdFor*() functions. Performs most of the actual escaping.
	 *
	 * @param string $id String to escape
	 * @param string $mode One of modes from $wgFragmentMode
	 * @return string
	 */
	private static function escapeIdInternal( $id, $mode ) {
		// Truncate overly-long IDs.  This isn't an HTML limit, it's just
		// griefer protection. [T251506]
		$id = mb_substr( $id, 0, 1024 );

		switch ( $mode ) {
			case 'html5':
				// html5 spec says ids must not have any of the following:
				// U+0009 TAB, U+000A LF, U+000C FF, U+000D CR, or U+0020 SPACE
				// In practice, in wikitext, only tab, LF, CR (and SPACE) are
				// possible using either Lua or html entities.
				$id = str_replace( [ "\t", "\n", "\f", "\r", " " ], '_', $id );
				break;
			case 'legacy':
				// This corresponds to 'noninitial' mode of the former escapeId()
				static $replace = [
					'%3A' => ':',
					'%' => '.'
				];

				$id = urlencode( str_replace( ' ', '_', $id ) );
				$id = strtr( $id, $replace );
				break;
			default:
				throw new InvalidArgumentException( "Invalid mode '$mode' passed to '" . __METHOD__ );
		}

		return $id;
	}

	/**
	 * Given a string containing a space delimited list of ids, escape each id
	 * to match ids escaped by the escapeIdForAttribute() function.
	 *
	 * @since 1.27
	 * @deprecated since 1.36. Unused outside this class, will be made private.
	 *
	 * @param string $referenceString Space delimited list of ids
	 * @return string
	 */
	public static function escapeIdReferenceList( $referenceString ) {
		wfDeprecated( __METHOD__, '1.36' );
		return self::escapeIdReferenceListInternal( $referenceString );
	}

	/**
	 * Given a string containing a space delimited list of ids, escape each id
	 * to match ids escaped by the escapeIdForAttribute() function.
	 *
	 * @param string $referenceString Space delimited list of ids
	 * @return string
	 */
	private static function escapeIdReferenceListInternal( $referenceString ) {
		# Explode the space delimited list string into an array of tokens
		$references = preg_split( '/\s+/', "{$referenceString}", -1, PREG_SPLIT_NO_EMPTY );

		# Escape each token as an id
		foreach ( $references as &$ref ) {
			$ref = self::escapeIdForAttribute( $ref );
		}

		# Merge the array back to a space delimited list string
		# If the array is empty, the result will be an empty string ('')
		$referenceString = implode( ' ', $references );

		return $referenceString;
	}

	/**
	 * Given a value, escape it so that it can be used as a CSS class and
	 * return it.
	 *
	 * @todo For extra validity, input should be validated UTF-8.
	 *
	 * @see https://www.w3.org/TR/CSS21/syndata.html Valid characters/format
	 *
	 * @param string $class
	 * @return string
	 */
	public static function escapeClass( $class ) {
		// Convert ugly stuff to underscores and kill underscores in ugly places
		return rtrim( preg_replace(
			[ '/(^[0-9\\-])|[\\x00-\\x20!"#$%&\'()*+,.\\/:;<=>?@[\\]^`{|}~]|\\xC2\\xA0/', '/_+/' ],
			'_',
			$class ), '_' );
	}

	/**
	 * Given HTML input, escape with htmlspecialchars but un-escape entities.
	 * This allows (generally harmless) entities like &#160; to survive.
	 *
	 * @param string $html HTML to escape
	 * @return string Escaped input
	 */
	public static function escapeHtmlAllowEntities( $html ) {
		$html = self::decodeCharReferences( $html );
		# It seems wise to escape ' as well as ", as a matter of course.  Can't
		# hurt. Use ENT_SUBSTITUTE so that incorrectly truncated multibyte characters
		# don't cause the entire string to disappear.
		$html = htmlspecialchars( $html, ENT_QUOTES | ENT_SUBSTITUTE );
		return $html;
	}

	/**
	 * Return an associative array of attribute names and values from
	 * a partial tag string. Attribute names are forced to lowercase,
	 * character references are decoded to UTF-8 text.
	 *
	 * @param string $text
	 * @return array
	 */
	public static function decodeTagAttributes( $text ) {
		if ( trim( $text ) == '' ) {
			return [];
		}

		$pairs = [];
		if ( !preg_match_all(
			self::getAttribsRegex(),
			$text,
			$pairs,
			PREG_SET_ORDER ) ) {
			return [];
		}

		$attribs = [];
		foreach ( $pairs as $set ) {
			$attribute = strtolower( $set[1] );

			// Filter attribute names with unacceptable characters
			if ( !preg_match( self::getAttribNameRegex(), $attribute ) ) {
				continue;
			}

			$value = self::getTagAttributeCallback( $set );

			// Normalize whitespace
			$value = preg_replace( '/[\t\r\n ]+/', ' ', $value );
			$value = trim( $value );

			// Decode character references
			$attribs[$attribute] = self::decodeCharReferences( $value );
		}
		return $attribs;
	}

	/**
	 * Build a partial tag string from an associative array of attribute
	 * names and values as returned by decodeTagAttributes.
	 *
	 * @param array $assoc_array
	 * @return string
	 */
	public static function safeEncodeTagAttributes( $assoc_array ) {
		$attribs = [];
		foreach ( $assoc_array as $attribute => $value ) {
			$encAttribute = htmlspecialchars( $attribute );
			$encValue = self::safeEncodeAttribute( $value );

			$attribs[] = "$encAttribute=\"$encValue\"";
		}
		return count( $attribs ) ? ' ' . implode( ' ', $attribs ) : '';
	}

	/**
	 * Pick the appropriate attribute value from a match set from the
	 * attribs regex matches.
	 *
	 * @param array $set
	 * @throws MWException When tag conditions are not met.
	 * @return string
	 */
	private static function getTagAttributeCallback( $set ) {
		if ( isset( $set[5] ) ) {
			# No quotes.
			return $set[5];
		} elseif ( isset( $set[4] ) ) {
			# Single-quoted
			return $set[4];
		} elseif ( isset( $set[3] ) ) {
			# Double-quoted
			return $set[3];
		} elseif ( !isset( $set[2] ) ) {
			# In XHTML, attributes must have a value so return an empty string.
			# See "Empty attribute syntax",
			# https://www.w3.org/TR/html5/syntax.html#syntax-attribute-name
			return "";
		} else {
			throw new MWException( "Tag conditions not met. This should never happen and is a bug." );
		}
	}

	/**
	 * @param string $text
	 * @return string
	 */
	private static function normalizeWhitespace( $text ) {
		return trim( preg_replace(
			'/(?:\r\n|[\x20\x0d\x0a\x09])+/',
			' ',
			$text ) );
	}

	/**
	 * Normalizes whitespace in a section name, such as might be returned
	 * by Parser::stripSectionName(), for use in the id's that are used for
	 * section links.
	 *
	 * @param string $section
	 * @return string
	 */
	public static function normalizeSectionNameWhitespace( $section ) {
		return trim( preg_replace( '/[ _]+/', ' ', $section ) );
	}

	/**
	 * Ensure that any entities and character references are legal
	 * for XML and XHTML specifically. Any stray bits will be
	 * &amp;-escaped to result in a valid text fragment.
	 *
	 * a. named char refs can only be &lt; &gt; &amp; &quot;, others are
	 *   numericized (this way we're well-formed even without a DTD)
	 * b. any numeric char refs must be legal chars, not invalid or forbidden
	 * c. use lower cased "&#x", not "&#X"
	 * d. fix or reject non-valid attributes
	 *
	 * @param string $text
	 * @return string
	 * @internal
	 */
	public static function normalizeCharReferences( $text ) {
		return preg_replace_callback(
			self::CHAR_REFS_REGEX,
			[ self::class, 'normalizeCharReferencesCallback' ],
			$text );
	}

	/**
	 * @param string $matches
	 * @return string
	 */
	private static function normalizeCharReferencesCallback( $matches ) {
		$ret = null;
		if ( $matches[1] != '' ) {
			$ret = self::normalizeEntity( $matches[1] );
		} elseif ( $matches[2] != '' ) {
			$ret = self::decCharReference( $matches[2] );
		} elseif ( $matches[3] != '' ) {
			$ret = self::hexCharReference( $matches[3] );
		}
		if ( $ret === null ) {
			return htmlspecialchars( $matches[0] );
		} else {
			return $ret;
		}
	}

	/**
	 * If the named entity is defined in HTML5
	 * return the equivalent numeric entity reference (except for the core &lt;
	 * &gt; &amp; &quot;). If the entity is a MediaWiki-specific alias, returns
	 * the HTML equivalent. Otherwise, returns HTML-escaped text of
	 * pseudo-entity source (eg &amp;foo;)
	 *
	 * @param string $name Semicolon-terminated name
	 * @return string
	 */
	private static function normalizeEntity( $name ) {
		if ( isset( self::MW_ENTITY_ALIASES[$name] ) ) {
			// Non-standard MediaWiki-specific entities
			return '&' . self::MW_ENTITY_ALIASES[$name];
		} elseif ( in_array( $name, [ 'lt;', 'gt;', 'amp;', 'quot;' ], true ) ) {
			// Keep these in word form
			return "&$name";
		} elseif ( isset( HTMLData::$namedEntityTranslations[$name] ) ) {
			// Beware: some entities expand to more than 1 codepoint
			return preg_replace_callback( '/./Ssu', function ( $m ) {
				return '&#' . UtfNormal\Utils::utf8ToCodepoint( $m[0] ) . ';';
			}, HTMLData::$namedEntityTranslations[$name] );
		} else {
			return "&amp;$name";
		}
	}

	/**
	 * @param int $codepoint
	 * @return null|string
	 */
	private static function decCharReference( $codepoint ) {
		$point = intval( $codepoint );
		if ( self::validateCodepoint( $point ) ) {
			return sprintf( '&#%d;', $point );
		} else {
			return null;
		}
	}

	/**
	 * @param int $codepoint
	 * @return null|string
	 */
	private static function hexCharReference( $codepoint ) {
		$point = hexdec( $codepoint );
		if ( self::validateCodepoint( $point ) ) {
			return sprintf( '&#x%x;', $point );
		} else {
			return null;
		}
	}

	/**
	 * Returns true if a given Unicode codepoint is a valid character in
	 * both HTML5 and XML.
	 * @param int $codepoint
	 * @return bool
	 */
	private static function validateCodepoint( $codepoint ) {
		# U+000C is valid in HTML5 but not allowed in XML.
		# U+000D is valid in XML but not allowed in HTML5.
		# U+007F - U+009F are disallowed in HTML5 (control characters).
		return $codepoint == 0x09
			|| $codepoint == 0x0a
			|| ( $codepoint >= 0x20 && $codepoint <= 0x7e )
			|| ( $codepoint >= 0xa0 && $codepoint <= 0xd7ff )
			|| ( $codepoint >= 0xe000 && $codepoint <= 0xfffd )
			|| ( $codepoint >= 0x10000 && $codepoint <= 0x10ffff );
	}

	/**
	 * Decode any character references, numeric or named entities,
	 * in the text and return a UTF-8 string.
	 *
	 * @param string $text
	 * @return string
	 */
	public static function decodeCharReferences( $text ) {
		return preg_replace_callback(
			self::CHAR_REFS_REGEX,
			[ self::class, 'decodeCharReferencesCallback' ],
			$text );
	}

	/**
	 * Decode any character references, numeric or named entities,
	 * in the next and normalize the resulting string. (T16952)
	 *
	 * This is useful for page titles, not for text to be displayed,
	 * MediaWiki allows HTML entities to escape normalization as a feature.
	 *
	 * @param string $text Already normalized, containing entities
	 * @return string Still normalized, without entities
	 */
	public static function decodeCharReferencesAndNormalize( $text ) {
		$text = preg_replace_callback(
			self::CHAR_REFS_REGEX,
			[ self::class, 'decodeCharReferencesCallback' ],
			$text,
			-1, // limit
			$count
		);

		if ( $count ) {
			return MediaWikiServices::getInstance()->getContentLanguage()->normalize( $text );
		} else {
			return $text;
		}
	}

	/**
	 * @param string $matches
	 * @return string
	 */
	private static function decodeCharReferencesCallback( $matches ) {
		if ( $matches[1] != '' ) {
			return self::decodeEntity( $matches[1] );
		} elseif ( $matches[2] != '' ) {
			return self::decodeChar( intval( $matches[2] ) );
		} elseif ( $matches[3] != '' ) {
			return self::decodeChar( hexdec( $matches[3] ) );
		}
		# Last case should be an ampersand by itself
		return $matches[0];
	}

	/**
	 * Return UTF-8 string for a codepoint if that is a valid
	 * character reference, otherwise U+FFFD REPLACEMENT CHARACTER.
	 * @param int $codepoint
	 * @return string
	 * @internal
	 */
	private static function decodeChar( $codepoint ) {
		if ( self::validateCodepoint( $codepoint ) ) {
			return UtfNormal\Utils::codepointToUtf8( $codepoint );
		} else {
			return UtfNormal\Constants::UTF8_REPLACEMENT;
		}
	}

	/**
	 * If the named entity is defined in HTML5
	 * return the UTF-8 encoding of that character. Otherwise, returns
	 * pseudo-entity source (eg "&foo;")
	 *
	 * @param string $name Semicolon-terminated entity name
	 * @return string
	 */
	private static function decodeEntity( $name ) {
		// These are MediaWiki-specific entities, not in the HTML standard
		if ( isset( self::MW_ENTITY_ALIASES[$name] ) ) {
			$name = self::MW_ENTITY_ALIASES[$name];
		}
		$trans = HTMLData::$namedEntityTranslations[$name] ?? null;
		return $trans ?? "&$name";
	}

	/**
	 * Fetch the list of acceptable attributes for a given element name.
	 *
	 * @param string $element
	 * @return array An associative array where keys are acceptable attribute
	 *   names
	 */
	private static function attributesAllowedInternal( $element ) {
		$list = self::setupAttributesAllowedInternal();
		return $list[$element] ?? [];
	}

	/**
	 * Foreach array key (an allowed HTML element), return an array
	 * of allowed attributes.
	 * @return array An associative array: keys are HTML element names;
	 *   values are associative arrays where the keys are allowed attribute
	 *   names.
	 */
	private static function setupAttributesAllowedInternal() {
		static $allowed;

		if ( $allowed !== null ) {
			return $allowed;
		}

		// For lookup efficiency flip each attributes array so the keys are
		// the valid attributes.
		$merge = function ( $a, $b, $c = [] ) {
			return array_merge( $a, array_flip( $b ), array_flip( $c ) );
		};
		$common = $merge( [], [
			# HTML
			'id',
			'class',
			'style',
			'lang',
			'dir',
			'title',
			'tabindex',

			# WAI-ARIA
			'aria-describedby',
			'aria-flowto',
			'aria-hidden',
			'aria-label',
			'aria-labelledby',
			'aria-owns',
			'role',

			# RDFa
			# These attributes are specified in section 9 of
			# https://www.w3.org/TR/2008/REC-rdfa-syntax-20081014
			'about',
			'property',
			'resource',
			'datatype',
			'typeof',

			# Microdata. These are specified by
			# https://html.spec.whatwg.org/multipage/microdata.html#the-microdata-model
			'itemid',
			'itemprop',
			'itemref',
			'itemscope',
			'itemtype',
		] );

		$block = $merge( $common, [ 'align' ] );

		$tablealign = [ 'align', 'valign' ];
		$tablecell = [
			'abbr',
			'axis',
			'headers',
			'scope',
			'rowspan',
			'colspan',
			'nowrap', # deprecated
			'width', # deprecated
			'height', # deprecated
			'bgcolor', # deprecated
		];

		# Numbers refer to sections in HTML 4.01 standard describing the element.
		# See: https://www.w3.org/TR/html4/
		$allowed = [
			# 7.5.4
			'div'        => $block,
			'center'     => $common, # deprecated
			'span'       => $common,

			# 7.5.5
			'h1'         => $block,
			'h2'         => $block,
			'h3'         => $block,
			'h4'         => $block,
			'h5'         => $block,
			'h6'         => $block,

			# 7.5.6
			# address

			# 8.2.4
			'bdo'        => $common,

			# 9.2.1
			'em'         => $common,
			'strong'     => $common,
			'cite'       => $common,
			'dfn'        => $common,
			'code'       => $common,
			'samp'       => $common,
			'kbd'        => $common,
			'var'        => $common,
			'abbr'       => $common,
			# acronym

			# 9.2.2
			'blockquote' => $merge( $common, [ 'cite' ] ),
			'q'          => $merge( $common, [ 'cite' ] ),

			# 9.2.3
			'sub'        => $common,
			'sup'        => $common,

			# 9.3.1
			'p'          => $block,

			# 9.3.2
			'br'         => $merge( $common, [ 'clear' ] ),

			# https://www.w3.org/TR/html5/text-level-semantics.html#the-wbr-element
			'wbr'        => $common,

			# 9.3.4
			'pre'        => $merge( $common, [ 'width' ] ),

			# 9.4
			'ins'        => $merge( $common, [ 'cite', 'datetime' ] ),
			'del'        => $merge( $common, [ 'cite', 'datetime' ] ),

			# 10.2
			'ul'         => $merge( $common, [ 'type' ] ),
			'ol'         => $merge( $common, [ 'type', 'start', 'reversed' ] ),
			'li'         => $merge( $common, [ 'type', 'value' ] ),

			# 10.3
			'dl'         => $common,
			'dd'         => $common,
			'dt'         => $common,

			# 11.2.1
			'table'      => $merge( $common,
								[ 'summary', 'width', 'border', 'frame',
										'rules', 'cellspacing', 'cellpadding',
										'align', 'bgcolor',
								] ),

			# 11.2.2
			'caption'    => $block,

			# 11.2.3
			'thead'      => $common,
			'tfoot'      => $common,
			'tbody'      => $common,

			# 11.2.4
			'colgroup'   => $merge( $common, [ 'span' ] ),
			'col'        => $merge( $common, [ 'span' ] ),

			# 11.2.5
			'tr'         => $merge( $common, [ 'bgcolor' ], $tablealign ),

			# 11.2.6
			'td'         => $merge( $common, $tablecell, $tablealign ),
			'th'         => $merge( $common, $tablecell, $tablealign ),

			# 12.2
			# NOTE: <a> is not allowed directly, but this list of allowed
			# attributes is used from the Parser object
			'a'          => $merge( $common, [ 'href', 'rel', 'rev' ] ), # rel/rev esp. for RDFa

			# 13.2
			# Not usually allowed, but may be used for extension-style hooks
			# such as <math> when it is rasterized, or if $wgAllowImageTag is
			# true
			'img'        => $merge( $common, [ 'alt', 'src', 'width', 'height', 'srcset' ] ),
			# Attributes for A/V tags added in T163583 / T133673
			'audio'      => $merge( $common, [ 'controls', 'preload', 'width', 'height' ] ),
			'video'      => $merge( $common, [ 'poster', 'controls', 'preload', 'width', 'height' ] ),
			'source'     => $merge( $common, [ 'type', 'src' ] ),
			'track'      => $merge( $common, [ 'type', 'src', 'srclang', 'kind', 'label' ] ),

			# 15.2.1
			'tt'         => $common,
			'b'          => $common,
			'i'          => $common,
			'big'        => $common,
			'small'      => $common,
			'strike'     => $common,
			's'          => $common,
			'u'          => $common,

			# 15.2.2
			'font'       => $merge( $common, [ 'size', 'color', 'face' ] ),
			# basefont

			# 15.3
			'hr'         => $merge( $common, [ 'width' ] ),

			# HTML Ruby annotation text module, simple ruby only.
			# https://www.w3.org/TR/html5/text-level-semantics.html#the-ruby-element
			'ruby'       => $common,
			# rbc
			'rb'         => $common,
			'rp'         => $common,
			'rt'         => $common, # $merge( $common, [ 'rbspan' ] ),
			'rtc'        => $common,

			# MathML root element, where used for extensions
			# 'title' may not be 100% valid here; it's XHTML
			# https://www.w3.org/TR/REC-MathML/
			'math'       => $merge( [], [ 'class', 'style', 'id', 'title' ] ),

			// HTML 5 section 4.5
			'figure'     => $common,
			'figcaption' => $common,

			# HTML 5 section 4.6
			'bdi' => $common,

			# HTML5 elements, defined by:
			# https://html.spec.whatwg.org/multipage/semantics.html#the-data-element
			'data' => $merge( $common, [ 'value' ] ),
			'time' => $merge( $common, [ 'datetime' ] ),
			'mark' => $common,

			// meta and link are only permitted by removeHTMLtags when Microdata
			// is enabled so we don't bother adding a conditional to hide these
			// Also meta and link are only valid in WikiText as Microdata elements
			// (ie: validateTag rejects tags missing the attributes needed for Microdata)
			// So we don't bother including $common attributes that have no purpose.
			'meta' => $merge( [], [ 'itemprop', 'content' ] ),
			'link' => $merge( [], [ 'itemprop', 'href', 'title' ] ),
		];

		return $allowed;
	}

	/**
	 * Take a fragment of (potentially invalid) HTML and return
	 * a version with any tags removed, encoded as plain text.
	 *
	 * Warning: this return value must be further escaped for literal
	 * inclusion in HTML output as of 1.10!
	 *
	 * @param string $html HTML fragment
	 * @return string
	 * @return-taint tainted
	 */
	public static function stripAllTags( $html ) {
		// Use RemexHtml to tokenize $html and extract the text
		$handler = new RemexStripTagHandler;
		$tokenizer = new RemexHtml\Tokenizer\Tokenizer( $handler, $html, [
			'ignoreErrors' => true,
			// don't ignore char refs, we want them to be decoded
			'ignoreNulls' => true,
			'skipPreprocess' => true,
		] );
		$tokenizer->execute();
		$text = $handler->getResult();

		$text = self::normalizeWhitespace( $text );
		return $text;
	}

	/**
	 * Hack up a private DOCTYPE with HTML's standard entity declarations.
	 * PHP 4 seemed to know these if you gave it an HTML doctype, but
	 * PHP 5.1 doesn't.
	 *
	 * Use for passing XHTML fragments to PHP's XML parsing functions
	 *
	 * @return string
	 * @deprecated since 1.36; will be made private or removed in a future
	 *    release.
	 */
	public static function hackDocType() {
		$out = "<!DOCTYPE html [\n";
		foreach ( HTMLData::$namedEntityTranslations as $entity => $translation ) {
			if ( substr( $entity, -1 ) !== ';' ) {
				// Some HTML entities omit the trailing semicolon;
				// wikitext does not permit these.
				continue;
			}
			$name = substr( $entity, 0, -1 );
			$expansion = self::normalizeEntity( $entity );
			if ( $entity === $expansion ) {
				// Skip &lt; &gt; etc
				continue;
			}
			$out .= "<!ENTITY $name \"$expansion\">";
		}
		$out .= "]>\n";
		return $out;
	}

	/**
	 * @param string $url
	 * @return mixed|string
	 */
	public static function cleanUrl( $url ) {
		# Normalize any HTML entities in input. They will be
		# re-escaped by makeExternalLink().
		$url = self::decodeCharReferences( $url );

		# Escape any control characters introduced by the above step
		$url = preg_replace_callback( '/[\][<>"\\x00-\\x20\\x7F\|]/',
			[ __CLASS__, 'cleanUrlCallback' ], $url );

		# Validate hostname portion
		$matches = [];
		if ( preg_match( '!^([^:]+:)(//[^/]+)?(.*)$!iD', $url, $matches ) ) {
			list( /* $whole */, $protocol, $host, $rest ) = $matches;

			// Characters that will be ignored in IDNs.
			// https://tools.ietf.org/html/rfc3454#section-3.1
			// Strip them before further processing so deny lists and such work.
			$strip = "/
				\\s|          # general whitespace
				\xc2\xad|     # 00ad SOFT HYPHEN
				\xe1\xa0\x86| # 1806 MONGOLIAN TODO SOFT HYPHEN
				\xe2\x80\x8b| # 200b ZERO WIDTH SPACE
				\xe2\x81\xa0| # 2060 WORD JOINER
				\xef\xbb\xbf| # feff ZERO WIDTH NO-BREAK SPACE
				\xcd\x8f|     # 034f COMBINING GRAPHEME JOINER
				\xe1\xa0\x8b| # 180b MONGOLIAN FREE VARIATION SELECTOR ONE
				\xe1\xa0\x8c| # 180c MONGOLIAN FREE VARIATION SELECTOR TWO
				\xe1\xa0\x8d| # 180d MONGOLIAN FREE VARIATION SELECTOR THREE
				\xe2\x80\x8c| # 200c ZERO WIDTH NON-JOINER
				\xe2\x80\x8d| # 200d ZERO WIDTH JOINER
				[\xef\xb8\x80-\xef\xb8\x8f] # fe00-fe0f VARIATION SELECTOR-1-16
				/xuD";

			$host = preg_replace( $strip, '', $host );

			// IPv6 host names are bracketed with [].  Url-decode these.
			if ( substr_compare( "//%5B", $host, 0, 5 ) === 0 &&
				preg_match( '!^//%5B([0-9A-Fa-f:.]+)%5D((:\d+)?)$!', $host, $matches )
			) {
				$host = '//[' . $matches[1] . ']' . $matches[2];
			}

			// @todo FIXME: Validate hostnames here

			return $protocol . $host . $rest;
		} else {
			return $url;
		}
	}

	/**
	 * @param array $matches
	 * @return string
	 */
	private static function cleanUrlCallback( $matches ) {
		return urlencode( $matches[0] );
	}

	/**
	 * Does a string look like an e-mail address?
	 *
	 * This validates an email address using an HTML5 specification found at:
	 * http://www.whatwg.org/html/states-of-the-type-attribute.html#valid-e-mail-address
	 * Which as of 2011-01-24 says:
	 *
	 *   A valid e-mail address is a string that matches the ABNF production
	 *   1*( atext / "." ) "@" ldh-str *( "." ldh-str ) where atext is defined
	 *   in RFC 5322 section 3.2.3, and ldh-str is defined in RFC 1034 section
	 *   3.5.
	 *
	 * This function is an implementation of the specification as requested in
	 * T24449.
	 *
	 * Client-side forms will use the same standard validation rules via JS or
	 * HTML 5 validation; additional restrictions can be enforced server-side
	 * by extensions via the 'isValidEmailAddr' hook.
	 *
	 * Note that this validation doesn't 100% match RFC 2822, but is believed
	 * to be liberal enough for wide use. Some invalid addresses will still
	 * pass validation here.
	 *
	 * @since 1.18
	 *
	 * @param string $addr E-mail address
	 * @return bool
	 */
	public static function validateEmail( $addr ) {
		$result = null;
		if ( !Hooks::runner()->onIsValidEmailAddr( $addr, $result ) ) {
			return $result;
		}

		// Please note strings below are enclosed in brackets [], this make the
		// hyphen "-" a range indicator. Hence it is double backslashed below.
		// See T28948
		$rfc5322_atext = "a-z0-9!#$%&'*+\\-\/=?^_`{|}~";
		$rfc1034_ldh_str = "a-z0-9\\-";

		$html5_email_regexp = "/
		^                      # start of string
		[$rfc5322_atext\\.]+    # user part which is liberal :p
		@                      # 'apostrophe'
		[$rfc1034_ldh_str]+       # First domain part
		(\\.[$rfc1034_ldh_str]+)*  # Following part prefixed with a dot
		$                      # End of string
		/ix"; // case Insensitive, eXtended

		return (bool)preg_match( $html5_email_regexp, $addr );
	}
}
