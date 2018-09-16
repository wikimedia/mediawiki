<?php
/**
 * Preprocessor using PHP arrays
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
 * Differences from DOM schema:
 *   * attribute nodes are children
 *   * "<h>" nodes that aren't at the top are replaced with <possible-h>
 *
 * Nodes are stored in a recursive array data structure. A node store is an
 * array where each element may be either a scalar (representing a text node)
 * or a "descriptor", which is a two-element array where the first element is
 * the node name and the second element is the node store for the children.
 *
 * Attributes are represented as children that have a node name starting with
 * "@", and a single text node child.
 *
 * @todo: Consider replacing descriptor arrays with objects of a new class.
 * Benchmark and measure resulting memory impact.
 *
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class Preprocessor_Hash extends Preprocessor {

	/**
	 * @var Parser
	 */
	public $parser;

	const CACHE_PREFIX = 'preprocess-hash';
	const CACHE_VERSION = 2;

	public function __construct( $parser ) {
		$this->parser = $parser;
	}

	/**
	 * @return PPFrame_Hash
	 */
	public function newFrame() {
		return new PPFrame_Hash( $this );
	}

	/**
	 * @param array $args
	 * @return PPCustomFrame_Hash
	 */
	public function newCustomFrame( $args ) {
		return new PPCustomFrame_Hash( $this, $args );
	}

	/**
	 * @param array $values
	 * @return PPNode_Hash_Array
	 */
	public function newPartNodeArray( $values ) {
		$list = [];

		foreach ( $values as $k => $val ) {
			if ( is_int( $k ) ) {
				$store = [ [ 'part', [
					[ 'name', [ [ '@index', [ $k ] ] ] ],
					[ 'value', [ strval( $val ) ] ],
				] ] ];
			} else {
				$store = [ [ 'part', [
					[ 'name', [ strval( $k ) ] ],
					'=',
					[ 'value', [ strval( $val ) ] ],
				] ] ];
			}

			$list[] = new PPNode_Hash_Tree( $store, 0 );
		}

		$node = new PPNode_Hash_Array( $list );
		return $node;
	}

	/**
	 * Preprocess some wikitext and return the document tree.
	 *
	 * @param string $text The text to parse
	 * @param int $flags Bitwise combination of:
	 *    Parser::PTD_FOR_INCLUSION    Handle "<noinclude>" and "<includeonly>" as if the text is being
	 *                                 included. Default is to assume a direct page view.
	 *
	 * The generated DOM tree must depend only on the input text and the flags.
	 * The DOM tree must be the same in OT_HTML and OT_WIKI mode, to avoid a regression of T6899.
	 *
	 * Any flag added to the $flags parameter here, or any other parameter liable to cause a
	 * change in the DOM tree for a given text, must be passed through the section identifier
	 * in the section edit link and thus back to extractSections().
	 *
	 * @throws MWException
	 * @return PPNode_Hash_Tree
	 */
	public function preprocessToObj( $text, $flags = 0 ) {
		global $wgDisableLangConversion;

		$tree = $this->cacheGetTree( $text, $flags );
		if ( $tree !== false ) {
			$store = json_decode( $tree );
			if ( is_array( $store ) ) {
				return new PPNode_Hash_Tree( $store, 0 );
			}
		}

		$forInclusion = $flags & Parser::PTD_FOR_INCLUSION;

		$xmlishElements = $this->parser->getStripList();
		$xmlishAllowMissingEndTag = [ 'includeonly', 'noinclude', 'onlyinclude' ];
		$enableOnlyinclude = false;
		if ( $forInclusion ) {
			$ignoredTags = [ 'includeonly', '/includeonly' ];
			$ignoredElements = [ 'noinclude' ];
			$xmlishElements[] = 'noinclude';
			if ( strpos( $text, '<onlyinclude>' ) !== false
				&& strpos( $text, '</onlyinclude>' ) !== false
			) {
				$enableOnlyinclude = true;
			}
		} else {
			$ignoredTags = [ 'noinclude', '/noinclude', 'onlyinclude', '/onlyinclude' ];
			$ignoredElements = [ 'includeonly' ];
			$xmlishElements[] = 'includeonly';
		}
		$xmlishRegex = implode( '|', array_merge( $xmlishElements, $ignoredTags ) );

		// Use "A" modifier (anchored) instead of "^", because ^ doesn't work with an offset
		$elementsRegex = "~($xmlishRegex)(?:\s|\/>|>)|(!--)~iA";

		$stack = new PPDStack_Hash;

		$searchBase = "[{<\n";
		if ( !$wgDisableLangConversion ) {
			$searchBase .= '-';
		}

		// For fast reverse searches
		$revText = strrev( $text );
		$lengthText = strlen( $text );

		// Input pointer, starts out pointing to a pseudo-newline before the start
		$i = 0;
		// Current accumulator. See the doc comment for Preprocessor_Hash for the format.
		$accum =& $stack->getAccum();
		// True to find equals signs in arguments
		$findEquals = false;
		// True to take notice of pipe characters
		$findPipe = false;
		$headingIndex = 1;
		// True if $i is inside a possible heading
		$inHeading = false;
		// True if there are no more greater-than (>) signs right of $i
		$noMoreGT = false;
		// Map of tag name => true if there are no more closing tags of given type right of $i
		$noMoreClosingTag = [];
		// True to ignore all input up to the next <onlyinclude>
		$findOnlyinclude = $enableOnlyinclude;
		// Do a line-start run without outputting an LF character
		$fakeLineStart = true;

		while ( true ) {
			// $this->memCheck();

			if ( $findOnlyinclude ) {
				// Ignore all input up to the next <onlyinclude>
				$startPos = strpos( $text, '<onlyinclude>', $i );
				if ( $startPos === false ) {
					// Ignored section runs to the end
					$accum[] = [ 'ignore', [ substr( $text, $i ) ] ];
					break;
				}
				$tagEndPos = $startPos + strlen( '<onlyinclude>' ); // past-the-end
				$accum[] = [ 'ignore', [ substr( $text, $i, $tagEndPos - $i ) ] ];
				$i = $tagEndPos;
				$findOnlyinclude = false;
			}

			if ( $fakeLineStart ) {
				$found = 'line-start';
				$curChar = '';
			} else {
				# Find next opening brace, closing brace or pipe
				$search = $searchBase;
				if ( $stack->top === false ) {
					$currentClosing = '';
				} else {
					$currentClosing = $stack->top->close;
					$search .= $currentClosing;
				}
				if ( $findPipe ) {
					$search .= '|';
				}
				if ( $findEquals ) {
					// First equals will be for the template
					$search .= '=';
				}
				$rule = null;
				# Output literal section, advance input counter
				$literalLength = strcspn( $text, $search, $i );
				if ( $literalLength > 0 ) {
					self::addLiteral( $accum, substr( $text, $i, $literalLength ) );
					$i += $literalLength;
				}
				if ( $i >= $lengthText ) {
					if ( $currentClosing == "\n" ) {
						// Do a past-the-end run to finish off the heading
						$curChar = '';
						$found = 'line-end';
					} else {
						# All done
						break;
					}
				} else {
					$curChar = $curTwoChar = $text[$i];
					if ( ( $i + 1 ) < $lengthText ) {
						$curTwoChar .= $text[$i + 1];
					}
					if ( $curChar == '|' ) {
						$found = 'pipe';
					} elseif ( $curChar == '=' ) {
						$found = 'equals';
					} elseif ( $curChar == '<' ) {
						$found = 'angle';
					} elseif ( $curChar == "\n" ) {
						if ( $inHeading ) {
							$found = 'line-end';
						} else {
							$found = 'line-start';
						}
					} elseif ( $curTwoChar == $currentClosing ) {
						$found = 'close';
						$curChar = $curTwoChar;
					} elseif ( $curChar == $currentClosing ) {
						$found = 'close';
					} elseif ( isset( $this->rules[$curTwoChar] ) ) {
						$curChar = $curTwoChar;
						$found = 'open';
						$rule = $this->rules[$curChar];
					} elseif ( isset( $this->rules[$curChar] ) ) {
						$found = 'open';
						$rule = $this->rules[$curChar];
					} else {
						# Some versions of PHP have a strcspn which stops on
						# null characters; ignore these and continue.
						# We also may get '-' and '}' characters here which
						# don't match -{ or $currentClosing.  Add these to
						# output and continue.
						if ( $curChar == '-' || $curChar == '}' ) {
							self::addLiteral( $accum, $curChar );
						}
						++$i;
						continue;
					}
				}
			}

			if ( $found == 'angle' ) {
				$matches = false;
				// Handle </onlyinclude>
				if ( $enableOnlyinclude
					&& substr( $text, $i, strlen( '</onlyinclude>' ) ) == '</onlyinclude>'
				) {
					$findOnlyinclude = true;
					continue;
				}

				// Determine element name
				if ( !preg_match( $elementsRegex, $text, $matches, 0, $i + 1 ) ) {
					// Element name missing or not listed
					self::addLiteral( $accum, '<' );
					++$i;
					continue;
				}
				// Handle comments
				if ( isset( $matches[2] ) && $matches[2] == '!--' ) {
					// To avoid leaving blank lines, when a sequence of
					// space-separated comments is both preceded and followed by
					// a newline (ignoring spaces), then
					// trim leading and trailing spaces and the trailing newline.

					// Find the end
					$endPos = strpos( $text, '-->', $i + 4 );
					if ( $endPos === false ) {
						// Unclosed comment in input, runs to end
						$inner = substr( $text, $i );
						$accum[] = [ 'comment', [ $inner ] ];
						$i = $lengthText;
					} else {
						// Search backwards for leading whitespace
						$wsStart = $i ? ( $i - strspn( $revText, " \t", $lengthText - $i ) ) : 0;

						// Search forwards for trailing whitespace
						// $wsEnd will be the position of the last space (or the '>' if there's none)
						$wsEnd = $endPos + 2 + strspn( $text, " \t", $endPos + 3 );

						// Keep looking forward as long as we're finding more
						// comments.
						$comments = [ [ $wsStart, $wsEnd ] ];
						while ( substr( $text, $wsEnd + 1, 4 ) == '<!--' ) {
							$c = strpos( $text, '-->', $wsEnd + 4 );
							if ( $c === false ) {
								break;
							}
							$c = $c + 2 + strspn( $text, " \t", $c + 3 );
							$comments[] = [ $wsEnd + 1, $c ];
							$wsEnd = $c;
						}

						// Eat the line if possible
						// TODO: This could theoretically be done if $wsStart == 0, i.e. for comments at
						// the overall start. That's not how Sanitizer::removeHTMLcomments() did it, but
						// it's a possible beneficial b/c break.
						if ( $wsStart > 0 && substr( $text, $wsStart - 1, 1 ) == "\n"
							&& substr( $text, $wsEnd + 1, 1 ) == "\n"
						) {
							// Remove leading whitespace from the end of the accumulator
							$wsLength = $i - $wsStart;
							$endIndex = count( $accum ) - 1;

							// Sanity check
							if ( $wsLength > 0
								&& $endIndex >= 0
								&& is_string( $accum[$endIndex] )
								&& strspn( $accum[$endIndex], " \t", -$wsLength ) === $wsLength
							) {
								$accum[$endIndex] = substr( $accum[$endIndex], 0, -$wsLength );
							}

							// Dump all but the last comment to the accumulator
							foreach ( $comments as $j => $com ) {
								$startPos = $com[0];
								$endPos = $com[1] + 1;
								if ( $j == ( count( $comments ) - 1 ) ) {
									break;
								}
								$inner = substr( $text, $startPos, $endPos - $startPos );
								$accum[] = [ 'comment', [ $inner ] ];
							}

							// Do a line-start run next time to look for headings after the comment
							$fakeLineStart = true;
						} else {
							// No line to eat, just take the comment itself
							$startPos = $i;
							$endPos += 2;
						}

						if ( $stack->top ) {
							$part = $stack->top->getCurrentPart();
							if ( !( isset( $part->commentEnd ) && $part->commentEnd == $wsStart - 1 ) ) {
								$part->visualEnd = $wsStart;
							}
							// Else comments abutting, no change in visual end
							$part->commentEnd = $endPos;
						}
						$i = $endPos + 1;
						$inner = substr( $text, $startPos, $endPos - $startPos + 1 );
						$accum[] = [ 'comment', [ $inner ] ];
					}
					continue;
				}
				$name = $matches[1];
				$lowerName = strtolower( $name );
				$attrStart = $i + strlen( $name ) + 1;

				// Find end of tag
				$tagEndPos = $noMoreGT ? false : strpos( $text, '>', $attrStart );
				if ( $tagEndPos === false ) {
					// Infinite backtrack
					// Disable tag search to prevent worst-case O(N^2) performance
					$noMoreGT = true;
					self::addLiteral( $accum, '<' );
					++$i;
					continue;
				}

				// Handle ignored tags
				if ( in_array( $lowerName, $ignoredTags ) ) {
					$accum[] = [ 'ignore', [ substr( $text, $i, $tagEndPos - $i + 1 ) ] ];
					$i = $tagEndPos + 1;
					continue;
				}

				$tagStartPos = $i;
				if ( $text[$tagEndPos - 1] == '/' ) {
					// Short end tag
					$attrEnd = $tagEndPos - 1;
					$inner = null;
					$i = $tagEndPos + 1;
					$close = null;
				} else {
					$attrEnd = $tagEndPos;
					// Find closing tag
					if (
						!isset( $noMoreClosingTag[$name] ) &&
						preg_match( "/<\/" . preg_quote( $name, '/' ) . "\s*>/i",
							$text, $matches, PREG_OFFSET_CAPTURE, $tagEndPos + 1 )
					) {
						$inner = substr( $text, $tagEndPos + 1, $matches[0][1] - $tagEndPos - 1 );
						$i = $matches[0][1] + strlen( $matches[0][0] );
						$close = $matches[0][0];
					} else {
						// No end tag
						if ( in_array( $name, $xmlishAllowMissingEndTag ) ) {
							// Let it run out to the end of the text.
							$inner = substr( $text, $tagEndPos + 1 );
							$i = $lengthText;
							$close = null;
						} else {
							// Don't match the tag, treat opening tag as literal and resume parsing.
							$i = $tagEndPos + 1;
							self::addLiteral( $accum,
								substr( $text, $tagStartPos, $tagEndPos + 1 - $tagStartPos ) );
							// Cache results, otherwise we have O(N^2) performance for input like <foo><foo><foo>...
							$noMoreClosingTag[$name] = true;
							continue;
						}
					}
				}
				// <includeonly> and <noinclude> just become <ignore> tags
				if ( in_array( $lowerName, $ignoredElements ) ) {
					$accum[] = [ 'ignore', [ substr( $text, $tagStartPos, $i - $tagStartPos ) ] ];
					continue;
				}

				if ( $attrEnd <= $attrStart ) {
					$attr = '';
				} else {
					// Note that the attr element contains the whitespace between name and attribute,
					// this is necessary for precise reconstruction during pre-save transform.
					$attr = substr( $text, $attrStart, $attrEnd - $attrStart );
				}

				$children = [
					[ 'name', [ $name ] ],
					[ 'attr', [ $attr ] ] ];
				if ( $inner !== null ) {
					$children[] = [ 'inner', [ $inner ] ];
				}
				if ( $close !== null ) {
					$children[] = [ 'close', [ $close ] ];
				}
				$accum[] = [ 'ext', $children ];
			} elseif ( $found == 'line-start' ) {
				// Is this the start of a heading?
				// Line break belongs before the heading element in any case
				if ( $fakeLineStart ) {
					$fakeLineStart = false;
				} else {
					self::addLiteral( $accum, $curChar );
					$i++;
				}

				$count = strspn( $text, '=', $i, 6 );
				if ( $count == 1 && $findEquals ) {
					// DWIM: This looks kind of like a name/value separator.
					// Let's let the equals handler have it and break the potential
					// heading. This is heuristic, but AFAICT the methods for
					// completely correct disambiguation are very complex.
				} elseif ( $count > 0 ) {
					$piece = [
						'open' => "\n",
						'close' => "\n",
						'parts' => [ new PPDPart_Hash( str_repeat( '=', $count ) ) ],
						'startPos' => $i,
						'count' => $count ];
					$stack->push( $piece );
					$accum =& $stack->getAccum();
					$stackFlags = $stack->getFlags();
					if ( isset( $stackFlags['findEquals'] ) ) {
						$findEquals = $stackFlags['findEquals'];
					}
					if ( isset( $stackFlags['findPipe'] ) ) {
						$findPipe = $stackFlags['findPipe'];
					}
					if ( isset( $stackFlags['inHeading'] ) ) {
						$inHeading = $stackFlags['inHeading'];
					}
					$i += $count;
				}
			} elseif ( $found == 'line-end' ) {
				$piece = $stack->top;
				// A heading must be open, otherwise \n wouldn't have been in the search list
				// FIXME: Don't use assert()
				// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.assert
				assert( $piece->open === "\n" );
				$part = $piece->getCurrentPart();
				// Search back through the input to see if it has a proper close.
				// Do this using the reversed string since the other solutions
				// (end anchor, etc.) are inefficient.
				$wsLength = strspn( $revText, " \t", $lengthText - $i );
				$searchStart = $i - $wsLength;
				if ( isset( $part->commentEnd ) && $searchStart - 1 == $part->commentEnd ) {
					// Comment found at line end
					// Search for equals signs before the comment
					$searchStart = $part->visualEnd;
					$searchStart -= strspn( $revText, " \t", $lengthText - $searchStart );
				}
				$count = $piece->count;
				$equalsLength = strspn( $revText, '=', $lengthText - $searchStart );
				if ( $equalsLength > 0 ) {
					if ( $searchStart - $equalsLength == $piece->startPos ) {
						// This is just a single string of equals signs on its own line
						// Replicate the doHeadings behavior /={count}(.+)={count}/
						// First find out how many equals signs there really are (don't stop at 6)
						$count = $equalsLength;
						if ( $count < 3 ) {
							$count = 0;
						} else {
							$count = min( 6, intval( ( $count - 1 ) / 2 ) );
						}
					} else {
						$count = min( $equalsLength, $count );
					}
					if ( $count > 0 ) {
						// Normal match, output <h>
						$element = [ [ 'possible-h',
							array_merge(
								[
									[ '@level', [ $count ] ],
									[ '@i', [ $headingIndex++ ] ]
								],
								$accum
							)
						] ];
					} else {
						// Single equals sign on its own line, count=0
						$element = $accum;
					}
				} else {
					// No match, no <h>, just pass down the inner text
					$element = $accum;
				}
				// Unwind the stack
				$stack->pop();
				$accum =& $stack->getAccum();
				$stackFlags = $stack->getFlags();
				if ( isset( $stackFlags['findEquals'] ) ) {
					$findEquals = $stackFlags['findEquals'];
				}
				if ( isset( $stackFlags['findPipe'] ) ) {
					$findPipe = $stackFlags['findPipe'];
				}
				if ( isset( $stackFlags['inHeading'] ) ) {
					$inHeading = $stackFlags['inHeading'];
				}

				// Append the result to the enclosing accumulator
				array_splice( $accum, count( $accum ), 0, $element );

				// Note that we do NOT increment the input pointer.
				// This is because the closing linebreak could be the opening linebreak of
				// another heading. Infinite loops are avoided because the next iteration MUST
				// hit the heading open case above, which unconditionally increments the
				// input pointer.
			} elseif ( $found == 'open' ) {
				# count opening brace characters
				$curLen = strlen( $curChar );
				$count = ( $curLen > 1 ) ?
					# allow the final character to repeat
					strspn( $text, $curChar[$curLen - 1], $i + 1 ) + 1 :
					strspn( $text, $curChar, $i );

				$savedPrefix = '';
				$lineStart = ( $i > 0 && $text[$i - 1] == "\n" );

				if ( $curChar === "-{" && $count > $curLen ) {
					// -{ => {{ transition because rightmost wins
					$savedPrefix = '-';
					$i++;
					$curChar = '{';
					$count--;
					$rule = $this->rules[$curChar];
				}

				# we need to add to stack only if opening brace count is enough for one of the rules
				if ( $count >= $rule['min'] ) {
					# Add it to the stack
					$piece = [
						'open' => $curChar,
						'close' => $rule['end'],
						'savedPrefix' => $savedPrefix,
						'count' => $count,
						'lineStart' => $lineStart,
					];

					$stack->push( $piece );
					$accum =& $stack->getAccum();
					$stackFlags = $stack->getFlags();
					if ( isset( $stackFlags['findEquals'] ) ) {
						$findEquals = $stackFlags['findEquals'];
					}
					if ( isset( $stackFlags['findPipe'] ) ) {
						$findPipe = $stackFlags['findPipe'];
					}
					if ( isset( $stackFlags['inHeading'] ) ) {
						$inHeading = $stackFlags['inHeading'];
					}
				} else {
					# Add literal brace(s)
					self::addLiteral( $accum, $savedPrefix . str_repeat( $curChar, $count ) );
				}
				$i += $count;
			} elseif ( $found == 'close' ) {
				$piece = $stack->top;
				# lets check if there are enough characters for closing brace
				$maxCount = $piece->count;
				if ( $piece->close === '}-' && $curChar === '}' ) {
					$maxCount--; # don't try to match closing '-' as a '}'
				}
				$curLen = strlen( $curChar );
				$count = ( $curLen > 1 ) ? $curLen :
					strspn( $text, $curChar, $i, $maxCount );

				# check for maximum matching characters (if there are 5 closing
				# characters, we will probably need only 3 - depending on the rules)
				$rule = $this->rules[$piece->open];
				if ( $count > $rule['max'] ) {
					# The specified maximum exists in the callback array, unless the caller
					# has made an error
					$matchingCount = $rule['max'];
				} else {
					# Count is less than the maximum
					# Skip any gaps in the callback array to find the true largest match
					# Need to use array_key_exists not isset because the callback can be null
					$matchingCount = $count;
					while ( $matchingCount > 0 && !array_key_exists( $matchingCount, $rule['names'] ) ) {
						--$matchingCount;
					}
				}

				if ( $matchingCount <= 0 ) {
					# No matching element found in callback array
					# Output a literal closing brace and continue
					$endText = substr( $text, $i, $count );
					self::addLiteral( $accum, $endText );
					$i += $count;
					continue;
				}
				$name = $rule['names'][$matchingCount];
				if ( $name === null ) {
					// No element, just literal text
					$endText = substr( $text, $i, $matchingCount );
					$element = $piece->breakSyntax( $matchingCount );
					self::addLiteral( $element, $endText );
				} else {
					# Create XML element
					$parts = $piece->parts;
					$titleAccum = $parts[0]->out;
					unset( $parts[0] );

					$children = [];

					# The invocation is at the start of the line if lineStart is set in
					# the stack, and all opening brackets are used up.
					if ( $maxCount == $matchingCount &&
							!empty( $piece->lineStart ) &&
							strlen( $piece->savedPrefix ) == 0 ) {
						$children[] = [ '@lineStart', [ 1 ] ];
					}
					$titleNode = [ 'title', $titleAccum ];
					$children[] = $titleNode;
					$argIndex = 1;
					foreach ( $parts as $part ) {
						if ( isset( $part->eqpos ) ) {
							$equalsNode = $part->out[$part->eqpos];
							$nameNode = [ 'name', array_slice( $part->out, 0, $part->eqpos ) ];
							$valueNode = [ 'value', array_slice( $part->out, $part->eqpos + 1 ) ];
							$partNode = [ 'part', [ $nameNode, $equalsNode, $valueNode ] ];
							$children[] = $partNode;
						} else {
							$nameNode = [ 'name', [ [ '@index', [ $argIndex++ ] ] ] ];
							$valueNode = [ 'value', $part->out ];
							$partNode = [ 'part', [ $nameNode, $valueNode ] ];
							$children[] = $partNode;
						}
					}
					$element = [ [ $name, $children ] ];
				}

				# Advance input pointer
				$i += $matchingCount;

				# Unwind the stack
				$stack->pop();
				$accum =& $stack->getAccum();

				# Re-add the old stack element if it still has unmatched opening characters remaining
				if ( $matchingCount < $piece->count ) {
					$piece->parts = [ new PPDPart_Hash ];
					$piece->count -= $matchingCount;
					# do we still qualify for any callback with remaining count?
					$min = $this->rules[$piece->open]['min'];
					if ( $piece->count >= $min ) {
						$stack->push( $piece );
						$accum =& $stack->getAccum();
					} elseif ( $piece->count == 1 && $piece->open === '{' && $piece->savedPrefix === '-' ) {
						$piece->savedPrefix = '';
						$piece->open = '-{';
						$piece->count = 2;
						$piece->close = $this->rules[$piece->open]['end'];
						$stack->push( $piece );
						$accum =& $stack->getAccum();
					} else {
						$s = substr( $piece->open, 0, -1 );
						$s .= str_repeat(
							substr( $piece->open, -1 ),
							$piece->count - strlen( $s )
						);
						self::addLiteral( $accum, $piece->savedPrefix . $s );
					}
				} elseif ( $piece->savedPrefix !== '' ) {
					self::addLiteral( $accum, $piece->savedPrefix );
				}

				$stackFlags = $stack->getFlags();
				if ( isset( $stackFlags['findEquals'] ) ) {
					$findEquals = $stackFlags['findEquals'];
				}
				if ( isset( $stackFlags['findPipe'] ) ) {
					$findPipe = $stackFlags['findPipe'];
				}
				if ( isset( $stackFlags['inHeading'] ) ) {
					$inHeading = $stackFlags['inHeading'];
				}

				# Add XML element to the enclosing accumulator
				array_splice( $accum, count( $accum ), 0, $element );
			} elseif ( $found == 'pipe' ) {
				$findEquals = true; // shortcut for getFlags()
				$stack->addPart();
				$accum =& $stack->getAccum();
				++$i;
			} elseif ( $found == 'equals' ) {
				$findEquals = false; // shortcut for getFlags()
				$accum[] = [ 'equals', [ '=' ] ];
				$stack->getCurrentPart()->eqpos = count( $accum ) - 1;
				++$i;
			}
		}

		# Output any remaining unclosed brackets
		foreach ( $stack->stack as $piece ) {
			array_splice( $stack->rootAccum, count( $stack->rootAccum ), 0, $piece->breakSyntax() );
		}

		# Enable top-level headings
		foreach ( $stack->rootAccum as &$node ) {
			if ( is_array( $node ) && $node[PPNode_Hash_Tree::NAME] === 'possible-h' ) {
				$node[PPNode_Hash_Tree::NAME] = 'h';
			}
		}

		$rootStore = [ [ 'root', $stack->rootAccum ] ];
		$rootNode = new PPNode_Hash_Tree( $rootStore, 0 );

		// Cache
		$tree = json_encode( $rootStore, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		if ( $tree !== false ) {
			$this->cacheSetTree( $text, $flags, $tree );
		}

		return $rootNode;
	}

	private static function addLiteral( array &$accum, $text ) {
		$n = count( $accum );
		if ( $n && is_string( $accum[$n - 1] ) ) {
			$accum[$n - 1] .= $text;
		} else {
			$accum[] = $text;
		}
	}
}

