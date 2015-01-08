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
 * @ingroup Parser
 * @codingStandardsIgnoreStart
 */
class Preprocessor_Hash implements Preprocessor {
	// @codingStandardsIgnoreEnd

	/**
	 * @var Parser
	 */
	public $parser;

	const CACHE_VERSION = 1;

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
		$list = array();

		foreach ( $values as $k => $val ) {
			$partNode = new PPNode_Hash_Tree( 'part' );
			$nameNode = new PPNode_Hash_Tree( 'name' );

			if ( is_int( $k ) ) {
				$nameNode->addChild( new PPNode_Hash_Attr( 'index', $k ) );
				$partNode->addChild( $nameNode );
			} else {
				$nameNode->addChild( new PPNode_Hash_Text( $k ) );
				$partNode->addChild( $nameNode );
				$partNode->addChild( new PPNode_Hash_Text( '=' ) );
			}

			$valueNode = new PPNode_Hash_Tree( 'value' );
			$valueNode->addChild( new PPNode_Hash_Text( $val ) );
			$partNode->addChild( $valueNode );

			$list[] = $partNode;
		}

		$node = new PPNode_Hash_Array( $list );
		return $node;
	}

	/**
	 * Preprocess some wikitext and return the document tree.
	 * This is the ghost of Parser::replace_variables().
	 *
	 * @param string $text The text to parse
	 * @param int $flags Bitwise combination of:
	 *    Parser::PTD_FOR_INCLUSION    Handle "<noinclude>" and "<includeonly>" as if the text is being
	 *                                 included. Default is to assume a direct page view.
	 *
	 * The generated DOM tree must depend only on the input text and the flags.
	 * The DOM tree must be the same in OT_HTML and OT_WIKI mode, to avoid a regression of bug 4899.
	 *
	 * Any flag added to the $flags parameter here, or any other parameter liable to cause a
	 * change in the DOM tree for a given text, must be passed through the section identifier
	 * in the section edit link and thus back to extractSections().
	 *
	 * The output of this function is currently only cached in process memory, but a persistent
	 * cache may be implemented at a later date which takes further advantage of these strict
	 * dependency requirements.
	 *
	 * @throws MWException
	 * @return PPNode_Hash_Tree
	 */
	public function preprocessToObj( $text, $flags = 0 ) {

		// Check cache.
		global $wgMemc, $wgPreprocessorCacheThreshold;

		$cacheable = $wgPreprocessorCacheThreshold !== false
			&& strlen( $text ) > $wgPreprocessorCacheThreshold;

		if ( $cacheable ) {

			$cacheKey = wfMemcKey( 'preprocess-hash', md5( $text ), $flags );
			$cacheValue = $wgMemc->get( $cacheKey );
			if ( $cacheValue ) {
				$version = substr( $cacheValue, 0, 8 );
				if ( intval( $version ) == self::CACHE_VERSION ) {
					$hash = unserialize( substr( $cacheValue, 8 ) );
					// From the cache
					wfDebugLog( "Preprocessor",
						"Loaded preprocessor hash from memcached (key $cacheKey)" );
					return $hash;
				}
			}
		}

		$rules = array(
			'{' => array(
				'end' => '}',
				'names' => array(
					2 => 'template',
					3 => 'tplarg',
				),
				'min' => 2,
				'max' => 3,
			),
			'[' => array(
				'end' => ']',
				'names' => array( 2 => null ),
				'min' => 2,
				'max' => 2,
			)
		);

		$forInclusion = $flags & Parser::PTD_FOR_INCLUSION;

		$xmlishElements = $this->parser->getStripList();
		$enableOnlyinclude = false;
		if ( $forInclusion ) {
			$ignoredTags = array( 'includeonly', '/includeonly' );
			$ignoredElements = array( 'noinclude' );
			$xmlishElements[] = 'noinclude';
			if ( strpos( $text, '<onlyinclude>' ) !== false
				&& strpos( $text, '</onlyinclude>' ) !== false
			) {
				$enableOnlyinclude = true;
			}
		} else {
			$ignoredTags = array( 'noinclude', '/noinclude', 'onlyinclude', '/onlyinclude' );
			$ignoredElements = array( 'includeonly' );
			$xmlishElements[] = 'includeonly';
		}
		$xmlishRegex = implode( '|', array_merge( $xmlishElements, $ignoredTags ) );

		// Use "A" modifier (anchored) instead of "^", because ^ doesn't work with an offset
		$elementsRegex = "~($xmlishRegex)(?:\s|\/>|>)|(!--)~iA";

		$stack = new PPDStack_Hash;

		$searchBase = "[{<\n";
		// For fast reverse searches
		$revText = strrev( $text );
		$lengthText = strlen( $text );

		// Input pointer, starts out pointing to a pseudo-newline before the start
		$i = 0;
		// Current accumulator
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
		// True to ignore all input up to the next <onlyinclude>
		$findOnlyinclude = $enableOnlyinclude;
		// Do a line-start run without outputting an LF character
		$fakeLineStart = true;

		while ( true ) {
			//$this->memCheck();

			if ( $findOnlyinclude ) {
				// Ignore all input up to the next <onlyinclude>
				$startPos = strpos( $text, '<onlyinclude>', $i );
				if ( $startPos === false ) {
					// Ignored section runs to the end
					$accum->addNodeWithText( 'ignore', substr( $text, $i ) );
					break;
				}
				$tagEndPos = $startPos + strlen( '<onlyinclude>' ); // past-the-end
				$accum->addNodeWithText( 'ignore', substr( $text, $i, $tagEndPos - $i ) );
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
					$accum->addLiteral( substr( $text, $i, $literalLength ) );
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
					$curChar = $text[$i];
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
					} elseif ( $curChar == $currentClosing ) {
						$found = 'close';
					} elseif ( isset( $rules[$curChar] ) ) {
						$found = 'open';
						$rule = $rules[$curChar];
					} else {
						# Some versions of PHP have a strcspn which stops on null characters
						# Ignore and continue
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
					$accum->addLiteral( '<' );
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
						$accum->addNodeWithText( 'comment', $inner );
						$i = $lengthText;
					} else {
						// Search backwards for leading whitespace
						$wsStart = $i ? ( $i - strspn( $revText, " \t", $lengthText - $i ) ) : 0;

						// Search forwards for trailing whitespace
						// $wsEnd will be the position of the last space (or the '>' if there's none)
						$wsEnd = $endPos + 2 + strspn( $text, " \t", $endPos + 3 );

						// Keep looking forward as long as we're finding more
						// comments.
						$comments = array( array( $wsStart, $wsEnd ) );
						while ( substr( $text, $wsEnd + 1, 4 ) == '<!--' ) {
							$c = strpos( $text, '-->', $wsEnd + 4 );
							if ( $c === false ) {
								break;
							}
							$c = $c + 2 + strspn( $text, " \t", $c + 3 );
							$comments[] = array( $wsEnd + 1, $c );
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
							// Sanity check first though
							$wsLength = $i - $wsStart;
							if ( $wsLength > 0
								&& $accum->lastNode instanceof PPNode_Hash_Text
								&& strspn( $accum->lastNode->value, " \t", -$wsLength ) === $wsLength
							) {
								$accum->lastNode->value = substr( $accum->lastNode->value, 0, -$wsLength );
							}

							// Dump all but the last comment to the accumulator
							foreach ( $comments as $j => $com ) {
								$startPos = $com[0];
								$endPos = $com[1] + 1;
								if ( $j == ( count( $comments ) - 1 ) ) {
									break;
								}
								$inner = substr( $text, $startPos, $endPos - $startPos );
								$accum->addNodeWithText( 'comment', $inner );
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
						$accum->addNodeWithText( 'comment', $inner );
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
					$accum->addLiteral( '<' );
					++$i;
					continue;
				}

				// Handle ignored tags
				if ( in_array( $lowerName, $ignoredTags ) ) {
					$accum->addNodeWithText( 'ignore', substr( $text, $i, $tagEndPos - $i + 1 ) );
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
					if ( preg_match( "/<\/" . preg_quote( $name, '/' ) . "\s*>/i",
							$text, $matches, PREG_OFFSET_CAPTURE, $tagEndPos + 1 )
					) {
						$inner = substr( $text, $tagEndPos + 1, $matches[0][1] - $tagEndPos - 1 );
						$i = $matches[0][1] + strlen( $matches[0][0] );
						$close = $matches[0][0];
					} else {
						// No end tag -- let it run out to the end of the text.
						$inner = substr( $text, $tagEndPos + 1 );
						$i = $lengthText;
						$close = null;
					}
				}
				// <includeonly> and <noinclude> just become <ignore> tags
				if ( in_array( $lowerName, $ignoredElements ) ) {
					$accum->addNodeWithText( 'ignore', substr( $text, $tagStartPos, $i - $tagStartPos ) );
					continue;
				}

				if ( $attrEnd <= $attrStart ) {
					$attr = '';
				} else {
					// Note that the attr element contains the whitespace between name and attribute,
					// this is necessary for precise reconstruction during pre-save transform.
					$attr = substr( $text, $attrStart, $attrEnd - $attrStart );
				}

				$extNode = new PPNode_Hash_Tree( 'ext' );
				$extNode->addChild( PPNode_Hash_Tree::newWithText( 'name', $name ) );
				$extNode->addChild( PPNode_Hash_Tree::newWithText( 'attr', $attr ) );
				if ( $inner !== null ) {
					$extNode->addChild( PPNode_Hash_Tree::newWithText( 'inner', $inner ) );
				}
				if ( $close !== null ) {
					$extNode->addChild( PPNode_Hash_Tree::newWithText( 'close', $close ) );
				}
				$accum->addNode( $extNode );
			} elseif ( $found == 'line-start' ) {
				// Is this the start of a heading?
				// Line break belongs before the heading element in any case
				if ( $fakeLineStart ) {
					$fakeLineStart = false;
				} else {
					$accum->addLiteral( $curChar );
					$i++;
				}

				$count = strspn( $text, '=', $i, 6 );
				if ( $count == 1 && $findEquals ) {
					// DWIM: This looks kind of like a name/value separator.
					// Let's let the equals handler have it and break the potential
					// heading. This is heuristic, but AFAICT the methods for
					// completely correct disambiguation are very complex.
				} elseif ( $count > 0 ) {
					$piece = array(
						'open' => "\n",
						'close' => "\n",
						'parts' => array( new PPDPart_Hash( str_repeat( '=', $count ) ) ),
						'startPos' => $i,
						'count' => $count );
					$stack->push( $piece );
					$accum =& $stack->getAccum();
					extract( $stack->getFlags() );
					$i += $count;
				}
			} elseif ( $found == 'line-end' ) {
				$piece = $stack->top;
				// A heading must be open, otherwise \n wouldn't have been in the search list
				assert( '$piece->open == "\n"' );
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
						$element = new PPNode_Hash_Tree( 'possible-h' );
						$element->addChild( new PPNode_Hash_Attr( 'level', $count ) );
						$element->addChild( new PPNode_Hash_Attr( 'i', $headingIndex++ ) );
						$element->lastChild->nextSibling = $accum->firstNode;
						$element->lastChild = $accum->lastNode;
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
				extract( $stack->getFlags() );

				// Append the result to the enclosing accumulator
				if ( $element instanceof PPNode ) {
					$accum->addNode( $element );
				} else {
					$accum->addAccum( $element );
				}
				// Note that we do NOT increment the input pointer.
				// This is because the closing linebreak could be the opening linebreak of
				// another heading. Infinite loops are avoided because the next iteration MUST
				// hit the heading open case above, which unconditionally increments the
				// input pointer.
			} elseif ( $found == 'open' ) {
				# count opening brace characters
				$count = strspn( $text, $curChar, $i );

				# we need to add to stack only if opening brace count is enough for one of the rules
				if ( $count >= $rule['min'] ) {
					# Add it to the stack
					$piece = array(
						'open' => $curChar,
						'close' => $rule['end'],
						'count' => $count,
						'lineStart' => ( $i > 0 && $text[$i - 1] == "\n" ),
					);

					$stack->push( $piece );
					$accum =& $stack->getAccum();
					extract( $stack->getFlags() );
				} else {
					# Add literal brace(s)
					$accum->addLiteral( str_repeat( $curChar, $count ) );
				}
				$i += $count;
			} elseif ( $found == 'close' ) {
				$piece = $stack->top;
				# lets check if there are enough characters for closing brace
				$maxCount = $piece->count;
				$count = strspn( $text, $curChar, $i, $maxCount );

				# check for maximum matching characters (if there are 5 closing
				# characters, we will probably need only 3 - depending on the rules)
				$rule = $rules[$piece->open];
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
					$accum->addLiteral( str_repeat( $curChar, $count ) );
					$i += $count;
					continue;
				}
				$name = $rule['names'][$matchingCount];
				if ( $name === null ) {
					// No element, just literal text
					$element = $piece->breakSyntax( $matchingCount );
					$element->addLiteral( str_repeat( $rule['end'], $matchingCount ) );
				} else {
					# Create XML element
					# Note: $parts is already XML, does not need to be encoded further
					$parts = $piece->parts;
					$titleAccum = $parts[0]->out;
					unset( $parts[0] );

					$element = new PPNode_Hash_Tree( $name );

					# The invocation is at the start of the line if lineStart is set in
					# the stack, and all opening brackets are used up.
					if ( $maxCount == $matchingCount && !empty( $piece->lineStart ) ) {
						$element->addChild( new PPNode_Hash_Attr( 'lineStart', 1 ) );
					}
					$titleNode = new PPNode_Hash_Tree( 'title' );
					$titleNode->firstChild = $titleAccum->firstNode;
					$titleNode->lastChild = $titleAccum->lastNode;
					$element->addChild( $titleNode );
					$argIndex = 1;
					foreach ( $parts as $part ) {
						if ( isset( $part->eqpos ) ) {
							// Find equals
							$lastNode = false;
							for ( $node = $part->out->firstNode; $node; $node = $node->nextSibling ) {
								if ( $node === $part->eqpos ) {
									break;
								}
								$lastNode = $node;
							}
							if ( !$node ) {
								if ( $cacheable ) {
								}
								throw new MWException( __METHOD__ . ': eqpos not found' );
							}
							if ( $node->name !== 'equals' ) {
								if ( $cacheable ) {
								}
								throw new MWException( __METHOD__ . ': eqpos is not equals' );
							}
							$equalsNode = $node;

							// Construct name node
							$nameNode = new PPNode_Hash_Tree( 'name' );
							if ( $lastNode !== false ) {
								$lastNode->nextSibling = false;
								$nameNode->firstChild = $part->out->firstNode;
								$nameNode->lastChild = $lastNode;
							}

							// Construct value node
							$valueNode = new PPNode_Hash_Tree( 'value' );
							if ( $equalsNode->nextSibling !== false ) {
								$valueNode->firstChild = $equalsNode->nextSibling;
								$valueNode->lastChild = $part->out->lastNode;
							}
							$partNode = new PPNode_Hash_Tree( 'part' );
							$partNode->addChild( $nameNode );
							$partNode->addChild( $equalsNode->firstChild );
							$partNode->addChild( $valueNode );
							$element->addChild( $partNode );
						} else {
							$partNode = new PPNode_Hash_Tree( 'part' );
							$nameNode = new PPNode_Hash_Tree( 'name' );
							$nameNode->addChild( new PPNode_Hash_Attr( 'index', $argIndex++ ) );
							$valueNode = new PPNode_Hash_Tree( 'value' );
							$valueNode->firstChild = $part->out->firstNode;
							$valueNode->lastChild = $part->out->lastNode;
							$partNode->addChild( $nameNode );
							$partNode->addChild( $valueNode );
							$element->addChild( $partNode );
						}
					}
				}

				# Advance input pointer
				$i += $matchingCount;

				# Unwind the stack
				$stack->pop();
				$accum =& $stack->getAccum();

				# Re-add the old stack element if it still has unmatched opening characters remaining
				if ( $matchingCount < $piece->count ) {
					$piece->parts = array( new PPDPart_Hash );
					$piece->count -= $matchingCount;
					# do we still qualify for any callback with remaining count?
					$min = $rules[$piece->open]['min'];
					if ( $piece->count >= $min ) {
						$stack->push( $piece );
						$accum =& $stack->getAccum();
					} else {
						$accum->addLiteral( str_repeat( $piece->open, $piece->count ) );
					}
				}

				extract( $stack->getFlags() );

				# Add XML element to the enclosing accumulator
				if ( $element instanceof PPNode ) {
					$accum->addNode( $element );
				} else {
					$accum->addAccum( $element );
				}
			} elseif ( $found == 'pipe' ) {
				$findEquals = true; // shortcut for getFlags()
				$stack->addPart();
				$accum =& $stack->getAccum();
				++$i;
			} elseif ( $found == 'equals' ) {
				$findEquals = false; // shortcut for getFlags()
				$accum->addNodeWithText( 'equals', '=' );
				$stack->getCurrentPart()->eqpos = $accum->lastNode;
				++$i;
			}
		}

		# Output any remaining unclosed brackets
		foreach ( $stack->stack as $piece ) {
			$stack->rootAccum->addAccum( $piece->breakSyntax() );
		}

		# Enable top-level headings
		for ( $node = $stack->rootAccum->firstNode; $node; $node = $node->nextSibling ) {
			if ( isset( $node->name ) && $node->name === 'possible-h' ) {
				$node->name = 'h';
			}
		}

		$rootNode = new PPNode_Hash_Tree( 'root' );
		$rootNode->firstChild = $stack->rootAccum->firstNode;
		$rootNode->lastChild = $stack->rootAccum->lastNode;

		// Cache
		if ( $cacheable ) {
			$cacheValue = sprintf( "%08d", self::CACHE_VERSION ) . serialize( $rootNode );
			$wgMemc->set( $cacheKey, $cacheValue, 86400 );
			wfDebugLog( "Preprocessor", "Saved preprocessor Hash to memcached (key $cacheKey)" );
		}

		return $rootNode;
	}
}

