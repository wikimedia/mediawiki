<?php

/**
 * This is a state machine style parser with two internal stacks:
 *   * A next state stack, which determines the state the machine will progress to next
 *   * A path stack, which keeps track of the logical location in the file.
 *
 * Reference grammar:
 *
 * file = T_OPEN_TAG *statement
 * statement = T_VARIABLE "=" expression ";"
 * expression = array / scalar / T_VARIABLE
 * array = T_ARRAY "(" [ element *( "," element ) [ "," ] ] ")"
 * element = assoc-element / expression
 * assoc-element = scalar T_DOUBLE_ARROW expression
 * scalar = T_LNUMBER / T_DNUMBER / T_STRING / T_CONSTANT_ENCAPSED_STRING
 */
class ConfEditor {
	/** The text to parse */
	var $text;

	/** The token array from token_get_all() */
	var $tokens;

	/** The current position in the token array */
	var $pos;

	/** The current 1-based line number */
	var $lineNum;

	/** The current 1-based column number */
	var $colNum;

	/** The current 0-based byte number */
	var $byteNum;

	/** The current ConfEditorToken object */
	var $currentToken;

	/** The previous ConfEditorToken object */
	var $prevToken;

	/**
	 * The state machine stack. This is an array of strings where the topmost
	 * element will be popped off and become the next parser state.
	 */
	var $stateStack;


	/**
	 * The path stack is a stack of associative arrays with the following elements:
	 *    name              The name of top level of the path
	 *    level             The level (number of elements) of the path
	 *    startByte         The byte offset of the start of the path
	 *    startToken        The token offset of the start
	 *    endByte           The byte offset of thee
	 *    endToken          The token offset of the end, plus one
	 *    valueStartToken   The start token offset of the value part
	 *    valueStartByte    The start byte offset of the value part
	 *    valueEndToken     The end token offset of the value part, plus one
	 *    valueEndByte      The end byte offset of the value part, plus one
	 *    nextArrayIndex    The next numeric array index at this level
	 *    hasComma          True if the array element ends with a comma
	 *    arrowByte         The byte offset of the "=>", or false if there isn't one
	 */
	var $pathStack;

	/**
	 * The elements of the top of the pathStack for every path encountered, indexed
	 * by slash-separated path.
	 */
	var $pathInfo;

	/**
	 * Next serial number for whitespace placeholder paths (\@extra-N)
	 */
	var $serial;

	/**
	 * Editor state. This consists of the internal copy/insert operations which
	 * are applied to the source string to obtain the destination string.
	 */
	var $edits;

	/**
	 * Simple entry point for command-line testing
	 *
	 * @param $text string
	 *
	 * @return string
	 */
	static function test( $text ) {
		try {
			$ce = new self( $text );
			$ce->parse();
		} catch ( ConfEditorParseError $e ) {
			return $e->getMessage() . "\n" . $e->highlight( $text );
		}
		return "OK";
	}

	/**
	 * Construct a new parser
	 */
	public function __construct( $text ) {
		$this->text = $text;
	}

