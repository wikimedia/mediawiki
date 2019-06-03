<?php
/**
 * Preprocessor using PHP's dom extension
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
 * @deprecated since 1.34, use Preprocessor_Hash
 */

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class Preprocessor_DOM extends Preprocessor {

	/**
	 * @var Parser
	 */
	public $parser;

	public $memoryLimit;

	const CACHE_PREFIX = 'preprocess-xml';

	/**
	 * @param Parser $parser
	 */
	public function __construct( $parser ) {
		wfDeprecated( __METHOD__, '1.34' ); // T204945
		$this->parser = $parser;
		$mem = ini_get( 'memory_limit' );
		$this->memoryLimit = false;
		if ( strval( $mem ) !== '' && $mem != -1 ) {
			if ( preg_match( '/^\d+$/', $mem ) ) {
				$this->memoryLimit = $mem;
			} elseif ( preg_match( '/^(\d+)M$/i', $mem, $m ) ) {
				$this->memoryLimit = $m[1] * 1048576;
			}
		}
	}

	/**
	 * @return PPFrame_DOM
	 */
	public function newFrame() {
		return new PPFrame_DOM( $this );
	}

	/**
	 * @param array $args
	 * @return PPCustomFrame_DOM
	 */
	public function newCustomFrame( $args ) {
		return new PPCustomFrame_DOM( $this, $args );
	}

	/**
	 * @param array $values
	 * @return PPNode_DOM
	 * @throws MWException
	 */
	public function newPartNodeArray( $values ) {
		// NOTE: DOM manipulation is slower than building & parsing XML! (or so Tim sais)
		$xml = "<list>";

		foreach ( $values as $k => $val ) {
			if ( is_int( $k ) ) {
				$xml .= "<part><name index=\"$k\"/><value>"
					. htmlspecialchars( $val ) . "</value></part>";
			} else {
				$xml .= "<part><name>" . htmlspecialchars( $k )
					. "</name>=<value>" . htmlspecialchars( $val ) . "</value></part>";
			}
		}

		$xml .= "</list>";

		$dom = new DOMDocument();
		Wikimedia\suppressWarnings();
		$result = $dom->loadXML( $xml );
		Wikimedia\restoreWarnings();
		if ( !$result ) {
			// Try running the XML through UtfNormal to get rid of invalid characters
			$xml = UtfNormal\Validator::cleanUp( $xml );
			// 1 << 19 == XML_PARSE_HUGE, needed so newer versions of libxml2
			// don't barf when the XML is >256 levels deep
			$result = $dom->loadXML( $xml, 1 << 19 );
		}

		if ( !$result ) {
			throw new MWException( 'Parameters passed to ' . __METHOD__ . ' result in invalid XML' );
		}

		$root = $dom->documentElement;
		$node = new PPNode_DOM( $root->childNodes );
		return $node;
	}

	/**
	 * @throws MWException
	 * @return bool
	 */
	public function memCheck() {
		if ( $this->memoryLimit === false ) {
			return true;
		}
		$usage = memory_get_usage();
		if ( $usage > $this->memoryLimit * 0.9 ) {
			$limit = intval( $this->memoryLimit * 0.9 / 1048576 + 0.5 );
			throw new MWException( "Preprocessor hit 90% memory limit ($limit MB)" );
		}
		return $usage <= $this->memoryLimit * 0.8;
	}

	/**
	 * Preprocess some wikitext and return the document tree.
	 * This is the ghost of Parser::replace_variables().
	 *
	 * @param string $text The text to parse
	 * @param int $flags Bitwise combination of:
	 *     Parser::PTD_FOR_INCLUSION  Handle "<noinclude>" and "<includeonly>"
	 *                                as if the text is being included. Default
	 *                                is to assume a direct page view.
	 *
	 * The generated DOM tree must depend only on the input text and the flags.
	 * The DOM tree must be the same in OT_HTML and OT_WIKI mode, to avoid a regression of T6899.
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
	 * @return PPNode_DOM
	 */
	public function preprocessToObj( $text, $flags = 0 ) {
		$xml = $this->cacheGetTree( $text, $flags );
		if ( $xml === false ) {
			$xml = $this->preprocessToXml( $text, $flags );
			$this->cacheSetTree( $text, $flags, $xml );
		}

		// Fail if the number of elements exceeds acceptable limits
		// Do not attempt to generate the DOM
		$this->parser->mGeneratedPPNodeCount += substr_count( $xml, '<' );
		$max = $this->parser->mOptions->getMaxGeneratedPPNodeCount();
		if ( $this->parser->mGeneratedPPNodeCount > $max ) {
			// if ( $cacheable ) { ... }
			throw new MWException( __METHOD__ . ': generated node count limit exceeded' );
		}

		$dom = new DOMDocument;
		Wikimedia\suppressWarnings();
		$result = $dom->loadXML( $xml );
		Wikimedia\restoreWarnings();
		if ( !$result ) {
			// Try running the XML through UtfNormal to get rid of invalid characters
			$xml = UtfNormal\Validator::cleanUp( $xml );
			// 1 << 19 == XML_PARSE_HUGE, needed so newer versions of libxml2
			// don't barf when the XML is >256 levels deep.
			$result = $dom->loadXML( $xml, 1 << 19 );
		}
		if ( $result ) {
			$obj = new PPNode_DOM( $dom->documentElement );
		}

		// if ( $cacheable ) { ... }

		if ( !$result ) {
			throw new MWException( __METHOD__ . ' generated invalid XML' );
		}
		return $obj;
	}

	/**
	 * @param string $text
	 * @param int $flags
	 * @return string
	 */
	public function preprocessToXml( $text, $flags = 0 ) {
		global $wgDisableLangConversion;

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

		$stack = new PPDStack;

		$searchBase = "[{<\n"; # }
		if ( !$wgDisableLangConversion ) {
			$searchBase .= '-';
		}

		// For fast reverse searches
		$revText = strrev( $text );
		$lengthText = strlen( $text );

		// Input pointer, starts out pointing to a pseudo-newline before the start
		$i = 0;
		// Current accumulator
		$accum =& $stack->getAccum();
		$accum = '<root>';
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
					$accum .= '<ignore>' . htmlspecialchars( substr( $text, $i ) ) . '</ignore>';
					break;
				}
				$tagEndPos = $startPos + strlen( '<onlyinclude>' ); // past-the-end
				$accum .= '<ignore>' . htmlspecialchars( substr( $text, $i, $tagEndPos - $i ) ) . '</ignore>';
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
					$accum .= htmlspecialchars( substr( $text, $i, $literalLength ) );
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
							$accum .= $curChar;
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
					$accum .= '&lt;';
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
						$accum .= '<comment>' . htmlspecialchars( $inner ) . '</comment>';
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
							// Sanity check first though
							$wsLength = $i - $wsStart;
							if ( $wsLength > 0
								&& strspn( $accum, " \t", -$wsLength ) === $wsLength
							) {
								$accum = substr( $accum, 0, -$wsLength );
							}

							// Dump all but the last comment to the accumulator
							foreach ( $comments as $j => $com ) {
								$startPos = $com[0];
								$endPos = $com[1] + 1;
								if ( $j == ( count( $comments ) - 1 ) ) {
									break;
								}
								$inner = substr( $text, $startPos, $endPos - $startPos );
								$accum .= '<comment>' . htmlspecialchars( $inner ) . '</comment>';
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
						$accum .= '<comment>' . htmlspecialchars( $inner ) . '</comment>';
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
					$accum .= '&lt;';
					++$i;
					continue;
				}

				// Handle ignored tags
				if ( in_array( $lowerName, $ignoredTags ) ) {
					$accum .= '<ignore>'
						. htmlspecialchars( substr( $text, $i, $tagEndPos - $i + 1 ) )
						. '</ignore>';
					$i = $tagEndPos + 1;
					continue;
				}

				$tagStartPos = $i;
				if ( $text[$tagEndPos - 1] == '/' ) {
					$attrEnd = $tagEndPos - 1;
					$inner = null;
					$i = $tagEndPos + 1;
					$close = '';
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
						$close = '<close>' . htmlspecialchars( $matches[0][0] ) . '</close>';
					} else {
						// No end tag
						if ( in_array( $name, $xmlishAllowMissingEndTag ) ) {
							// Let it run out to the end of the text.
							$inner = substr( $text, $tagEndPos + 1 );
							$i = $lengthText;
							$close = '';
						} else {
							// Don't match the tag, treat opening tag as literal and resume parsing.
							$i = $tagEndPos + 1;
							$accum .= htmlspecialchars( substr( $text, $tagStartPos, $tagEndPos + 1 - $tagStartPos ) );
							// Cache results, otherwise we have O(N^2) performance for input like <foo><foo><foo>...
							$noMoreClosingTag[$name] = true;
							continue;
						}
					}
				}
				// <includeonly> and <noinclude> just become <ignore> tags
				if ( in_array( $lowerName, $ignoredElements ) ) {
					$accum .= '<ignore>' . htmlspecialchars( substr( $text, $tagStartPos, $i - $tagStartPos ) )
						. '</ignore>';
					continue;
				}

				$accum .= '<ext>';
				if ( $attrEnd <= $attrStart ) {
					$attr = '';
				} else {
					$attr = substr( $text, $attrStart, $attrEnd - $attrStart );
				}
				$accum .= '<name>' . htmlspecialchars( $name ) . '</name>' .
					// Note that the attr element contains the whitespace between name and attribute,
					// this is necessary for precise reconstruction during pre-save transform.
					'<attr>' . htmlspecialchars( $attr ) . '</attr>';
				if ( $inner !== null ) {
					$accum .= '<inner>' . htmlspecialchars( $inner ) . '</inner>';
				}
				$accum .= $close . '</ext>';
			} elseif ( $found == 'line-start' ) {
				// Is this the start of a heading?
				// Line break belongs before the heading element in any case
				if ( $fakeLineStart ) {
					$fakeLineStart = false;
				} else {
					$accum .= $curChar;
					$i++;
				}

				$count = strspn( $text, '=', $i, 6 );
				if ( $count == 1 && $findEquals ) {
					// DWIM: This looks kind of like a name/value separator.
					// Let's let the equals handler have it and break the
					// potential heading. This is heuristic, but AFAICT the
					// methods for completely correct disambiguation are very
					// complex.
				} elseif ( $count > 0 ) {
					$piece = [
						'open' => "\n",
						'close' => "\n",
						'parts' => [ new PPDPart( str_repeat( '=', $count ) ) ],
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
						$element = "<h level=\"$count\" i=\"$headingIndex\">$accum</h>";
						$headingIndex++;
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
				$accum .= $element;
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
					$accum .= htmlspecialchars( $savedPrefix . str_repeat( $curChar, $count ) );
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
					$accum .= htmlspecialchars( $endText );
					$i += $count;
					continue;
				}
				$name = $rule['names'][$matchingCount];
				if ( $name === null ) {
					// No element, just literal text
					$endText = substr( $text, $i, $matchingCount );
					$element = $piece->breakSyntax( $matchingCount ) . $endText;
				} else {
					# Create XML element
					# Note: $parts is already XML, does not need to be encoded further
					$parts = $piece->parts;
					$title = $parts[0]->out;
					unset( $parts[0] );

					# The invocation is at the start of the line if lineStart is set in
					# the stack, and all opening brackets are used up.
					if ( $maxCount == $matchingCount &&
							!empty( $piece->lineStart ) &&
							strlen( $piece->savedPrefix ) == 0 ) {
						$attr = ' lineStart="1"';
					} else {
						$attr = '';
					}

					$element = "<$name$attr>";
					$element .= "<title>$title</title>";
					$argIndex = 1;
					foreach ( $parts as $part ) {
						if ( isset( $part->eqpos ) ) {
							$argName = substr( $part->out, 0, $part->eqpos );
							$argValue = substr( $part->out, $part->eqpos + 1 );
							$element .= "<part><name>$argName</name>=<value>$argValue</value></part>";
						} else {
							$element .= "<part><name index=\"$argIndex\" /><value>{$part->out}</value></part>";
							$argIndex++;
						}
					}
					$element .= "</$name>";
				}

				# Advance input pointer
				$i += $matchingCount;

				# Unwind the stack
				$stack->pop();
				$accum =& $stack->getAccum();

				# Re-add the old stack element if it still has unmatched opening characters remaining
				if ( $matchingCount < $piece->count ) {
					$piece->parts = [ new PPDPart ];
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
						$accum .= $piece->savedPrefix . $s;
					}
				} elseif ( $piece->savedPrefix !== '' ) {
					$accum .= $piece->savedPrefix;
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
				$accum .= $element;
			} elseif ( $found == 'pipe' ) {
				$findEquals = true; // shortcut for getFlags()
				$stack->addPart();
				$accum =& $stack->getAccum();
				++$i;
			} elseif ( $found == 'equals' ) {
				$findEquals = false; // shortcut for getFlags()
				$stack->getCurrentPart()->eqpos = strlen( $accum );
				$accum .= '=';
				++$i;
			}
		}

		# Output any remaining unclosed brackets
		foreach ( $stack->stack as $piece ) {
			$stack->rootAccum .= $piece->breakSyntax();
		}
		$stack->rootAccum .= '</root>';
		$xml = $stack->rootAccum;

		return $xml;
	}
}