/**
 * Stack class to help Preprocessor::preprocessToObj()
 * @ingroup Parser
 * @codingStandardsIgnoreStart
 */
class PPDStack_Hash extends PPDStack {
	// @codingStandardsIgnoreEnd

	public function __construct() {
		$this->elementClass = 'PPDStackElement_Hash';
		parent::__construct();
		$this->rootAccum = new PPDAccum_Hash;
	}
}

/**
 * @ingroup Parser
 * @codingStandardsIgnoreStart
 */
class PPDStackElement_Hash extends PPDStackElement {
	// @codingStandardsIgnoreENd

	public function __construct( $data = array() ) {
		$this->partClass = 'PPDPart_Hash';
		parent::__construct( $data );
	}

	/**
	 * Get the accumulator that would result if the close is not found.
	 *
	 * @param int|bool $openingCount
	 * @return PPDAccum_Hash
	 */
	public function breakSyntax( $openingCount = false ) {
		if ( $this->open == "\n" ) {
			$accum = $this->parts[0]->out;
		} else {
			if ( $openingCount === false ) {
				$openingCount = $this->count;
			}
			$accum = new PPDAccum_Hash;
			$accum->addLiteral( str_repeat( $this->open, $openingCount ) );
			$first = true;
			foreach ( $this->parts as $part ) {
				if ( $first ) {
					$first = false;
				} else {
					$accum->addLiteral( '|' );
				}
				$accum->addAccum( $part->out );
			}
		}
		return $accum;
	}
}

