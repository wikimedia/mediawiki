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
 */

class Sanitizer {
	/**
	 * Cleans up HTML, removes dangerous tags and attributes, and
	 * removes HTML comments
	 * @access private
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

		$htmlattrs = Sanitizer::getHTMLattrs () ;

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
						$newparams = Sanitizer::fixTagAttributes($params);

					}
					if ( ! $badtag ) {
						$rest = str_replace( '>', '&gt;', $rest );
						$text .= "<$slash$t $newparams$brace$rest";
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
					$newparams = Sanitizer::fixTagAttributes($params);
					$rest = str_replace( '>', '&gt;', $rest );
					$text .= "<$slash$t $newparams$brace$rest";
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
	 * Return allowed HTML attributes
	 *
	 * @access private
	 */
	function getHTMLattrs () {
		$htmlattrs = array( # Allowed attributes--no scripting, etc.
				'title', 'align', 'lang', 'dir', 'width', 'height',
				'bgcolor', 'clear', /* BR */ 'noshade', /* HR */
				'cite', /* BLOCKQUOTE, Q */ 'size', 'face', 'color',
				/* FONT */ 'type', 'start', 'value', 'compact',
				/* For various lists, mostly deprecated but safe */
				'summary', 'width', 'border', 'frame', 'rules',
				'cellspacing', 'cellpadding', 'valign', 'char',
				'charoff', 'colgroup', 'col', 'span', 'abbr', 'axis',
				'headers', 'scope', 'rowspan', 'colspan', /* Tables */
				'id', 'class', 'name', 'style' /* For CSS */
				);
		return $htmlattrs ;
	}

	/**
	 * Remove non approved attributes and javascript in css
	 *
	 * @access private
	 */
	function fixTagAttributes ( $t ) {
		if ( trim ( $t ) == '' ) return '' ; # Saves runtime ;-)
		$htmlattrs = Sanitizer::getHTMLattrs() ;

		# Strip non-approved attributes from the tag
		$t = preg_replace(
			'/(\\w+)(\\s*=\\s*([^\\s\">]+|\"[^\">]*\"))?/e',
			"(in_array(strtolower(\"\$1\"),\$htmlattrs)?(\"\$1\".((\"x\$3\" != \"x\")?\"=\$3\":'')):'')",
			$t);

		$t = str_replace ( '<></>' , '' , $t ) ; # This should fix bug 980557

		# Strip javascript "expression" from stylesheets. Brute force approach:
		# If anythin offensive is found, all attributes of the HTML tag are dropped

		if( preg_match(
			'/style\\s*=.*(expression|tps*:\/\/|url\\s*\().*/is',
			wfMungeToUtf8( $t ) ) )
		{
			$t='';
		}

		return trim ( $t ) ;
	}

}

?>