	/**
	 * Edit the text. Returns the edited text.
	 * @param $ops Array of operations.
	 *
	 * Operations are given as an associative array, with members:
	 *    type:     One of delete, set, append or insert (required)
	 *    path:     The path to operate on (required)
	 *    key:      The array key to insert/append, with PHP quotes
	 *    value:    The value, with PHP quotes
	 *
	 * delete
	 *    Deletes an array element or statement with the specified path.
	 *    e.g.
	 *        array('type' => 'delete', 'path' => '$foo/bar/baz' )
	 *    is equivalent to the runtime PHP code:
	 *        unset( $foo['bar']['baz'] );
	 *
	 * set
	 *    Sets the value of an array element. If the element doesn't exist, it
	 *    is appended to the array. If it does exist, the value is set, with
	 *    comments and indenting preserved.
	 *
	 * append
	 *    Appends a new element to the end of the array. Adds a trailing comma.
	 *    e.g.
	 *        array( 'type' => 'append', 'path', '$foo/bar',
	 *            'key' => 'baz', 'value' => "'x'" )
	 *    is like the PHP code:
	 *        $foo['bar']['baz'] = 'x';
	 *
	 * insert
	 *    Insert a new element at the start of the array.
	 *
	 */
	public function edit( $ops ) {
		$this->parse();

		$this->edits = array(
			array( 'copy', 0, strlen( $this->text ) )
		);
		foreach ( $ops as $op ) {
			$type = $op['type'];
			$path = $op['path'];
			$value = isset( $op['value'] ) ? $op['value'] : null;
			$key = isset( $op['key'] ) ? $op['key'] : null;

			switch ( $type ) {
			case 'delete':
				list( $start, $end ) = $this->findDeletionRegion( $path );
				$this->replaceSourceRegion( $start, $end, false );
				break;
			case 'set':
				if ( isset( $this->pathInfo[$path] ) ) {
					list( $start, $end ) = $this->findValueRegion( $path );
					$encValue = $value; // var_export( $value, true );
					$this->replaceSourceRegion( $start, $end, $encValue );
					break;
				}
				// No existing path, fall through to append
				$slashPos = strrpos( $path, '/' );
				$key = var_export( substr( $path, $slashPos + 1 ), true );
				$path = substr( $path, 0, $slashPos );
				// Fall through
			case 'append':
				// Find the last array element
				$lastEltPath = $this->findLastArrayElement( $path );
				if ( $lastEltPath === false ) {
					throw new MWException( "Can't find any element of array \"$path\"" );
				}
				$lastEltInfo = $this->pathInfo[$lastEltPath];

				// Has it got a comma already?
				if ( strpos( $lastEltPath, '@extra' ) === false && !$lastEltInfo['hasComma'] ) {
					// No comma, insert one after the value region
					list( , $end ) = $this->findValueRegion( $lastEltPath );
					$this->replaceSourceRegion( $end - 1, $end - 1, ',' );
				}

				// Make the text to insert
				list( $start, $end ) = $this->findDeletionRegion( $lastEltPath );

				if ( $key === null ) {
					list( $indent, ) = $this->getIndent( $start );
					$textToInsert = "$indent$value,";
				} else {
					list( $indent, $arrowIndent ) =
						$this->getIndent( $start, $key, $lastEltInfo['arrowByte'] );
					$textToInsert = "$indent$key$arrowIndent=> $value,";
				}
				$textToInsert .= ( $indent === false ? ' ' : "\n" );

				// Insert the item
				$this->replaceSourceRegion( $end, $end, $textToInsert );
				break;
			case 'insert':
				// Find first array element
				$firstEltPath = $this->findFirstArrayElement( $path );
				if ( $firstEltPath === false ) {
					throw new MWException( "Can't find array element of \"$path\"" );
				}
				list( $start, ) = $this->findDeletionRegion( $firstEltPath );
				$info = $this->pathInfo[$firstEltPath];

				// Make the text to insert
				if ( $key === null ) {
					list( $indent, ) = $this->getIndent( $start );
					$textToInsert = "$indent$value,";
				} else {
					list( $indent, $arrowIndent ) =
						$this->getIndent( $start, $key, $info['arrowByte'] );
					$textToInsert = "$indent$key$arrowIndent=> $value,";
				}
				$textToInsert .= ( $indent === false ? ' ' : "\n" );

				// Insert the item
				$this->replaceSourceRegion( $start, $start, $textToInsert );
				break;
			default:
				throw new MWException( "Unrecognised operation: \"$type\"" );
			}
		}

		// Do the edits
		$out = '';
		foreach ( $this->edits as $edit ) {
			if ( $edit[0] == 'copy' ) {
				$out .= substr( $this->text, $edit[1], $edit[2] - $edit[1] );
			} else { // if ( $edit[0] == 'insert' )
				$out .= $edit[1];
			}
		}

		// Do a second parse as a sanity check
		$this->text = $out;
		try {
			$this->parse();
		} catch ( ConfEditorParseError $e ) {
			throw new MWException(
				"Sorry, ConfEditor broke the file during editing and it won't parse anymore: " .
				$e->getMessage() );
		}
		return $out;
	}