/**
 * @ingroup Parser
 * @codingStandardsIgnoreStart
 */
class PPDPart_Hash extends PPDPart {
	// @codingStandardsIgnoreEnd

	public function __construct( $out = '' ) {
		$accum = new PPDAccum_Hash;
		if ( $out !== '' ) {
			$accum->addLiteral( $out );
		}
		parent::__construct( $accum );
	}
}

/**
 * @ingroup Parser
 * @codingStandardsIgnoreStart
 */
class PPDAccum_Hash {
	// @codingStandardsIgnoreEnd

	public $firstNode, $lastNode;

	public function __construct() {
		$this->firstNode = $this->lastNode = false;
	}

	/**
	 * Append a string literal
	 * @param string $s
	 */
	public function addLiteral( $s ) {
		if ( $this->lastNode === false ) {
			$this->firstNode = $this->lastNode = new PPNode_Hash_Text( $s );
		} elseif ( $this->lastNode instanceof PPNode_Hash_Text ) {
			$this->lastNode->value .= $s;
		} else {
			$this->lastNode->nextSibling = new PPNode_Hash_Text( $s );
			$this->lastNode = $this->lastNode->nextSibling;
		}
	}

	/**
	 * Append a PPNode
	 * @param PPNode $node
	 */
	public function addNode( PPNode $node ) {
		if ( $this->lastNode === false ) {
			$this->firstNode = $this->lastNode = $node;
		} else {
			$this->lastNode->nextSibling = $node;
			$this->lastNode = $node;
		}
	}

