<?php
/**
 * Fuzz tester for the JsonFallback library.
 *
 * Copyright © 2013 Kevin Israel
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @file
 * @ingroup JsonFallback
 */

require_once __DIR__ . '/../Maintenance.php';

/**
 * Maintenance script to test the JsonFallback library against random inputs.
 *
 * @ingroup JsonFallback
 */
class JsonFallbackFuzzer extends Maintenance {

	/**
	 * Allow the generated integer to contain leading zeros.
	 */
	const ALLOW_LEADING_ZEROS = 1;

	/**
	 * Allow the generated integer to begin with a plus sign.
	 */
	const ALLOW_PLUS = 2;

	/**
	 * Forbid the generated integer from beginning with a minus sign.
	 */
	const DISALLOW_MINUS = 4;

	/**
	 * @var string $filename Name of currently executing test case
	 */
	protected $filename;

	/**
	 * @var array $tokens The original token stream
	 */
	protected $tokens = array();

	/**
	 * @var bool $runningTest Whether JsonFallback is executing right now
	 */
	protected $runningTest = false;

	/**
	 * @var bool $errorCaught Whether JsonFallback has triggered a PHP error
	 */
	protected $errorCaught = false;

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Tests the JsonFallback library against random inputs';
		$this->addOption( 'originals', 'Number of random files to generate', false, true );
		$this->addOption( 'multiplier', 'Number of mutated files to derive from each original', false, true );
		$this->addOption( 'max-depth', 'Maximum nesting depth for generated JSON', false, true );
		$this->addOption( 'max-mutations', 'Maximum number of mutations per file', false, true );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		// Comparing JsonFallback's result against itself isn't very useful
		if ( ( new ReflectionFunction( 'json_decode' ) )->isUserDefined() ) {
			$this->error( 'Please load the JSON extension.', 1 );
		}

		// Check options
		$originals = (int)$this->getOption( 'originals', 10000 );
		$multiplier = (int)$this->getOption( 'multiplier', 1000 );
		$maxDepth = (int)$this->getOption( 'max-depth', 4 );
		$maxMutations = (int)$this->getOption( 'max-mutations', 10 );

		if ( $originals < 1 ) {
			$this->error( '--originals must be a positive integer', 1 );
		} elseif ( $multiplier < 0 ) {
			$this->error( '--multiplier must not be negative', 1 );
		} elseif ( $maxDepth < 0 ) {
			$this->error( '--max-depth must not be negative', 1 );
		} elseif ( $maxMutations < 1 ) {
			$this->error( '--max-mutations must be a positive integer', 1 );
		}

		// Calculate lengths of filename components
		$majorLen = strlen( $originals - 1 );
		$minorLen = strlen( $multiplier - 1 );

		set_error_handler( array( $this, 'phpErrorHandler' ) );