	/**
	 * Get the variables defined in the text
	 * @return array( varname => value )
	 */
	function getVars() {
		$vars = array();
		$this->parse();
		foreach( $this->pathInfo as $path => $data ) {
			if ( $path[0] != '$' )
				continue;
			$trimmedPath = substr( $path, 1 );
			$name = $data['name'];
			if ( $name[0] == '@' )
				continue;
			if ( $name[0] == '$' )
				$name = substr( $name, 1 );
			$parentPath = substr( $trimmedPath, 0,
				strlen( $trimmedPath ) - strlen( $name ) );
			if( substr( $parentPath, -1 ) == '/' )
				$parentPath = substr( $parentPath, 0, -1 );

			$value = substr( $this->text, $data['valueStartByte'],
				$data['valueEndByte'] - $data['valueStartByte']
			);
			$this->setVar( $vars, $parentPath, $name,
				$this->parseScalar( $value ) );
		}
		return $vars;
	}

	/**
	 * Set a value in an array, unless it's set already. For instance,
	 * setVar( $arr, 'foo/bar', 'baz', 3 ); will set
	 * $arr['foo']['bar']['baz'] = 3;
	 * @param $array array
	 * @param $path string slash-delimited path
	 * @param $key mixed Key
	 * @param $value mixed Value
	 */
	function setVar( &$array, $path, $key, $value ) {
		$pathArr = explode( '/', $path );
		$target =& $array;
		if ( $path !== '' ) {
			foreach ( $pathArr as $p ) {
				if( !isset( $target[$p] ) )
					$target[$p] = array();
				$target =& $target[$p];
			}
		}
		if ( !isset( $target[$key] ) )
			$target[$key] = $value;
	}

	/**
	 * Parse a scalar value in PHP
	 * @return mixed Parsed value
	 */
	function parseScalar( $str ) {
		if ( $str !== '' && $str[0] == '\'' )
			// Single-quoted string
			// @todo FIXME: trim() call is due to mystery bug where whitespace gets
			// appended to the token; without it we ended up reading in the
			// extra quote on the end!
			return strtr( substr( trim( $str ), 1, -1 ),
				array( '\\\'' => '\'', '\\\\' => '\\' ) );
		if ( $str !== '' && $str[0] == '"' )
			// Double-quoted string
			// @todo FIXME: trim() call is due to mystery bug where whitespace gets
			// appended to the token; without it we ended up reading in the
			// extra quote on the end!
			return stripcslashes( substr( trim( $str ), 1, -1 ) );
		if ( substr( $str, 0, 4 ) == 'true' )
			return true;
		if ( substr( $str, 0, 5 ) == 'false' )
			return false;
		if ( substr( $str, 0, 4 ) == 'null' )
			return null;
		// Must be some kind of numeric value, so let PHP's weak typing
		// be useful for a change
		return $str;
	}

	/**
	 * Replace the byte offset region of the source with $newText.
	 * Works by adding elements to the $this->edits array.
	 */
	function replaceSourceRegion( $start, $end, $newText = false ) {
		// Split all copy operations with a source corresponding to the region
		// in question.
		$newEdits = array();
		foreach ( $this->edits as $edit ) {
			if ( $edit[0] !== 'copy' ) {
				$newEdits[] = $edit;
				continue;
			}
			$copyStart = $edit[1];
			$copyEnd = $edit[2];
			if ( $start >= $copyEnd || $end <= $copyStart ) {
				// Outside this region
				$newEdits[] = $edit;
				continue;
			}
			if ( ( $start < $copyStart && $end > $copyStart )
				|| ( $start < $copyEnd && $end > $copyEnd )
			) {
				throw new MWException( "Overlapping regions found, can't do the edit" );
			}
			// Split the copy
			$newEdits[] = array( 'copy', $copyStart, $start );
			if ( $newText !== false ) {
				$newEdits[] = array( 'insert', $newText );
			}
			$newEdits[] = array( 'copy', $end, $copyEnd );
		}
		$this->edits = $newEdits;
	}

