<?php
/**
 * jsmin.php - PHP implementation of Douglas Crockford's JSMin.
 *
 * This is pretty much a direct port of jsmin.c to PHP with just a few
 * PHP-specific performance tweaks. Also, whereas jsmin.c reads from stdin and
 * outputs to stdout, this library accepts a string as input and returns another
 * string as output.
 *
 * PHP 5 or higher is required.
 *
 * Permission is hereby granted to use this version of the library under the
 * same terms as jsmin.c, which has the following license:
 *
 * --
 * Copyright (c) 2002 Douglas Crockford  (www.crockford.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * The Software shall be used for Good, not Evil.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * --
 *
 * @file
 * @author Ryan Grove <ryan@wonko.com>
 * @copyright 2002 Douglas Crockford <douglas@crockford.com> (jsmin.c)
 * @copyright 2008 Ryan Grove <ryan@wonko.com> (PHP port)
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.1.1 (2008-03-02)
 * @link http://github.com/rgrove/jsmin-php/
 */

class JSMin {
	const ORD_LF    = 10;
	const ORD_SPACE = 32;

	// Action constants
	const OUTPUT = 1;
	const DELETE_A = 2;
	const DELETE_B = 3;

	/** Current character */
	protected $a           = '';

	/** Next character */
	protected $b           = '';

	protected $input       = '';
	protected $inputIndex  = 0;
	protected $inputLength = 0;
	protected $lookAhead   = null;
	protected $output      = '';

	// -- Public Static Methods --------------------------------------------------

	public static function minify( $js ) {
		$jsmin = new self( $js );
		$ret = $jsmin->min();
		return $ret;
	}

	// -- Public Instance Methods ------------------------------------------------

	public function __construct( $input ) {
		// Fix line endings
		$this->input       = str_replace( "\r\n", "\n", $input );
		// Replace tabs and other control characters (except LF) with spaces
		$this->input = preg_replace( '/[\x00-\x09\x0b-\x1f]/', ' ', $this->input );
		$this->inputLength = strlen( $this->input );
	}

	// -- Protected Instance Methods ---------------------------------------------

	/**
	 * Do something! What you do is determined by the argument:
	 *		- self::OUTPUT     Output A. Copy B to A. Get the next B.
	 *		- self::DELETE_A   Copy B to A. Get the next B. (Delete A).
	 *		- self::DELETE_B   Get the next B. (Delete B).
	 *  action treats a string as a single character. Wow!
	 *  action recognizes a regular expression if it is preceded by ( or , or =.
	 */
	protected function action( $d ) {
		switch( $d ) {
			case self::OUTPUT:
				$this->output .= $this->a;

			case self::DELETE_A:
				$this->a = $this->b;

				if ( $this->a === "'" || $this->a === '"' ) {
					$interestingChars = $this->a . "\\\n";
					$this->output .= $this->a;
					for ( ; ; ) {
						$runLength = strcspn( $this->input, $interestingChars, $this->inputIndex );
						$this->output .= substr( $this->input, $this->inputIndex, $runLength );
						$this->inputIndex += $runLength;
						$c = $this->get();

						if ( $c === $this->b ) {
							break;
						}

						if ( $c === "\n" || $c === null ) {
							throw new JSMinException( 'Unterminated string literal.' );
						}

						if ( $c === '\\' ) {
							$this->output .= $c . $this->get();
						}
					}
				}

			case self::DELETE_B:
				$this->b = $this->next();

				if ( $this->b === '/' && (
						$this->a === '(' || $this->a === ',' || $this->a === '=' ||
						$this->a === ':' || $this->a === '[' || $this->a === '!' ||
						$this->a === '&' || $this->a === '|' || $this->a === '?' ) ) {

					$this->output .= $this->a . $this->b;

					for ( ; ; ) {
						$runLength = strcspn( $this->input, "/\\\n", $this->inputIndex );
						$this->output .= substr( $this->input, $this->inputIndex, $runLength );
						$this->inputIndex += $runLength;
						$this->a = $this->get();

						if ( $this->a === '/' ) {
							break;
						} elseif ( $this->a === '\\' ) {
							$this->output .= $this->a;
							$this->a       = $this->get();
						} elseif ( $this->a === "\n" || $this->a === null ) {
							throw new JSMinException( 'Unterminated regular expression ' .
									'literal.' );
						}

						$this->output .= $this->a;
					}

					$this->b = $this->next();
				}
		}
	}

	/**
	 * Return the next character from the input. Watch out for lookahead. If
     * the character is a control character, translate it to a space or
     * linefeed.
	 */
	protected function get() {
		if ( $this->inputIndex < $this->inputLength ) {
			return $this->input[$this->inputIndex++];
		} else {
			return null;
		}
	}

	/**
	 * Return true if the character is a letter, digit, underscore,
	 * dollar sign, or non-ASCII character.
	 */
	protected function isAlphaNum( $c ) {
		return ord( $c ) > 126 || $c === '\\' || preg_match( '/^[\w\$]$/', $c ) === 1;
	}

	/**
	 * Copy the input to the output, deleting the characters which are
	 * insignificant to JavaScript. Comments will be removed. Tabs will be
	 * replaced with spaces. Carriage returns will be replaced with linefeeds.
	 * Most spaces and linefeeds will be removed.
	 */
	protected function min() {
		$this->a = "\n";
		$this->action( self::DELETE_B );

		while ( $this->a !== null ) {
			switch ( $this->a ) {
				case ' ':
					if ( $this->isAlphaNum( $this->b ) ) {
						$this->action( self::OUTPUT );
					} else {
						$this->action( self::DELETE_A );
					}
					break;

				case "\n":
					switch ( $this->b ) {
						case ' ':
							$this->action( self::DELETE_B );
							break;

						default:
							$this->action( self::OUTPUT );
					}
					break;

				default:
					switch ( $this->b ) {
						case ' ':
							if ( $this->isAlphaNum( $this->a ) ) {
								$this->action( self::OUTPUT );
								break;
							}

							$this->action( self::DELETE_B );
							break;
						default:
							$this->action( self::OUTPUT );
							break;
					}
			}
		}

		// Remove initial line break
		if ( $this->output[0] !== "\n" ) {
			throw new JSMinException( 'Unexpected lack of line break.' );
		}
		if ( $this->output === "\n" ) {
			return '';
		} else {
			return substr( $this->output, 1 );
		}
	}

	/**
	 * Get the next character, excluding comments.
	 */
	protected function next() {
		if ( $this->inputIndex >= $this->inputLength ) {
			return null;
		}
		$c = $this->input[$this->inputIndex++];

		if ( $this->inputIndex >= $this->inputLength ) {
			return $c;
		}

		if ( $c === '/' ) {
			switch( $this->input[$this->inputIndex] ) {
				case '/':
					$this->inputIndex += strcspn( $this->input, "\n", $this->inputIndex ) + 1;
					return "\n";
				case '*':
					$endPos = strpos( $this->input, '*/', $this->inputIndex + 1 );
					if ( $endPos === false ) {
						throw new JSMinException( 'Unterminated comment.' );
					}
					$numLines = substr_count( $this->input, "\n", $this->inputIndex, 
						$endPos - $this->inputIndex );
					$this->inputIndex = $endPos + 2;
					if ( $numLines ) {
						return str_repeat( "\n", $numLines );
					} else {
						return ' ';
					}
				default:
					return $c;
			}
		}

		return $c;
	}
}

// -- Exceptions ---------------------------------------------------------------
class JSMinException extends Exception {}
