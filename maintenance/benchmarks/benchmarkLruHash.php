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
 * @ingroup Benchmark
 */

use MediaWiki\Maintenance\Benchmarker;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that benchmarks HashBagOStuff and MapCacheLRU.
 *
 * @ingroup Benchmark
 */
class BenchmarkLruHash extends Benchmarker {
	/** @inheritDoc */
	protected $defaultCount = 1000;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmarks HashBagOStuff and MapCacheLRU.' );
		$this->addOption( 'method', 'One of "construct" or "set". Default: [All]', false, true );
	}

	public function execute() {
		$exampleKeys = [];
		$max = 100;
		$count = 500;
		while ( $count-- ) {
			$exampleKeys[] = wfRandomString();
		}
		// 1000 keys (1...500, 500...1)
		$keys = array_merge( $exampleKeys, array_reverse( $exampleKeys ) );

		$method = $this->getOption( 'method' );
		$benches = [];

		if ( !$method || $method === 'construct' ) {
			$benches['HashBagOStuff::__construct'] = [
				'function' => static function () use ( $max ) {
					$obj = new HashBagOStuff( [ 'maxKeys' => $max ] );
				},
			];
			$benches['MapCacheLRU::__construct'] = [
				'function' => static function () use ( $max ) {
					$obj = new MapCacheLRU( $max );
				},
			];
		}

		if ( !$method || $method === 'set' ) {
			$hObj = null;
			'@phan-var BagOStuff $hObj';
			$benches['HashBagOStuff::set'] = [
				'setup' => static function () use ( &$hObj, $max ) {
					$hObj = new HashBagOStuff( [ 'maxKeys' => $max ] );
				},
				'function' => static function () use ( &$hObj, $keys ) {
					foreach ( $keys as $i => $key ) {
						$hObj->set( $key, $i );
					}
				}
			];
			$mObj = null;
			'@phan-var MapCacheLRU $mObj';
			$benches['MapCacheLRU::set'] = [
				'setup' => static function () use ( &$mObj, $max ) {
					$mObj = new MapCacheLRU( $max );
				},
				'function' => static function () use ( &$mObj, $keys ) {
					foreach ( $keys as $i => $key ) {
						$mObj->set( $key, $i );
					}
				}
			];
		}

		if ( !$method || $method === 'get' ) {
			$hObj = null;
			'@phan-var BagOStuff $hObj';
			$benches['HashBagOStuff::get'] = [
				'setup' => static function () use ( &$hObj, $max, $keys ) {
					$hObj = new HashBagOStuff( [ 'maxKeys' => $max ] );
					foreach ( $keys as $i => $key ) {
						$hObj->set( $key, $i );
					}
				},
				'function' => static function () use ( &$hObj, $keys ) {
					foreach ( $keys as $key ) {
						$hObj->get( $key );
					}
				}
			];
			$mObj = null;
			'@phan-var MapCacheLRU $mObj';
			$benches['MapCacheLRU::get'] = [
				'setup' => static function () use ( &$mObj, $max, $keys ) {
					$mObj = new MapCacheLRU( $max );
					foreach ( $keys as $i => $key ) {
						$mObj->set( $key, $i );
					}
				},
				'function' => static function () use ( &$mObj, $keys ) {
					foreach ( $keys as $key ) {
						$mObj->get( $key );
					}
				}
			];
		}

		$this->bench( $benches );
	}
}

// @codeCoverageIgnoreStart
$maintClass = BenchmarkLruHash::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