	/**
	 * Finds the source byte region which you would want to delete, if $pathName
	 * was to be deleted. Includes the leading spaces and tabs, the trailing line
	 * break, and any comments in between.
	 */
	function findDeletionRegion( $pathName ) {
		if ( !isset( $this->pathInfo[$pathName] ) ) {
			throw new MWException( "Can't find path \"$pathName\"" );
		}
		$path = $this->pathInfo[$pathName];
		// Find the start
		$this->firstToken();
		while ( $this->pos != $path['startToken'] ) {
			$this->nextToken();
		}
		$regionStart = $path['startByte'];
		for ( $offset = -1; $offset >= -$this->pos; $offset-- ) {
			$token = $this->getTokenAhead( $offset );
			if ( !$token->isSkip() ) {
				// If there is other content on the same line, don't move the start point
				// back, because that will cause the regions to overlap.
				$regionStart = $path['startByte'];
				break;
			}
			$lfPos = strrpos( $token->text, "\n" );
			if ( $lfPos === false ) {
				$regionStart -= strlen( $token->text );
			} else {
				// The line start does not include the LF
				$regionStart -= strlen( $token->text ) - $lfPos - 1;
				break;
			}
		}
		// Find the end
		while ( $this->pos != $path['endToken'] ) {
			$this->nextToken();
		}
		$regionEnd = $path['endByte']; // past the end
		for ( $offset = 0; $offset < count( $this->tokens ) - $this->pos; $offset++ ) {
			$token = $this->getTokenAhead( $offset );
			if ( !$token->isSkip() ) {
				break;
			}
			$lfPos = strpos( $token->text, "\n" );
			if ( $lfPos === false ) {
				$regionEnd += strlen( $token->text );
			} else {
				// This should point past the LF
				$regionEnd += $lfPos + 1;
				break;
			}
		}
		return array( $regionStart, $regionEnd );
	}

	/**
	 * Find the byte region in the source corresponding to the value part.
	 * This includes the quotes, but does not include the trailing comma
	 * or semicolon.
	 *
	 * The end position is the past-the-end (end + 1) value as per convention.
	 */
	function findValueRegion( $pathName ) {
		if ( !isset( $this->pathInfo[$pathName] ) ) {
			throw new MWException( "Can't find path \"$pathName\"" );
		}
		$path = $this->pathInfo[$pathName];
		if ( $path['valueStartByte'] === false || $path['valueEndByte'] === false ) {
			throw new MWException( "Can't find value region for path \"$pathName\"" );
		}
		return array( $path['valueStartByte'], $path['valueEndByte'] );
	}

	/**
	 * Find the path name of the last element in the array.
	 * If the array is empty, this will return the \@extra interstitial element.
	 * If the specified path is not found or is not an array, it will return false.
	 */
	function findLastArrayElement( $path ) {
		// Try for a real element
		$lastEltPath = false;
		foreach ( $this->pathInfo as $candidatePath => $info ) {
			$part1 = substr( $candidatePath, 0, strlen( $path ) + 1 );
			$part2 = substr( $candidatePath, strlen( $path ) + 1, 1 );
			if ( $part2 == '@' ) {
				// Do nothing
			} elseif ( $part1 == "$path/" ) {
				$lastEltPath = $candidatePath;
			} elseif ( $lastEltPath !== false ) {
				break;
			}
		}
		if ( $lastEltPath !== false ) {
			return $lastEltPath;
		}

		// Try for an interstitial element
		$extraPath = false;
		foreach ( $this->pathInfo as $candidatePath => $info ) {
			$part1 = substr( $candidatePath, 0, strlen( $path ) + 1 );
			if ( $part1 == "$path/" ) {
				$extraPath = $candidatePath;
			} elseif ( $extraPath !== false ) {
				break;
			}
		}
		return $extraPath;
	}

	/**
	 * Find the path name of first element in the array.
	 * If the array is empty, this will return the \@extra interstitial element.
	 * If the specified path is not found or is not an array, it will return false.
	 */
	function findFirstArrayElement( $path ) {
		// Try for an ordinary element
		foreach ( $this->pathInfo as $candidatePath => $info ) {
			$part1 = substr( $candidatePath, 0, strlen( $path ) + 1 );
			$part2 = substr( $candidatePath, strlen( $path ) + 1, 1 );
			if ( $part1 == "$path/" && $part2 != '@' ) {
				return $candidatePath;
			}
		}

		// Try for an interstitial element
		foreach ( $this->pathInfo as $candidatePath => $info ) {
			$part1 = substr( $candidatePath, 0, strlen( $path ) + 1 );
			if ( $part1 == "$path/" ) {
				return $candidatePath;
			}
		}
		return false;
	}