/**
 * Stack class to help Preprocessor::preprocessToObj()
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPDStack_Hash extends PPDStack {

	public function __construct() {
		$this->elementClass = PPDStackElement_Hash::class;
		parent::__construct();
		$this->rootAccum = [];
	}
}

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPDStackElement_Hash extends PPDStackElement {

	public function __construct( $data = [] ) {
		$this->partClass = PPDPart_Hash::class;
		parent::__construct( $data );
	}

	/**
	 * Get the accumulator that would result if the close is not found.
	 *
	 * @param int|bool $openingCount
	 * @return array
	 */
	public function breakSyntax( $openingCount = false ) {
		if ( $this->open == "\n" ) {
			$accum = array_merge( [ $this->savedPrefix ], $this->parts[0]->out );
		} else {
			if ( $openingCount === false ) {
				$openingCount = $this->count;
			}
			$s = substr( $this->open, 0, -1 );
			$s .= str_repeat(
				substr( $this->open, -1 ),
				$openingCount - strlen( $s )
			);
			$accum = [ $this->savedPrefix . $s ];
			$lastIndex = 0;
			$first = true;
			foreach ( $this->parts as $part ) {
				if ( $first ) {
					$first = false;
				} elseif ( is_string( $accum[$lastIndex] ) ) {
					$accum[$lastIndex] .= '|';
				} else {
					$accum[++$lastIndex] = '|';
				}
				foreach ( $part->out as $node ) {
					if ( is_string( $node ) && is_string( $accum[$lastIndex] ) ) {
						$accum[$lastIndex] .= $node;
					} else {
						$accum[++$lastIndex] = $node;
					}
				}
			}
		}
		return $accum;
	}
}

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPDPart_Hash extends PPDPart {

	public function __construct( $out = '' ) {
		if ( $out !== '' ) {
			$accum = [ $out ];
		} else {
			$accum = [];
		}
		parent::__construct( $accum );
	}
}