		for ( $major = 0; $major < $originals; $major++ ) {
			$this->output( "$major/$originals\n" );
			$failure = false;

			// First generate some valid JSON
			$this->generate( $maxDepth );

			for ( $minor = 0; $minor <= $multiplier; $minor++ ) {

				// Mutate the JSON (if not testing the original)
				if ( !$minor ) {
					$this->filename = sprintf( "test%0{$majorLen}d.orig.json", $major );
					$original = $this->filename;
					$json = implode( '', $this->tokens );
				} else {
					$this->filename = sprintf( "test%0{$majorLen}d.%0{$minorLen}d.json",
						$major, $minor - 1 );

					$mutations = mt_rand( 1, $maxMutations );
					$tokenMutations = mt_rand( 0, $mutations );
					$byteMutations = $mutations - $tokenMutations;

					$tokens = $this->mutateTokens( $this->tokens, $tokenMutations );
					$json = $this->mutateBytes( implode( '', $tokens ), $byteMutations );
				}

				// Save test case to file now in case a fatal error happens
				file_put_contents( $this->filename, $json );

				// Try native
				$nativeDecoded = json_decode( $json, true );
				$nativeError = json_last_error();

				// If there's some serious generation bug that causes many outputs to be
				// totally invalid, testing won't be very thorough.
				if ( !$minor && $nativeError !== JSON_ERROR_NONE ) {
					$this->reportTestFailure( 'bug in fuzzer (generated invalid JSON?)' );
					$failure = true;
					break;
				}

				// Try JsonFallback
				$this->errorCaught = false;
				$this->runningTest = true;
				$fallbackDecoded = JsonFallback::decode( $json, true );
				$fallbackError = JsonFallback::last_error();
				$this->runningTest = false;

				if ( $this->errorCaught ) {
					$failure = true;
					continue;
				}

				// JsonFallback does not report control character errors distinctly from other
				// syntax errors, so fudge the native error code.
				if ( $nativeError === JSON_ERROR_CTRL_CHAR ) {
					$fudgedNativeError = JSON_ERROR_SYNTAX;
				} else {
					$fudgedNativeError = $nativeError;
				}

				// Now compare error codes
				if ( $fudgedNativeError !== $fallbackError ) {
					$this->reportTestFailure( 'error code mismatch, native ' . $nativeError .
						' fallback ' . $fallbackError );
					$failure = true;
					continue;
				}

				// Now compare return values
				if ( serialize( $nativeDecoded ) !== serialize( $fallbackDecoded ) ) {
					$this->reportTestFailure( 'retval mismatch' );
					file_put_contents( "{$this->filename}.native", var_export( $nativeDecoded, true ) );
					file_put_contents( "{$this->filename}.fallback", var_export( $fallbackDecoded, true ) );
					$failure = true;
					continue;
				}

				// Don't need to keep passing test cases (except for the original, which we
				// need to keep around if any derived test case fails)
				if ( $minor ) {
					unlink( $this->filename );
				}
			}

			if ( !$failure ) {
				unlink( $original );
			}
		}

