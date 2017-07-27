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

require_once __DIR__ . '/Benchmarker.php';

/**
 * Maintenance script that benchmarks HashBagOStuff and MapCacheLRU.
 *
 * @ingroup Benchmark
 */
class BenchmarkLruHash extends Benchmarker {
	protected $defaultCount = 1000;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmarks HashBagOStuff and MapCacheLRU.' );
		$this->addOption( 'construct', 'Run construct only', false, false );
		$this->addOption( 'fill', 'Run fill only', false, false );
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

		$fill = $this->hasOption( 'fill' ) || !$this->hasOption( 'construct' );
		$construct = $this->hasOption( 'construct' ) || !$this->hasOption( 'fill' );
		$benches = [];

		if ( $construct ) {
			$benches['HashBagOStuff-construct'] = [
				'function' => function () use ( $max ) {
					$obj = new HashBagOStuff( [ 'maxKeys' => $max ] );
				},
			];
			$benches['MapCacheLRU-construct'] = [
				'function' => function () use ( $max ) {
					$obj = new MapCacheLRU( $max );
				},
			];
		}

		if ( $fill ) {
			// For the fill bechmark, ensure object creation is not measured.
			$hObj = null;
			$benches['HashBagOStuff-fill'] = [
				'setup' => function () use ( &$hObj, $max ) {
					$hObj = new HashBagOStuff( [ 'maxKeys' => $max ] );
				},
				'function' => function () use ( &$hObj, &$keys ) {
					foreach ( $keys as $i => $key ) {
						$hObj->set( $key, $i );
					}
				}
			];
			$mObj = null;
			$benches['MapCacheLRU-fill'] = [
				'setup' => function () use ( &$mObj, $max ) {
					$mObj = new MapCacheLRU( $max );
				},
				'function' => function () use ( &$mObj, &$keys ) {
					foreach ( $keys as $i => $key ) {
						$mObj->set( $key, $i );
					}
				}
			];
		}

		$this->bench( $benches );
	}
}

$maintClass = BenchmarkLruHash::class;
require_once RUN_MAINTENANCE_IF_MAIN;
