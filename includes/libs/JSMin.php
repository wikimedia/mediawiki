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
		$jsmin = new JSMin( $js );
		$ret = $jsmin->min();
		return $ret;
	}

	// -- Public Instance Methods ------------------------------------------------

	public function __construct( $input ) {
		$this->input       = str_replace( "\r\n", "\n", $input );
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
				// Output A. Copy B to A. Get the next B.
				$this->output .= $this->a;

			case self::DELETE_A:
				// Copy B to A. Get the next B. (Delete A).
				$this->a = $this->b;

				if ( $this->a === "'" || $this->a === '"' ) {
					for ( ; ; ) {
						$this->output .= $this->a;
						$this->a       = $this->get();

						if ( $this->a === $this->b ) {
							break;
						}

						if ( ord( $this->a ) <= self::ORD_LF ) {
							throw new JSMinException( 'Unterminated string literal.' );
						}

						if ( $this->a === '\\' ) {
							$this->output .= $this->a;
							$this->a       = $this->get();
						}
					}
				}

			case self::DELETE_B:
				// Get the next B. (Delete B).
				$this->b = $this->next();

				if ( $this->b === '/' && (
						$this->a === '(' || $this->a === ',' || $this->a === '=' ||
						$this->a === ':' || $this->a === '[' || $this->a === '!' ||
						$this->a === '&' || $this->a === '|' || $this->a === '?' ) ) {

					$this->output .= $this->a . $this->b;

					for ( ; ; ) {
						$this->a = $this->get();

						if ( $this->a === '/' ) {
							break;
						} elseif ( $this->a === '\\' ) {
							$this->output .= $this->a;
							$this->a       = $this->get();
						} elseif ( ord( $this->a ) <= self::ORD_LF ) {
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
		$c = $this->lookAhead;
		$this->lookAhead = null;

		if ( $c === null ) {
			if ( $this->inputIndex < $this->inputLength ) {
				$c = substr( $this->input, $this->inputIndex, 1 );
				$this->inputIndex += 1;
			} else {
				$c = null;
			}
		}

		if ( $c === "\r" ) {
			return "\n";
		}

		if ( $c === null || $c === "\n" || ord( $c ) >= self::ORD_SPACE ) {
			return $c;
		}

		return ' ';
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
						case '{':
						case '[':
						case '(':
						case '+':
						case '-':
							$this->action( self::OUTPUT );
							break;

						case ' ':
							$this->action( self::DELETE_B );
							break;

						default:
							if ( $this->isAlphaNum( $this->b ) ) {
								$this->action( self::OUTPUT );
							}
							else {
								$this->action( self::DELETE_A );
							}
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

						case "\n":
							switch ( $this->a ) {
								case '}':
								case ']':
								case ')':
								case '+':
								case '-':
								case '"':
								case "'":
									$this->action( self::OUTPUT );
									break;

								default:
									if ( $this->isAlphaNum( $this->a ) ) {
										$this->action( self::OUTPUT );
									}
									else {
										$this->action( self::DELETE_B );
									}
							}
							break;

						default:
							$this->action( self::OUTPUT );
							break;
					}
			}
		}

		return $this->output;
	}

	/**
	 * Get the next character, excluding comments. peek() is used to see
     * if a '/' is followed by a '/' or '*'.
	 */
	protected function next() {
		$c = $this->get();

		if ( $c === '/' ) {
			switch( $this->peek() ) {
				case '/':
					for ( ; ; ) {
						$c = $this->get();

						if ( ord( $c ) <= self::ORD_LF ) {
							return $c;
						}
					}

				case '*':
					$this->get();

					for ( ; ; ) {
						switch( $this->get() ) {
							case '*':
								if ( $this->peek() === '/' ) {
									$this->get();
									return ' ';
								}
								break;

							case null:
								throw new JSMinException( 'Unterminated comment.' );
						}
					}

				default:
					return $c;
			}
		}

		return $c;
	}

	/** 
	 * Get the next character without getting it.
	 */
	protected function peek() {
		$this->lookAhead = $this->get();
		return $this->lookAhead;
	}
}

// -- Exceptions ---------------------------------------------------------------
class JSMinException extends Exception {}
