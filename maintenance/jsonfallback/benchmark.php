<?php
/**
 * Comparison of JSON library performance.
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
 * Maintenance script to compare the performance of JsonFallback against that of
 * other JSON libraries.
 *
 * - Native extension (whatever is loaded into PHP)
 * - Services_JSON <https://pear.php.net/package/Services_JSON>
 * - MediaWiki 1.21 version of Services_JSON
 * <https://git.wikimedia.org/blob/mediawiki%2Fcore.git/REL1_21/includes%2Fjson%2FServices_JSON.php>
 * - Zend\\Json <http://framework.zend.com/manual/2.2/en/modules/zend.json.introduction.html>
 *
 * @ingroup JsonFallback
 */
class JsonFallbackBenchmark extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Compare the performance of JsonFallback against other libraries';
		$this->addOption( 'loader', 'Name of a file that includes the other JSON libraries',
			false, true );
		$this->addOption( 'count', 'Number of times to run the benchmarks', false, true );
		$this->addOption( 'encode', "Test the libraries' encoders" );
		$this->addOption( 'decode', "Test the libraries' decoders" );
		$this->addOption( 'assoc', 'Use associative arrays rather than stdClass objects' );
		$this->addOption( 'options', 'Combination of options to use. Separate with "|". ' .
			'Not all libraries support all options.', false, true );
		$this->addOption( 'file', 'Name of JSON file to use', true, true );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		if ( $this->hasOption( 'loader' ) ) {
			include $this->getOption( 'loader' );
		}

		// Check options
		$count = (int)$this->getOption( 'count', 1 );
		$encode = $this->hasOption( 'encode' );
		$decode = $this->hasOption( 'decode' );
		$assoc = $this->hasOption( 'assoc' );
		$filename = $this->getOption( 'file' );

		if ( $count < 1 ) {
			$this->error( '--count must be a positive integer', 1 );
		} elseif ( !( $encode xor $decode ) ) {
			$this->error( 'Either --encode or --decode must be specified', 1 );
		} elseif ( !is_file( $filename ) ) {
			$this->error( '--file must be an existing file', 1 );
		}

		$options = 0;
		if ( $this->hasOption( 'options' ) ) {
			$ref = new ReflectionClass( 'JsonFallback' );
			$constants = $ref->getConstants();
			foreach ( explode( '|', $this->getOption( 'options' ) ) as $optionText ) {
				$optionText = trim( $optionText );
				$filteredOptionText = preg_replace( '/^JSON_/', '', $optionText );
				if ( preg_match( '/^0|[1-9][0-9]*$/', $optionText ) ) {
					$options |= (int)$optionText;
				} elseif ( isset( $constants[$filteredOptionText] ) ) {
					$options |= $constants[$filteredOptionText];
				} else {
					$this->error( "\"$optionText\" is not a valid encode/decode option", 1 );
				}
			}
		}

		// Run tests
		$json = file_get_contents( $filename );
		if ( $encode ) {
			$value = json_decode( $json, $assoc );
			if ( $value === null ) {
				$this->output( "Warning: decoded JSON as null; is it invalid?\n" );
			}
			$results = $this->encodeTimes( $value, $count, $options );
		} else {
			$results = $this->decodeTimes( $json, $count, $assoc, $options );
		}

		$jf = $results['JsonFallback'];
		foreach ( $results as $lib => $time ) {
			$this->output( sprintf( "%s: %.2f s (%.2f s each; %s)\n",
				$lib, $time, $time / $count, $this->timesAsFast( $time, $jf ) ) );
		}
	}

	/**
	 * @param mixed $value Value to encode
	 * @param int $count Number of times to encode using each library
	 * @param int $options Bitfield of JsonFallback class constants
	 * @return array: Keys are library names; values are times in seconds
	 */
	protected function encodeTimes( $value, $count, $options ) {
		$results = array();

		$st = microtime( true );
		for ( $i = $count; $i--; ) {
			$json = JsonFallback::encode( $value, $options );
		}
		$et = microtime( true );
		$results['JsonFallback'] = $et - $st;

		$func = new ReflectionFunction( 'json_encode' );
		if ( $func->isInternal() ) {
			$st = microtime( true );
			for ( $i = $count; $i--; ) {
				$json = json_encode( $value, $options );
			}
			$et = microtime( true );
			$results['Native'] = $et - $st;
		}

		// Now for the other pure PHP JSON libraries, which have different interfaces
		$irrelevantOpts = JsonFallback::PRETTY_PRINT | JsonFallback::PARTIAL_OUTPUT_ON_ERROR;

		if ( class_exists( 'Services_JSON' ) ) {

			if ( ( $options & ~$irrelevantOpts ) !== 0 ) {
				$this->output( 'Warning: Services_JSON does not support one or more of ' .
					"the specified options.\n" );
			}

			$sj = new Services_JSON;

			$encoded = false;
			if ( $options & JsonFallback::PRETTY_PRINT ) {
				$method = new ReflectionMethod( $sj, 'encode' );
				$params = $method->getParameters();
				if ( isset( $params[1] ) && $params[1]->getName() === 'pretty' ) {
					$st = microtime( true );
					for ( $i = $count; $i--; ) {
						$json = $sj->encode( $value, true );
					}
					$et = microtime( true );
					$encoded = true;
				} else {
					$this->output( 'Warning: Using the PEAR version of Services_JSON. ' .
						"Only the MediaWiki fork supports pretty printing.\n" );
				}
			}

			if ( !$encoded ) {
				if ( method_exists( $sj, 'encodeUnsafe' ) ) {
					$st = microtime( true );
					for ( $i = $count; $i--; ) {
						$json = $sj->encodeUnsafe( $value );
					}
					$et = microtime( true );
				} else {
					$st = microtime( true );
					for ( $i = $count; $i--; ) {
						$json = $sj->encode( $value );
					}
					$et = microtime( true );
				}
			}

			$results['Services_JSON'] = $et - $st;
		}

		if ( class_exists( 'Zend\Json\Encoder' ) ) {
			$zfStringOpts = JsonFallback::HEX_TAG | JsonFallback::HEX_AMP | JsonFallback::HEX_APOS |
				JsonFallback::HEX_QUOT;

			if ( ( $options & ~$irrelevantOpts ) !== $zfStringOpts ) {
				$this->output( 'Warning: Zend\Json does not support one or more of ' .
					"the specified (and/or unspecified) options.\n" );
			}

			$st = microtime( true );
			for ( $i = $count; $i--; ) {
				$json = Zend\Json\Encoder::encode( $value );
				if ( $options & JsonFallback::PRETTY_PRINT ) {
					$json = Zend\Json\Json::prettyPrint( $json );
				}
			}
			$et = microtime( true );
			$results['Zend\Json'] = $et - $st;
		}

		return $results;
	}

	/**
	 * @param string $json JSON representation to decode
	 * @param int $count Number of times to decode using each library
	 * @param bool $assoc Whether to decode as associative arrays rather than objects
	 * @param int $options Bitfield of JsonFallback class constants
	 * @return array: Keys are library names; values are times in seconds
	 */
	protected function decodeTimes( $json, $count, $assoc, $options ) {
		$st = microtime( true );
		for ( $i = $count; $i--; ) {
			$value = JsonFallback::decode( $json, $assoc, 512, $options );
		}
		$et = microtime( true );
		$results['JsonFallback'] = $et - $st;

		$func = new ReflectionFunction( 'json_decode' );
		if ( $func->isInternal() ) {
			$decoded = false;
			if ( $options & JsonFallback::BIGINT_AS_STRING ) {
				if ( defined( 'JSON_BIGINT_AS_STRING' ) ) {
					$st = microtime( true );
					for ( $i = $count; $i--; ) {
						$value = json_decode( $json, $assoc, 512, JSON_BIGINT_AS_STRING );
					}
					$et = microtime( true );
					$decoded = true;
				} else {
					$this->output( 'Warning: Your version of PHP does not support ' .
						"JSON_BIGINT_AS_STRING.\n" );
				}
			}

			if ( !$decoded ) {
				$st = microtime( true );
				for ( $i = $count; $i--; ) {
					$value = json_decode( $json, $assoc );
				}
				$et = microtime( true );
			}

			$results['Native'] = $et - $st;
		}

		// Now for the other pure PHP JSON libraries, which have different interfaces
		if ( class_exists( 'Services_JSON' ) ) {
			if ( $options & JsonFallback::BIGINT_AS_STRING ) {
				$this->output( "Warning: Services_JSON does not support BIGINT_AS_STRING.\n" );
			}

			$sj = new Services_JSON( $assoc ? SERVICES_JSON_LOOSE_TYPE : 0 );
			$st = microtime( true );
			for ( $i = $count; $i--; ) {
				$value = $sj->decode( $json );
			}
			$et = microtime( true );
			$results['Services_JSON'] = $et - $st;
		}

		if ( class_exists( 'Zend\Json\Decoder' ) ) {
			if ( $options & JsonFallback::BIGINT_AS_STRING ) {
				$this->output( "Warning: Zend\\Json does not support BIGINT_AS_STRING.\n" );
			}

			$decodeType = $assoc ? Zend\Json\Json::TYPE_ARRAY : Zend\Json\Json::TYPE_OBJECT;
			$st = microtime( true );
			for ( $i = $count; $i--; ) {
				$value = Zend\Json\Decoder::decode( $json, $decodeType );
			}
			$et = microtime( true );
			$results['Zend\Json'] = $et - $st;
		}

		return $results;
	}

	/**
	 * @param float $a
	 * @param float $b
	 * @return string
	 */
	protected function timesAsFast( $a, $b ) {
		if ( $a == 0 || $b == 0 ) {
			return 'cannot compare';
		}

		if ( $a < $b ) {
			return sprintf( '%.2fx faster', $b / $a );
		} elseif ( $a > $b ) {
			return sprintf( '%.2fx slower', $a / $b );
		} else {
			return 'same';
		}
	}
}

$maintClass = 'JsonFallbackBenchmark';
require_once RUN_MAINTENANCE_IF_MAIN;
