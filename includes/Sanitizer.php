<?php
/**
 * XHTML sanitizer for MediaWiki
 *
 * Copyright (C) 2002-2005 Brion Vibber <brion@pobox.com> et al
 * http://www.mediawiki.org/
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
 * @package MediaWiki
 * @subpackage Parser
 */

/**
 * Regular expression to match various types of character references in
 * Sanitizer::normalizeCharReferences and Sanitizer::decodeCharReferences
 */
define( 'MW_CHAR_REFS_REGEX',
	'/&([A-Za-z0-9]+);
	 |&\#([0-9]+);
	 |&\#x([0-9A-Za-z]+);
	 |&\#X([0-9A-Za-z]+);
	 |(&)/x' );

/**
 * Regular expression to match HTML/XML attribute pairs within a tag.
 * Allows some... latitude.
 * Used in Sanitizer::fixTagAttributes and Sanitizer::decodeTagAttributes
 */
$attrib = '[A-Za-z0-9]';
$space = '[\x09\x0a\x0d\x20]';
define( 'MW_ATTRIBS_REGEX',
	"/(?:^|$space)($attrib+)
	  ($space*=$space*
		(?:
		 # The attribute value: quoted or alone
		  \"([^<\"]*)\"
		 | '([^<']*)'
		 |  ([a-zA-Z0-9!#$%&()*,\\-.\\/:;<>?@[\\]^_`{|}~]+)
		 |  (\#[0-9a-fA-F]+) # Technically wrong, but lots of
							 # colors are specified like this.
							 # We'll be normalizing it.
		)
	   )?(?=$space|\$)/sx" );

/**
 * List of all named character entities defined in HTML 4.01
 * http://www.w3.org/TR/html4/sgml/entities.html
 * @private
 */
global $wgHtmlEntities;
$wgHtmlEntities = array(
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
	'zwnj'     => 8204 );

/** @package MediaWiki */
class Sanitizer {
	/**
	 * Cleans up HTML, removes dangerous tags and attributes, and
	 * removes HTML comments
	 * @private
	 * @param string $text
	 * @param callback $processCallback to do any variable or parameter replacements in HTML attribute values
	 * @param array $args for the processing callback
	 * @return string
	 */
	function removeHTMLtags( $text, $processCallback = null, $args = array() ) {
		global $wgUseTidy, $wgUserHtml;
		$fname = 'Parser::removeHTMLtags';
		wfProfileIn( $fname );

		if( $wgUserHtml ) {
			$htmlpairs = array( # Tags that must be closed
				'b', 'del', 'i', 'ins', 'u', 'font', 'big', 'small', 'sub', 'sup', 'h1',
				'h2', 'h3', 'h4', 'h5', 'h6', 'cite', 'code', 'em', 's',
				'strike', 'strong', 'tt', 'var', 'div', 'center',
				'blockquote', 'ol', 'ul', 'dl', 'table', 'caption', 'pre',
				'ruby', 'rt' , 'rb' , 'rp', 'p', 'span', 'u'
			);
			$htmlsingle = array(
				'br', 'hr', 'li', 'dt', 'dd'
			);
			$htmlsingleonly = array( # Elements that cannot have close tags
				'br', 'hr'
			);
			$htmlnest = array( # Tags that can be nested--??
				'table', 'tr', 'td', 'th', 'div', 'blockquote', 'ol', 'ul',
				'dl', 'font', 'big', 'small', 'sub', 'sup', 'span'
			);
			$tabletags = array( # Can only appear inside table
				'td', 'th', 'tr',
			);
			$htmllist = array( # Tags used by list
				'ul','ol',
			);
			$listtags = array( # Tags that can appear in a list
				'li',
			);

		} else {
			$htmlpairs = array();
			$htmlsingle = array();
			$htmlnest = array();
			$tabletags = array();
		}

		$htmlsingle = array_merge( $tabletags, $htmlsingle );
		$htmlelements = array_merge( $htmlsingle, $htmlpairs );

		# Remove HTML comments
		$text = Sanitizer::removeHTMLcomments( $text );
		$bits = explode( '<', $text );
		$text = array_shift( $bits );
		if(!$wgUseTidy) {
			$tagstack = array(); $tablestack = array();
			foreach ( $bits as $x ) {
				$prev = error_reporting( E_ALL & ~( E_NOTICE | E_WARNING ) );
				preg_match( '/^(\\/?)(\\w+)([^>]*?)(\\/{0,1}>)([^<]*)$/',
				$x, $regs );
				list( $qbar, $slash, $t, $params, $brace, $rest ) = $regs;
				error_reporting( $prev );

				$badtag = 0 ;
				if ( in_array( $t = strtolower( $t ), $htmlelements ) ) {
					# Check our stack
					if ( $slash ) {
						# Closing a tag...
						if( in_array( $t, $htmlsingleonly ) ) {
							$badtag = 1;
						} elseif ( ( $ot = @array_pop( $tagstack ) ) != $t ) {
							@array_push( $tagstack, $ot );
							# <li> can be nested in <ul> or <ol>, skip those cases:
							if(!(in_array($ot, $htmllist) && in_array($t, $listtags) )) {
								$badtag = 1;
							}
						} else {
							if ( $t == 'table' ) {
								$tagstack = array_pop( $tablestack );
							}
						}
						$newparams = '';
					} else {
						# Keep track for later
						if ( in_array( $t, $tabletags ) &&
						! in_array( 'table', $tagstack ) ) {
							$badtag = 1;
						} else if ( in_array( $t, $tagstack ) &&
						! in_array ( $t , $htmlnest ) ) {
							$badtag = 1 ;
						# Is it a self closed htmlpair ? (bug 5487)
						} else if( $brace == '/>' &&
						in_array($t, $htmlpairs) ) {
							$badtag = 1;
						} elseif( in_array( $t, $htmlsingleonly ) ) {
							# Hack to force empty tag for uncloseable elements
							$brace = '/>';
						} else if( in_array( $t, $htmlsingle ) ) {
							# Hack to not close $htmlsingle tags
							$brace = NULL;
						} else {
							if ( $t == 'table' ) {
								array_push( $tablestack, $tagstack );
								$tagstack = array();
							}
							array_push( $tagstack, $t );
						}

						# Replace any variables or template parameters with
						# plaintext results.
						if( is_callable( $processCallback ) ) {
							call_user_func_array( $processCallback, array( &$params, $args ) );
						}

						# Strip non-approved attributes from the tag
						$newparams = Sanitizer::fixTagAttributes( $params, $t );
					}
					if ( ! $badtag ) {
						$rest = str_replace( '>', '&gt;', $rest );
						$close = ( $brace == '/>' ) ? ' /' : '';
						$text .= "<$slash$t$newparams$close>$rest";
						continue;
					}
				}
				$text .= '&lt;' . str_replace( '>', '&gt;', $x);
			}
			# Close off any remaining tags
			while ( is_array( $tagstack ) && ($t = array_pop( $tagstack )) ) {
				$text .= "</$t>\n";
				if ( $t == 'table' ) { $tagstack = array_pop( $tablestack ); }
			}
		} else {
			# this might be possible using tidy itself
			foreach ( $bits as $x ) {
				preg_match( '/^(\\/?)(\\w+)([^>]*?)(\\/{0,1}>)([^<]*)$/',
				$x, $regs );
				@list( $qbar, $slash, $t, $params, $brace, $rest ) = $regs;
				if ( in_array( $t = strtolower( $t ), $htmlelements ) ) {
					if( is_callable( $processCallback ) ) {
						call_user_func_array( $processCallback, array( &$params, $args ) );
					}
					$newparams = Sanitizer::fixTagAttributes( $params, $t );
					$rest = str_replace( '>', '&gt;', $rest );
					$text .= "<$slash$t$newparams$brace$rest";
				} else {
					$text .= '&lt;' . str_replace( '>', '&gt;', $x);
				}
			}
		}
		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Remove '<!--', '-->', and everything between.
	 * To avoid leaving blank lines, when a comment is both preceded
	 * and followed by a newline (ignoring spaces), trim leading and
	 * trailing spaces and one of the newlines.
	 *
	 * @private
	 * @param string $text
	 * @return string
	 */
	function removeHTMLcomments( $text ) {
		$fname='Parser::removeHTMLcomments';
		wfProfileIn( $fname );
		while (($start = strpos($text, '<!--')) !== false) {
			$end = strpos($text, '-->', $start + 4);
			if ($end === false) {
				# Unterminated comment; bail out
				break;
			}

			$end += 3;

			# Trim space and newline if the comment is both
			# preceded and followed by a newline
			$spaceStart = max($start - 1, 0);
			$spaceLen = $end - $spaceStart;
			while (substr($text, $spaceStart, 1) === ' ' && $spaceStart > 0) {
				$spaceStart--;
				$spaceLen++;
			}
			while (substr($text, $spaceStart + $spaceLen, 1) === ' ')
				$spaceLen++;
			if (substr($text, $spaceStart, 1) === "\n" and substr($text, $spaceStart + $spaceLen, 1) === "\n") {
				# Remove the comment, leading and trailing
				# spaces, and leave only one newline.
				$text = substr_replace($text, "\n", $spaceStart, $spaceLen + 1);
			}
			else {
				# Remove just the comment.
				$text = substr_replace($text, '', $start, $end - $start);
			}
		}
		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Take a tag soup fragment listing an HTML element's attributes
	 * and normalize it to well-formed XML, discarding unwanted attributes.
	 *
	 * - Normalizes attribute names to lowercase
	 * - Discards attributes not on a whitelist for the given element
	 * - Turns broken or invalid entities into plaintext
	 * - Double-quotes all attribute values
	 * - Attributes without values are given the name as attribute
	 * - Double attributes are discarded
	 * - Unsafe style attributes are discarded
	 * - Prepends space if there are attributes.
	 *
	 * @param string $text
	 * @param string $element
	 * @return string
	 *
	 * @todo Check for legal values where the DTD limits things.
	 * @todo Check for unique id attribute :P
	 */
	function fixTagAttributes( $text, $element ) {
		if( trim( $text ) == '' ) {
			return '';
		}

		# Unquoted attribute
		# Since we quote this later, this can be anything distinguishable
		# from the end of the attribute
		$pairs = array();
		if( !preg_match_all(
			MW_ATTRIBS_REGEX,
			$text,
			$pairs,
			PREG_SET_ORDER ) ) {
			return '';
		}

		$whitelist = array_flip( Sanitizer::attributeWhitelist( $element ) );
		$attribs = array();
		foreach( $pairs as $set ) {
			$attribute = strtolower( $set[1] );
			if( !isset( $whitelist[$attribute] ) ) {
				continue;
			}

			$raw   = Sanitizer::getTagAttributeCallback( $set );
			$value = Sanitizer::normalizeAttributeValue( $raw );

			# Strip javascript "expression" from stylesheets.
			# http://msdn.microsoft.com/workshop/author/dhtml/overview/recalc.asp
			if( $attribute == 'style' ) {
				$stripped = Sanitizer::decodeCharReferences( $value );

				// Remove any comments; IE gets token splitting wrong
				$stripped = preg_replace( '!/\\*.*?\\*/!S', ' ', $stripped );
				$value = htmlspecialchars( $stripped );

				// ... and continue checks
				$stripped = preg_replace( '!\\\\([0-9A-Fa-f]{1,6})[ \\n\\r\\t\\f]?!e',
					'codepointToUtf8(hexdec("$1"))', $stripped );
				$stripped = str_replace( '\\', '', $stripped );
				if( preg_match( '/(expression|tps*:\/\/|url\\s*\().*/is',
						$stripped ) ) {
					# haxx0r
					continue;
				}
			}

			if ( $attribute === 'id' )
				$value = Sanitizer::escapeId( $value );

			# Templates and links may be expanded in later parsing,
			# creating invalid or dangerous output. Suppress this.
			$value = strtr( $value, array(
				'<'    => '&lt;',   // This should never happen,
				'>'    => '&gt;',   // we've received invalid input
				'"'    => '&quot;', // which should have been escaped.
				'{'    => '&#123;',
				'['    => '&#91;',
				"''"   => '&#39;&#39;',
				'ISBN' => '&#73;SBN',
				'RFC'  => '&#82;FC',
				'PMID' => '&#80;MID',
				'|'    => '&#124;',
				'__'   => '&#95;_',
			) );

			# Stupid hack
			$value = preg_replace_callback(
				'/(' . wfUrlProtocols() . ')/',
				array( 'Sanitizer', 'armorLinksCallback' ),
				$value );

			// If this attribute was previously set, override it.
			// Output should only have one attribute of each name.
			$attribs[$attribute] = "$attribute=\"$value\"";
		}

		return count( $attribs ) ? ' ' . implode( ' ', $attribs ) : '';
	}

	/**
	 * Given a value escape it so that it can be used in an id attribute and
	 * return it, this does not validate the value however (see first link)
	 *
	 * @link http://www.w3.org/TR/html401/types.html#type-name Valid characters
	 *                                                          in the id and
	 *                                                          name attributes
	 * @link http://www.w3.org/TR/html401/struct/links.html#h-12.2.3 Anchors with the id attribute
	 *
	 * @bug 4461
	 *
	 * @static
	 *
	 * @param string $id
	 * @return string
	 */
	function escapeId( $id ) {
		static $replace = array(
			'%3A' => ':',
			'%' => '.'
		);

		$id = urlencode( Sanitizer::decodeCharReferences( strtr( $id, ' ', '_' ) ) );

		return str_replace( array_keys( $replace ), array_values( $replace ), $id );
	}

	/**
	 * Regex replace callback for armoring links against further processing.
	 * @param array $matches
	 * @return string
	 * @private
	 */
	function armorLinksCallback( $matches ) {
		return str_replace( ':', '&#58;', $matches[1] );
	}

	/**
	 * Return an associative array of attribute names and values from
	 * a partial tag string. Attribute names are forces to lowercase,
	 * character references are decoded to UTF-8 text.
	 *
	 * @param string
	 * @return array
	 */
	function decodeTagAttributes( $text ) {
		$attribs = array();

		if( trim( $text ) == '' ) {
			return $attribs;
		}

		$pairs = array();
		if( !preg_match_all(
			MW_ATTRIBS_REGEX,
			$text,
			$pairs,
			PREG_SET_ORDER ) ) {
			return $attribs;
		}

		foreach( $pairs as $set ) {
			$attribute = strtolower( $set[1] );
			$value = Sanitizer::getTagAttributeCallback( $set );
			$attribs[$attribute] = Sanitizer::decodeCharReferences( $value );
		}
		return $attribs;
	}

	/**
	 * Pick the appropriate attribute value from a match set from the
	 * MW_ATTRIBS_REGEX matches.
	 *
	 * @param array $set
	 * @return string
	 * @private
	 */
	function getTagAttributeCallback( $set ) {
		if( isset( $set[6] ) ) {
			# Illegal #XXXXXX color with no quotes.
			return $set[6];
		} elseif( isset( $set[5] ) ) {
			# No quotes.
			return $set[5];
		} elseif( isset( $set[4] ) ) {
			# Single-quoted
			return $set[4];
		} elseif( isset( $set[3] ) ) {
			# Double-quoted
			return $set[3];
		} elseif( !isset( $set[2] ) ) {
			# In XHTML, attributes must have a value.
			# For 'reduced' form, return explicitly the attribute name here.
			return $set[1];
		} else {
			wfDebugDieBacktrace( "Tag conditions not met. This should never happen and is a bug." );
		}
	}

	/**
	 * Normalize whitespace and character references in an XML source-
	 * encoded text for an attribute value.
	 *
	 * See http://www.w3.org/TR/REC-xml/#AVNormalize for background,
	 * but note that we're not returning the value, but are returning
	 * XML source fragments that will be slapped into output.
	 *
	 * @param string $text
	 * @return string
	 * @private
	 */
	function normalizeAttributeValue( $text ) {
		return str_replace( '"', '&quot;',
			preg_replace(
				'/\r\n|[\x20\x0d\x0a\x09]/',
				' ',
				Sanitizer::normalizeCharReferences( $text ) ) );
	}

	/**
	 * Ensure that any entities and character references are legal
	 * for XML and XHTML specifically. Any stray bits will be
	 * &amp;-escaped to result in a valid text fragment.
	 *
	 * a. any named char refs must be known in XHTML
	 * b. any numeric char refs must be legal chars, not invalid or forbidden
	 * c. use &#x, not &#X
	 * d. fix or reject non-valid attributes
	 *
	 * @param string $text
	 * @return string
	 * @private
	 */
	function normalizeCharReferences( $text ) {
		return preg_replace_callback(
			MW_CHAR_REFS_REGEX,
			array( 'Sanitizer', 'normalizeCharReferencesCallback' ),
			$text );
	}
	/**
	 * @param string $matches
	 * @return string
	 */
	function normalizeCharReferencesCallback( $matches ) {
		$ret = null;
		if( $matches[1] != '' ) {
			$ret = Sanitizer::normalizeEntity( $matches[1] );
		} elseif( $matches[2] != '' ) {
			$ret = Sanitizer::decCharReference( $matches[2] );
		} elseif( $matches[3] != ''  ) {
			$ret = Sanitizer::hexCharReference( $matches[3] );
		} elseif( $matches[4] != '' ) {
			$ret = Sanitizer::hexCharReference( $matches[4] );
		}
		if( is_null( $ret ) ) {
			return htmlspecialchars( $matches[0] );
		} else {
			return $ret;
		}
	}

	/**
	 * If the named entity is defined in the HTML 4.0/XHTML 1.0 DTD,
	 * return the named entity reference as is. Otherwise, returns
	 * HTML-escaped text of pseudo-entity source (eg &amp;foo;)
	 *
	 * @param string $name
	 * @return string
	 */
	function normalizeEntity( $name ) {
		global $wgHtmlEntities;
		if( isset( $wgHtmlEntities[$name] ) ) {
			return "&$name;";
		} else {
			return "&amp;$name;";
		}
	}

	function decCharReference( $codepoint ) {
		$point = intval( $codepoint );
		if( Sanitizer::validateCodepoint( $point ) ) {
			return sprintf( '&#%d;', $point );
		} else {
			return null;
		}
	}

	function hexCharReference( $codepoint ) {
		$point = hexdec( $codepoint );
		if( Sanitizer::validateCodepoint( $point ) ) {
			return sprintf( '&#x%x;', $point );
		} else {
			return null;
		}
	}

	/**
	 * Returns true if a given Unicode codepoint is a valid character in XML.
	 * @param int $codepoint
	 * @return bool
	 */
	function validateCodepoint( $codepoint ) {
		return ($codepoint ==    0x09)
			|| ($codepoint ==    0x0a)
			|| ($codepoint ==    0x0d)
			|| ($codepoint >=    0x20 && $codepoint <=   0xd7ff)
			|| ($codepoint >=  0xe000 && $codepoint <=   0xfffd)
			|| ($codepoint >= 0x10000 && $codepoint <= 0x10ffff);
	}

	/**
	 * Decode any character references, numeric or named entities,
	 * in the text and return a UTF-8 string.
	 *
	 * @param string $text
	 * @return string
	 * @public
	 */
	function decodeCharReferences( $text ) {
		return preg_replace_callback(
			MW_CHAR_REFS_REGEX,
			array( 'Sanitizer', 'decodeCharReferencesCallback' ),
			$text );
	}

	/**
	 * @param string $matches
	 * @return string
	 */
	function decodeCharReferencesCallback( $matches ) {
		if( $matches[1] != '' ) {
			return Sanitizer::decodeEntity( $matches[1] );
		} elseif( $matches[2] != '' ) {
			return  Sanitizer::decodeChar( intval( $matches[2] ) );
		} elseif( $matches[3] != ''  ) {
			return  Sanitizer::decodeChar( hexdec( $matches[3] ) );
		} elseif( $matches[4] != '' ) {
			return  Sanitizer::decodeChar( hexdec( $matches[4] ) );
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
	function decodeChar( $codepoint ) {
		if( Sanitizer::validateCodepoint( $codepoint ) ) {
			return codepointToUtf8( $codepoint );
		} else {
			return UTF8_REPLACEMENT;
		}
	}

	/**
	 * If the named entity is defined in the HTML 4.0/XHTML 1.0 DTD,
	 * return the UTF-8 encoding of that character. Otherwise, returns
	 * pseudo-entity source (eg &foo;)
	 *
	 * @param string $name
	 * @return string
	 */
	function decodeEntity( $name ) {
		global $wgHtmlEntities;
		if( isset( $wgHtmlEntities[$name] ) ) {
			return codepointToUtf8( $wgHtmlEntities[$name] );
		} else {
			return "&$name;";
		}
	}

	/**
	 * Fetch the whitelist of acceptable attributes for a given
	 * element name.
	 *
	 * @param string $element
	 * @return array
	 */
	function attributeWhitelist( $element ) {
		static $list;
		if( !isset( $list ) ) {
			$list = Sanitizer::setupAttributeWhitelist();
		}
		return isset( $list[$element] )
			? $list[$element]
			: array();
	}

	/**
	 * @return array
	 */
	function setupAttributeWhitelist() {
		$common = array( 'id', 'class', 'lang', 'dir', 'title', 'style' );
		$block = array_merge( $common, array( 'align' ) );
		$tablealign = array( 'align', 'char', 'charoff', 'valign' );
		$tablecell = array( 'abbr',
		                    'axis',
		                    'headers',
		                    'scope',
		                    'rowspan',
		                    'colspan',
		                    'nowrap', # deprecated
		                    'width',  # deprecated
		                    'height', # deprecated
		                    'bgcolor' # deprecated
		                    );

		# Numbers refer to sections in HTML 4.01 standard describing the element.
		# See: http://www.w3.org/TR/html4/
		$whitelist = array (
			# 7.5.4
			'div'        => $block,
			'center'     => $common, # deprecated
			'span'       => $block, # ??

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
			# bdo

			# 9.2.1
			'em'         => $common,
			'strong'     => $common,
			'cite'       => $common,
			# dfn
			'code'       => $common,
			# samp
			# kbd
			'var'        => $common,
			# abbr
			# acronym

			# 9.2.2
			'blockquote' => array_merge( $common, array( 'cite' ) ),
			# q

			# 9.2.3
			'sub'        => $common,
			'sup'        => $common,

			# 9.3.1
			'p'          => $block,

			# 9.3.2
			'br'         => array( 'id', 'class', 'title', 'style', 'clear' ),

			# 9.3.4
			'pre'        => array_merge( $common, array( 'width' ) ),

			# 9.4
			'ins'        => array_merge( $common, array( 'cite', 'datetime' ) ),
			'del'        => array_merge( $common, array( 'cite', 'datetime' ) ),

			# 10.2
			'ul'         => array_merge( $common, array( 'type' ) ),
			'ol'         => array_merge( $common, array( 'type', 'start' ) ),
			'li'         => array_merge( $common, array( 'type', 'value' ) ),

			# 10.3
			'dl'         => $common,
			'dd'         => $common,
			'dt'         => $common,

			# 11.2.1
			'table'      => array_merge( $common,
								array( 'summary', 'width', 'border', 'frame',
											 'rules', 'cellspacing', 'cellpadding',
											 'align', 'bgcolor', 'frame', 'rules',
											 'border' ) ),

			# 11.2.2
			'caption'    => array_merge( $common, array( 'align' ) ),

			# 11.2.3
			'thead'      => array_merge( $common, $tablealign ),
			'tfoot'      => array_merge( $common, $tablealign ),
			'tbody'      => array_merge( $common, $tablealign ),

			# 11.2.4
			'colgroup'   => array_merge( $common, array( 'span', 'width' ), $tablealign ),
			'col'        => array_merge( $common, array( 'span', 'width' ), $tablealign ),

			# 11.2.5
			'tr'         => array_merge( $common, array( 'bgcolor' ), $tablealign ),

			# 11.2.6
			'td'         => array_merge( $common, $tablecell, $tablealign ),
			'th'         => array_merge( $common, $tablecell, $tablealign ),

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
			'font'       => array_merge( $common, array( 'size', 'color', 'face' ) ),
			# basefont

			# 15.3
			'hr'         => array_merge( $common, array( 'noshade', 'size', 'width' ) ),

			# XHTML Ruby annotation text module, simple ruby only.
			# http://www.w3c.org/TR/ruby/
			'ruby'       => $common,
			# rbc
			# rtc
			'rb'         => $common,
			'rt'         => $common, #array_merge( $common, array( 'rbspan' ) ),
			'rp'         => $common,
			);
		return $whitelist;
	}

	/**
	 * Take a fragment of (potentially invalid) HTML and return
	 * a version with any tags removed, encoded suitably for literal
	 * inclusion in an attribute value.
	 *
	 * @param string $text HTML fragment
	 * @return string
	 */
	function stripAllTags( $text ) {
		# Actual <tags>
		$text = preg_replace( '/ < .*? > /x', '', $text );

		# Normalize &entities and whitespace
		$text = Sanitizer::normalizeAttributeValue( $text );

		# Will be placed into "double-quoted" attributes,
		# make sure remaining bits are safe.
		$text = str_replace(
			array('<', '>', '"'),
			array('&lt;', '&gt;', '&quot;'),
			$text );

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
	 * @static
	 */
	function hackDocType() {
		global $wgHtmlEntities;
		$out = "<!DOCTYPE html [\n";
		foreach( $wgHtmlEntities as $entity => $codepoint ) {
			$out .= "<!ENTITY $entity \"&#$codepoint;\">";
		}
		$out .= "]>\n";
		return $out;
	}

}

?>
