<?php
/**
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
 * An expansion frame, used as a context to expand the result of preprocessToObj()
 * @deprecated since 1.34, use PPFrame_Hash
 * @ingroup Parser
 * @phan-file-suppress PhanUndeclaredMethod
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPFrame_DOM implements PPFrame {

	/**
	 * @var Preprocessor
	 */
	public $preprocessor;

	/**
	 * @var Parser
	 */
	public $parser;

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
		$this->title = $this->parser->getTitle();
		$this->titleCache = [ $this->title ? $this->title->getPrefixedDBkey() : false ];
		$this->loopCheckHash = [];
		$this->depth = 0;
		$this->childExpansionCache = [];
	}

	/**
	 * Create a new child frame
	 * $args is optionally a multi-root PPNode or array containing the template arguments
	 *
	 * @param bool|array|PPNode_DOM $args
	 * @param Title|bool $title
	 * @param int $indexOffset
	 * @return PPTemplateFrame_DOM
	 */
	public function newChild( $args = false, $title = false, $indexOffset = 0 ) {
		$namedArgs = [];
		$numberedArgs = [];
		if ( $title === false ) {
			$title = $this->title;
		}
		if ( $args !== false ) {
			$xpath = false;
			if ( $args instanceof PPNode_DOM ) {
				$args = $args->node;
			}
			// @phan-suppress-next-line PhanTypeSuspiciousNonTraversableForeach
			foreach ( $args as $arg ) {
				if ( $arg instanceof PPNode_DOM ) {
					$arg = $arg->node;
				}
				if ( !$xpath || $xpath->document !== $arg->ownerDocument ) {
					$xpath = new DOMXPath( $arg->ownerDocument );
				}

				$nameNodes = $xpath->query( 'name', $arg );
				$value = $xpath->query( 'value', $arg );
				if ( $nameNodes->item( 0 )->hasAttributes() ) {
					// Numbered parameter
					$index = $nameNodes->item( 0 )->attributes->getNamedItem( 'index' )->textContent;
					$index = $index - $indexOffset;
					if ( isset( $namedArgs[$index] ) || isset( $numberedArgs[$index] ) ) {
						$this->parser->getOutput()->addWarning( wfMessage( 'duplicate-args-warning',
							wfEscapeWikiText( $this->title ),
							wfEscapeWikiText( $title ),
							wfEscapeWikiText( $index ) )->text() );
						$this->parser->addTrackingCategory( 'duplicate-args-category' );
					}
					$numberedArgs[$index] = $value->item( 0 );
					unset( $namedArgs[$index] );
				} else {
					// Named parameter
					$name = trim( $this->expand( $nameNodes->item( 0 ), PPFrame::STRIP_COMMENTS ) );
					if ( isset( $namedArgs[$name] ) || isset( $numberedArgs[$name] ) ) {
						$this->parser->getOutput()->addWarning( wfMessage( 'duplicate-args-warning',
							wfEscapeWikiText( $this->title ),
							wfEscapeWikiText( $title ),
							wfEscapeWikiText( $name ) )->text() );
						$this->parser->addTrackingCategory( 'duplicate-args-category' );
					}
					$namedArgs[$name] = $value->item( 0 );
					unset( $numberedArgs[$name] );
				}
			}
		}
		return new PPTemplateFrame_DOM( $this->preprocessor, $this, $numberedArgs, $namedArgs, $title );
	}

	/**
	 * @throws MWException
	 * @param string|int $key
	 * @param string|PPNode_DOM|DOMNode|DOMNodeList $root
	 * @param int $flags
	 * @return string
	 */
	public function cachedExpand( $key, $root, $flags = 0 ) {
		// we don't have a parent, so we don't have a cache
		return $this->expand( $root, $flags );
	}

	/**
	 * @throws MWException
	 * @param string|PPNode_DOM|DOMNode|DOMNodeList $root
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

		if ( $root instanceof PPNode_DOM ) {
			$root = $root->node;
		}
		if ( $root instanceof DOMDocument ) {
			$root = $root->documentElement;
		}

		$outStack = [ '', '' ];
		$iteratorStack = [ false, $root ];
		$indexStack = [ 0, 0 ];

		while ( count( $iteratorStack ) > 1 ) {
			$level = count( $outStack ) - 1;
			$iteratorNode =& $iteratorStack[$level];
			$out =& $outStack[$level];
			$index =& $indexStack[$level];

			if ( $iteratorNode instanceof PPNode_DOM ) {
				$iteratorNode = $iteratorNode->node;
			}

			if ( is_array( $iteratorNode ) ) {
				if ( $index >= count( $iteratorNode ) ) {
					// All done with this iterator
					$iteratorStack[$level] = false;
					$contextNode = false;
				} else {
					$contextNode = $iteratorNode[$index];
					$index++;
				}
			} elseif ( $iteratorNode instanceof DOMNodeList ) {
				if ( $index >= $iteratorNode->length ) {
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

			if ( $contextNode instanceof PPNode_DOM ) {
				$contextNode = $contextNode->node;
			}

			$newIterator = false;

			if ( $contextNode === false ) {
				// nothing to do
			} elseif ( is_string( $contextNode ) ) {
				$out .= $contextNode;
			} elseif ( is_array( $contextNode ) || $contextNode instanceof DOMNodeList ) {
				$newIterator = $contextNode;
			} elseif ( $contextNode instanceof DOMNode ) {
				if ( $contextNode->nodeType == XML_TEXT_NODE ) {
					$out .= $contextNode->nodeValue;
				} elseif ( $contextNode->nodeName == 'template' ) {
					# Double-brace expansion
					$xpath = new DOMXPath( $contextNode->ownerDocument );
					$titles = $xpath->query( 'title', $contextNode );
					$title = $titles->item( 0 );
					$parts = $xpath->query( 'part', $contextNode );
					if ( $flags & PPFrame::NO_TEMPLATES ) {
						$newIterator = $this->virtualBracketedImplode( '{{', '|', '}}', $title, $parts );
					} else {
						$lineStart = $contextNode->getAttribute( 'lineStart' );
						$params = [
							'title' => new PPNode_DOM( $title ),
							'parts' => new PPNode_DOM( $parts ),
							'lineStart' => $lineStart ];
						$ret = $this->parser->braceSubstitution( $params, $this );
						if ( isset( $ret['object'] ) ) {
							$newIterator = $ret['object'];
						} else {
							$out .= $ret['text'];
						}
					}
				} elseif ( $contextNode->nodeName == 'tplarg' ) {
					# Triple-brace expansion
					$xpath = new DOMXPath( $contextNode->ownerDocument );
					$titles = $xpath->query( 'title', $contextNode );
					$title = $titles->item( 0 );
					$parts = $xpath->query( 'part', $contextNode );
					if ( $flags & PPFrame::NO_ARGS ) {
						$newIterator = $this->virtualBracketedImplode( '{{{', '|', '}}}', $title, $parts );
					} else {
						$params = [
							'title' => new PPNode_DOM( $title ),
							'parts' => new PPNode_DOM( $parts ) ];
						$ret = $this->parser->argSubstitution( $params, $this );
						if ( isset( $ret['object'] ) ) {
							$newIterator = $ret['object'];
						} else {
							$out .= $ret['text'];
						}
					}
				} elseif ( $contextNode->nodeName == 'comment' ) {
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
						$out .= $this->parser->insertStripItem( $contextNode->textContent );
					} else {
						# Recover the literal comment in RECOVER_COMMENTS and pre+no-remove
						$out .= $contextNode->textContent;
					}
				} elseif ( $contextNode->nodeName == 'ignore' ) {
					# Output suppression used by <includeonly> etc.
					# OT_WIKI will only respect <ignore> in substed templates.
					# The other output types respect it unless NO_IGNORE is set.
					# extractSections() sets NO_IGNORE and so never respects it.
					if ( ( !isset( $this->parent ) && $this->parser->ot['wiki'] )
						|| ( $flags & PPFrame::NO_IGNORE )
					) {
						$out .= $contextNode->textContent;
					} else {
						$out .= '';
					}
				} elseif ( $contextNode->nodeName == 'ext' ) {
					# Extension tag
					$xpath = new DOMXPath( $contextNode->ownerDocument );
					$names = $xpath->query( 'name', $contextNode );
					$attrs = $xpath->query( 'attr', $contextNode );
					$inners = $xpath->query( 'inner', $contextNode );
					$closes = $xpath->query( 'close', $contextNode );
					if ( $flags & PPFrame::NO_TAGS ) {
						$s = '<' . $this->expand( $names->item( 0 ), $flags );
						if ( $attrs->length > 0 ) {
							$s .= $this->expand( $attrs->item( 0 ), $flags );
						}
						if ( $inners->length > 0 ) {
							$s .= '>' . $this->expand( $inners->item( 0 ), $flags );
							if ( $closes->length > 0 ) {
								$s .= $this->expand( $closes->item( 0 ), $flags );
							}
						} else {
							$s .= '/>';
						}
						$out .= $s;
					} else {
						$params = [
							'name' => new PPNode_DOM( $names->item( 0 ) ),
							'attr' => $attrs->length > 0 ? new PPNode_DOM( $attrs->item( 0 ) ) : null,
							'inner' => $inners->length > 0 ? new PPNode_DOM( $inners->item( 0 ) ) : null,
							'close' => $closes->length > 0 ? new PPNode_DOM( $closes->item( 0 ) ) : null,
						];
						$out .= $this->parser->extensionSubstitution( $params, $this );
					}
				} elseif ( $contextNode->nodeName == 'h' ) {
					# Heading
					$s = $this->expand( $contextNode->childNodes, $flags );

					# Insert a heading marker only for <h> children of <root>
					# This is to stop extractSections from going over multiple tree levels
					if ( $contextNode->parentNode->nodeName == 'root' && $this->parser->ot['html'] ) {
						# Insert heading index marker
						$headingIndex = $contextNode->getAttribute( 'i' );
						$titleText = $this->title->getPrefixedDBkey();
						$this->parser->mHeadings[] = [ $titleText, $headingIndex ];
						$serial = count( $this->parser->mHeadings ) - 1;
						$marker = Parser::MARKER_PREFIX . "-h-$serial-" . Parser::MARKER_SUFFIX;
						$count = $contextNode->getAttribute( 'level' );
						$s = substr( $s, 0, $count ) . $marker . substr( $s, $count );
						$this->parser->mStripState->addGeneral( $marker, '' );
					}
					$out .= $s;
				} else {
					# Generic recursive expansion
					$newIterator = $contextNode->childNodes;
				}
			} else {
				throw new MWException( __METHOD__ . ': Invalid parameter type' );
			}

			if ( $newIterator !== false ) {
				if ( $newIterator instanceof PPNode_DOM ) {
					$newIterator = $newIterator->node;
				}
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
	 * @param string|PPNode_DOM|DOMNode ...$args
	 * @return string
	 */
	public function implodeWithFlags( $sep, $flags, ...$args ) {
		$first = true;
		$s = '';
		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_DOM ) {
				$root = $root->node;
			}
			if ( !is_array( $root ) && !( $root instanceof DOMNodeList ) ) {
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
	 *
	 * @param string $sep
	 * @param string|PPNode_DOM|DOMNode ...$args
	 * @return string
	 */
	public function implode( $sep, ...$args ) {
		$first = true;
		$s = '';
		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_DOM ) {
				$root = $root->node;
			}
			if ( !is_array( $root ) && !( $root instanceof DOMNodeList ) ) {
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
	 * @param string|PPNode_DOM|DOMNode ...$args
	 * @return array
	 * @suppress PhanParamSignatureMismatch
	 */
	public function virtualImplode( $sep, ...$args ) {
		$out = [];
		$first = true;

		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_DOM ) {
				$root = $root->node;
			}
			if ( !is_array( $root ) && !( $root instanceof DOMNodeList ) ) {
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
		return $out;
	}

	/**
	 * Virtual implode with brackets
	 * @param string $start
	 * @param string $sep
	 * @param string $end
	 * @param string|PPNode_DOM|DOMNode ...$args
	 * @return array
	 * @suppress PhanParamSignatureMismatch
	 */
	public function virtualBracketedImplode( $start, $sep, $end, ...$args ) {
		$out = [ $start ];
		$first = true;

		foreach ( $args as $root ) {
			if ( $root instanceof PPNode_DOM ) {
				$root = $root->node;
			}
			if ( !is_array( $root ) && !( $root instanceof DOMNodeList ) ) {
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
		return $out;
	}

	public function __toString() {
		return 'frame{}';
	}

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
