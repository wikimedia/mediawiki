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

/**
 * HTML sanitizer for MediaWiki
 * @ingroup Parser
 */
class Sanitizer {
	/**
	 * Regular expression to match various types of character references in
	 * Sanitizer::normalizeCharReferences and Sanitizer::decodeCharReferences
	 */
	const CHAR_REFS_REGEX =
		'/&([A-Za-z0-9\x80-\xff]+);
		 |&\#([0-9]+);
		 |&\#[xX]([0-9A-Fa-f]+);
		 |(&)/x';

	/**
	 * Acceptable tag name charset from HTML5 parsing spec
	 * https://www.w3.org/TR/html5/syntax.html#tag-open-state
	 */
	const ELEMENT_BITS_REGEX = '!^(/?)([A-Za-z][^\t\n\v />\0]*+)([^>]*?)(/?>)([^<]*)$!';

	/**
	 * Blacklist for evil uris like javascript:
	 * WARNING: DO NOT use this in any place that actually requires blacklisting
	 * for security reasons. There are NUMEROUS[1] ways to bypass blacklisting, the
	 * only way to be secure from javascript: uri based xss vectors is to whitelist
	 * things that you know are safe and deny everything else.
	 * [1]: http://ha.ckers.org/xss.html
	 */
	const EVIL_URI_PATTERN = '!(^|\s|\*/\s*)(javascript|vbscript)([^\w]|$)!i';
	const XMLNS_ATTRIBUTE_PATTERN = "/^xmlns:[:A-Z_a-z-.0-9]+$/";

	/**
	 * Tells escapeUrlForHtml() to encode the ID using the wiki's primary encoding.
	 *
	 * @since 1.30
	 */
	const ID_PRIMARY = 0;

	/**
	 * Tells escapeUrlForHtml() to encode the ID using the fallback encoding, or return false
	 * if no fallback is configured.
	 *
	 * @since 1.30
	 */
	const ID_FALLBACK = 1;