/**
 * An expansion frame, used as a context to expand the result of preprocessToObj()
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPFrame_Hash implements PPFrame {

	/**
	 * @var Parser
	 */
	public $parser;

	/**
	 * @var Preprocessor
	 */
	public $preprocessor;

	/**
	 * @var Title
	 */
	public $title;
	public $titleCache;

	/**
	 * Hashtable listing templates which are disallowed for expansion in this frame,
	 * having been encountered previously in parent frames.
	 */
	public $loopCheckHash;

	/**
	 * Recursion depth of this frame, top = 0
	 * Note that this is NOT the same as expansion depth in expand()
	 */
	public $depth;

	private $volatile = false;
	private $ttl = null;

	/**
	 * @var array
	 */
	protected $childExpansionCache;

	/**
	 * Construct a new preprocessor frame.
	 * @param Preprocessor $preprocessor The parent preprocessor
	 */
	public function __construct( $preprocessor ) {
		$this->preprocessor = $preprocessor;
		$this->parser = $preprocessor->parser;
		$this->title = $this->parser->mTitle;
		$this->titleCache = [ $this->title ? $this->title->getPrefixedDBkey() : false ];
		$this->loopCheckHash = [];
		$this->depth = 0;
		$this->childExpansionCache = [];
	}

	/**
	 * Create a new child frame
	 * $args is optionally a multi-root PPNode or array containing the template arguments
	 *
	 * @param array|bool|PPNode_Hash_Array $args
	 * @param Title|bool $title
	 * @param int $indexOffset
	 * @throws MWException
	 * @return PPTemplateFrame_Hash
	 */
	public function newChild( $args = false, $title = false, $indexOffset = 0 ) {
		$namedArgs = [];
		$numberedArgs = [];
		if ( $title === false ) {
			$title = $this->title;
		}
		if ( $args !== false ) {
			if ( $args instanceof PPNode_Hash_Array ) {
				$args = $args->value;
			} elseif ( !is_array( $args ) ) {
				throw new MWException( __METHOD__ . ': $args must be array or PPNode_Hash_Array' );
			}
			foreach ( $args as $arg ) {
				$bits = $arg->splitArg();
				if ( $bits['index'] !== '' ) {
					// Numbered parameter
					$index = $bits['index'] - $indexOffset;
					if ( isset( $namedArgs[$index] ) || isset( $numberedArgs[$index] ) ) {
						$this->parser->getOutput()->addWarning( wfMessage( 'duplicate-args-warning',
							wfEscapeWikiText( $this->title ),
							wfEscapeWikiText( $title ),
							wfEscapeWikiText( $index ) )->text() );
						$this->parser->addTrackingCategory( 'duplicate-args-category' );
					}
					$numberedArgs[$index] = $bits['value'];
					unset( $namedArgs[$index] );
				} else {
					// Named parameter
					$name = trim( $this->expand( $bits['name'], PPFrame::STRIP_COMMENTS ) );
					if ( isset( $namedArgs[$name] ) || isset( $numberedArgs[$name] ) ) {
						$this->parser->getOutput()->addWarning( wfMessage( 'duplicate-args-warning',
							wfEscapeWikiText( $this->title ),
							wfEscapeWikiText( $title ),
							wfEscapeWikiText( $name ) )->text() );
						$this->parser->addTrackingCategory( 'duplicate-args-category' );
					}
					$namedArgs[$name] = $bits['value'];
					unset( $numberedArgs[$name] );
				}
			}
		}
		return new PPTemplateFrame_Hash( $this->preprocessor, $this, $numberedArgs, $namedArgs, $title );
	}

	/**
	 * @throws MWException
	 * @param string|int $key
	 * @param string|PPNode $root
	 * @param int $flags
	 * @return string
	 */
	public function cachedExpand( $key, $root, $flags = 0 ) {
		// we don't have a parent, so we don't have a cache
		return $this->expand( $root, $flags );
	}

	/**
	 * @throws MWException
	 * @param string|PPNode $root
	 * @param int $flags
	 * @return string
	 */
	public function expand( $root, $flags = 0 ) {
		static $expansionDepth = 0;
		if ( is_string( $root ) ) {
			return $root;
		}

		if ( ++$this->parser->mPPNodeCount > $this->parser->mOptions->getMaxPPNodeCount() ) {
			$this->parser->limitationWarn( 'node-count-exceeded',
					$this->parser->mPPNodeCount,
					$this->parser->mOptions->getMaxPPNodeCount()
			);
			return '<span class="error">Node-count limit exceeded</span>';
		}
		if ( $expansionDepth > $this->parser->mOptions->getMaxPPExpandDepth() ) {
			$this->parser->limitationWarn( 'expansion-depth-exceeded',
					$expansionDepth,
					$this->parser->mOptions->getMaxPPExpandDepth()
			);
			return '<span class="error">Expansion depth limit exceeded</span>';
		}
		++$expansionDepth;
		if ( $expansionDepth > $this->parser->mHighestExpansionDepth ) {
			$this->parser->mHighestExpansionDepth = $expansionDepth;
		}

		$outStack = [ '', '' ];
		$iteratorStack = [ false, $root ];
		$indexStack = [ 0, 0 ];

		while ( count( $iteratorStack ) > 1 ) {
			$level = count( $outStack ) - 1;
			$iteratorNode =& $iteratorStack[$level];
			$out =& $outStack[$level];
			$index =& $indexStack[$level];

			if ( is_array( $iteratorNode ) ) {
				if ( $index >= count( $iteratorNode ) ) {
					// All done with this iterator
					$iteratorStack[$level] = false;
					$contextNode = false;
				} else {
					$contextNode = $iteratorNode[$index];
					$index++;
				}
			} elseif ( $iteratorNode instanceof PPNode_Hash_Array ) {
				if ( $index >= $iteratorNode->getLength() ) {
					// All done with this iterator
					$iteratorStack[$level] = false;
					$contextNode = false;
				} else {
					$contextNode = $iteratorNode->item( $index );
					$index++;
				}
			} else {
				// Copy to $contextNode and then delete from iterator stack,
				// because this is not an iterator but we do have to execute it once
				$contextNode = $iteratorStack[$level];
				$iteratorStack[$level] = false;
			}

			$newIterator = false;
			$contextName = false;
			$contextChildren = false;

			if ( $contextNode === false ) {
				// nothing to do
			} elseif ( is_string( $contextNode ) ) {
				$out .= $contextNode;
			} elseif ( $contextNode instanceof PPNode_Hash_Array ) {
				$newIterator = $contextNode;
			} elseif ( $contextNode instanceof PPNode_Hash_Attr ) {
				// No output
			} elseif ( $contextNode instanceof PPNode_Hash_Text ) {
				$out .= $contextNode->value;
			} elseif ( $contextNode instanceof PPNode_Hash_Tree ) {
				$contextName = $contextNode->name;
				$contextChildren = $contextNode->getRawChildren();
			} elseif ( is_array( $contextNode ) ) {
				// Node descriptor array
				if ( count( $contextNode ) !== 2 ) {
					throw new MWException( __METHOD__ .
						': found an array where a node descriptor should be' );
				}
				list( $contextName, $contextChildren ) = $contextNode;
			} else {
				throw new MWException( __METHOD__ . ': Invalid parameter type' );
			}

			// Handle node descriptor array or tree object
			if ( $contextName === false ) {
				// Not a node, already handled above
			} elseif ( $contextName[0] === '@' ) {
				// Attribute: no output
			} elseif ( $contextName === 'template' ) {
				# Double-brace expansion
				$bits = PPNode_Hash_Tree::splitRawTemplate( $contextChildren );
				if ( $flags & PPFrame::NO_TEMPLATES ) {
					$newIterator = $this->virtualBracketedImplode(
						'{{', '|', '}}',
						$bits['title'],
						$bits['parts']
					);
				} else {
					$ret = $this->parser->braceSubstitution( $bits, $this );
					if ( isset( $ret['object'] ) ) {
						$newIterator = $ret['object'];
					} else {
						$out .= $ret['text'];
					}
				}
			} elseif ( $contextName === 'tplarg' ) {
				# Triple-brace expansion
				$bits = PPNode_Hash_Tree::splitRawTemplate( $contextChildren );
				if ( $flags & PPFrame::NO_ARGS ) {
					$newIterator = $this->virtualBracketedImplode(
						'{{{', '|', '}}}',
						$bits['title'],
						$bits['parts']
					);
				} else {
					$ret = $this->parser->argSubstitution( $bits, $this );
					if ( isset( $ret['object'] ) ) {
						$newIterator = $ret['object'];
					} else {
						$out .= $ret['text'];
					}
				}
			} elseif ( $contextName === 'comment' ) {
				# HTML-style comment
				# Remove it in HTML, pre+remove and STRIP_COMMENTS modes
				# Not in RECOVER_COMMENTS mode (msgnw) though.
				if ( ( $this->parser->ot['html']
					|| ( $this->parser->ot['pre'] && $this->parser->mOptions->getRemoveComments() )
					|| ( $flags & PPFrame::STRIP_COMMENTS )
					) && !( $flags & PPFrame::RECOVER_COMMENTS )
				) {
					$out .= '';
				} elseif ( $this->parser->ot['wiki'] && !( $flags & PPFrame::RECOVER_COMMENTS ) ) {
					# Add a strip marker in PST mode so that pstPass2() can
					# run some old-fashioned regexes on the result.
					# Not in RECOVER_COMMENTS mode (extractSections) though.
					$out .= $this->parser->insertStripItem( $contextChildren[0] );
				} else {
					# Recover the literal comment in RECOVER_COMMENTS and pre+no-remove
					$out .= $contextChildren[0];
				}
			} elseif ( $contextName === 'ignore' ) {
				# Output suppression used by <includeonly> etc.
				# OT_WIKI will only respect <ignore> in substed templates.
				# The other output types respect it unless NO_IGNORE is set.
				# extractSections() sets NO_IGNORE and so never respects it.
				if ( ( !isset( $this->parent ) && $this->parser->ot['wiki'] )
					|| ( $flags & PPFrame::NO_IGNORE )
				) {
					$out .= $contextChildren[0];
				} else {
					// $out .= '';
				}
			} elseif ( $contextName === 'ext' ) {
				# Extension tag
				$bits = PPNode_Hash_Tree::splitRawExt( $contextChildren ) +
					[ 'attr' => null, 'inner' => null, 'close' => null ];
				if ( $flags & PPFrame::NO_TAGS ) {
					$s = '<' . $bits['name']->getFirstChild()->value;
					if ( $bits['attr'] ) {
						$s .= $bits['attr']->getFirstChild()->value;
					}
					if ( $bits['inner'] ) {
						$s .= '>' . $bits['inner']->getFirstChild()->value;
						if ( $bits['close'] ) {
							$s .= $bits['close']->getFirstChild()->value;
						}
					} else {
						$s .= '/>';
					}
					$out .= $s;
				} else {
					$out .= $this->parser->extensionSubstitution( $bits, $this );
				}
			} elseif ( $contextName === 'h' ) {
				# Heading
				if ( $this->parser->ot['html'] ) {
					# Expand immediately and insert heading index marker
					$s = $this->expand( $contextChildren, $flags );
					$bits = PPNode_Hash_Tree::splitRawHeading( $contextChildren );
					$titleText = $this->title->getPrefixedDBkey();
					$this->parser->mHeadings[] = [ $titleText, $bits['i'] ];
					$serial = count( $this->parser->mHeadings ) - 1;
					$marker = Parser::MARKER_PREFIX . "-h-$serial-" . Parser::MARKER_SUFFIX;
					$s = substr( $s, 0, $bits['level'] ) . $marker . substr( $s, $bits['level'] );
					$this->parser->mStripState->addGeneral( $marker, '' );
					$out .= $s;
				} else {
					# Expand in virtual stack
					$newIterator = $contextChildren;
				}
			} else {
				# Generic recursive expansion
				$newIterator = $contextChildren;
			}

			if ( $newIterator !== false ) {
				$outStack[] = '';
				$iteratorStack[] = $newIterator;
				$indexStack[] = 0;
			} elseif ( $iteratorStack[$level] === false ) {
				// Return accumulated value to parent
				// With tail recursion
				while ( $iteratorStack[$level] === false && $level > 0 ) {
					$outStack[$level - 1] .= $out;
					array_pop( $outStack );
					array_pop( $iteratorStack );
					array_pop( $indexStack );
					$level--;
				}
			}
		}
		--$expansionDepth;
		return $outStack[0];
	}

	/**
	 * @param string $sep
	 * @param int $flags
	 * @param string|PPNode $args,...
	 * @return string
	 */
	public function implodeWithFlags( $sep, $flags /*, ... */ ) {
		$args = array_slice( func_get_args(), 2 );

		$first = true;
		$s = '';
		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_Hash_Array ) {
				$root = $root->value;
			}
			if ( !is_array( $root ) ) {
				$root = [ $root ];
			}
			foreach ( $root as $node ) {
				if ( $first ) {
					$first = false;
				} else {
					$s .= $sep;
				}
				$s .= $this->expand( $node, $flags );
			}
		}
		return $s;
	}

	/**
	 * Implode with no flags specified
	 * This previously called implodeWithFlags but has now been inlined to reduce stack depth
	 * @param string $sep
	 * @param string|PPNode $args,...
	 * @return string
	 */
	public function implode( $sep /*, ... */ ) {
		$args = array_slice( func_get_args(), 1 );

		$first = true;
		$s = '';
		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_Hash_Array ) {
				$root = $root->value;
			}
			if ( !is_array( $root ) ) {
				$root = [ $root ];
			}
			foreach ( $root as $node ) {
				if ( $first ) {
					$first = false;
				} else {
					$s .= $sep;
				}
				$s .= $this->expand( $node );
			}
		}
		return $s;
	}

	/**
	 * Makes an object that, when expand()ed, will be the same as one obtained
	 * with implode()
	 *
	 * @param string $sep
	 * @param string|PPNode $args,...
	 * @return PPNode_Hash_Array
	 */
	public function virtualImplode( $sep /*, ... */ ) {
		$args = array_slice( func_get_args(), 1 );
		$out = [];
		$first = true;

		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_Hash_Array ) {
				$root = $root->value;
			}
			if ( !is_array( $root ) ) {
				$root = [ $root ];
			}
			foreach ( $root as $node ) {
				if ( $first ) {
					$first = false;
				} else {
					$out[] = $sep;
				}
				$out[] = $node;
			}
		}
		return new PPNode_Hash_Array( $out );
	}

	/**
	 * Virtual implode with brackets
	 *
	 * @param string $start
	 * @param string $sep
	 * @param string $end
	 * @param string|PPNode $args,...
	 * @return PPNode_Hash_Array
	 */
	public function virtualBracketedImplode( $start, $sep, $end /*, ... */ ) {
		$args = array_slice( func_get_args(), 3 );
		$out = [ $start ];
		$first = true;

		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_Hash_Array ) {
				$root = $root->value;
			}
			if ( !is_array( $root ) ) {
				$root = [ $root ];
			}
			foreach ( $root as $node ) {
				if ( $first ) {
					$first = false;
				} else {
					$out[] = $sep;
				}
				$out[] = $node;
			}
		}
		$out[] = $end;
		return new PPNode_Hash_Array( $out );
	}

	public function __toString() {
		return 'frame{}';
	}

	/**
	 * @param bool $level
	 * @return array|bool|string
	 */
	public function getPDBK( $level = false ) {
		if ( $level === false ) {
			return $this->title->getPrefixedDBkey();
		} else {
			return $this->titleCache[$level] ?? false;
		}
	}

	/**
	 * @return array
	 */
	public function getArguments() {
		return [];
	}

	/**
	 * @return array
	 */
	public function getNumberedArguments() {
		return [];
	}

	/**
	 * @return array
	 */
	public function getNamedArguments() {
		return [];
	}

	/**
	 * Returns true if there are no arguments in this frame
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return true;
	}

	/**
	 * @param int|string $name
	 * @return bool Always false in this implementation.
	 */
	public function getArgument( $name ) {
		return false;
	}

	/**
	 * Returns true if the infinite loop check is OK, false if a loop is detected
	 *
	 * @param Title $title
	 *
	 * @return bool
	 */
	public function loopCheck( $title ) {
		return !isset( $this->loopCheckHash[$title->getPrefixedDBkey()] );
	}

	/**
	 * Return true if the frame is a template frame
	 *
	 * @return bool
	 */
	public function isTemplate() {
		return false;
	}

	/**
	 * Get a title of frame
	 *
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set the volatile flag
	 *
	 * @param bool $flag
	 */
	public function setVolatile( $flag = true ) {
		$this->volatile = $flag;
	}

	/**
	 * Get the volatile flag
	 *
	 * @return bool
	 */
	public function isVolatile() {
		return $this->volatile;
	}

	/**
	 * Set the TTL
	 *
	 * @param int $ttl
	 */
	public function setTTL( $ttl ) {
		if ( $ttl !== null && ( $this->ttl === null || $ttl < $this->ttl ) ) {
			$this->ttl = $ttl;
		}
	}

	/**
	 * Get the TTL
	 *
	 * @return int|null
	 */
	public function getTTL() {
		return $this->ttl;
	}
}

