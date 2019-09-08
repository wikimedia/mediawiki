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
	const CACHE_PREFIX = 'preprocess-hash';
	const CACHE_VERSION = 2;

	/**
	 * @param Parser $parser
	 */
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
				/** @var PPDStackElement_Hash $piece */
				$piece = $stack->top;
				'@phan-var PPDStackElement_Hash $piece';
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
