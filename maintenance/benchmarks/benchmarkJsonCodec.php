<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Json\JsonCodec;
use MediaWiki\Maintenance\Benchmarker;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * @ingroup Benchmark
 */
class BenchmarkJsonCodec extends Benchmarker {
	/** @inheritDoc */
	protected $defaultCount = 100;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmarks JsonCodec.' );
		$this->addOption(
			'file',
			'A json file, or a php file that returns a data structure. Default is config-schema.php',
			false,
			true
		);
		$this->addOption(
			'assoc',
			'Whether JSON objects should be represented as associative arrays when loading'
		);
	}

	public function execute() {
		$file = $this->getOption( 'file', MW_INSTALL_PATH . '/includes/config-schema.php' );
		$data = $this->loadData( $file );
		$bytes = json_encode( $data );

		$codec = new JsonCodec();

		$this->bench( [
			"JsonCodec::serialize ($file)" => [
				'function' => static function ( JsonCodec $codec, $data ) {
					$codec->serialize( $data );
				},
				'args' => [ $codec, $data ]
			],
			"JsonCodec::deserialize ($file)" => [
				'function' => static function ( JsonCodec $codec, $bytes ) {
					$codec->deserialize( $bytes );
				},
				'args' => [ $codec, $bytes ]
			],
			"JsonCodec::detectNonSerializableData ($file)" => [
				'function' => static function ( JsonCodec $codec, $data ) {
					$codec->detectNonSerializableData( $data );
				},
				'args' => [ $codec, $data ]
			],
		] );
	}

	/** @return mixed */
	private function loadData( string $file ) {
		if ( str_ends_with( $file, '.php' ) ) {
			$data = include $file;
			if ( !$data ) {
				$this->fatalError( "Failed to load data from " . $file );
			}
			return $data;
		}

		$raw = $this->loadFile( $file );
		$data = json_decode( $raw, $this->getOption( 'assoc', false ) );

		if ( !$data ) {
			$this->fatalError( "Failed to load data from " . $file );
		}

		return $data;
	}
}

// @codeCoverageIgnoreStart
$maintClass = BenchmarkJsonCodec::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
