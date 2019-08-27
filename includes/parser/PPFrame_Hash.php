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
	 * @param string|PPNode ...$args
	 * @return string
	 */
	public function implodeWithFlags( $sep, $flags, ...$args ) {
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
	 * @param string|PPNode ...$args
	 * @return string
	 */
	public function implode( $sep, ...$args ) {
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
	 * @param string|PPNode ...$args
	 * @return PPNode_Hash_Array
	 */
	public function virtualImplode( $sep, ...$args ) {
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
	 * @param string|PPNode ...$args
	 * @return PPNode_Hash_Array
	 */
	public function virtualBracketedImplode( $start, $sep, $end, ...$args ) {
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