/**
 * Expansion frame with template arguments
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPTemplateFrame_Hash extends PPFrame_Hash {

	public $numberedArgs, $namedArgs, $parent;
	public $numberedExpansionCache, $namedExpansionCache;

	/**
	 * @param Preprocessor $preprocessor
	 * @param bool|PPFrame $parent
	 * @param array $numberedArgs
	 * @param array $namedArgs
	 * @param bool|Title $title
	 */
	public function __construct( $preprocessor, $parent = false, $numberedArgs = [],
		$namedArgs = [], $title = false
	) {
		parent::__construct( $preprocessor );

		$this->parent = $parent;
		$this->numberedArgs = $numberedArgs;
		$this->namedArgs = $namedArgs;
		$this->title = $title;
		$pdbk = $title ? $title->getPrefixedDBkey() : false;
		$this->titleCache = $parent->titleCache;
		$this->titleCache[] = $pdbk;
		$this->loopCheckHash = /*clone*/ $parent->loopCheckHash;
		if ( $pdbk !== false ) {
			$this->loopCheckHash[$pdbk] = true;
		}
		$this->depth = $parent->depth + 1;
		$this->numberedExpansionCache = $this->namedExpansionCache = [];
	}

	public function __toString() {
		$s = 'tplframe{';
		$first = true;
		$args = $this->numberedArgs + $this->namedArgs;
		foreach ( $args as $name => $value ) {
			if ( $first ) {
				$first = false;
			} else {
				$s .= ', ';
			}
			$s .= "\"$name\":\"" .
				str_replace( '"', '\\"', $value->__toString() ) . '"';
		}
		$s .= '}';
		return $s;
	}

	/**
	 * @throws MWException
	 * @param string|int $key
	 * @param string|PPNode $root
	 * @param int $flags
	 * @return string
	 */
	public function cachedExpand( $key, $root, $flags = 0 ) {
		if ( isset( $this->parent->childExpansionCache[$key] ) ) {
			return $this->parent->childExpansionCache[$key];
		}
		$retval = $this->expand( $root, $flags );
		if ( !$this->isVolatile() ) {
			$this->parent->childExpansionCache[$key] = $retval;
		}
		return $retval;
	}

	/**
	 * Returns true if there are no arguments in this frame
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return !count( $this->numberedArgs ) && !count( $this->namedArgs );
	}

	/**
	 * @return array
	 */
	public function getArguments() {
		$arguments = [];
		foreach ( array_merge(
				array_keys( $this->numberedArgs ),
				array_keys( $this->namedArgs ) ) as $key ) {
			$arguments[$key] = $this->getArgument( $key );
		}
		return $arguments;
	}

	/**
	 * @return array
	 */
	public function getNumberedArguments() {
		$arguments = [];
		foreach ( array_keys( $this->numberedArgs ) as $key ) {
			$arguments[$key] = $this->getArgument( $key );
		}
		return $arguments;
	}

	/**
	 * @return array
	 */
	public function getNamedArguments() {
		$arguments = [];
		foreach ( array_keys( $this->namedArgs ) as $key ) {
			$arguments[$key] = $this->getArgument( $key );
		}
		return $arguments;
	}

	/**
	 * @param int $index
	 * @return string|bool
	 */
	public function getNumberedArgument( $index ) {
		if ( !isset( $this->numberedArgs[$index] ) ) {
			return false;
		}
		if ( !isset( $this->numberedExpansionCache[$index] ) ) {
			# No trimming for unnamed arguments
			$this->numberedExpansionCache[$index] = $this->parent->expand(
				$this->numberedArgs[$index],
				PPFrame::STRIP_COMMENTS
			);
		}
		return $this->numberedExpansionCache[$index];
	}

	/**
	 * @param string $name
	 * @return string|bool
	 */
	public function getNamedArgument( $name ) {
		if ( !isset( $this->namedArgs[$name] ) ) {
			return false;
		}
		if ( !isset( $this->namedExpansionCache[$name] ) ) {
			# Trim named arguments post-expand, for backwards compatibility
			$this->namedExpansionCache[$name] = trim(
				$this->parent->expand( $this->namedArgs[$name], PPFrame::STRIP_COMMENTS ) );
		}
		return $this->namedExpansionCache[$name];
	}

	/**
	 * @param int|string $name
	 * @return string|bool
	 */
	public function getArgument( $name ) {
		$text = $this->getNumberedArgument( $name );
		if ( $text === false ) {
			$text = $this->getNamedArgument( $name );
		}
		return $text;
	}

	/**
	 * Return true if the frame is a template frame
	 *
	 * @return bool
	 */
	public function isTemplate() {
		return true;
	}

	public function setVolatile( $flag = true ) {
		parent::setVolatile( $flag );
		$this->parent->setVolatile( $flag );
	}

	public function setTTL( $ttl ) {
		parent::setTTL( $ttl );
		$this->parent->setTTL( $ttl );
	}
}

