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
 * @file
 * @ingroup Parser
 */

/**
 * Regular expression to match various types of character references in
 * Sanitizer::normalizeCharReferences and Sanitizer::decodeCharReferences
 */
define( 'MW_CHAR_REFS_REGEX',
	'/&([A-Za-z0-9\x80-\xff]+);
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
	"/(?:^|$space)((?:xml:|xmlns:)?$attrib+)
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
 * Regular expression to match URIs that could trigger script execution
 */
define( 'MW_EVIL_URI_PATTERN', '!(^|\s|\*/\s*)(javascript|vbscript)([^\w]|$)!i' );

/**
 * Regular expression to match namespace attributes
 */
define( 'MW_XMLNS_ATTRIBUTE_PATTRN', "/^xmlns:$attrib+$/" );

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

/**
 * Character entity aliases accepted by MediaWiki
 */
global $wgHtmlEntityAliases;
$wgHtmlEntityAliases = array(
	'רלמ' => 'rlm',
	'رلم' => 'rlm',
);


/**
 * XHTML sanitizer for MediaWiki
 * @ingroup Parser
 */
class Sanitizer {
	/**
	 * Cleans up HTML, removes dangerous tags and attributes, and
	 * removes HTML comments
	 * @private
	 * @param $text String
	 * @param $processCallback Callback to do any variable or parameter replacements in HTML attribute values
	 * @param $args Array for the processing callback
	 * @param $extratags Array for any extra tags to include
	 * @param $removetags Array for any tags (default or extra) to exclude
	 * @return string
	 */
	static function removeHTMLtags( $text, $processCallback = null, $args = array(), $extratags = array(), $removetags = array() ) {
		global $wgUseTidy;

		static $htmlpairsStatic, $htmlsingle, $htmlsingleonly, $htmlnest, $tabletags,
			$htmllist, $listtags, $htmlsingleallowed, $htmlelementsStatic, $staticInitialised;

		wfProfileIn( __METHOD__ );

		if ( !$staticInitialised ) {

			$htmlpairsStatic = array( # Tags that must be closed
				'b', 'del', 'i', 'ins', 'u', 'font', 'big', 'small', 'sub', 'sup', 'h1',
				'h2', 'h3', 'h4', 'h5', 'h6', 'cite', 'code', 'em', 's',
				'strike', 'strong', 'tt', 'var', 'div', 'center',
				'blockquote', 'ol', 'ul', 'dl', 'table', 'caption', 'pre',
				'ruby', 'rt' , 'rb' , 'rp', 'p', 'span', 'u', 'abbr'
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
			$tabletags = array( # Can only appear inside table, we will close them
				'td', 'th', 'tr',
			);
			$htmllist = array( # Tags used by list
				'ul','ol',
			);
			$listtags = array( # Tags that can appear in a list
				'li',
			);

			global $wgAllowImageTag;
			if ( $wgAllowImageTag ) {
				$htmlsingle[] = 'img';
				$htmlsingleonly[] = 'img';
			}

			$htmlsingleallowed = array_unique( array_merge( $htmlsingle, $tabletags ) );
			$htmlelementsStatic = array_unique( array_merge( $htmlsingle, $htmlpairsStatic, $htmlnest ) );

			# Convert them all to hashtables for faster lookup
			$vars = array( 'htmlpairsStatic', 'htmlsingle', 'htmlsingleonly', 'htmlnest', 'tabletags',
				'htmllist', 'listtags', 'htmlsingleallowed', 'htmlelementsStatic' );
			foreach ( $vars as $var ) {
				$$var = array_flip( $$var );
			}
			$staticInitialised = true;
		}
		# Populate $htmlpairs and $htmlelements with the $extratags and $removetags arrays
		$extratags = array_flip( $extratags );
		$removetags = array_flip( $removetags );
		$htmlpairs = array_merge( $extratags, $htmlpairsStatic );
		$htmlelements = array_diff_key( array_merge( $extratags, $htmlelementsStatic ) , $removetags );

		# Remove HTML comments
		$text = Sanitizer::removeHTMLcomments( $text );
		$bits = explode( '<', $text );
		$text = str_replace( '>', '&gt;', array_shift( $bits ) );
		if ( !$wgUseTidy ) {
			$tagstack = $tablestack = array();
			foreach ( $bits as $x ) {
				$regs = array();
				# $slash: Does the current element start with a '/'?
				# $t: Current element name
				# $params: String between element name and >
				# $brace: Ending '>' or '/>'
				# $rest: Everything until the next element of $bits
				if( preg_match( '!^(/?)(\\w+)([^>]*?)(/{0,1}>)([^<]*)$!', $x, $regs ) ) {
					list( /* $qbar */, $slash, $t, $params, $brace, $rest ) = $regs;
				} else {
					$slash = $t = $params = $brace = $rest = null;
				}

				$badtag = false;
				if ( isset( $htmlelements[$t = strtolower( $t )] ) ) {
					# Check our stack
					if ( $slash && isset( $htmlsingleonly[$t] ) ) {
						$badtag = true;
					} elseif ( $slash ) {
						# Closing a tag... is it the one we just opened?
						$ot = @array_pop( $tagstack );
						if ( $ot != $t ) {
							if ( isset( $htmlsingleallowed[$ot] ) ) {
								# Pop all elements with an optional close tag
								# and see if we find a match below them
								$optstack = array();
								array_push( $optstack, $ot );
								$ot = @array_pop( $tagstack );
								while ( $ot != $t && isset( $htmlsingleallowed[$ot] ) ) {
									array_push( $optstack, $ot );
									$ot = @array_pop( $tagstack );
								}
								if ( $t != $ot ) {
									# No match. Push the optional elements back again
									$badtag = true;
									while ( $ot = @array_pop( $optstack ) ) {
										array_push( $tagstack, $ot );
									}
								}
							} else {
								@array_push( $tagstack, $ot );
								# <li> can be nested in <ul> or <ol>, skip those cases:
								if ( !isset( $htmllist[$ot] ) || !isset( $listtags[$t] ) ) {
									$badtag = true;
								}
							}
						} else {
							if ( $t == 'table' ) {
								$tagstack = array_pop( $tablestack );
							}
						}
						$newparams = '';
					} else {
						# Keep track for later
						if ( isset( $tabletags[$t] ) &&
						!in_array( 'table', $tagstack ) ) {
							$badtag = true;
						} elseif ( in_array( $t, $tagstack ) &&
						!isset( $htmlnest [$t ] ) ) {
							$badtag = true;
						# Is it a self closed htmlpair ? (bug 5487)
						} elseif ( $brace == '/>' &&
						isset( $htmlpairs[$t] ) ) {
							$badtag = true;
						} elseif ( isset( $htmlsingleonly[$t] ) ) {
							# Hack to force empty tag for uncloseable elements
							$brace = '/>';
						} elseif ( isset( $htmlsingle[$t] ) ) {
							# Hack to not close $htmlsingle tags
							$brace = null;
						} elseif ( isset( $tabletags[$t] )
						&& in_array( $t, $tagstack ) ) {
							// New table tag but forgot to close the previous one
							$text .= "</$t>";
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
					if ( !$badtag ) {
						$rest = str_replace( '>', '&gt;', $rest );
						$close = ( $brace == '/>' && !$slash ) ? ' /' : '';
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
				@list( /* $qbar */, $slash, $t, $params, $brace, $rest ) = $regs;
				if ( isset( $htmlelements[$t = strtolower( $t )] ) ) {
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
		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * Remove '<!--', '-->', and everything between.
	 * To avoid leaving blank lines, when a comment is both preceded
	 * and followed by a newline (ignoring spaces), trim leading and
	 * trailing spaces and one of the newlines.
	 *
	 * @private
	 * @param $text String
	 * @return string
	 */
	static function removeHTMLcomments( $text ) {
		wfProfileIn( __METHOD__ );
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
		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * Take an array of attribute names and values and normalize or discard
	 * illegal values for the given element type.
	 *
	 * - Discards attributes not on a whitelist for the given element
	 * - Unsafe style attributes are discarded
	 * - Invalid id attributes are reencoded
	 *
	 * @param $attribs Array
	 * @param $element String
	 * @return Array
	 *
	 * @todo Check for legal values where the DTD limits things.
	 * @todo Check for unique id attribute :P
	 */
	static function validateTagAttributes( $attribs, $element ) {
		return Sanitizer::validateAttributes( $attribs,
			Sanitizer::attributeWhitelist( $element ) );
	}

	/**
	 * Take an array of attribute names and values and normalize or discard
	 * illegal values for the given whitelist.
	 *
	 * - Discards attributes not the given whitelist
	 * - Unsafe style attributes are discarded
	 * - Invalid id attributes are reencoded
	 *
	 * @param $attribs Array
	 * @param $whitelist Array: list of allowed attribute names
	 * @return Array
	 *
	 * @todo Check for legal values where the DTD limits things.
	 * @todo Check for unique id attribute :P
	 */
	static function validateAttributes( $attribs, $whitelist ) {
		global $wgAllowRdfaAttributes, $wgAllowMicrodataAttributes;

		$whitelist = array_flip( $whitelist );
		$hrefExp = '/^(' . wfUrlProtocols() . ')[^\s]+$/';

		$out = array();
		foreach( $attribs as $attribute => $value ) {
			#allow XML namespace declaration if RDFa is enabled
			if ( $wgAllowRdfaAttributes && preg_match( MW_XMLNS_ATTRIBUTE_PATTRN, $attribute ) ) {
				if ( !preg_match( MW_EVIL_URI_PATTERN, $value ) ) {
					$out[$attribute] = $value;
				}

				continue;
			}

			if( !isset( $whitelist[$attribute] ) ) {
				continue;
			}

			# Strip javascript "expression" from stylesheets.
			# http://msdn.microsoft.com/workshop/author/dhtml/overview/recalc.asp
			if( $attribute == 'style' ) {
				$value = Sanitizer::checkCss( $value );
			}

			if ( $attribute === 'id' ) {
				$value = Sanitizer::escapeId( $value, 'noninitial' );
			}

			//RDFa and microdata properties allow URLs, URIs and/or CURIs. check them for sanity
			if ( $attribute === 'rel' || $attribute === 'rev' || 
				$attribute === 'about' || $attribute === 'property' || $attribute === 'resource' || #RDFa
				$attribute === 'datatype' || $attribute === 'typeof' ||                             #RDFa
				$attribute === 'itemid' || $attribute === 'itemprop' || $attribute === 'itemref' || #HTML5 microdata
				$attribute === 'itemscope' || $attribute === 'itemtype' ) {                         #HTML5 microdata

				//Paranoia. Allow "simple" values but suppress javascript
				if ( preg_match( MW_EVIL_URI_PATTERN, $value ) ) {
					continue; 
				}
			}

			# NOTE: even though elements using href/src are not allowed directly, supply
			#       validation code that can be used by tag hook handlers, etc
			if ( $attribute === 'href' || $attribute === 'src' ) {
				if ( !preg_match( $hrefExp, $value ) ) {
					continue; //drop any href or src attributes not using an allowed protocol.
						  //NOTE: this also drops all relative URLs
				}
			}

			// If this attribute was previously set, override it.
			// Output should only have one attribute of each name.
			$out[$attribute] = $value;
		}

		if ( $wgAllowMicrodataAttributes ) {
			# There are some complicated validity constraints we need to
			# enforce here.  First of all, we don't want to allow non-standard
			# itemtypes.
			$allowedTypes = array(
				'http://microformats.org/profile/hcard',
				'http://microformats.org/profile/hcalendar#vevent',
				'http://n.whatwg.org/work',
			);
			if ( isset( $out['itemtype'] ) && !in_array( $out['itemtype'],
			$allowedTypes ) ) {
				# Kill everything
				unset( $out['itemscope'] );
			}
			# itemtype, itemid, itemref don't make sense without itemscope
			if ( !array_key_exists( 'itemscope', $out ) ) {
				unset( $out['itemtype'] );
				unset( $out['itemid'] );
				unset( $out['itemref'] );
			}
			# TODO: Strip itemprop if we aren't descendants of an itemscope.
		}
		return $out;
	}

	/**
	 * Merge two sets of HTML attributes.  Conflicting items in the second set
	 * will override those in the first, except for 'class' attributes which
	 * will be combined (if they're both strings).
	 *
	 * @todo implement merging for other attributes such as style
	 * @param $a Array
	 * @param $b Array
	 * @return array
	 */
	static function mergeAttributes( $a, $b ) {
		$out = array_merge( $a, $b );
		if( isset( $a['class'] ) && isset( $b['class'] )
		&& is_string( $a['class'] ) && is_string( $b['class'] )
		&& $a['class'] !== $b['class'] ) {
			$classes = preg_split( '/\s+/', "{$a['class']} {$b['class']}",
				-1, PREG_SPLIT_NO_EMPTY );
			$out['class'] = implode( ' ', array_unique( $classes ) );
		}
		return $out;
	}

	/**
	 * Pick apart some CSS and check it for forbidden or unsafe structures.
	 * Returns a sanitized string, or false if it was just too evil.
	 *
	 * Currently URL references, 'expression', 'tps' are forbidden.
	 *
	 * @param $value String
	 * @return Mixed
	 */
	static function checkCss( $value ) {
		$value = Sanitizer::decodeCharReferences( $value );

		// Remove any comments; IE gets token splitting wrong
		$value = StringUtils::delimiterReplace( '/*', '*/', ' ', $value );

		// Decode escape sequences and line continuation
		// See the grammar in the CSS 2 spec, appendix D.
		static $decodeRegex, $reencodeTable;
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
			array( __CLASS__, 'cssDecodeCallback' ), $value );

		// Reject problematic keywords and control characters
		if ( preg_match( '/[\000-\010\016-\037\177]/', $value ) ) {
			return '/* invalid control char */';
		} elseif ( preg_match( '! expression | filter\s*: | accelerator\s*: | url\s*\( !ix', $value ) ) {
			return '/* insecure input */';
		}
		return $value;
	}

	static function cssDecodeCallback( $matches ) {
		if ( $matches[1] !== '' ) {
			// Line continuation
			return '';
		} elseif ( $matches[2] !== '' ) {
			$char = codepointToUtf8( hexdec( $matches[2] ) );
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
	* Take an associative array of attribute name/value pairs
	* and generate a css style representing all the style-related
	* attributes. If there already a style attribute in the array,
	* it is also included in the value returned.
	*/
	static function styleFromAttributes( $attributes ) {
		$styles = array();

		foreach ( $attributes as $attribute => $value ) {
			if ( $attribute == 'bgcolor' ) {
				$styles[] = "background-color: $value";
			} else if ( $attribute == 'border' ) {
				$styles[] = "border-width: $value";
			} else if ( $attribute == 'align' ) {
				$styles[] = "text-align: $value";
			} else if ( $attribute == 'valign' ) {
				$styles[] = "vertical-align: $value";
			} else if ( $attribute == 'width' ) {
				if ( preg_match( '/\d+/', $value ) === false ) {
				      $value .= 'px';
				}

				$styles[] = "width: $value";
			} else if ( $attribute == 'height' ) {
				if ( preg_match( '/\d+/', $value ) === false ) {
				      $value .= 'px';
				}

				$styles[] = "height: $value";
			} else if ( $attribute == 'nowrap' ) {
				if ( $value ) {
					$styles[] = "white-space: nowrap";
				}
			}
		}

		if ( isset( $attributes[ 'style' ] ) ) {
			$styles[] = $attributes[ 'style' ];
		} 

		if ( !$styles ) return '';
		else return implode( '; ', $styles );
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
	 *
	 * @param $text String
	 * @param $element String
	 * @param $defaults Array (optional) associative array of default attributes to splice in. 
	 *			class and style attributes are combined. Otherwise, values from
	 *			$attributes take precedence over values from $defaults.
	 * @return String
	 */
	static function fixTagAttributes( $text, $element, $defaults = null ) {
		if( trim( $text ) == '' ) {
			return '';
		}

		$decoded = Sanitizer::decodeTagAttributes( $text );
		$stripped = Sanitizer::validateTagAttributes( $decoded, $element );
		$attribs = Sanitizer::collapseTagAttributes( $stripped, $defaults );

		return $attribs;
	}

	/**
	 * Take an associative array or attribute name/value pairs
	 * and collapses it to well-formed XML.
	 * Does not filter attributes.
	 * Output is safe for further wikitext processing, with escaping of
	 * values that could trigger problems.
	 *
	 * - Double-quotes all attribute values
	 * - Prepends space if there are attributes.
	 *
	 * @param $attributes Array is an associative array of attribute name/value pairs. 
	 * 			Assumed to be sanitized already.
	 * @param $defaults Array (optional) associative array of default attributes to splice in. 
	 *			class and style attributes are combined. Otherwise, values from
	 *			$attributes take precedence over values from $defaults.
	 * @return String
	 */
	static function collapseTagAttributes( $attributes, $defaults = null ) {
		if ( $defaults ) {
			foreach( $defaults as $attribute => $value ) {
				if ( isset( $attributes[ $attribute ] ) ) {
					if ( $attribute == 'class' ) {
						$value .= ' '. $attributes[ $attribute ];
					} else if ( $attribute == 'style' ) {
						$value .= '; ' . $attributes[ $attribute ];
					} else {
						continue;
					}
				}

				$attributes[ $attribute ] = $value;
			}
		}

		$chunks = array();

		foreach( $attributes as $attribute => $value ) {
			$encAttribute = htmlspecialchars( $attribute );
			$encValue = Sanitizer::safeEncodeAttribute( $value );

			$chunks[] = "$encAttribute=\"$encValue\"";
		}
		return count( $chunks ) ? ' ' . implode( ' ', $chunks ) : '';
	}

	/**
	 * Encode an attribute value for HTML output.
	 * @param $text String
	 * @return HTML-encoded text fragment
	 */
	static function encodeAttribute( $text ) {
		$encValue = htmlspecialchars( $text, ENT_QUOTES );

		// Whitespace is normalized during attribute decoding,
		// so if we've been passed non-spaces we must encode them
		// ahead of time or they won't be preserved.
		$encValue = strtr( $encValue, array(
			"\n" => '&#10;',
			"\r" => '&#13;',
			"\t" => '&#9;',
		) );

		return $encValue;
	}

	/**
	 * Encode an attribute value for HTML tags, with extra armoring
	 * against further wiki processing.
	 * @param $text String
	 * @return HTML-encoded text fragment
	 */
	static function safeEncodeAttribute( $text ) {
		$encValue = Sanitizer::encodeAttribute( $text );

		# Templates and links may be expanded in later parsing,
		# creating invalid or dangerous output. Suppress this.
		$encValue = strtr( $encValue, array(
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
		$encValue = preg_replace_callback(
			'/(' . wfUrlProtocols() . ')/',
			array( 'Sanitizer', 'armorLinksCallback' ),
			$encValue );
		return $encValue;
	}

	/**
	 * Given a value, escape it so that it can be used in an id attribute and
	 * return it.  This will use HTML5 validation if $wgExperimentalHtmlIds is
	 * true, allowing anything but ASCII whitespace.  Otherwise it will use
	 * HTML 4 rules, which means a narrow subset of ASCII, with bad characters
	 * escaped with lots of dots.
	 *
	 * To ensure we don't have to bother escaping anything, we also strip ', ",
	 * & even if $wgExperimentalIds is true.  TODO: Is this the best tactic?
	 * We also strip # because it upsets IE6.
	 *
	 * @see http://www.w3.org/TR/html401/types.html#type-name Valid characters
	 *                                                          in the id and
	 *                                                          name attributes
	 * @see http://www.w3.org/TR/html401/struct/links.html#h-12.2.3 Anchors with the id attribute
	 * @see http://www.whatwg.org/specs/web-apps/current-work/multipage/elements.html#the-id-attribute
	 *   HTML5 definition of id attribute
	 *
	 * @param $id String: id to escape
	 * @param $options Mixed: string or array of strings (default is array()):
	 *   'noninitial': This is a non-initial fragment of an id, not a full id,
	 *       so don't pay attention if the first character isn't valid at the
	 *       beginning of an id.  Only matters if $wgExperimentalHtmlIds is
	 *       false.
	 *   'legacy': Behave the way the old HTML 4-based ID escaping worked even
	 *       if $wgExperimentalHtmlIds is used, so we can generate extra
	 *       anchors and links won't break.
	 * @return String
	 */
	static function escapeId( $id, $options = array() ) {
		global $wgHtml5, $wgExperimentalHtmlIds;
		$options = (array)$options;

		if ( $wgHtml5 && $wgExperimentalHtmlIds && !in_array( 'legacy', $options ) ) {
			$id = Sanitizer::decodeCharReferences( $id );
			$id = preg_replace( '/[ \t\n\r\f_\'"&#]+/', '_', $id );
			$id = trim( $id, '_' );
			if ( $id === '' ) {
				# Must have been all whitespace to start with.
				return '_';
			} else {
				return $id;
			}
		}

		# HTML4-style escaping
		static $replace = array(
			'%3A' => ':',
			'%' => '.'
		);

		$id = urlencode( Sanitizer::decodeCharReferences( strtr( $id, ' ', '_' ) ) );
		$id = str_replace( array_keys( $replace ), array_values( $replace ), $id );

		if ( !preg_match( '/^[a-zA-Z]/', $id )
		&& !in_array( 'noninitial', $options ) )  {
			// Initial character must be a letter!
			$id = "x$id";
		}
		return $id;
	}

	/**
	 * Given a value, escape it so that it can be used as a CSS class and
	 * return it.
	 *
	 * @todo For extra validity, input should be validated UTF-8.
	 *
	 * @see http://www.w3.org/TR/CSS21/syndata.html Valid characters/format
	 *
	 * @param $class String
	 * @return String
	 */
	static function escapeClass( $class ) {
		// Convert ugly stuff to underscores and kill underscores in ugly places
		return rtrim(preg_replace(
			array('/(^[0-9\\-])|[\\x00-\\x20!"#$%&\'()*+,.\\/:;<=>?@[\\]^`{|}~]|\\xC2\\xA0/','/_+/'),
			'_',
			$class ), '_');
	}

	/**
	 * Given HTML input, escape with htmlspecialchars but un-escape entites.
	 * This allows (generally harmless) entities like &#160; to survive.
	 *
	 * @param $html String to escape
	 * @return String: escaped input
	 */
	static function escapeHtmlAllowEntities( $html ) {
		# It seems wise to escape ' as well as ", as a matter of course.  Can't
		# hurt.
		$html = htmlspecialchars( $html, ENT_QUOTES );
		$html = str_replace( '&amp;', '&', $html );
		$html = Sanitizer::normalizeCharReferences( $html );
		return $html;
	}

	/**
	 * Regex replace callback for armoring links against further processing.
	 * @param $matches Array
	 * @return string
	 */
	private static function armorLinksCallback( $matches ) {
		return str_replace( ':', '&#58;', $matches[1] );
	}

	/**
	 * Return an associative array of attribute names and values from
	 * a partial tag string. Attribute names are forces to lowercase,
	 * character references are decoded to UTF-8 text.
	 *
	 * @param $text String
	 * @return Array
	 */
	public static function decodeTagAttributes( $text ) {
		if( trim( $text ) == '' ) {
			return array();
		}

		$attribs = array();
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

			// Normalize whitespace
			$value = preg_replace( '/[\t\r\n ]+/', ' ', $value );
			$value = trim( $value );

			// Decode character references
			$attribs[$attribute] = Sanitizer::decodeCharReferences( $value );
		}
		return $attribs;
	}

	/**
	 * Pick the appropriate attribute value from a match set from the
	 * MW_ATTRIBS_REGEX matches.
	 *
	 * @param $set Array
	 * @return String
	 */
	private static function getTagAttributeCallback( $set ) {
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
			throw new MWException( "Tag conditions not met. This should never happen and is a bug." );
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
	 * @param $text String
	 * @return String
	 */
	private static function normalizeAttributeValue( $text ) {
		return str_replace( '"', '&quot;',
			self::normalizeWhitespace(
				Sanitizer::normalizeCharReferences( $text ) ) );
	}

	private static function normalizeWhitespace( $text ) {
		return preg_replace(
			'/\r\n|[\x20\x0d\x0a\x09]/',
			' ',
			$text );
	}

	/**
	 * Normalizes whitespace in a section name, such as might be returned
	 * by Parser::stripSectionName(), for use in the id's that are used for
	 * section links.
	 *
	 * @param $section String
	 * @return String
	 */
	static function normalizeSectionNameWhitespace( $section ) {
		return trim( preg_replace( '/[ _]+/', ' ', $section ) );
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
	 * @param $text String
	 * @return String
	 * @private
	 */
	static function normalizeCharReferences( $text ) {
		return preg_replace_callback(
			MW_CHAR_REFS_REGEX,
			array( 'Sanitizer', 'normalizeCharReferencesCallback' ),
			$text );
	}
	/**
	 * @param $matches String
	 * @return String
	 */
	static function normalizeCharReferencesCallback( $matches ) {
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
	 * return the named entity reference as is. If the entity is a
	 * MediaWiki-specific alias, returns the HTML equivalent. Otherwise,
	 * returns HTML-escaped text of pseudo-entity source (eg &amp;foo;)
	 *
	 * @param $name String
	 * @return String
	 */
	static function normalizeEntity( $name ) {
		global $wgHtmlEntities, $wgHtmlEntityAliases;
		if ( isset( $wgHtmlEntityAliases[$name] ) ) {
			return "&{$wgHtmlEntityAliases[$name]};";
		} elseif( isset( $wgHtmlEntities[$name] ) ) {
			return "&$name;";
		} else {
			return "&amp;$name;";
		}
	}

	static function decCharReference( $codepoint ) {
		$point = intval( $codepoint );
		if( Sanitizer::validateCodepoint( $point ) ) {
			return sprintf( '&#%d;', $point );
		} else {
			return null;
		}
	}

	static function hexCharReference( $codepoint ) {
		$point = hexdec( $codepoint );
		if( Sanitizer::validateCodepoint( $point ) ) {
			return sprintf( '&#x%x;', $point );
		} else {
			return null;
		}
	}

	/**
	 * Returns true if a given Unicode codepoint is a valid character in XML.
	 * @param $codepoint Integer
	 * @return Boolean
	 */
	private static function validateCodepoint( $codepoint ) {
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
	 * @param $text String
	 * @return String
	 */
	public static function decodeCharReferences( $text ) {
		return preg_replace_callback(
			MW_CHAR_REFS_REGEX,
			array( 'Sanitizer', 'decodeCharReferencesCallback' ),
			$text );
	}

	/**
	 * Decode any character references, numeric or named entities,
	 * in the next and normalize the resulting string. (bug 14952)
	 *
	 * This is useful for page titles, not for text to be displayed,
	 * MediaWiki allows HTML entities to escape normalization as a feature.
	 *
	 * @param $text String (already normalized, containing entities)
	 * @return String (still normalized, without entities)
	 */
	public static function decodeCharReferencesAndNormalize( $text ) {
		global $wgContLang;
		$text = preg_replace_callback(
			MW_CHAR_REFS_REGEX,
			array( 'Sanitizer', 'decodeCharReferencesCallback' ),
			$text, /* limit */ -1, $count );

		if ( $count ) {
			return $wgContLang->normalize( $text );
		} else {
			return $text;
		}
	}

	/**
	 * @param $matches String
	 * @return String
	 */
	static function decodeCharReferencesCallback( $matches ) {
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
	 * @param $codepoint Integer
	 * @return String
	 * @private
	 */
	static function decodeChar( $codepoint ) {
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
	 * @param $name Strings
	 * @return String
	 */
	static function decodeEntity( $name ) {
		global $wgHtmlEntities, $wgHtmlEntityAliases;
		if ( isset( $wgHtmlEntityAliases[$name] ) ) {
			$name = $wgHtmlEntityAliases[$name];
		}
		if( isset( $wgHtmlEntities[$name] ) ) {
			return codepointToUtf8( $wgHtmlEntities[$name] );
		} else {
			return "&$name;";
		}
	}

	/**
	 * Fetch the whitelist of acceptable attributes for a given element name.
	 *
	 * @param $element String
	 * @return Array
	 */
	static function attributeWhitelist( $element ) {
		static $list;
		if( !isset( $list ) ) {
			$list = Sanitizer::setupAttributeWhitelist();
		}
		return isset( $list[$element] )
			? $list[$element]
			: array();
	}

	/**
	 * Foreach array key (an allowed HTML element), return an array
	 * of allowed attributes
	 * @return Array
	 */
	static function setupAttributeWhitelist() {
		global $wgAllowRdfaAttributes, $wgHtml5, $wgAllowMicrodataAttributes;

		$common = array( 'id', 'class', 'lang', 'dir', 'title', 'style' );

		if ( $wgAllowRdfaAttributes ) {
			#RDFa attributes as specified in section 9 of http://www.w3.org/TR/2008/REC-rdfa-syntax-20081014
			$common = array_merge( $common, array(
			    'about', 'property', 'resource', 'datatype', 'typeof', 
			) );
		}

		if ( $wgHtml5 && $wgAllowMicrodataAttributes ) {
			# add HTML5 microdata tages as pecified by http://www.whatwg.org/specs/web-apps/current-work/multipage/microdata.html#the-microdata-model
			$common = array_merge( $common, array(
			    'itemid', 'itemprop', 'itemref', 'itemscope', 'itemtype'
			) );
		}

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
			'abbr'       => $common,
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
										'align', 'bgcolor',
								) ),

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

			# 12.2 # NOTE: <a> is not allowed directly, but the attrib whitelist is used from the Parser object
			'a'          => array_merge( $common, array( 'href', 'rel', 'rev' ) ), # rel/rev esp. for RDFa 

			# 13.2
			# Not usually allowed, but may be used for extension-style hooks
			# such as <math> when it is rasterized, or if $wgAllowImageTag is
			# true
			'img'        => array_merge( $common, array( 'alt', 'src', 'width', 'height' ) ),

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

			# MathML root element, where used for extensions
			# 'title' may not be 100% valid here; it's XHTML
			# http://www.w3.org/TR/REC-MathML/
			'math'       => array( 'class', 'style', 'id', 'title' ),
			);
		return $whitelist;
	}

	/**
	 * Take a fragment of (potentially invalid) HTML and return
	 * a version with any tags removed, encoded as plain text.
	 *
	 * Warning: this return value must be further escaped for literal
	 * inclusion in HTML output as of 1.10!
	 *
	 * @param $text String: HTML fragment
	 * @return String
	 */
	static function stripAllTags( $text ) {
		# Actual <tags>
		$text = StringUtils::delimiterReplace( '<', '>', '', $text );

		# Normalize &entities and whitespace
		$text = self::decodeCharReferences( $text );
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
	 * @return String
	 */
	static function hackDocType() {
		global $wgHtmlEntities;
		$out = "<!DOCTYPE html [\n";
		foreach( $wgHtmlEntities as $entity => $codepoint ) {
			$out .= "<!ENTITY $entity \"&#$codepoint;\">";
		}
		$out .= "]>\n";
		return $out;
	}

	static function cleanUrl( $url ) {
		# Normalize any HTML entities in input. They will be
		# re-escaped by makeExternalLink().
		$url = Sanitizer::decodeCharReferences( $url );

		# Escape any control characters introduced by the above step
		$url = preg_replace( '/[\][<>"\\x00-\\x20\\x7F\|]/e', "urlencode('\\0')", $url );

		# Validate hostname portion
		$matches = array();
		if( preg_match( '!^([^:]+:)(//[^/]+)?(.*)$!iD', $url, $matches ) ) {
			list( /* $whole */, $protocol, $host, $rest ) = $matches;

			// Characters that will be ignored in IDNs.
			// http://tools.ietf.org/html/3454#section-3.1
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
				[\xef\xb8\x80-\xef\xb8\x8f] # fe00-fe00f VARIATION SELECTOR-1-16
				/xuD";

			$host = preg_replace( $strip, '', $host );

			// @todo Fixme: validate hostnames here

			return $protocol . $host . $rest;
		} else {
			return $url;
		}
	}

}