	/**
	 * Get the indent string which sits after a given start position.
	 * Returns false if the position is not at the start of the line.
	 */
	function getIndent( $pos, $key = false, $arrowPos = false ) {
		$arrowIndent = ' ';
		if ( $pos == 0 || $this->text[$pos-1] == "\n" ) {
			$indentLength = strspn( $this->text, " \t", $pos );
			$indent = substr( $this->text, $pos, $indentLength );
		} else {
			$indent = false;
		}
		if ( $indent !== false && $arrowPos !== false ) {
			$arrowIndentLength = $arrowPos - $pos - $indentLength - strlen( $key );
			if ( $arrowIndentLength > 0 ) {
				$arrowIndent = str_repeat( ' ', $arrowIndentLength );
			}
		}
		return array( $indent, $arrowIndent );
	}

	/**
	 * Run the parser on the text. Throws an exception if the string does not
	 * match our defined subset of PHP syntax.
	 */
	public function parse() {
		$this->initParse();
		$this->pushState( 'file' );
		$this->pushPath( '@extra-' . ($this->serial++) );
		$token = $this->firstToken();

		while ( !$token->isEnd() ) {
			$state = $this->popState();
			if ( !$state ) {
				$this->error( 'internal error: empty state stack' );
			}

			switch ( $state ) {
			case 'file':
				$this->expect( T_OPEN_TAG );
				$token = $this->skipSpace();
				if ( $token->isEnd() ) {
					break 2;
				}
				$this->pushState( 'statement', 'file 2' );
				break;
			case 'file 2':
				$token = $this->skipSpace();
				if ( $token->isEnd() ) {
					break 2;
				}
				$this->pushState( 'statement', 'file 2' );
				break;
			case 'statement':
				$token = $this->skipSpace();
				if ( !$this->validatePath( $token->text ) ) {
					$this->error( "Invalid variable name \"{$token->text}\"" );
				}
				$this->nextPath( $token->text );
				$this->expect( T_VARIABLE );
				$this->skipSpace();
				$arrayAssign = false;
				if ( $this->currentToken()->type == '[' ) {
					$this->nextToken();
					$token = $this->skipSpace();
					if ( !$token->isScalar() ) {
						$this->error( "expected a string or number for the array key" );
					}
					if ( $token->type == T_CONSTANT_ENCAPSED_STRING ) {
						$text = $this->parseScalar( $token->text );
					} else {
						$text = $token->text;
					}
					if ( !$this->validatePath( $text ) ) {
						$this->error( "Invalid associative array name \"$text\"" );
					}
					$this->pushPath( $text );
					$this->nextToken();
					$this->skipSpace();
					$this->expect( ']' );
					$this->skipSpace();
					$arrayAssign = true;
				}
				$this->expect( '=' );
				$this->skipSpace();
				$this->startPathValue();
				if ( $arrayAssign )
					$this->pushState( 'expression', 'array assign end' );
				else
					$this->pushState( 'expression', 'statement end' );
				break;
			case 'array assign end':
			case 'statement end':
				$this->endPathValue();
				if ( $state == 'array assign end' )
					$this->popPath();
				$this->skipSpace();
				$this->expect( ';' );
				$this->nextPath( '@extra-' . ($this->serial++) );
				break;
			case 'expression':
				$token = $this->skipSpace();
				if ( $token->type == T_ARRAY ) {
					$this->pushState( 'array' );
				} elseif ( $token->isScalar() ) {
					$this->nextToken();
				} elseif ( $token->type == T_VARIABLE ) {
					$this->nextToken();
				} else {
					$this->error( "expected simple expression" );
				}
				break;
			case 'array':
				$this->skipSpace();
				$this->expect( T_ARRAY );
				$this->skipSpace();
				$this->expect( '(' );
				$this->skipSpace();
				$this->pushPath( '@extra-' . ($this->serial++) );
				if ( $this->isAhead( ')' ) ) {
					// Empty array
					$this->pushState( 'array end' );
				} else {
					$this->pushState( 'element', 'array end' );
				}
				break;
			case 'array end':
				$this->skipSpace();
				$this->popPath();
				$this->expect( ')' );
				break;
			case 'element':
				$token = $this->skipSpace();
				// Look ahead to find the double arrow
				if ( $token->isScalar() && $this->isAhead( T_DOUBLE_ARROW, 1 ) ) {
					// Found associative element
					$this->pushState( 'assoc-element', 'element end' );
				} else {
					// Not associative
					$this->nextPath( '@next' );
					$this->startPathValue();
					$this->pushState( 'expression', 'element end' );
				}
				break;
			case 'element end':
				$token = $this->skipSpace();
				if ( $token->type == ',' ) {
					$this->endPathValue();
					$this->markComma();
					$this->nextToken();
					$this->nextPath( '@extra-' . ($this->serial++) );
					// Look ahead to find ending bracket
					if ( $this->isAhead( ")" ) ) {
						// Found ending bracket, no continuation
						$this->skipSpace();
					} else {
						// No ending bracket, continue to next element
						$this->pushState( 'element' );
					}
				} elseif ( $token->type == ')' ) {
					// End array
					$this->endPathValue();
				} else {
					$this->error( "expected the next array element or the end of the array" );
				}
				break;
			case 'assoc-element':
				$token = $this->skipSpace();
				if ( !$token->isScalar() ) {
					$this->error( "expected a string or number for the array key" );
				}
				if ( $token->type == T_CONSTANT_ENCAPSED_STRING ) {
					$text = $this->parseScalar( $token->text );
				} else {
					$text = $token->text;
				}
				if ( !$this->validatePath( $text ) ) {
					$this->error( "Invalid associative array name \"$text\"" );
				}
				$this->nextPath( $text );
				$this->nextToken();
				$this->skipSpace();
				$this->markArrow();
				$this->expect( T_DOUBLE_ARROW );
				$this->skipSpace();
				$this->startPathValue();
				$this->pushState( 'expression' );
				break;
			}
		}
		if ( count( $this->stateStack ) ) {
			$this->error( 'unexpected end of file' );
		}
		$this->popPath();
	}