		$this->output( "Done.\n" );
	}

	/**
	 * Reports any PHP error that occurs.
	 *
	 * @param int $errno Type of error (e.g. E_NOTICE)
	 * @param string $errstr Message
	 * @param string $errfile Filename
	 * @param int $errline Line number
	 */
	public function phpErrorHandler( $errno, $errstr, $errfile, $errline ) {
		$info = "errno=$errno errstr=$errstr errfile=$errfile errline=$errline";

		if ( $this->runningTest ) {
			$message = 'PHP error caught!';
			$this->errorCaught = true;
		} else {
			$message = 'fuzzer caused a PHP error?';
		}

		$this->reportTestFailure( "$message\n$info" );
	}

	/**
	 * Reports any test failure that occurs.
	 *
	 * @param string $message
	 */
	protected function reportTestFailure( $message ) {
		$this->output( "{$this->filename}: $message\n\n" );
	}

	/**
	 * Replaces a given number of JSON tokens with a randomly selected alternative from a list.
	 *
	 * @param array $tokens Original token stream
	 * @param int $n Number of tokens to alter
	 * @return string: Altered token stream
	 */
	protected function mutateTokens( $tokens, $n ) {
		$choices = array(
			'true', 'false', 'null', '"', '{', '[', '}', ']', '\\\\', '\"', '\u', '\b', '\t',
			'\n', '\f', '\r', '\/', '0', '12', '-3', '4.56', '78e2', '9.1011e-1', '',
		);

		$tokenCount = count( $tokens );
		$choiceCount = count( $choices );

		while ( $n-- ) {
			// XXX: The same token can be mutated twice!
			$i = mt_rand( 0, $tokenCount - 1 );
			$type = mt_rand( 0, $choiceCount - 1 );
			$tokens[$i] = $choices[$type];
		}

		return $tokens;
	}

	/**
	 * Replaces a given number of bytes in a string with random values.
	 *
	 * @param string $s Original string
	 * @param int $n Number of bytes to alter
	 * @return string: Altered string
	 */
	protected function mutateBytes( $s, $n ) {
		if ( $s === '' ) {
			return '';
		}

		$len = strlen( $s );
		while ( $n-- ) {
			// XXX: The same byte can be mutated twice!
			$s[mt_rand( 0, $len - 1 )] = chr( mt_rand( 0, 255 ) );
		}

		return $s;
	}

	/**
	 * Generates an array of tokens, that when joined together, substantially conform
	 * to RFC 4627.
	 *
	 * @param $maxDepth Maximum nesting depth
	 */
	protected function generate( $maxDepth ) {
		$this->tokens = array();
		$this->addWhitespace();
		if ( mt_rand( 0, 1 ) ) {
			$this->addObject( $maxDepth );
		} else {
			$this->addArray( $maxDepth );
		}
		$this->addWhitespace();
	}

	/**
	 * Adds a token to the token stream.
	 *
	 * @param string $s
	 */
	protected function addToken( $s ) {
		$this->tokens[] = $s;
	}

	/**
	 * Adds a random array to the token stream.
	 *
	 * @param int $maxDepth Maximum nesting depth
	 */
	protected function addArray( $maxDepth ) {
		$this->addToken( '[' );
		$this->addWhitespace();

		$n = mt_rand( 0, 10 );
		for ( $i = 0; $i < $n; $i++ ) {
			if ( $i > 0 ) {
				$this->addToken( ',' );
				$this->addWhitespace();
			}
			if ( $maxDepth > 0 ) {
				$this->addValue( $maxDepth - 1 );
			} else {
				$this->addScalarValue();
			}
			$this->addWhitespace();
		}

		$this->addToken( ']' );
	}

	/**
	 * Adds a random object to the token stream.
	 *
	 * @param int $maxDepth Maximum nesting level
	 */
	protected function addObject( $maxDepth ) {
		$this->addToken( '{' );
		$this->addWhitespace();

		$n = mt_rand( 0, 10 );
		for ( $i = 0; $i < $n; $i++ ) {
			if ( $i > 0 ) {
				$this->addToken( ',' );
				$this->addWhitespace();
			}
			$this->addString();
			$this->addWhitespace();
			$this->addToken( ':' );
			$this->addWhitespace();
			if ( $maxDepth > 0 ) {
				$this->addValue( $maxDepth - 1 );
			} else {
				$this->addScalarValue();
			}
			$this->addWhitespace();
		}

		$this->addToken( '}' );
	}

	/**
	 * Adds random whitespace to the token stream.
	 */
	protected function addWhitespace() {
		$whitespaceChars = "\t\n\r ";
		$choices = strlen( $whitespaceChars );

		$n = (int)pow( fmod( mt_rand(), sqrt( 15 ) ), 2 );
		for ( $i = 0; $i < $n; $i++ ) {
			$this->addToken( $whitespaceChars[mt_rand( 0, $choices - 1 )] );
		}
	}

	/**
	 * Adds a token to the token stream, randomizing the cases of its characters.
	 *
	 * @param string $s
	 */
	protected function addCaseInsensitiveToken( $s ) {
		$s2 = '';
		$n = strlen( $s );
		for ( $i = 0; $i < $n; $i++ ) {
			if ( mt_rand( 0, 1 ) ) {
				$s2 .= strtoupper( $s[$i] );
			} else {
				$s2 .= strtolower( $s[$i] );
			}
		}
		$this->addToken( $s2 );
	}

	/**
	 * Adds true, false, or null to the token stream.
	 */
	protected function addSimpleKeyword() {
		static $keywords = array( 'true', 'false', 'null' );
		$this->addToken( $keywords[mt_rand( 0, 2 )] );
	}

	/**
	 * Adds a random integer, somewhat biased toward zero, to the token stream.
	 *
	 * @param int $opts
	 * @param int $maxLength Maximum number of digits
	 */
	protected function addInteger( $opts = 0, $maxLength = 15 ) {
		if ( !( $opts & self::DISALLOW_MINUS ) && mt_rand( 0, 1 ) ) {
			$this->addToken( '-' );
		} elseif ( $opts & self::ALLOW_PLUS && mt_rand( 0, 1 ) ) {
			$this->addToken( '+' );
		}

		$s = '';
		$n = (int)pow( fmod( mt_rand(), sqrt( $maxLength ) ), 2 );

		for ( $i = 0; $i < $n; $i++ ) {
			if ( $opts & self::ALLOW_LEADING_ZEROS || $i > 0 ) {
				$this->addToken( mt_rand( 0, 9 ) );
			} else {
				$this->addToken( mt_rand( 1, 9 ) );
			}
		}

		if ( $s === '' ) {
			$s = '0';
		}
		$this->addToken( $s );
	}

	/**
	 * Adds a random number (integer or float) to the token stream.
	 */
	protected function addNumber() {
		$this->addInteger();

		if ( mt_rand( 0, 1 ) ) {
			$this->addToken( '.' );
			$this->addInteger( self::ALLOW_LEADING_ZEROS | self::DISALLOW_MINUS );
		}

		if ( mt_rand( 0, 1 ) ) {
			$this->addToken( mt_rand( 0, 1 ) ? 'E' : 'e' );
			$this->addInteger( self::ALLOW_LEADING_ZEROS | self::ALLOW_PLUS, 4 );
		}
	}

	/**
	 * Adds a random string to the token stream.
	 */
	protected function addString() {
		$this->addToken( '"' );

		$n = (int)pow( fmod( mt_rand(), sqrt( 200 ) ), 2 );
		for ( $i = 0; $i < $n; $i++ ) {
			$this->addCharacter();
		}

		$this->addToken( '"' );
	}

	/**
	 * Adds a random string character to the token stream.
	 */
	protected function addCharacter() {
		$type = mt_rand( 0, 2 );
		switch ( $type ) {
			case 0: // ASCII
				$cp = mt_rand( 0, 0x7f );
				break;
			case 1: // Non-ASCII code point in the BMP
				$cp = mt_rand( 0x80, 0xffff );
				break;
			case 2: // Code point outside the BMP
				$cp = mt_rand( 0x10000, 0x10ffff );
				break;
		}

		static $specials = array(
			0x08 => '\b', 0x09 => '\t', 0x0a => '\n', 0x0c => '\f', 0x0d => '\r',
			0x22 => '\"', 0x2f => '\/', 0x5c => '\\\\',
		);

		if ( isset( $specials[$cp] ) && mt_rand( 0, 1 ) ) {
			$this->addToken( $specials[$cp] );
		} elseif (
			$cp >= 0x20 && // No control characters
			$cp !== 0x22 && $cp !== 0x5c && // No metacharacters
			( $cp < 0xd800 || $cp > 0xdfff ) && // No surrogates
			mt_rand( 0, 1 ) )
		{
			$this->addToken( codepointToUtf8( $cp ) );
		} else {
			if ( $cp >= 0x10000 ) {
				$cp -= 0x10000;
				$this->addToken( '\u' );
				$this->addCaseInsensitiveToken( sprintf( '%04x', 0xd800 + ( $cp >> 10 ) ) );
				$this->addToken( '\u' );
				$this->addCaseInsensitiveToken( sprintf( '%04x', 0xdc00 + ( $cp & 0x3ff ) ) );
			} else {
				$this->addToken( '\u' );
				$this->addCaseInsensitiveToken( sprintf( '%04x', $cp ) );
			}
		}
	}

	/**
	 * Adds a random scalar to the token stream.
	 */
	protected function addScalarValue() {
		$type = mt_rand( 0, 2 );
		switch ( $type ) {
			case 0:
				$this->addSimpleKeyword();
				break;
			case 1:
				$this->addNumber();
				break;
			case 2:
				$this->addString();
				break;
		}
	}

	/**
	 * Adds a random scalar, object, or array to the token stream.
	 */
	protected function addValue( $maxDepth ) {
		$type = mt_rand( 0, 2 );
		switch ( $type ) {
			case 0:
				$this->addScalarValue();
				break;
			case 1:
				$this->addObject( $maxDepth - 1 );
				break;
			case 2:
				$this->addArray( $maxDepth - 1 );
				break;
		}
	}

}

$maintClass = 'JsonFallbackFuzzer';
require_once RUN_MAINTENANCE_IF_MAIN;
