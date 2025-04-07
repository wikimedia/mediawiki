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