	/**
	 * Initialise a parse.
	 */
	protected function initParse() {
		$this->tokens = token_get_all( $this->text );
		$this->stateStack = array();
		$this->pathStack = array();
		$this->firstToken();
		$this->pathInfo = array();
		$this->serial = 1;
	}

	/**
	 * Set the parse position. Do not call this except from firstToken() and
	 * nextToken(), there is more to update than just the position.
	 */
	protected function setPos( $pos ) {
		$this->pos = $pos;
		if ( $this->pos >= count( $this->tokens ) ) {
			$this->currentToken = ConfEditorToken::newEnd();
		} else {
			$this->currentToken = $this->newTokenObj( $this->tokens[$this->pos] );
		}
		return $this->currentToken;
	}

	/**
	 * Create a ConfEditorToken from an element of token_get_all()
	 */
	function newTokenObj( $internalToken ) {
		if ( is_array( $internalToken ) ) {
			return new ConfEditorToken( $internalToken[0], $internalToken[1] );
		} else {
			return new ConfEditorToken( $internalToken, $internalToken );
		}
	}

	/**
	 * Reset the parse position
	 */
	function firstToken() {
		$this->setPos( 0 );
		$this->prevToken = ConfEditorToken::newEnd();
		$this->lineNum = 1;
		$this->colNum = 1;
		$this->byteNum = 0;
		return $this->currentToken;
	}

	/**
	 * Get the current token
	 */
	function currentToken() {
		return $this->currentToken;
	}

