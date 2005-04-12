<?php

/**
 * (X)HTML sanitizer for MediaWiki
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @package MediaWiki
 * @subpackage Parser
 */

class Sanitizer {
	/**
	 * Cleans up HTML, removes dangerous tags and attributes, and
	 * removes HTML comments
	 * @access private
	 * @param string $text
	 * @return string
	 */
	function removeHTMLtags( $text ) {
		global $wgUseTidy, $wgUserHtml;
		$fname = 'Parser::removeHTMLtags';
		wfProfileIn( $fname );

		if( $wgUserHtml ) {
			$htmlpairs = array( # Tags that must be closed
				'b', 'del', 'i', 'ins', 'u', 'font', 'big', 'small', 'sub', 'sup', 'h1',
				'h2', 'h3', 'h4', 'h5', 'h6', 'cite', 'code', 'em', 's',
				'strike', 'strong', 'tt', 'var', 'div', 'center',
				'blockquote', 'ol', 'ul', 'dl', 'table', 'caption', 'pre',
				'ruby', 'rt' , 'rb' , 'rp', 'p', 'span'
			);
			$htmlsingle = array(
				'br', 'hr', 'li', 'dt', 'dd'
			);
			$htmlnest = array( # Tags that can be nested--??
				'table', 'tr', 'td', 'th', 'div', 'blockquote', 'ol', 'ul',
				'dl', 'font', 'big', 'small', 'sub', 'sup', 'span'
			);
			$tabletags = array( # Can only appear inside table
				'td', 'th', 'tr'
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
				preg_match( '/^(\\/?)(\\w+)([^>]*)(\\/{0,1}>)([^<]*)$/',
				$x, $regs );
				list( $qbar, $slash, $t, $params, $brace, $rest ) = $regs;
				error_reporting( $prev );

				$badtag = 0 ;
				if ( in_array( $t = strtolower( $t ), $htmlelements ) ) {
					# Check our stack
					if ( $slash ) {
						# Closing a tag...
						if ( ! in_array( $t, $htmlsingle ) &&
						( $ot = @array_pop( $tagstack ) ) != $t ) {
							@array_push( $tagstack, $ot );
							$badtag = 1;
						} else {
							if ( $t == 'table' ) {
								$tagstack = array_pop( $tablestack );
							}
							$newparams = '';
						}
					} else {
						# Keep track for later
						if ( in_array( $t, $tabletags ) &&
						! in_array( 'table', $tagstack ) ) {
							$badtag = 1;
						} else if ( in_array( $t, $tagstack ) &&
						! in_array ( $t , $htmlnest ) ) {
							$badtag = 1 ;
						} else if ( ! in_array( $t, $htmlsingle ) ) {
							if ( $t == 'table' ) {
								array_push( $tablestack, $tagstack );
								$tagstack = array();
							}
							array_push( $tagstack, $t );
						}
						# Strip non-approved attributes from the tag
						$newparams = Sanitizer::fixTagAttributes( $params, $t );
					}
					if ( ! $badtag ) {
						$rest = str_replace( '>', '&gt;', $rest );
						$text .= "<$slash$t$newparams$brace$rest";
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
				preg_match( '/^(\\/?)(\\w+)([^>]*)(\\/{0,1}>)([^<]*)$/',
				$x, $regs );
				@list( $qbar, $slash, $t, $params, $brace, $rest ) = $regs;
				if ( in_array( $t = strtolower( $t ), $htmlelements ) ) {
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
	 * @access private
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
		
		$attrib = '[A-Za-z0-9]'; #FIXME
		$space = '[\x09\x0a\x0d\x20]';
		if( !preg_match_all(
			"/(?:^|$space)($attrib+)
			  ($space*=$space*
			    (?:
			     # The attribute value: quoted or alone
			      \"([^<\"]*)\"
			     | '([^<']*)'
			     |  ([a-zA-Z0-9._:-]+)
			     |  (\#[0-9a-fA-F]+) # Technically wrong, but lots of
			                         # colors are specified like this.
			                         # We'll be normalizing it.
			    )
			   )?(?=$space|\$)/sx",
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
			if( $set[2] == '' ) {
				# In XHTML, attributes must have a value.
				$value = $set[1];
			} elseif( $set[3] != '' ) {
				# Double-quoted
				$value = Sanitizer::normalizeAttributeValue( $set[3] );
			} elseif( $set[4] != '' ) {
				# Single-quoted
				$value = str_replace( '"', '&quot;',
					Sanitizer::normalizeAttributeValue( $set[4] ) );
			} elseif( $set[5] != '' ) {
				# No quotes.
				$value = Sanitizer::normalizeAttributeValue( $set[5] );
			} elseif( $set[6] != '' ) {
				# Illegal #XXXXXX color with no quotes.
				$value = Sanitizer::normalizeAttributeValue( $set[6] );
			} else {
				wfDebugDieBacktrace( "Tag conditions not met. Something's very odd." );
			}
			
			# Strip javascript "expression" from stylesheets.
			# http://msdn.microsoft.com/workshop/author/dhtml/overview/recalc.asp
			if( $attribute == 'style' && preg_match(
				'/(expression|tps*:\/\/|url\\s*\().*/is',
					wfMungeToUtf8( $value ) ) ) {
				# haxx0r
				continue;
			}
			
			if( !isset( $attribs[$attribute] ) ) {
				$attribs[$attribute] = "$attribute=\"$value\"";
			}
		}
		if( empty( $attribs ) ) {
			return '';
		} else {
			return ' ' . implode( ' ', $attribs );
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
	 * @access private
	 */
	function normalizeAttributeValue( $text ) {
		return preg_replace(
			'/\r\n|[\x20\x0d\x0a\x09]/',
			' ',
			Sanitizer::normalizeCharReferences( $text ) );
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
	 * @access private
	 */
	function normalizeCharReferences( $text ) {
		return preg_replace_callback(
			'/&([A-Za-z0-9]+);
			 |&\#([0-9]+);
			 |&\#x([0-9A-Za-z]+);
			 |&\#X([0-9A-Za-z]+);
			 |(&)/x',
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
		# List of all named character entities defined in HTML 4.01
		# http://www.w3.org/TR/html4/sgml/entities.html
		static $htmlEntities = array(
			'aacute' => true,
			'Aacute' => true,
			'acirc' => true,
			'Acirc' => true,
			'acute' => true,
			'aelig' => true,
			'AElig' => true,
			'agrave' => true,
			'Agrave' => true,
			'alefsym' => true,
			'alpha' => true,
			'Alpha' => true,
			'amp' => true,
			'and' => true,
			'ang' => true,
			'apos' => true,
			'aring' => true,
			'Aring' => true,
			'asymp' => true,
			'atilde' => true,
			'Atilde' => true,
			'auml' => true,
			'Auml' => true,
			'bdquo' => true,
			'beta' => true,
			'Beta' => true,
			'brvbar' => true,
			'bull' => true,
			'cap' => true,
			'ccedil' => true,
			'Ccedil' => true,
			'cedil' => true,
			'cent' => true,
			'chi' => true,
			'Chi' => true,
			'circ' => true,
			'clubs' => true,
			'cong' => true,
			'copy' => true,
			'crarr' => true,
			'cup' => true,
			'curren' => true,
			'dagger' => true,
			'Dagger' => true,
			'darr' => true,
			'dArr' => true,
			'deg' => true,
			'delta' => true,
			'Delta' => true,
			'diams' => true,
			'divide' => true,
			'eacute' => true,
			'Eacute' => true,
			'ecirc' => true,
			'Ecirc' => true,
			'egrave' => true,
			'Egrave' => true,
			'empty' => true,
			'emsp' => true,
			'ensp' => true,
			'epsilon' => true,
			'Epsilon' => true,
			'equiv' => true,
			'eta' => true,
			'Eta' => true,
			'eth' => true,
			'ETH' => true,
			'euml' => true,
			'Euml' => true,
			'euro' => true,
			'exist' => true,
			'fnof' => true,
			'forall' => true,
			'frac12' => true,
			'frac14' => true,
			'frac34' => true,
			'frasl' => true,
			'gamma' => true,
			'Gamma' => true,
			'ge' => true,
			'gt' => true,
			'harr' => true,
			'hArr' => true,
			'hearts' => true,
			'hellip' => true,
			'iacute' => true,
			'Iacute' => true,
			'icirc' => true,
			'Icirc' => true,
			'iexcl' => true,
			'igrave' => true,
			'Igrave' => true,
			'image' => true,
			'infin' => true,
			'int' => true,
			'iota' => true,
			'Iota' => true,
			'iquest' => true,
			'isin' => true,
			'iuml' => true,
			'Iuml' => true,
			'kappa' => true,
			'Kappa' => true,
			'lambda' => true,
			'Lambda' => true,
			'lang' => true,
			'laquo' => true,
			'larr' => true,
			'lArr' => true,
			'lceil' => true,
			'ldquo' => true,
			'le' => true,
			'lfloor' => true,
			'lowast' => true,
			'loz' => true,
			'lrm' => true,
			'lsaquo' => true,
			'lsquo' => true,
			'lt' => true,
			'macr' => true,
			'mdash' => true,
			'micro' => true,
			'middot' => true,
			'minus' => true,
			'mu' => true,
			'Mu' => true,
			'nabla' => true,
			'nbsp' => true,
			'ndash' => true,
			'ne' => true,
			'ni' => true,
			'not' => true,
			'notin' => true,
			'nsub' => true,
			'ntilde' => true,
			'Ntilde' => true,
			'nu' => true,
			'Nu' => true,
			'oacute' => true,
			'Oacute' => true,
			'ocirc' => true,
			'Ocirc' => true,
			'oelig' => true,
			'OElig' => true,
			'ograve' => true,
			'Ograve' => true,
			'oline' => true,
			'omega' => true,
			'Omega' => true,
			'omicron' => true,
			'Omicron' => true,
			'oplus' => true,
			'or' => true,
			'ordf' => true,
			'ordm' => true,
			'oslash' => true,
			'Oslash' => true,
			'otilde' => true,
			'Otilde' => true,
			'otimes' => true,
			'ouml' => true,
			'Ouml' => true,
			'para' => true,
			'part' => true,
			'permil' => true,
			'perp' => true,
			'phi' => true,
			'Phi' => true,
			'pi' => true,
			'Pi' => true,
			'piv' => true,
			'plusmn' => true,
			'pound' => true,
			'prime' => true,
			'Prime' => true,
			'prod' => true,
			'prop' => true,
			'psi' => true,
			'Psi' => true,
			'quot' => true,
			'radic' => true,
			'rang' => true,
			'raquo' => true,
			'rarr' => true,
			'rArr' => true,
			'rceil' => true,
			'rdquo' => true,
			'real' => true,
			'reg' => true,
			'rfloor' => true,
			'rho' => true,
			'Rho' => true,
			'rlm' => true,
			'rsaquo' => true,
			'rsquo' => true,
			'sbquo' => true,
			'scaron' => true,
			'Scaron' => true,
			'sdot' => true,
			'sect' => true,
			'shy' => true,
			'sigma' => true,
			'Sigma' => true,
			'sigmaf' => true,
			'sim' => true,
			'spades' => true,
			'sub' => true,
			'sube' => true,
			'sum' => true,
			'sup' => true,
			'sup1' => true,
			'sup2' => true,
			'sup3' => true,
			'supe' => true,
			'szlig' => true,
			'tau' => true,
			'Tau' => true,
			'there4' => true,
			'theta' => true,
			'Theta' => true,
			'thetasym' => true,
			'thinsp' => true,
			'thorn' => true,
			'THORN' => true,
			'tilde' => true,
			'times' => true,
			'trade' => true,
			'uacute' => true,
			'Uacute' => true,
			'uarr' => true,
			'uArr' => true,
			'ucirc' => true,
			'Ucirc' => true,
			'ugrave' => true,
			'Ugrave' => true,
			'uml' => true,
			'upsih' => true,
			'upsilon' => true,
			'Upsilon' => true,
			'uuml' => true,
			'Uuml' => true,
			'weierp' => true,
			'xi' => true,
			'Xi' => true,
			'yacute' => true,
			'Yacute' => true,
			'yen' => true,
			'yuml' => true,
			'Yuml' => true,
			'zeta' => true,
			'Zeta' => true,
			'zwj' => true,
			'zwnj' => true );
		if( isset( $htmlEntities[$name] ) ) {
			return "&$name;";
		} else {
			return "&amp;$name;";
		}
	}
	
	function decCharReference( $codepoint ) {
		$point = IntVal( $codepoint );
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
	 * Fetch the whitelist of acceptable attributes for a given
	 * element name.
	 *
	 * @param string $element
	 * @return array
	 */
	function attributeWhitelist( $element ) {
		$list = Sanitizer::setupAttributeWhitelist();
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
		                    'width', # deprecated
		                    'height' # deprecated
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
		$text = preg_replace( '/<[^>]*>/', '', $text );
		
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

}

?>