/**
 * Expansion frame with custom arguments
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPCustomFrame_Hash extends PPFrame_Hash {

	public $args;

	public function __construct( $preprocessor, $args ) {
		parent::__construct( $preprocessor );
		$this->args = $args;
	}

	public function __toString() {
		$s = 'cstmframe{';
		$first = true;
		foreach ( $this->args as $name => $value ) {
			if ( $first ) {
				$first = false;
			} else {
				$s .= ', ';
			}
			$s .= "\"$name\":\"" .
				str_replace( '"', '\\"', $value->__toString() ) . '"';
		}
		$s .= '}';
		return $s;
	}

	/**
	 * @return bool
	 */
	public function isEmpty() {
		return !count( $this->args );
	}

	/**
	 * @param int|string $index
	 * @return string|bool
	 */
	public function getArgument( $index ) {
		if ( !isset( $this->args[$index] ) ) {
			return false;
		}
		return $this->args[$index];
	}

	public function getArguments() {
		return $this->args;
	}
}

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_Hash_Tree implements PPNode {

	public $name;

	/**
	 * The store array for children of this node. It is "raw" in the sense that
	 * nodes are two-element arrays ("descriptors") rather than PPNode_Hash_*
	 * objects.
	 */
	private $rawChildren;

	/**
	 * The store array for the siblings of this node, including this node itself.
	 */
	private $store;

	/**
	 * The index into $this->store which contains the descriptor of this node.
	 */
	private $index;

	/**
	 * The offset of the name within descriptors, used in some places for
	 * readability.
	 */
	const NAME = 0;

	/**
	 * The offset of the child list within descriptors, used in some places for
	 * readability.
	 */
	const CHILDREN = 1;

	/**
	 * Construct an object using the data from $store[$index]. The rest of the
	 * store array can be accessed via getNextSibling().
	 *
	 * @param array $store
	 * @param int $index
	 */
	public function __construct( array $store, $index ) {
		$this->store = $store;
		$this->index = $index;
		list( $this->name, $this->rawChildren ) = $this->store[$index];
	}

	/**
	 * Construct an appropriate PPNode_Hash_* object with a class that depends
	 * on what is at the relevant store index.
	 *
	 * @param array $store
	 * @param int $index
	 * @return PPNode_Hash_Tree|PPNode_Hash_Attr|PPNode_Hash_Text
	 */
	public static function factory( array $store, $index ) {
		if ( !isset( $store[$index] ) ) {
			return false;
		}

		$descriptor = $store[$index];
		if ( is_string( $descriptor ) ) {
			$class = PPNode_Hash_Text::class;
		} elseif ( is_array( $descriptor ) ) {
			if ( $descriptor[self::NAME][0] === '@' ) {
				$class = PPNode_Hash_Attr::class;
			} else {
				$class = self::class;
			}
		} else {
			throw new MWException( __METHOD__ . ': invalid node descriptor' );
		}
		return new $class( $store, $index );
	}

	/**
	 * Convert a node to XML, for debugging
	 */
	public function __toString() {
		$inner = '';
		$attribs = '';
		for ( $node = $this->getFirstChild(); $node; $node = $node->getNextSibling() ) {
			if ( $node instanceof PPNode_Hash_Attr ) {
				$attribs .= ' ' . $node->name . '="' . htmlspecialchars( $node->value ) . '"';
			} else {
				$inner .= $node->__toString();
			}
		}
		if ( $inner === '' ) {
			return "<{$this->name}$attribs/>";
		} else {
			return "<{$this->name}$attribs>$inner</{$this->name}>";
		}
	}

	/**
	 * @return PPNode_Hash_Array
	 */
	public function getChildren() {
		$children = [];
		foreach ( $this->rawChildren as $i => $child ) {
			$children[] = self::factory( $this->rawChildren, $i );
		}
		return new PPNode_Hash_Array( $children );
	}

	/**
	 * Get the first child, or false if there is none. Note that this will
	 * return a temporary proxy object: different instances will be returned
	 * if this is called more than once on the same node.
	 *
	 * @return PPNode_Hash_Tree|PPNode_Hash_Attr|PPNode_Hash_Text|bool
	 */
	public function getFirstChild() {
		if ( !isset( $this->rawChildren[0] ) ) {
			return false;
		} else {
			return self::factory( $this->rawChildren, 0 );
		}
	}

	/**
	 * Get the next sibling, or false if there is none. Note that this will
	 * return a temporary proxy object: different instances will be returned
	 * if this is called more than once on the same node.
	 *
	 * @return PPNode_Hash_Tree|PPNode_Hash_Attr|PPNode_Hash_Text|bool
	 */
	public function getNextSibling() {
		return self::factory( $this->store, $this->index + 1 );
	}

	/**
	 * Get an array of the children with a given node name
	 *
	 * @param string $name
	 * @return PPNode_Hash_Array
	 */
	public function getChildrenOfType( $name ) {
		$children = [];
		foreach ( $this->rawChildren as $i => $child ) {
			if ( is_array( $child ) && $child[self::NAME] === $name ) {
				$children[] = self::factory( $this->rawChildren, $i );
			}
		}
		return new PPNode_Hash_Array( $children );
	}

	/**
	 * Get the raw child array. For internal use.
	 * @return array
	 */
	public function getRawChildren() {
		return $this->rawChildren;
	}

	/**
	 * @return bool
	 */
	public function getLength() {
		return false;
	}

	/**
	 * @param int $i
	 * @return bool
	 */
	public function item( $i ) {
		return false;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Split a "<part>" node into an associative array containing:
	 *  - name          PPNode name
	 *  - index         String index
	 *  - value         PPNode value
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitArg() {
		return self::splitRawArg( $this->rawChildren );
	}

	/**
	 * Like splitArg() but for a raw child array. For internal use only.
	 * @param array $children
	 * @return array
	 */
	public static function splitRawArg( array $children ) {
		$bits = [];
		foreach ( $children as $i => $child ) {
			if ( !is_array( $child ) ) {
				continue;
			}
			if ( $child[self::NAME] === 'name' ) {
				$bits['name'] = new self( $children, $i );
				if ( isset( $child[self::CHILDREN][0][self::NAME] )
					&& $child[self::CHILDREN][0][self::NAME] === '@index'
				) {
					$bits['index'] = $child[self::CHILDREN][0][self::CHILDREN][0];
				}
			} elseif ( $child[self::NAME] === 'value' ) {
				$bits['value'] = new self( $children, $i );
			}
		}

		if ( !isset( $bits['name'] ) ) {
			throw new MWException( 'Invalid brace node passed to ' . __METHOD__ );
		}
		if ( !isset( $bits['index'] ) ) {
			$bits['index'] = '';
		}
		return $bits;
	}

	/**
	 * Split an "<ext>" node into an associative array containing name, attr, inner and close
	 * All values in the resulting array are PPNodes. Inner and close are optional.
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitExt() {
		return self::splitRawExt( $this->rawChildren );
	}

	/**
	 * Like splitExt() but for a raw child array. For internal use only.
	 * @param array $children
	 * @return array
	 */
	public static function splitRawExt( array $children ) {
		$bits = [];
		foreach ( $children as $i => $child ) {
			if ( !is_array( $child ) ) {
				continue;
			}
			switch ( $child[self::NAME] ) {
				case 'name':
					$bits['name'] = new self( $children, $i );
					break;
				case 'attr':
					$bits['attr'] = new self( $children, $i );
					break;
				case 'inner':
					$bits['inner'] = new self( $children, $i );
					break;
				case 'close':
					$bits['close'] = new self( $children, $i );
					break;
			}
		}
		if ( !isset( $bits['name'] ) ) {
			throw new MWException( 'Invalid ext node passed to ' . __METHOD__ );
		}
		return $bits;
	}

	/**
	 * Split an "<h>" node
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitHeading() {
		if ( $this->name !== 'h' ) {
			throw new MWException( 'Invalid h node passed to ' . __METHOD__ );
		}
		return self::splitRawHeading( $this->rawChildren );
	}

	/**
	 * Like splitHeading() but for a raw child array. For internal use only.
	 * @param array $children
	 * @return array
	 */
	public static function splitRawHeading( array $children ) {
		$bits = [];
		foreach ( $children as $i => $child ) {
			if ( !is_array( $child ) ) {
				continue;
			}
			if ( $child[self::NAME] === '@i' ) {
				$bits['i'] = $child[self::CHILDREN][0];
			} elseif ( $child[self::NAME] === '@level' ) {
				$bits['level'] = $child[self::CHILDREN][0];
			}
		}
		if ( !isset( $bits['i'] ) ) {
			throw new MWException( 'Invalid h node passed to ' . __METHOD__ );
		}
		return $bits;
	}

	/**
	 * Split a "<template>" or "<tplarg>" node
	 *
	 * @throws MWException
	 * @return array
	 */
	public function splitTemplate() {
		return self::splitRawTemplate( $this->rawChildren );
	}

	/**
	 * Like splitTemplate() but for a raw child array. For internal use only.
	 * @param array $children
	 * @return array
	 */
	public static function splitRawTemplate( array $children ) {
		$parts = [];
		$bits = [ 'lineStart' => '' ];
		foreach ( $children as $i => $child ) {
			if ( !is_array( $child ) ) {
				continue;
			}
			switch ( $child[self::NAME] ) {
				case 'title':
					$bits['title'] = new self( $children, $i );
					break;
				case 'part':
					$parts[] = new self( $children, $i );
					break;
				case '@lineStart':
					$bits['lineStart'] = '1';
					break;
			}
		}
		if ( !isset( $bits['title'] ) ) {
			throw new MWException( 'Invalid node passed to ' . __METHOD__ );
		}
		$bits['parts'] = new PPNode_Hash_Array( $parts );
		return $bits;
	}
}

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_Hash_Text implements PPNode {

	public $value;
	private $store, $index;

	/**
	 * Construct an object using the data from $store[$index]. The rest of the
	 * store array can be accessed via getNextSibling().
	 *
	 * @param array $store
	 * @param int $index
	 */
	public function __construct( array $store, $index ) {
		$this->value = $store[$index];
		if ( !is_scalar( $this->value ) ) {
			throw new MWException( __CLASS__ . ' given object instead of string' );
		}
		$this->store = $store;
		$this->index = $index;
	}

	public function __toString() {
		return htmlspecialchars( $this->value );
	}

	public function getNextSibling() {
		return PPNode_Hash_Tree::factory( $this->store, $this->index + 1 );
	}

	public function getChildren() {
		return false;
	}

	public function getFirstChild() {
		return false;
	}

	public function getChildrenOfType( $name ) {
		return false;
	}

	public function getLength() {
		return false;
	}

	public function item( $i ) {
		return false;
	}

	public function getName() {
		return '#text';
	}

	public function splitArg() {
		throw new MWException( __METHOD__ . ': not supported' );
	}

	public function splitExt() {
		throw new MWException( __METHOD__ . ': not supported' );
	}

	public function splitHeading() {
		throw new MWException( __METHOD__ . ': not supported' );
	}
}

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_Hash_Array implements PPNode {

	public $value;

	public function __construct( $value ) {
		$this->value = $value;
	}

	public function __toString() {
		return var_export( $this, true );
	}

	public function getLength() {
		return count( $this->value );
	}

	public function item( $i ) {
		return $this->value[$i];
	}

	public function getName() {
		return '#nodelist';
	}

	public function getNextSibling() {
		return false;
	}

	public function getChildren() {
		return false;
	}

	public function getFirstChild() {
		return false;
	}

	public function getChildrenOfType( $name ) {
		return false;
	}

	public function splitArg() {
		throw new MWException( __METHOD__ . ': not supported' );
	}

	public function splitExt() {
		throw new MWException( __METHOD__ . ': not supported' );
	}

	public function splitHeading() {
		throw new MWException( __METHOD__ . ': not supported' );
	}
}

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPNode_Hash_Attr implements PPNode {

	public $name, $value;
	private $store, $index;

	/**
	 * Construct an object using the data from $store[$index]. The rest of the
	 * store array can be accessed via getNextSibling().
	 *
	 * @param array $store
	 * @param int $index
	 */
	public function __construct( array $store, $index ) {
		$descriptor = $store[$index];
		if ( $descriptor[PPNode_Hash_Tree::NAME][0] !== '@' ) {
			throw new MWException( __METHOD__ . ': invalid name in attribute descriptor' );
		}
		$this->name = substr( $descriptor[PPNode_Hash_Tree::NAME], 1 );
		$this->value = $descriptor[PPNode_Hash_Tree::CHILDREN][0];
		$this->store = $store;
		$this->index = $index;
	}

	public function __toString() {
		return "<@{$this->name}>" . htmlspecialchars( $this->value ) . "</@{$this->name}>";
	}

	public function getName() {
		return $this->name;
	}

	public function getNextSibling() {
		return PPNode_Hash_Tree::factory( $this->store, $this->index + 1 );
	}

	public function getChildren() {
		return false;
	}

	public function getFirstChild() {
		return false;
	}

	public function getChildrenOfType( $name ) {
		return false;
	}

	public function getLength() {
		return false;
	}

	public function item( $i ) {
		return false;
	}

	public function splitArg() {
		throw new MWException( __METHOD__ . ': not supported' );
	}

	public function splitExt() {
		throw new MWException( __METHOD__ . ': not supported' );
	}

	public function splitHeading() {
		throw new MWException( __METHOD__ . ': not supported' );
	}
}