	/**
	 * Advance the current position and return the resulting next token
	 */
	function nextToken() {
		if ( $this->currentToken ) {
			$text = $this->currentToken->text;
			$lfCount = substr_count( $text, "\n" );
			if ( $lfCount ) {
				$this->lineNum += $lfCount;
				$this->colNum = strlen( $text ) - strrpos( $text, "\n" );
			} else {
				$this->colNum += strlen( $text );
			}
			$this->byteNum += strlen( $text );
		}
		$this->prevToken = $this->currentToken;
		$this->setPos( $this->pos + 1 );
		return $this->currentToken;
	}

	/**
	 * Get the token $offset steps ahead of the current position.
	 * $offset may be negative, to get tokens behind the current position.
	 */
	function getTokenAhead( $offset ) {
		$pos = $this->pos + $offset;
		if ( $pos >= count( $this->tokens ) || $pos < 0 ) {
			return ConfEditorToken::newEnd();
		} else {
			return $this->newTokenObj( $this->tokens[$pos] );
		}
	}

	/**
	 * Advances the current position past any whitespace or comments
	 */
	function skipSpace() {
		while ( $this->currentToken && $this->currentToken->isSkip() ) {
			$this->nextToken();
		}
		return $this->currentToken;
	}

	/**
	 * Throws an error if the current token is not of the given type, and
	 * then advances to the next position.
	 */
	function expect( $type ) {
		if ( $this->currentToken && $this->currentToken->type == $type ) {
			return $this->nextToken();
		} else {
			$this->error( "expected " . $this->getTypeName( $type ) .
				", got " . $this->getTypeName( $this->currentToken->type ) );
		}
	}

	/**
	 * Push a state or two on to the state stack.
	 */
	function pushState( $nextState, $stateAfterThat = null ) {
		if ( $stateAfterThat !== null ) {
			$this->stateStack[] = $stateAfterThat;
		}
		$this->stateStack[] = $nextState;
	}

	/**
	 * Pop a state from the state stack.
	 */
	function popState() {
		return array_pop( $this->stateStack );
	}

	/**
	 * Returns true if the user input path is valid.
	 * This exists to allow "/" and "@" to be reserved for string path keys
	 */
	function validatePath( $path ) {
		return strpos( $path, '/' ) === false && substr( $path, 0, 1 ) != '@';
	}

	/**
	 * Internal function to update some things at the end of a path region. Do
	 * not call except from popPath() or nextPath().
	 */
	function endPath() {
		$key = '';
		foreach ( $this->pathStack as $pathInfo ) {
			if ( $key !== '' ) {
				$key .= '/';
			}
			$key .= $pathInfo['name'];
		}
		$pathInfo['endByte'] = $this->byteNum;
		$pathInfo['endToken'] = $this->pos;
		$this->pathInfo[$key] = $pathInfo;
	}

	/**
	 * Go up to a new path level, for example at the start of an array.
	 */
	function pushPath( $path ) {
		$this->pathStack[] = array(
			'name' => $path,
			'level' => count( $this->pathStack ) + 1,
			'startByte' => $this->byteNum,
			'startToken' => $this->pos,
			'valueStartToken' => false,
			'valueStartByte' => false,
			'valueEndToken' => false,
			'valueEndByte' => false,
			'nextArrayIndex' => 0,
			'hasComma' => false,
			'arrowByte' => false
		);
	}

	/**
	 * Go down a path level, for example at the end of an array.
	 */
	function popPath() {
		$this->endPath();
		array_pop( $this->pathStack );
	}

	/**
	 * Go to the next path on the same level. This ends the current path and
	 * starts a new one. If $path is \@next, the new path is set to the next
	 * numeric array element.
	 */
	function nextPath( $path ) {
		$this->endPath();
		$i = count( $this->pathStack ) - 1;
		if ( $path == '@next' ) {
			$nextArrayIndex =& $this->pathStack[$i]['nextArrayIndex'];
			$this->pathStack[$i]['name'] = $nextArrayIndex;
			$nextArrayIndex++;
		} else {
			$this->pathStack[$i]['name'] = $path;
		}
		$this->pathStack[$i] =
			array(
				'startByte' => $this->byteNum,
				'startToken' => $this->pos,
				'valueStartToken' => false,
				'valueStartByte' => false,
				'valueEndToken' => false,
				'valueEndByte' => false,
				'hasComma' => false,
				'arrowByte' => false,
			) + $this->pathStack[$i];
	}

