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
 * @ingroup Benchmark
 * @ingroup JsonFallback
 */

require_once __DIR__ . '/Benchmarker.php';

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
 * @ingroup Benchmark
 * @ingroup JsonFallback
 */
class BenchJsonFallback extends Benchmarker {

	public $input;

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Compare the performance of JsonFallback against other libraries';
		$this->addOption( 'loader', 'Name of a file that includes the other JSON libraries',
			false, true );
		$this->addOption( 'encode', "Test the libraries' encoders" );
		$this->addOption( 'decode', "Test the libraries' decoders" );
		$this->addOption( 'assoc', 'Use associative arrays rather than stdClass objects' );
		$this->addOption( 'options', 'Combination of options to use (comma separated). ' .
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
			foreach ( explode( ',', $this->getOption( 'options' ) ) as $optionText ) {
				$optionText = trim( $optionText );
				if ( preg_match( '/^(?:0|[1-9][0-9]*)$/', $optionText ) ) {
					$options |= (int)$optionText;
				} elseif ( isset( $constants[$optionText] ) ) {
					$options |= $constants[$optionText];
				} else {
					$this->error( "\"$optionText\" is not a valid encode/decode option", 1 );
				}
			}
		}

		// Run tests
		$json = file_get_contents( $filename );
		if ( $encode ) {
			$this->input = json_decode( $json, $assoc );
			if ( $this->input === null ) {
				$this->output( "Warning: decoded JSON as null; is it invalid?\n" );
			}
			$this->bench( $this->listEncoders( $options ) );
		} else {
			$this->input = $json;
			$this->bench( $this->listDecoders( $assoc, $options ) );
		}

		$this->output( $this->getFormattedResults() );
	}

	/**
	 * @param int $options Bitfield of JsonFallback class constants
	 * @return array
	 */
	protected function listEncoders( $options ) {
		$funcs = array(
			array(
				'function' => array( $this, 'jsonFallbackEncode' ),
				'args' =>  array( $options ),
			)
		);

		$ref = new ReflectionFunction( 'json_encode' );
		if ( $ref->isInternal() ) {
			$funcs[] = array(
				'function' => array( $this, 'phpEncode' ),
				'args' => array( $options ),
			);
		}

		// Now for the other pure PHP JSON libraries, which have different interfaces
		$irrelevantOpts = JsonFallback::PRETTY_PRINT | JsonFallback::PARTIAL_OUTPUT_ON_ERROR;

		if ( class_exists( 'Services_JSON' ) ) {
			if ( ( $options & ~$irrelevantOpts ) !== 0 ) {
				$this->output( 'Warning: Services_JSON does not support one or more of ' .
					"the specified options.\n" );
			}

			if ( method_exists( 'Services_JSON', 'encodeUnsafe' ) ) {
				$func = 'pearServicesJSONEncode';
				if ( $options & JsonFallback::PRETTY_PRINT ) {
					$this->output( 'Warning: Using the PEAR version of Services_JSON. ' .
						"Only the MediaWiki fork supports pretty printing.\n" );
				}
			} else {
				$func = 'mwServicesJSONEncode';
			}

			$funcs[] = array(
				'function' => array( $this, $func ),
				'args' => array( $options ),
			);
		}

		if ( class_exists( 'Zend\Json\Encoder' ) ) {
			$zfStringOpts = JsonFallback::HEX_TAG | JsonFallback::HEX_AMP | JsonFallback::HEX_APOS |
				JsonFallback::HEX_QUOT;

			if ( ( $options & ~$irrelevantOpts ) !== $zfStringOpts ) {
				$this->output( 'Warning: Zend\Json does not support one or more of ' .
					"the specified (and/or unspecified) options.\n" );
			}

			$funcs[] = array(
				'function' => array( $this, 'zendJsonEncode' ),
				'args' => array( $options ),
			);
		}

		return $funcs;
	}

	/**
	 * @param bool $assoc Whether to decode as associative arrays rather than objects
	 * @param int $options Bitfield of JsonFallback class constants
	 * @return array
	 */
	protected function listDecoders( $assoc, $options ) {
		$funcs = array(
			array(
				'function' => array( $this, 'jsonFallbackDecode' ),
				'args' => array( $assoc, $options ),
			)
		);

		$ref = new ReflectionFunction( 'json_decode' );
		if ( $ref->isInternal() ) {
			$isPHP54 = defined( 'JSON_BIGINT_AS_STRING' );
			if ( !$isPHP54 && ( $options & JsonFallback::BIGINT_AS_STRING ) ) {
				$this->output( 'Warning: Your version of PHP does not support ' .
					"JSON_BIGINT_AS_STRING.\n" );
			}

			$funcs[] = array(
				'function' => array( $this, $isPHP54 ? 'php54Decode' : 'php53Decode' ),
				'args' => array( $assoc, $options ),
			);
		}

		// Now for the other pure PHP JSON libraries, which have different interfaces
		if ( class_exists( 'Services_JSON' ) ) {
			if ( $options & JsonFallback::BIGINT_AS_STRING ) {
				$this->output( "Warning: Services_JSON does not support BIGINT_AS_STRING.\n" );
			}

			$funcs[] = array(
				'function' => array( $this, 'servicesJsonDecode' ),
				'args' => array( $assoc, $options ),
			);
		}

		if ( class_exists( 'Zend\Json\Decoder' ) ) {
			if ( $options & JsonFallback::BIGINT_AS_STRING ) {
				$this->output( "Warning: Zend\\Json does not support BIGINT_AS_STRING.\n" );
			}

			$funcs[] = array(
				'function' => array( $this, 'zendJsonDecode' ),
				'args' => array( $assoc, $options ),
			);
		}

		return $funcs;
	}

	public function jsonFallbackEncode( $options ) {
		$json = JsonFallback::encode( $this->input, $options );
	}

	public function phpEncode( $options ) {
		$json = json_encode( $this->input, $options );
	}

	public function pearServicesJSONEncode( $options ) {
		$sj = new Services_JSON;
		$json = $sj->encodeUnsafe( $this->input );
	}

	public function mwServicesJSONEncode( $options ) {
		$sj = new Services_JSON;
		$json = $sj->encode( $this->input, (bool)( $options & JsonFallback::PRETTY_PRINT ) );
	}

	public function zendJsonEncode( $options ) {
		$json = Zend\Json\Encoder::encode( $this->input );
		if ( $options & JsonFallback::PRETTY_PRINT ) {
			$json = Zend\Json\Json::prettyPrint( $json );
		}
	}

	public function jsonFallbackDecode( $assoc, $options ) {
		$value = JsonFallback::decode( $this->input, $assoc, 512, $options );
	}

	public function php54Decode( $assoc, $options ) {
		$value = json_decode( $this->input, $assoc, 512, $options );
	}

	public function php53Decode( $assoc, $options ) {
		$value = json_decode( $this->input, $assoc );
	}

	public function servicesJSONDecode( $assoc, $options ) {
		$sj = new Services_JSON( $assoc ? SERVICES_JSON_LOOSE_TYPE : 0 );
		$value = $sj->decode( $this->input );
	}

	public function zendJsonDecode( $assoc, $options ) {
		$decodeType = $assoc ? Zend\Json\Json::TYPE_ARRAY : Zend\Json\Json::TYPE_OBJECT;
		$value = Zend\Json\Decoder::decode( $this->input, $decodeType );
	}

}

$maintClass = 'BenchJsonFallback';
require_once RUN_MAINTENANCE_IF_MAIN;