	/**
	 * List of all named character entities defined in HTML 4.01
	 * https://www.w3.org/TR/html4/sgml/entities.html
	 * As well as &apos; which is only defined starting in XHTML1.
	 */
	private static $htmlEntities = [
		'Aacute'   => 193,
		'aacute'   => 225,
		'Acirc'    => 194,
		'acirc'    => 226,
		'acute'    => 180,
		'AElig'    => 198,
		'aelig'    => 230,
		'Agrave'   => 192,
		'agrave'   => 224,
		'alefsym'  => 8501,
		'Alpha'    => 913,
		'alpha'    => 945,
		'amp'      => 38,
		'and'      => 8743,
		'ang'      => 8736,
		'apos'     => 39, // New in XHTML & HTML 5; avoid in output for compatibility with IE.
		'Aring'    => 197,
		'aring'    => 229,
		'asymp'    => 8776,
		'Atilde'   => 195,
		'atilde'   => 227,
		'Auml'     => 196,
		'auml'     => 228,
		'bdquo'    => 8222,
		'Beta'     => 914,
		'beta'     => 946,
		'brvbar'   => 166,
		'bull'     => 8226,
		'cap'      => 8745,
		'Ccedil'   => 199,
		'ccedil'   => 231,
		'cedil'    => 184,
		'cent'     => 162,
		'Chi'      => 935,
		'chi'      => 967,
		'circ'     => 710,
		'clubs'    => 9827,
		'cong'     => 8773,
		'copy'     => 169,
		'crarr'    => 8629,
		'cup'      => 8746,
		'curren'   => 164,
		'dagger'   => 8224,
		'Dagger'   => 8225,
		'darr'     => 8595,
		'dArr'     => 8659,
		'deg'      => 176,
		'Delta'    => 916,
		'delta'    => 948,
		'diams'    => 9830,
		'divide'   => 247,
		'Eacute'   => 201,
		'eacute'   => 233,
		'Ecirc'    => 202,
		'ecirc'    => 234,
		'Egrave'   => 200,
		'egrave'   => 232,
		'empty'    => 8709,
		'emsp'     => 8195,
		'ensp'     => 8194,
		'Epsilon'  => 917,
		'epsilon'  => 949,
		'equiv'    => 8801,
		'Eta'      => 919,
		'eta'      => 951,
		'ETH'      => 208,
		'eth'      => 240,
		'Euml'     => 203,
		'euml'     => 235,
		'euro'     => 8364,
		'exist'    => 8707,
		'fnof'     => 402,
		'forall'   => 8704,
		'frac12'   => 189,
		'frac14'   => 188,
		'frac34'   => 190,
		'frasl'    => 8260,
		'Gamma'    => 915,
		'gamma'    => 947,
		'ge'       => 8805,
		'gt'       => 62,
		'harr'     => 8596,
		'hArr'     => 8660,
		'hearts'   => 9829,
		'hellip'   => 8230,
		'Iacute'   => 205,
		'iacute'   => 237,
		'Icirc'    => 206,
		'icirc'    => 238,
		'iexcl'    => 161,
		'Igrave'   => 204,
		'igrave'   => 236,
		'image'    => 8465,
		'infin'    => 8734,
		'int'      => 8747,
		'Iota'     => 921,
		'iota'     => 953,
		'iquest'   => 191,
		'isin'     => 8712,
		'Iuml'     => 207,
		'iuml'     => 239,
		'Kappa'    => 922,
		'kappa'    => 954,
		'Lambda'   => 923,
		'lambda'   => 955,
		'lang'     => 9001,
		'laquo'    => 171,
		'larr'     => 8592,
		'lArr'     => 8656,
		'lceil'    => 8968,
		'ldquo'    => 8220,
		'le'       => 8804,
		'lfloor'   => 8970,
		'lowast'   => 8727,
		'loz'      => 9674,
		'lrm'      => 8206,
		'lsaquo'   => 8249,
		'lsquo'    => 8216,
		'lt'       => 60,
		'macr'     => 175,
		'mdash'    => 8212,
		'micro'    => 181,
		'middot'   => 183,
		'minus'    => 8722,
		'Mu'       => 924,
		'mu'       => 956,
		'nabla'    => 8711,
		'nbsp'     => 160,
		'ndash'    => 8211,
		'ne'       => 8800,
		'ni'       => 8715,
		'not'      => 172,
		'notin'    => 8713,
		'nsub'     => 8836,
		'Ntilde'   => 209,
		'ntilde'   => 241,
		'Nu'       => 925,
		'nu'       => 957,
		'Oacute'   => 211,
		'oacute'   => 243,
		'Ocirc'    => 212,
		'ocirc'    => 244,
		'OElig'    => 338,
		'oelig'    => 339,
		'Ograve'   => 210,
		'ograve'   => 242,
		'oline'    => 8254,
		'Omega'    => 937,
		'omega'    => 969,
		'Omicron'  => 927,
		'omicron'  => 959,
		'oplus'    => 8853,
		'or'       => 8744,
		'ordf'     => 170,
		'ordm'     => 186,
		'Oslash'   => 216,
		'oslash'   => 248,
		'Otilde'   => 213,
		'otilde'   => 245,
		'otimes'   => 8855,
		'Ouml'     => 214,
		'ouml'     => 246,
		'para'     => 182,
		'part'     => 8706,
		'permil'   => 8240,
		'perp'     => 8869,
		'Phi'      => 934,
		'phi'      => 966,
		'Pi'       => 928,
		'pi'       => 960,
		'piv'      => 982,
		'plusmn'   => 177,
		'pound'    => 163,
		'prime'    => 8242,
		'Prime'    => 8243,
		'prod'     => 8719,
		'prop'     => 8733,
		'Psi'      => 936,
		'psi'      => 968,
		'quot'     => 34,
		'radic'    => 8730,
		'rang'     => 9002,
		'raquo'    => 187,
		'rarr'     => 8594,
		'rArr'     => 8658,
		'rceil'    => 8969,
		'rdquo'    => 8221,
		'real'     => 8476,
		'reg'      => 174,
		'rfloor'   => 8971,
		'Rho'      => 929,
		'rho'      => 961,
		'rlm'      => 8207,
		'rsaquo'   => 8250,
		'rsquo'    => 8217,
		'sbquo'    => 8218,
		'Scaron'   => 352,
		'scaron'   => 353,
		'sdot'     => 8901,
		'sect'     => 167,
		'shy'      => 173,
		'Sigma'    => 931,
		'sigma'    => 963,
		'sigmaf'   => 962,
		'sim'      => 8764,
		'spades'   => 9824,
		'sub'      => 8834,
		'sube'     => 8838,
		'sum'      => 8721,
		'sup'      => 8835,
		'sup1'     => 185,
		'sup2'     => 178,
		'sup3'     => 179,
		'supe'     => 8839,
		'szlig'    => 223,
		'Tau'      => 932,
		'tau'      => 964,
		'there4'   => 8756,
		'Theta'    => 920,
		'theta'    => 952,
		'thetasym' => 977,
		'thinsp'   => 8201,
		'THORN'    => 222,
		'thorn'    => 254,
		'tilde'    => 732,
		'times'    => 215,
		'trade'    => 8482,
		'Uacute'   => 218,
		'uacute'   => 250,
		'uarr'     => 8593,
		'uArr'     => 8657,
		'Ucirc'    => 219,
		'ucirc'    => 251,
		'Ugrave'   => 217,
		'ugrave'   => 249,
		'uml'      => 168,
		'upsih'    => 978,
		'Upsilon'  => 933,
		'upsilon'  => 965,
		'Uuml'     => 220,
		'uuml'     => 252,
		'weierp'   => 8472,
		'Xi'       => 926,
		'xi'       => 958,
		'Yacute'   => 221,
		'yacute'   => 253,
		'yen'      => 165,
		'Yuml'     => 376,
		'yuml'     => 255,
		'Zeta'     => 918,
		'zeta'     => 950,
		'zwj'      => 8205,
		'zwnj'     => 8204
	];