	/**
	 * Mark the start of the value part of a path.
	 */
	function startPathValue() {
		$path =& $this->pathStack[count( $this->pathStack ) - 1];
		$path['valueStartToken'] = $this->pos;
		$path['valueStartByte'] = $this->byteNum;
	}

	/**
	 * Mark the end of the value part of a path.
	 */
	function endPathValue() {
		$path =& $this->pathStack[count( $this->pathStack ) - 1];
		$path['valueEndToken'] = $this->pos;
		$path['valueEndByte'] = $this->byteNum;
	}

	/**
	 * Mark the comma separator in an array element
	 */
	function markComma() {
		$path =& $this->pathStack[count( $this->pathStack ) - 1];
		$path['hasComma'] = true;
	}

	/**
	 * Mark the arrow separator in an associative array element
	 */
	function markArrow() {
		$path =& $this->pathStack[count( $this->pathStack ) - 1];
		$path['arrowByte'] = $this->byteNum;
	}

	/**
	 * Generate a parse error
	 */
	function error( $msg ) {
		throw new ConfEditorParseError( $this, $msg );
	}

	/**
	 * Get a readable name for the given token type.
	 */
	function getTypeName( $type ) {
		if ( is_int( $type ) ) {
			return token_name( $type );
		} else {
			return "\"$type\"";
		}
	}

	/**
	 * Looks ahead to see if the given type is the next token type, starting
	 * from the current position plus the given offset. Skips any intervening
	 * whitespace.
	 */
	function isAhead( $type, $offset = 0 ) {
		$ahead = $offset;
		$token = $this->getTokenAhead( $offset );
		while ( !$token->isEnd() ) {
			if ( $token->isSkip() ) {
				$ahead++;
				$token = $this->getTokenAhead( $ahead );
				continue;
			} elseif ( $token->type == $type ) {
				// Found the type
				return true;
			} else {
				// Not found
				return false;
			}
		}
		return false;
	}

	/**
	 * Get the previous token object
	 */
	function prevToken() {
		return $this->prevToken;
	}

	/**
	 * Echo a reasonably readable representation of the tokenizer array.
	 */
	function dumpTokens() {
		$out = '';
		foreach ( $this->tokens as $token ) {
			$obj = $this->newTokenObj( $token );
			$out .= sprintf( "%-28s %s\n",
				$this->getTypeName( $obj->type ),
				addcslashes( $obj->text, "\0..\37" ) );
		}
		echo "<pre>" . htmlspecialchars( $out ) . "</pre>";
	}
}

/**
 * Exception class for parse errors
 */
class ConfEditorParseError extends MWException {
	var $lineNum, $colNum;
	function __construct( $editor, $msg ) {
		$this->lineNum = $editor->lineNum;
		$this->colNum = $editor->colNum;
		parent::__construct( "Parse error on line {$editor->lineNum} " .
			"col {$editor->colNum}: $msg" );
	}

	function highlight( $text ) {
		$lines = StringUtils::explode( "\n", $text );
		foreach ( $lines as $lineNum => $line ) {
			if ( $lineNum == $this->lineNum - 1 ) {
				return "$line\n" .str_repeat( ' ', $this->colNum - 1 ) . "^\n";
			}
		}
	}

}

/**
 * Class to wrap a token from the tokenizer.
 */
class ConfEditorToken {
	var $type, $text;

	static $scalarTypes = array( T_LNUMBER, T_DNUMBER, T_STRING, T_CONSTANT_ENCAPSED_STRING );
	static $skipTypes = array( T_WHITESPACE, T_COMMENT, T_DOC_COMMENT );

	static function newEnd() {
		return new self( 'END', '' );
	}

	function __construct( $type, $text ) {
		$this->type = $type;
		$this->text = $text;
	}

	function isSkip() {
		return in_array( $this->type, self::$skipTypes );
	}

	function isScalar() {
		return in_array( $this->type, self::$scalarTypes );
	}

	function isEnd() {
		return $this->type == 'END';
	}
}

