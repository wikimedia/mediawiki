<?php

/**
 * This is the part of the wikitext parser which handles automatic paragraphs
 * and conversion of start-of-line prefixes to HTML lists.
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
class BlockLevelPass {
	private $DTopen = false;
	private $inPre = false;
	private $lastParagraph = '';
	private $lineStart;
	private $text;

	# State constants for the definition list colon extraction
	const COLON_STATE_TEXT = 0;
	const COLON_STATE_TAG = 1;
	const COLON_STATE_TAGSTART = 2;
	const COLON_STATE_CLOSETAG = 3;
	const COLON_STATE_TAGSLASH = 4;
	const COLON_STATE_COMMENT = 5;
	const COLON_STATE_COMMENTDASH = 6;
	const COLON_STATE_COMMENTDASHDASH = 7;
	const COLON_STATE_LC = 8;

	/**
	 * Make lists from lines starting with ':', '*', '#', etc.
	 *
	 * @param string $text
	 * @param bool $lineStart Whether or not this is at the start of a line.
	 * @return string The lists rendered as HTML
	 */
	public static function doBlockLevels( $text, $lineStart ) {
		$pass = new self( $text, $lineStart );
		return $pass->execute();
	}

	/**
	 * @param string $text
	 * @param bool $lineStart
	 */
	private function __construct( $text, $lineStart ) {
		$this->text = $text;
		$this->lineStart = $lineStart;
	}

	/**
	 * @return bool
	 */
	private function hasOpenParagraph() {
		return $this->lastParagraph !== '';
	}

	/**
	 * If a pre or p is open, return the corresponding close tag and update
	 * the state. If no tag is open, return an empty string.
	 * @param bool $atTheEnd Omit trailing newline if we've reached the end.
	 * @return string
	 */
	private function closeParagraph( $atTheEnd = false ) {
		$result = '';
		if ( $this->hasOpenParagraph() ) {
			$result = '</' . $this->lastParagraph . '>';
			if ( !$atTheEnd ) {
				$result .= "\n";
			}
		}
		$this->inPre = false;
		$this->lastParagraph = '';
		return $result;
	}

	/**
	 * getCommon() returns the length of the longest common substring
	 * of both arguments, starting at the beginning of both.
	 *
	 * @param string $st1
	 * @param string $st2
	 *
	 * @return int
	 */
	private function getCommon( $st1, $st2 ) {
		$shorter = min( strlen( $st1 ), strlen( $st2 ) );

		for ( $i = 0; $i < $shorter; ++$i ) {
			if ( $st1[$i] !== $st2[$i] ) {
				break;
			}
		}
		return $i;
	}

	/**
	 * Open the list item element identified by the prefix character.
	 *
	 * @param string $char
	 *
	 * @return string
	 */
	private function openList( $char ) {
		$result = $this->closeParagraph();

		if ( $char === '*' ) {
			$result .= "<ul><li>";
		} elseif ( $char === '#' ) {
			$result .= "<ol><li>";
		} elseif ( $char === ':' ) {
			$result .= "<dl><dd>";
		} elseif ( $char === ';' ) {
			$result .= "<dl><dt>";
			$this->DTopen = true;
		} else {
			$result = '<!-- ERR 1 -->';
		}

		return $result;
	}

	/**
	 * Close the current list item and open the next one.
	 * @param string $char
	 *
	 * @return string
	 */
	private function nextItem( $char ) {
		if ( $char === '*' || $char === '#' ) {
			return "</li>\n<li>";
		} elseif ( $char === ':' || $char === ';' ) {
			$close = "</dd>\n";
			if ( $this->DTopen ) {
				$close = "</dt>\n";
			}
			if ( $char === ';' ) {
				$this->DTopen = true;
				return $close . '<dt>';
			} else {
				$this->DTopen = false;
				return $close . '<dd>';
			}
		}
		return '<!-- ERR 2 -->';
	}

	/**
	 * Close the current list item identified by the prefix character.
	 * @param string $char
	 *
	 * @return string
	 */
	private function closeList( $char ) {
		if ( $char === '*' ) {
			$text = "</li></ul>";
		} elseif ( $char === '#' ) {
			$text = "</li></ol>";
		} elseif ( $char === ':' ) {
			if ( $this->DTopen ) {
				$this->DTopen = false;
				$text = "</dt></dl>";
			} else {
				$text = "</dd></dl>";
			}
		} else {
			return '<!-- ERR 3 -->';
		}
		return $text;
	}

	/**
	 * Execute the pass.
	 * @return string
	 */
	private function execute() {
		$text = $this->text;
		# Parsing through the text line by line.  The main thing
		# happening here is handling of block-level elements p, pre,
		# and making lists from lines starting with * # : etc.
		$textLines = StringUtils::explode( "\n", $text );

		$lastPrefix = $output = '';
		$this->DTopen = $inBlockElem = false;
		$prefixLength = 0;
		$pendingPTag = false;
		$inBlockquote = false;

		for ( $textLines->rewind(); $textLines->valid(); ) {
			$inputLine = $textLines->current();
			$textLines->next();
			$notLastLine = $textLines->valid();

			# Fix up $lineStart
			if ( !$this->lineStart ) {
				$output .= $inputLine;
				$this->lineStart = true;
				continue;
			}
			# * = ul
			# # = ol
			# ; = dt
			# : = dd

			$lastPrefixLength = strlen( $lastPrefix );
			$preCloseMatch = preg_match( '/<\\/pre/i', $inputLine );
			$preOpenMatch = preg_match( '/<pre/i', $inputLine );
			# If not in a <pre> element, scan for and figure out what prefixes are there.
			if ( !$this->inPre ) {
				# Multiple prefixes may abut each other for nested lists.
				$prefixLength = strspn( $inputLine, '*#:;' );
				$prefix = substr( $inputLine, 0, $prefixLength );

				# eh?
				# ; and : are both from definition-lists, so they're equivalent
				#  for the purposes of determining whether or not we need to open/close
				#  elements.
				$prefix2 = str_replace( ';', ':', $prefix );
				$t = substr( $inputLine, $prefixLength );
				$this->inPre = (bool)$preOpenMatch;
			} else {
				# Don't interpret any other prefixes in preformatted text
				$prefixLength = 0;
				$prefix = $prefix2 = '';
				$t = $inputLine;
			}

			# List generation
			if ( $prefixLength && $lastPrefix === $prefix2 ) {
				# Same as the last item, so no need to deal with nesting or opening stuff
				$output .= $this->nextItem( substr( $prefix, -1 ) );
				$pendingPTag = false;

				if ( substr( $prefix, -1 ) === ';' ) {
					# The one nasty exception: definition lists work like this:
					# ; title : definition text
					# So we check for : in the remainder text to split up the
					# title and definition, without b0rking links.
					$term = $t2 = '';
					if ( $this->findColonNoLinks( $t, $term, $t2 ) !== false ) {
						$t = $t2;
						// Trim whitespace in list items
						$output .= trim( $term ) . $this->nextItem( ':' );
					}
				}
			} elseif ( $prefixLength || $lastPrefixLength ) {
				# We need to open or close prefixes, or both.

				# Either open or close a level...
				$commonPrefixLength = $this->getCommon( $prefix, $lastPrefix );
				$pendingPTag = false;

				# Close all the prefixes which aren't shared.
				while ( $commonPrefixLength < $lastPrefixLength ) {
					$output .= $this->closeList( $lastPrefix[$lastPrefixLength - 1] );
					--$lastPrefixLength;
				}

				# Continue the current prefix if appropriate.
				if ( $prefixLength <= $commonPrefixLength && $commonPrefixLength > 0 ) {
					$output .= $this->nextItem( $prefix[$commonPrefixLength - 1] );
				}

				# Close an open <dt> if we have a <dd> (":") starting on this line
				if ( $this->DTopen && $commonPrefixLength > 0 && $prefix[$commonPrefixLength - 1] === ':' ) {
					$output .= $this->nextItem( ':' );
				}

				# Open prefixes where appropriate.
				if ( $lastPrefix && $prefixLength > $commonPrefixLength ) {
					$output .= "\n";
				}
				while ( $prefixLength > $commonPrefixLength ) {
					$char = $prefix[$commonPrefixLength];
					$output .= $this->openList( $char );

					if ( $char === ';' ) {
						# @todo FIXME: This is dupe of code above
						if ( $this->findColonNoLinks( $t, $term, $t2 ) !== false ) {
							$t = $t2;
							// Trim whitespace in list items
							$output .= trim( $term ) . $this->nextItem( ':' );
						}
					}
					++$commonPrefixLength;
				}
				if ( !$prefixLength && $lastPrefix ) {
					$output .= "\n";
				}
				$lastPrefix = $prefix2;
			}

			# If we have no prefixes, go to paragraph mode.
			if ( $prefixLength == 0 ) {
				# No prefix (not in list)--go to paragraph mode
				# @todo consider using a stack for nestable elements like span, table and div

				// P-wrapping and indent-pre are suppressed inside, not outside
				$blockElems = 'table|h1|h2|h3|h4|h5|h6|pre|p|ul|ol|dl';
				// P-wrapping and indent-pre are suppressed outside, not inside
				$antiBlockElems = 'td|th';

				$openMatch = preg_match(
					'/<('
						. "({$blockElems})|\\/({$antiBlockElems})|"
						// Always suppresses
						. '\\/?(tr|dt|dd|li)'
						. ')\\b/iS',
					$t
				);
				$closeMatch = preg_match(
					'/<('
						. "\\/({$blockElems})|({$antiBlockElems})|"
						// Never suppresses
						. '\\/?(center|blockquote|div|hr|mw:)'
						. ')\\b/iS',
					$t
				);

				// Any match closes the paragraph, but only when `!$closeMatch`
				// do we enter block mode.  The oddities with table rows and
				// cells are to avoid paragraph wrapping in interstitial spaces
				// leading to fostered content.

				if ( $openMatch || $closeMatch ) {
					$pendingPTag = false;
					// Only close the paragraph if we're not inside a <pre> tag, or if
					// that <pre> tag has just been opened
					if ( !$this->inPre || $preOpenMatch ) {
						// @todo T7718: paragraph closed
						$output .= $this->closeParagraph();
					}
					if ( $preOpenMatch && !$preCloseMatch ) {
						$this->inPre = true;
					}
					$bqOffset = 0;
					while ( preg_match( '/<(\\/?)blockquote[\s>]/i', $t,
						$bqMatch, PREG_OFFSET_CAPTURE, $bqOffset )
					) {
						$inBlockquote = !$bqMatch[1][0]; // is this a close tag?
						$bqOffset = $bqMatch[0][1] + strlen( $bqMatch[0][0] );
					}
					$inBlockElem = !$closeMatch;
				} elseif ( !$inBlockElem && !$this->inPre ) {
					if ( substr( $t, 0, 1 ) == ' '
						&& ( $this->lastParagraph === 'pre' || trim( $t ) != '' )
						&& !$inBlockquote
					) {
						# pre
						if ( $this->lastParagraph !== 'pre' ) {
							$pendingPTag = false;
							$output .= $this->closeParagraph() . '<pre>';
							$this->lastParagraph = 'pre';
						}
						$t = substr( $t, 1 );
					} elseif ( preg_match( '/^(?:<style\\b[^>]*>.*?<\\/style>\s*|<link\\b[^>]*>\s*)+$/iS', $t ) ) {
						# T186965: <style> or <link> by itself on a line shouldn't open or close paragraphs.
						# But it should clear $pendingPTag.
						if ( $pendingPTag ) {
							$output .= $this->closeParagraph();
							$pendingPTag = false;
						}
					} else {
						# paragraph
						if ( trim( $t ) === '' ) {
							if ( $pendingPTag ) {
								$output .= $pendingPTag . '<br />';
								$pendingPTag = false;
								$this->lastParagraph = 'p';
							} elseif ( $this->lastParagraph !== 'p' ) {
								$output .= $this->closeParagraph();
								$pendingPTag = '<p>';
							} else {
								$pendingPTag = '</p><p>';
							}
						} elseif ( $pendingPTag ) {
							$output .= $pendingPTag;
							$pendingPTag = false;
							$this->lastParagraph = 'p';
						} elseif ( $this->lastParagraph !== 'p' ) {
							$output .= $this->closeParagraph() . '<p>';
							$this->lastParagraph = 'p';
						}
					}
				}
			}
			# somewhere above we forget to get out of pre block (T2785)
			if ( $preCloseMatch && $this->inPre ) {
				$this->inPre = false;
			}
			if ( $pendingPTag === false ) {
				if ( $prefixLength === 0 ) {
					$output .= $t;
					// Add a newline if there's an open paragraph
					// or we've yet to reach the last line.
					if ( $notLastLine || $this->hasOpenParagraph() ) {
						$output .= "\n";
					}
				} else {
					// Trim whitespace in list items
					$output .= trim( $t );
				}
			}
		}
		while ( $prefixLength ) {
			$output .= $this->closeList( $prefix2[$prefixLength - 1] );
			--$prefixLength;
			// Note that a paragraph is only ever opened when `prefixLength`
			// is zero, but we'll choose to be overly cautious.
			if ( !$prefixLength && $this->hasOpenParagraph() ) {
				$output .= "\n";
			}
		}
		$output .= $this->closeParagraph( true );
		return $output;
	}

	/**
	 * Split up a string on ':', ignoring any occurrences inside tags
	 * to prevent illegal overlapping.
	 *
	 * @param string $str The string to split
	 * @param string &$before Set to everything before the ':'
	 * @param string &$after Set to everything after the ':'
	 * @throws MWException
	 * @return string The position of the ':', or false if none found
	 */
	private function findColonNoLinks( $str, &$before, &$after ) {
		if ( !preg_match( '/:|<|-\{/', $str, $m, PREG_OFFSET_CAPTURE ) ) {
			# Nothing to find!
			return false;
		}

		if ( $m[0][0] === ':' ) {
			# Easy; no tag nesting to worry about
			$colonPos = $m[0][1];
			$before = substr( $str, 0, $colonPos );
			$after = substr( $str, $colonPos + 1 );
			return $colonPos;
		}

		# Ugly state machine to walk through avoiding tags.
		$state = self::COLON_STATE_TEXT;
		$ltLevel = 0;
		$lcLevel = 0;
		$len = strlen( $str );
		for ( $i = $m[0][1]; $i < $len; $i++ ) {
			$c = $str[$i];

			switch ( $state ) {
				case self::COLON_STATE_TEXT:
					switch ( $c ) {
						case "<":
							# Could be either a <start> tag or an </end> tag
							$state = self::COLON_STATE_TAGSTART;
							break;
						case ":":
							if ( $ltLevel === 0 ) {
								# We found it!
								$before = substr( $str, 0, $i );
								$after = substr( $str, $i + 1 );
								return $i;
							}
							# Embedded in a tag; don't break it.
							break;
						default:
							# Skip ahead looking for something interesting
							if ( !preg_match( '/:|<|-\{/', $str, $m, PREG_OFFSET_CAPTURE, $i ) ) {
								# Nothing else interesting
								return false;
							}
							if ( $m[0][0] === '-{' ) {
								$state = self::COLON_STATE_LC;
								$lcLevel++;
								$i = $m[0][1] + 1;
							} else {
								# Skip ahead to next interesting character.
								$i = $m[0][1] - 1;
							}
							break;
					}
					break;
				case self::COLON_STATE_LC:
					# In language converter markup -{ ... }-
					if ( !preg_match( '/-\{|\}-/', $str, $m, PREG_OFFSET_CAPTURE, $i ) ) {
						# Nothing else interesting to find; abort!
						# We're nested in language converter markup, but there
						# are no close tags left.  Abort!
						break 2;
					} elseif ( $m[0][0] === '-{' ) {
						$i = $m[0][1] + 1;
						$lcLevel++;
					} elseif ( $m[0][0] === '}-' ) {
						$i = $m[0][1] + 1;
						$lcLevel--;
						if ( $lcLevel === 0 ) {
							$state = self::COLON_STATE_TEXT;
						}
					}
					break;
				case self::COLON_STATE_TAG:
					# In a <tag>
					switch ( $c ) {
						case ">":
							$ltLevel++;
							$state = self::COLON_STATE_TEXT;
							break;
						case "/":
							# Slash may be followed by >?
							$state = self::COLON_STATE_TAGSLASH;
							break;
						default:
							# ignore
					}
					break;
				case self::COLON_STATE_TAGSTART:
					switch ( $c ) {
						case "/":
							$state = self::COLON_STATE_CLOSETAG;
							break;
						case "!":
							$state = self::COLON_STATE_COMMENT;
							break;
						case ">":
							# Illegal early close? This shouldn't happen D:
							$state = self::COLON_STATE_TEXT;
							break;
						default:
							$state = self::COLON_STATE_TAG;
					}
					break;
				case self::COLON_STATE_CLOSETAG:
					# In a </tag>
					if ( $c === ">" ) {
						if ( $ltLevel > 0 ) {
							$ltLevel--;
						} else {
							# ignore the excess close tag, but keep looking for
							# colons. (This matches Parsoid behavior.)
							wfDebug( __METHOD__ . ": Invalid input; too many close tags\n" );
						}
						$state = self::COLON_STATE_TEXT;
					}
					break;
				case self::COLON_STATE_TAGSLASH:
					if ( $c === ">" ) {
						# Yes, a self-closed tag <blah/>
						$state = self::COLON_STATE_TEXT;
					} else {
						# Probably we're jumping the gun, and this is an attribute
						$state = self::COLON_STATE_TAG;
					}
					break;
				case self::COLON_STATE_COMMENT:
					if ( $c === "-" ) {
						$state = self::COLON_STATE_COMMENTDASH;
					}
					break;
				case self::COLON_STATE_COMMENTDASH:
					if ( $c === "-" ) {
						$state = self::COLON_STATE_COMMENTDASHDASH;
					} else {
						$state = self::COLON_STATE_COMMENT;
					}
					break;
				case self::COLON_STATE_COMMENTDASHDASH:
					if ( $c === ">" ) {
						$state = self::COLON_STATE_TEXT;
					} else {
						$state = self::COLON_STATE_COMMENT;
					}
					break;
				default:
					throw new MWException( "State machine error in " . __METHOD__ );
			}
		}
		if ( $ltLevel > 0 || $lcLevel > 0 ) {
			wfDebug(
				__METHOD__ . ": Invalid input; not enough close tags " .
				"(level $ltLevel/$lcLevel, state $state)\n"
			);
			return false;
		}
		return false;
	}
}