	/**
	 * Character entity aliases accepted by MediaWiki
	 */
	private static $htmlEntityAliases = [
		'רלמ' => 'rlm',
		'رلم' => 'rlm',
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
	static function getAttribsRegex() {
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
	static function getAttribNameRegex() {
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
	 * @param callable|null $warnCallback (Deprecated) Callback allowing the
	 *   addition of a tracking category when bad input is encountered.
	 *   DO NOT ADD NEW PARAMETERS AFTER $warnCallback, since it will be
	 *   removed shortly.
	 * @return string
	 */
	public static function removeHTMLtags( $text, $processCallback = null,
		$args = [], $extratags = [], $removetags = [], $warnCallback = null
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
		if ( !MWTidy::isEnabled() ) {
			wfDeprecated( 'disabling tidy', '1.33' );
			$tagstack = $tablestack = [];
			foreach ( $bits as $x ) {
				$regs = [];
				# $slash: Does the current element start with a '/'?
				# $t: Current element name
				# $params: String between element name and >
				# $brace: Ending '>' or '/>'
				# $rest: Everything until the next element of $bits
				if ( preg_match( self::ELEMENT_BITS_REGEX, $x, $regs ) ) {
					list( /* $qbar */, $slash, $t, $params, $brace, $rest ) = $regs;
				} else {
					$slash = $t = $params = $brace = $rest = null;
				}

				$badtag = false;
				$t = strtolower( $t );
				if ( isset( $htmlelements[$t] ) ) {
					# Check our stack
					if ( $slash && isset( $htmlsingleonly[$t] ) ) {
						$badtag = true;
					} elseif ( $slash ) {
						# Closing a tag... is it the one we just opened?
						Wikimedia\suppressWarnings();
						$ot = array_pop( $tagstack );
						Wikimedia\restoreWarnings();

						if ( $ot != $t ) {
							if ( isset( $htmlsingleallowed[$ot] ) ) {
								# Pop all elements with an optional close tag
								# and see if we find a match below them
								$optstack = [];
								array_push( $optstack, $ot );
								Wikimedia\suppressWarnings();
								$ot = array_pop( $tagstack );
								Wikimedia\restoreWarnings();
								while ( $ot != $t && isset( $htmlsingleallowed[$ot] ) ) {
									array_push( $optstack, $ot );
									Wikimedia\suppressWarnings();
									$ot = array_pop( $tagstack );
									Wikimedia\restoreWarnings();
								}
								if ( $t != $ot ) {
									# No match. Push the optional elements back again
									$badtag = true;
									Wikimedia\suppressWarnings();
									$ot = array_pop( $optstack );
									Wikimedia\restoreWarnings();
									while ( $ot ) {
										array_push( $tagstack, $ot );
										Wikimedia\suppressWarnings();
										$ot = array_pop( $optstack );
										Wikimedia\restoreWarnings();
									}
								}
							} else {
								Wikimedia\suppressWarnings();
								array_push( $tagstack, $ot );
								Wikimedia\restoreWarnings();

								# <li> can be nested in <ul> or <ol>, skip those cases:
								if ( !isset( $htmllist[$ot] ) || !isset( $listtags[$t] ) ) {
									$badtag = true;
								}
							}
						} elseif ( $t == 'table' ) {
							$tagstack = array_pop( $tablestack );
						}
						$newparams = '';
					} else {
						# Keep track for later
						if ( isset( $tabletags[$t] ) && !in_array( 'table', $tagstack ) ) {
							$badtag = true;
						} elseif ( in_array( $t, $tagstack ) && !isset( $htmlnest[$t] ) ) {
							$badtag = true;
						#  Is it a self closed htmlpair ? (T7487)
						} elseif ( $brace == '/>' && isset( $htmlpairs[$t] ) ) {
							// Eventually we'll just remove the self-closing
							// slash, in order to be consistent with HTML5
							// semantics.
							// $brace = '>';
							// For now, let's just warn authors to clean up.
							if ( is_callable( $warnCallback ) ) {
								call_user_func_array( $warnCallback, [ 'deprecated-self-close-category' ] );
							}
							$badtag = true;
						} elseif ( isset( $htmlsingleonly[$t] ) ) {
							# Hack to force empty tag for unclosable elements
							$brace = '/>';
						} elseif ( isset( $htmlsingle[$t] ) ) {
							# Hack to not close $htmlsingle tags
							$brace = null;
							# Still need to push this optionally-closed tag to
							# the tag stack so that we can match end tags
							# instead of marking them as bad.
							array_push( $tagstack, $t );
						} elseif ( isset( $tabletags[$t] ) && in_array( $t, $tagstack ) ) {
							// New table tag but forgot to close the previous one
							$text .= "</$t>";
						} else {
							if ( $t == 'table' ) {
								array_push( $tablestack, $tagstack );
								$tagstack = [];
							}
							array_push( $tagstack, $t );
						}

						# Replace any variables or template parameters with
						# plaintext results.
						if ( is_callable( $processCallback ) ) {
							call_user_func_array( $processCallback, [ &$params, $args ] );
						}

						if ( !self::validateTag( $params, $t ) ) {
							$badtag = true;
						}

						# Strip non-approved attributes from the tag
						$newparams = self::fixTagAttributes( $params, $t );
					}
					if ( !$badtag ) {
						$rest = str_replace( '>', '&gt;', $rest );
						$close = ( $brace == '/>' && !$slash ) ? ' /' : '';
						$text .= "<$slash$t$newparams$close>$rest";
						continue;
					}
				}
				$text .= '&lt;' . str_replace( '>', '&gt;', $x );
			}
			# Close off any remaining tags
			while ( is_array( $tagstack ) && ( $t = array_pop( $tagstack ) ) ) {
				$text .= "</$t>\n";
				if ( $t == 'table' ) {
					$tagstack = array_pop( $tablestack );
				}
			}
		} else {
			# this might be possible using tidy itself
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
							// Eventually we'll just remove the self-closing
							// slash, in order to be consistent with HTML5
							// semantics.
							// $brace = '>';
							// For now, let's just warn authors to clean up.
							if ( is_callable( $warnCallback ) ) {
								call_user_func_array( $warnCallback, [ 'deprecated-self-close-category' ] );
							}
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
	static function validateTag( $params, $element ) {
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
	 * - Discards attributes not on a whitelist for the given element
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
	static function validateTagAttributes( $attribs, $element ) {
		return self::validateAttributes( $attribs,
			self::attributeWhitelist( $element ) );
	}

	/**
	 * Take an array of attribute names and values and normalize or discard
	 * illegal values for the given whitelist.
	 *
	 * - Discards attributes not on the given whitelist
	 * - Unsafe style attributes are discarded
	 * - Invalid id attributes are re-encoded
	 *
	 * @param array $attribs
	 * @param array $whitelist List of allowed attribute names
	 * @return array
	 *
	 * @todo Check for legal values where the DTD limits things.
	 * @todo Check for unique id attribute :P
	 */
	static function validateAttributes( $attribs, $whitelist ) {
		$whitelist = array_flip( $whitelist );
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
			if ( !preg_match( '/^data-[^:]*$/i', $attribute )
				&& !isset( $whitelist[$attribute] )
				|| self::isReservedDataAttribute( $attribute )
			) {
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
				$value = self::escapeIdReferenceList( $value );
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
	static function mergeAttributes( $a, $b ) {
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
	 *  - convert characters that IE6 interprets into ascii
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

		// Normalize Halfwidth and Fullwidth Unicode block that IE6 might treat as ascii
		$value = preg_replace_callback(
			'/[！-［］-ｚ]/u', // U+FF01 to U+FF5A, excluding U+FF3C (T60088)
			function ( $matches ) {
				$cp = UtfNormal\Utils::utf8ToCodepoint( $matches[0] );
				if ( $cp === false ) {
					return '';
				}
				return chr( $cp - 65248 ); // ASCII range \x21-\x7A
			},
			$value
		);

		// Convert more characters IE6 might treat as ascii
		// U+0280, U+0274, U+207F, U+029F, U+026A, U+207D, U+208D
		$value = str_replace(
			[ 'ʀ', 'ɴ', 'ⁿ', 'ʟ', 'ɪ', '⁽', '₍' ],
			[ 'r', 'n', 'n', 'l', 'i', '(', '(' ],
			$value
		);

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

		// S followed by repeat, iteration, or prolonged sound marks,
		// which IE will treat as "ss"
		$value = preg_replace(
			'/s(?:
				\xE3\x80\xB1 | # U+3031
				\xE3\x82\x9D | # U+309D
				\xE3\x83\xBC | # U+30FC
				\xE3\x83\xBD | # U+30FD
				\xEF\xB9\xBC | # U+FE7C
				\xEF\xB9\xBD | # U+FE7D
				\xEF\xBD\xB0   # U+FF70
			)/ix',
			'ss',
			$value
		);

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
	static function checkCss( $value ) {
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
	static function cssDecodeCallback( $matches ) {
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
	 * - Discards attributes not on a whitelist for the given element
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
	static function fixTagAttributes( $text, $element, $sorted = false ) {
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
	static function encodeAttribute( $text ) {
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
			'/(\S) (?=[?:;!%»›](?!\w))/u' => "\\1$space",
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
	static function safeEncodeAttribute( $text ) {
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
	 * Given a value, escape it so that it can be used in an id attribute and
	 * return it.  This will use HTML5 validation, allowing anything but ASCII
	 * whitespace.
	 *
	 * To ensure we don't have to bother escaping anything, we also strip ', ".
	 * TODO: Is this the best tactic?
	 *
	 * We also strip # because it upsets IE, and % because it could be
	 * ambiguous if it's part of something that looks like a percent escape
	 * (which don't work reliably in fragments cross-browser).
	 *
	 * @deprecated since 1.30, use one of this class' escapeIdFor*() functions
	 *
	 * @see https://www.w3.org/TR/html401/types.html#type-name Valid characters
	 *   in the id and name attributes
	 * @see https://www.w3.org/TR/html401/struct/links.html#h-12.2.3 Anchors with
	 *   the id attribute
	 * @see https://www.w3.org/TR/html5/dom.html#the-id-attribute
	 *   HTML5 definition of id attribute
	 *
	 * @param string $id Id to escape
	 * @param string|array $options String or array of strings (default is array()):
	 *   'noninitial': This is a non-initial fragment of an id, not a full id,
	 *       so don't pay attention if the first character isn't valid at the
	 *       beginning of an id.
	 * @return string
	 */
	static function escapeId( $id, $options = [] ) {
		$options = (array)$options;

		// HTML4-style escaping
		static $replace = [
			'%3A' => ':',
			'%' => '.'
		];

		$id = urlencode( strtr( $id, ' ', '_' ) );
		$id = strtr( $id, $replace );

		if ( !preg_match( '/^[a-zA-Z]/', $id ) && !in_array( 'noninitial', $options ) ) {
			// Initial character must be a letter!
			$id = "x$id";
		}
		return $id;
	}

	/**
	 * Given a section name or other user-generated or otherwise unsafe string, escapes it to be
	 * a valid HTML id attribute.
	 *
	 * WARNING: unlike escapeId(), the output of this function is not guaranteed to be HTML safe,
	 * be sure to use proper escaping.
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
	 * WARNING: unlike escapeId(), the output of this function is not guaranteed to be HTML safe,
	 * be sure to use proper escaping.
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

		$id = self::escapeIdInternal( $id, $mode );

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

		$id = self::escapeIdInternal( $id, $wgExternalInterwikiFragmentMode );

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
		switch ( $mode ) {
			case 'html5':
				$id = str_replace( ' ', '_', $id );
				break;
			case 'legacy':
				// This corresponds to 'noninitial' mode of the old escapeId()
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
	 *
	 * @param string $referenceString Space delimited list of ids
	 * @return string
	 */
	public static function escapeIdReferenceList( $referenceString ) {
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
	static function escapeClass( $class ) {
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
	static function escapeHtmlAllowEntities( $html ) {
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
	static function normalizeSectionNameWhitespace( $section ) {
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
	 * @private
	 */
	static function normalizeCharReferences( $text ) {
		return preg_replace_callback(
			self::CHAR_REFS_REGEX,
			[ self::class, 'normalizeCharReferencesCallback' ],
			$text );
	}

	/**
	 * @param string $matches
	 * @return string
	 */
	static function normalizeCharReferencesCallback( $matches ) {
		$ret = null;
		if ( $matches[1] != '' ) {
			$ret = self::normalizeEntity( $matches[1] );
		} elseif ( $matches[2] != '' ) {
			$ret = self::decCharReference( $matches[2] );
		} elseif ( $matches[3] != '' ) {
			$ret = self::hexCharReference( $matches[3] );
		}
		if ( is_null( $ret ) ) {
			return htmlspecialchars( $matches[0] );
		} else {
			return $ret;
		}
	}

	/**
	 * If the named entity is defined in the HTML 4.0/XHTML 1.0 DTD,
	 * return the equivalent numeric entity reference (except for the core &lt;
	 * &gt; &amp; &quot;). If the entity is a MediaWiki-specific alias, returns
	 * the HTML equivalent. Otherwise, returns HTML-escaped text of
	 * pseudo-entity source (eg &amp;foo;)
	 *
	 * @param string $name
	 * @return string
	 */
	static function normalizeEntity( $name ) {
		if ( isset( self::$htmlEntityAliases[$name] ) ) {
			return '&' . self::$htmlEntityAliases[$name] . ';';
		} elseif ( in_array( $name, [ 'lt', 'gt', 'amp', 'quot' ] ) ) {
			return "&$name;";
		} elseif ( isset( self::$htmlEntities[$name] ) ) {
			return '&#' . self::$htmlEntities[$name] . ';';
		} else {
			return "&amp;$name;";
		}
	}

	/**
	 * @param int $codepoint
	 * @return null|string
	 */
	static function decCharReference( $codepoint ) {
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
	static function hexCharReference( $codepoint ) {
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
			-1, //limit
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
	static function decodeCharReferencesCallback( $matches ) {
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
	 * @private
	 */
	static function decodeChar( $codepoint ) {
		if ( self::validateCodepoint( $codepoint ) ) {
			return UtfNormal\Utils::codepointToUtf8( $codepoint );
		} else {
			return UtfNormal\Constants::UTF8_REPLACEMENT;
		}
	}

	/**
	 * If the named entity is defined in the HTML 4.0/XHTML 1.0 DTD,
	 * return the UTF-8 encoding of that character. Otherwise, returns
	 * pseudo-entity source (eg "&foo;")
	 *
	 * @param string $name
	 * @return string
	 */
	static function decodeEntity( $name ) {
		if ( isset( self::$htmlEntityAliases[$name] ) ) {
			$name = self::$htmlEntityAliases[$name];
		}
		if ( isset( self::$htmlEntities[$name] ) ) {
			return UtfNormal\Utils::codepointToUtf8( self::$htmlEntities[$name] );
		} else {
			return "&$name;";
		}
	}

	/**
	 * Fetch the whitelist of acceptable attributes for a given element name.
	 *
	 * @param string $element
	 * @return array
	 */
	static function attributeWhitelist( $element ) {
		$list = self::setupAttributeWhitelist();
		return $list[$element] ?? [];
	}

	/**
	 * Foreach array key (an allowed HTML element), return an array
	 * of allowed attributes
	 * @return array
	 */
	static function setupAttributeWhitelist() {
		static $whitelist;

		if ( $whitelist !== null ) {
			return $whitelist;
		}

		$common = [
			# HTML
			'id',
			'class',
			'style',
			'lang',
			'dir',
			'title',

			# WAI-ARIA
			'aria-describedby',
			'aria-flowto',
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
		];

		$block = array_merge( $common, [ 'align' ] );
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
		$whitelist = [
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
			'blockquote' => array_merge( $common, [ 'cite' ] ),
			'q'          => array_merge( $common, [ 'cite' ] ),

			# 9.2.3
			'sub'        => $common,
			'sup'        => $common,

			# 9.3.1
			'p'          => $block,

			# 9.3.2
			'br'         => array_merge( $common, [ 'clear' ] ),

			# https://www.w3.org/TR/html5/text-level-semantics.html#the-wbr-element
			'wbr'        => $common,

			# 9.3.4
			'pre'        => array_merge( $common, [ 'width' ] ),

			# 9.4
			'ins'        => array_merge( $common, [ 'cite', 'datetime' ] ),
			'del'        => array_merge( $common, [ 'cite', 'datetime' ] ),

			# 10.2
			'ul'         => array_merge( $common, [ 'type' ] ),
			'ol'         => array_merge( $common, [ 'type', 'start', 'reversed' ] ),
			'li'         => array_merge( $common, [ 'type', 'value' ] ),

			# 10.3
			'dl'         => $common,
			'dd'         => $common,
			'dt'         => $common,

			# 11.2.1
			'table'      => array_merge( $common,
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
			'colgroup'   => array_merge( $common, [ 'span' ] ),
			'col'        => array_merge( $common, [ 'span' ] ),

			# 11.2.5
			'tr'         => array_merge( $common, [ 'bgcolor' ], $tablealign ),

			# 11.2.6
			'td'         => array_merge( $common, $tablecell, $tablealign ),
			'th'         => array_merge( $common, $tablecell, $tablealign ),

			# 12.2
			# NOTE: <a> is not allowed directly, but the attrib
			# whitelist is used from the Parser object
			'a'          => array_merge( $common, [ 'href', 'rel', 'rev' ] ), # rel/rev esp. for RDFa

			# 13.2
			# Not usually allowed, but may be used for extension-style hooks
			# such as <math> when it is rasterized, or if $wgAllowImageTag is
			# true
			'img'        => array_merge( $common, [ 'alt', 'src', 'width', 'height', 'srcset' ] ),

			'video'      => array_merge( $common, [ 'poster', 'controls', 'preload', 'width', 'height' ] ),
			'source'     => array_merge( $common, [ 'type', 'src' ] ),
			'track'      => array_merge( $common, [ 'type', 'src', 'srclang', 'kind', 'label' ] ),

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
			'font'       => array_merge( $common, [ 'size', 'color', 'face' ] ),
			# basefont

			# 15.3
			'hr'         => array_merge( $common, [ 'width' ] ),

			# HTML Ruby annotation text module, simple ruby only.
			# https://www.w3.org/TR/html5/text-level-semantics.html#the-ruby-element
			'ruby'       => $common,
			# rbc
			'rb'         => $common,
			'rp'         => $common,
			'rt'         => $common, # array_merge( $common, array( 'rbspan' ) ),
			'rtc'        => $common,

			# MathML root element, where used for extensions
			# 'title' may not be 100% valid here; it's XHTML
			# https://www.w3.org/TR/REC-MathML/
			'math'       => [ 'class', 'style', 'id', 'title' ],

			// HTML 5 section 4.5
			'figure'     => $common,
			'figcaption' => $common,

			# HTML 5 section 4.6
			'bdi' => $common,

			# HTML5 elements, defined by:
			# https://html.spec.whatwg.org/multipage/semantics.html#the-data-element
			'data' => array_merge( $common, [ 'value' ] ),
			'time' => array_merge( $common, [ 'datetime' ] ),
			'mark' => $common,

			// meta and link are only permitted by removeHTMLtags when Microdata
			// is enabled so we don't bother adding a conditional to hide these
			// Also meta and link are only valid in WikiText as Microdata elements
			// (ie: validateTag rejects tags missing the attributes needed for Microdata)
			// So we don't bother including $common attributes that have no purpose.
			'meta' => [ 'itemprop', 'content' ],
			'link' => [ 'itemprop', 'href', 'title' ],
		];

		return $whitelist;
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
	 */
	static function stripAllTags( $html ) {
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
	 */
	static function hackDocType() {
		$out = "<!DOCTYPE html [\n";
		foreach ( self::$htmlEntities as $entity => $codepoint ) {
			$out .= "<!ENTITY $entity \"&#$codepoint;\">";
		}
		$out .= "]>\n";
		return $out;
	}

	/**
	 * @param string $url
	 * @return mixed|string
	 */
	static function cleanUrl( $url ) {
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
			// Strip them before further processing so blacklists and such work.
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
	static function cleanUrlCallback( $matches ) {
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
		if ( !Hooks::run( 'isValidEmailAddr', [ $addr, &$result ] ) ) {
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
