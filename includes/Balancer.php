<?php
/**
 * An HTML5 Balancer, per the specification in
 * https://phabricator.wikimedia.org/T114445
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
 * @since 1.27
 * @author C. Scott Ananian, 2016
 */
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\ParameterAssertionException;

/**
 * An HTML5 Balancer.
 *
 * The following simplifications have been made:
 * - We handle body content only (ie, we start `in body`.)
 * - The document is never in "quirks mode".
 * - All occurrences of < and > have been entity escaped, so we
 *   can parse tags by simply splitting on those two characters.
 *   Similarly, all attributes have been "cleaned" and are double-quoted
 *   and escaped.
 * - All comments and null characters are assumed to have been removed.
 *
 * @ingroup Parser
 * @since 1.27
 * @see https://html.spec.whatwg.org/multipage/syntax.html#tree-construction
 */
class Balancer {
	private static $blockBlacklist = [
		'p' => true, 'a' => true, 'table' => true, 'h1' => true, 'h2' => true,
		'h3' => true, 'h4' => true, 'h5' => true, 'h6' => true,
		'style' => true, 'script' => true, 'xmp' => true, 'iframe' => true,
		'noembed' => true, 'noframes' => true, 'plaintext' => true,
		'noscript' => true, 'textarea' => true, 'select' => true,
		'template' => true, 'dd' => true, 'dt' => true, 'pre' => true
	];
	private static $emptyElements = [
		'area' => true, 'base' => true, 'basefont' => true,
		'bgsound' => true, 'br' => true, 'col' => true, 'command' => true,
		'embed' => true, 'frame' => true, 'hr' => true, 'img' => true,
		'input' => true, 'keygen' => true, 'link' => true, 'meta' => true,
		'param' => true, 'source' => true, 'track' => true, 'wbr' => true
	];

	/**
	 * Return a balanced HTML string for the HTML fragment given by $text,
	 * subject to the caveats listed in the class description.
	 *
	 * @param string $text The markup to be balanced
	 * @return string The balanced markup
	 */
	public static function balance( $text ) {
		$bitsIterator = new ExplodeIterator( '<', $text );
		$stack = [];
		$mode = [ 'outer' ];

		// First element is text not tag
		$result = $bitsIterator->current();
		$bitsIterator->next();

		// Now process each tag.
		while ( $bitsIterator->valid() ) {
			$x = $bitsIterator->current();
			$bitsIterator->next();
			$regs = [];
			# $slash: Does the current element start with a '/'?
			# $t: Current element name
			# $attribs: String between element name and >
			# $brace: Ending '>' or '/>'
			# $rest: Everything until the next element from the $bitsIterator
			if ( preg_match( Sanitizer::ELEMENT_BITS_REGEX, $x, $regs ) ) {
				list( /* $qbar */, $slash, $t, $attribs, $brace, $rest ) = $regs;
				$t = strtolower( $t );
			} else {
				$result .= '&lt;' . str_replace( '>', '&gt;', $x );
				Assert::invariant( false, "Bad HTML input" );
				continue;
			}
			if ( $t === 'mw:balance-block' ) {
				$j = 0;
				if ( $slash ) {
					// Find the matching balance tag
					for ( $j = count( $stack ) - 1; $j >= 0; $j-- ) {
						if ( $stack[$j] === $t )
							break;
					}
					// Point right after the matching tag.
					$j += 1;
				} else {
					// close blacklisted tags in outer context
					for ( $len = count( $stack ); $j < $len; $j++ ) {
						if ( Balancer::$blockBlacklist[$stack[$j]] )
							break;
					}
				}
				while ( $j < count( $stack ) ) {
					$result .= '</' . array_pop( $stack ) . '>';
				}
			}
			if ( $slash ) {
				if ( $stack[count( $stack ) - 1] === $t ) {
					array_pop( $stack );
					$result .= '<' . $x;
					continue;
				} else {
					$result .= $rest;
					continue;
				}
			}
			if ( !Balancer::$emptyElements[ $t ] ) {
				array_push( $stack, $t );
			}
			$result .= '<' . $x;
		}

		return $result;
	}
}