	/**
	 * Append a tree node with text contents
	 * @param string $name
	 * @param string $value
	 */
	public function addNodeWithText( $name, $value ) {
		$node = PPNode_Hash_Tree::newWithText( $name, $value );
		$this->addNode( $node );
	}

	/**
	 * Append a PPDAccum_Hash
	 * Takes over ownership of the nodes in the source argument. These nodes may
	 * subsequently be modified, especially nextSibling.
	 * @param PPDAccum_Hash $accum
	 */
	public function addAccum( $accum ) {
		if ( $accum->lastNode === false ) {
			// nothing to add
		} elseif ( $this->lastNode === false ) {
			$this->firstNode = $accum->firstNode;
			$this->lastNode = $accum->lastNode;
		} else {
			$this->lastNode->nextSibling = $accum->firstNode;
			$this->lastNode = $accum->lastNode;
		}
	}
}

/**
 * An expansion frame, used as a context to expand the result of preprocessToObj()
 * @ingroup Parser
 * @codingStandardsIgnoreStart
 */
class PPFrame_Hash implements PPFrame {
	// @codingStandardsIgnoreEnd

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
		$this->titleCache = array( $this->title ? $this->title->getPrefixedDBkey() : false );
		$this->loopCheckHash = array();
		$this->depth = 0;
		$this->childExpansionCache = array();
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
		$namedArgs = array();
		$numberedArgs = array();
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
						$this->parser->addTrackingCategory( 'duplicate-args-category' );
					}
					$numberedArgs[$index] = $bits['value'];
					unset( $namedArgs[$index] );
				} else {
					// Named parameter
					$name = trim( $this->expand( $bits['name'], PPFrame::STRIP_COMMENTS ) );
					if ( isset( $namedArgs[$name] ) || isset( $numberedArgs[$name] ) ) {
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

		$outStack = array( '', '' );
		$iteratorStack = array( false, $root );
		$indexStack = array( 0, 0 );

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

			if ( $contextNode === false ) {
				// nothing to do
			} elseif ( is_string( $contextNode ) ) {
				$out .= $contextNode;
			} elseif ( is_array( $contextNode ) || $contextNode instanceof PPNode_Hash_Array ) {
				$newIterator = $contextNode;
			} elseif ( $contextNode instanceof PPNode_Hash_Attr ) {
				// No output
			} elseif ( $contextNode instanceof PPNode_Hash_Text ) {
				$out .= $contextNode->value;
			} elseif ( $contextNode instanceof PPNode_Hash_Tree ) {
				if ( $contextNode->name == 'template' ) {
					# Double-brace expansion
					$bits = $contextNode->splitTemplate();
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
				} elseif ( $contextNode->name == 'tplarg' ) {
					# Triple-brace expansion
					$bits = $contextNode->splitTemplate();
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
				} elseif ( $contextNode->name == 'comment' ) {
					# HTML-style comment
					# Remove it in HTML, pre+remove and STRIP_COMMENTS modes
					if ( $this->parser->ot['html']
						|| ( $this->parser->ot['pre'] && $this->parser->mOptions->getRemoveComments() )
						|| ( $flags & PPFrame::STRIP_COMMENTS )
					) {
						$out .= '';
					} elseif ( $this->parser->ot['wiki'] && !( $flags & PPFrame::RECOVER_COMMENTS ) ) {
						# Add a strip marker in PST mode so that pstPass2() can
						# run some old-fashioned regexes on the result.
						# Not in RECOVER_COMMENTS mode (extractSections) though.
						$out .= $this->parser->insertStripItem( $contextNode->firstChild->value );
					} else {
						# Recover the literal comment in RECOVER_COMMENTS and pre+no-remove
						$out .= $contextNode->firstChild->value;
					}
				} elseif ( $contextNode->name == 'ignore' ) {
					# Output suppression used by <includeonly> etc.
					# OT_WIKI will only respect <ignore> in substed templates.
					# The other output types respect it unless NO_IGNORE is set.
					# extractSections() sets NO_IGNORE and so never respects it.
					if ( ( !isset( $this->parent ) && $this->parser->ot['wiki'] )
						|| ( $flags & PPFrame::NO_IGNORE )
					) {
						$out .= $contextNode->firstChild->value;
					} else {
						//$out .= '';
					}
				} elseif ( $contextNode->name == 'ext' ) {
					# Extension tag
					$bits = $contextNode->splitExt() + array( 'attr' => null, 'inner' => null, 'close' => null );
					if ( $flags & PPFrame::NO_TAGS ) {
						$s = '<' . $bits['name']->firstChild->value;
						if ( $bits['attr'] ) {
							$s .= $bits['attr']->firstChild->value;
						}
						if ( $bits['inner'] ) {
							$s .= '>' . $bits['inner']->firstChild->value;
							if ( $bits['close'] ) {
								$s .= $bits['close']->firstChild->value;
							}
						} else {
							$s .= '/>';
						}
						$out .= $s;
					} else {
						$out .= $this->parser->extensionSubstitution( $bits, $this );
					}
				} elseif ( $contextNode->name == 'h' ) {
					# Heading
					if ( $this->parser->ot['html'] ) {
						# Expand immediately and insert heading index marker
						$s = '';
						for ( $node = $contextNode->firstChild; $node; $node = $node->nextSibling ) {
							$s .= $this->expand( $node, $flags );
						}

						$bits = $contextNode->splitHeading();
						$titleText = $this->title->getPrefixedDBkey();
						$this->parser->mHeadings[] = array( $titleText, $bits['i'] );
						$serial = count( $this->parser->mHeadings ) - 1;
						$marker = "{$this->parser->mUniqPrefix}-h-$serial-" . Parser::MARKER_SUFFIX;
						$s = substr( $s, 0, $bits['level'] ) . $marker . substr( $s, $bits['level'] );
						$this->parser->mStripState->addGeneral( $marker, '' );
						$out .= $s;
					} else {
						# Expand in virtual stack
						$newIterator = $contextNode->getChildren();
					}
				} else {
					# Generic recursive expansion
					$newIterator = $contextNode->getChildren();
				}
			} else {
				throw new MWException( __METHOD__ . ': Invalid parameter type' );
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
				$root = array( $root );
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
				$root = array( $root );
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
		$out = array();
		$first = true;

		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_Hash_Array ) {
				$root = $root->value;
			}
			if ( !is_array( $root ) ) {
				$root = array( $root );
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
		$out = array( $start );
		$first = true;

		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_Hash_Array ) {
				$root = $root->value;
			}
			if ( !is_array( $root ) ) {
				$root = array( $root );
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
			return isset( $this->titleCache[$level] ) ? $this->titleCache[$level] : false;
		}
	}

	/**
	 * @return array
	 */
	public function getArguments() {
		return array();
	}

	/**
	 * @return array
	 */
	public function getNumberedArguments() {
		return array();
	}

	/**
	 * @return array
	 */
	public function getNamedArguments() {
		return array();
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
	 * @param string $name
	 * @return bool
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
 * @codingStandardsIgnoreStart
 */
class PPTemplateFrame_Hash extends PPFrame_Hash {
	// @codingStandardsIgnoreEnd

	public $numberedArgs, $namedArgs, $parent;
	public $numberedExpansionCache, $namedExpansionCache;

	/**
	 * @param Preprocessor $preprocessor
	 * @param bool|PPFrame $parent
	 * @param array $numberedArgs
	 * @param array $namedArgs
	 * @param bool|Title $title
	 */
	public function __construct( $preprocessor, $parent = false, $numberedArgs = array(),
		$namedArgs = array(), $title = false
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
		$this->numberedExpansionCache = $this->namedExpansionCache = array();
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
		$arguments = array();
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
		$arguments = array();
		foreach ( array_keys( $this->numberedArgs ) as $key ) {
			$arguments[$key] = $this->getArgument( $key );
		}
		return $arguments;
	}

	/**
	 * @return array
	 */
	public function getNamedArguments() {
		$arguments = array();
		foreach ( array_keys( $this->namedArgs ) as $key ) {
			$arguments[$key] = $this->getArgument( $key );
		}
		return $arguments;
	}

	/**
	 * @param int $index
	 * @return array|bool
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
	 * @return bool
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
	 * @param string $name
	 * @return array|bool
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
 * @codingStandardsIgnoreStart
 */
class PPCustomFrame_Hash extends PPFrame_Hash {
	// @codingStandardsIgnoreEnd

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
	 * @param int $index
	 * @return bool
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
 * @codingStandardsIgnoreStart
 */
class PPNode_Hash_Tree implements PPNode {
	// @codingStandardsIgnoreEnd

	public $name, $firstChild, $lastChild, $nextSibling;

	public function __construct( $name ) {
		$this->name = $name;
		$this->firstChild = $this->lastChild = $this->nextSibling = false;
	}

	public function __toString() {
		$inner = '';
		$attribs = '';
		for ( $node = $this->firstChild; $node; $node = $node->nextSibling ) {
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
	 * @param string $name
	 * @param string $text
	 * @return PPNode_Hash_Tree
	 */
	public static function newWithText( $name, $text ) {
		$obj = new self( $name );
		$obj->addChild( new PPNode_Hash_Text( $text ) );
		return $obj;
	}

	public function addChild( $node ) {
		if ( $this->lastChild === false ) {
			$this->firstChild = $this->lastChild = $node;
		} else {
			$this->lastChild->nextSibling = $node;
			$this->lastChild = $node;
		}
	}

	/**
	 * @return PPNode_Hash_Array
	 */
	public function getChildren() {
		$children = array();
		for ( $child = $this->firstChild; $child; $child = $child->nextSibling ) {
			$children[] = $child;
		}
		return new PPNode_Hash_Array( $children );
	}

	public function getFirstChild() {
		return $this->firstChild;
	}

	public function getNextSibling() {
		return $this->nextSibling;
	}

	public function getChildrenOfType( $name ) {
		$children = array();
		for ( $child = $this->firstChild; $child; $child = $child->nextSibling ) {
			if ( isset( $child->name ) && $child->name === $name ) {
				$children[] = $child;
			}
		}
		return $children;
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
		$bits = array();
		for ( $child = $this->firstChild; $child; $child = $child->nextSibling ) {
			if ( !isset( $child->name ) ) {
				continue;
			}
			if ( $child->name === 'name' ) {
				$bits['name'] = $child;
				if ( $child->firstChild instanceof PPNode_Hash_Attr
					&& $child->firstChild->name === 'index'
				) {
					$bits['index'] = $child->firstChild->value;
				}
			} elseif ( $child->name === 'value' ) {
				$bits['value'] = $child;
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
		$bits = array();
		for ( $child = $this->firstChild; $child; $child = $child->nextSibling ) {
			if ( !isset( $child->name ) ) {
				continue;
			}
			if ( $child->name == 'name' ) {
				$bits['name'] = $child;
			} elseif ( $child->name == 'attr' ) {
				$bits['attr'] = $child;
			} elseif ( $child->name == 'inner' ) {
				$bits['inner'] = $child;
			} elseif ( $child->name == 'close' ) {
				$bits['close'] = $child;
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
		$bits = array();
		for ( $child = $this->firstChild; $child; $child = $child->nextSibling ) {
			if ( !isset( $child->name ) ) {
				continue;
			}
			if ( $child->name == 'i' ) {
				$bits['i'] = $child->value;
			} elseif ( $child->name == 'level' ) {
				$bits['level'] = $child->value;
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
		$parts = array();
		$bits = array( 'lineStart' => '' );
		for ( $child = $this->firstChild; $child; $child = $child->nextSibling ) {
			if ( !isset( $child->name ) ) {
				continue;
			}
			if ( $child->name == 'title' ) {
				$bits['title'] = $child;
			}
			if ( $child->name == 'part' ) {
				$parts[] = $child;
			}
			if ( $child->name == 'lineStart' ) {
				$bits['lineStart'] = '1';
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
 * @codingStandardsIgnoreStart
 */
class PPNode_Hash_Text implements PPNode {
	// @codingStandardsIgnoreEnd

	public $value, $nextSibling;

	public function __construct( $value ) {
		if ( is_object( $value ) ) {
			throw new MWException( __CLASS__ . ' given object instead of string' );
		}
		$this->value = $value;
	}

	public function __toString() {
		return htmlspecialchars( $this->value );
	}

	public function getNextSibling() {
		return $this->nextSibling;
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
 * @codingStandardsIgnoreStart
 */
class PPNode_Hash_Array implements PPNode {
	// @codingStandardsIgnoreEnd

	public $value, $nextSibling;

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
		return $this->nextSibling;
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
 * @codingStandardsIgnoreStart
 */
class PPNode_Hash_Attr implements PPNode {
	// @codingStandardsIgnoreEnd

	public $name, $value, $nextSibling;

	public function __construct( $name, $value ) {
		$this->name = $name;
		$this->value = $value;
	}

	public function __toString() {
		return "<@{$this->name}>" . htmlspecialchars( $this->value ) . "</@{$this->name}>";
	}

	public function getName() {
		return $this->name;
	}

	public function getNextSibling() {
		return $this->nextSibling;
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
