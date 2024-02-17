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

namespace Wikimedia\Tests;

use LogicException;
use MediaWikiCoversValidator;
use Monolog\Test\TestCase;
use XhprofData;

/**
 * @copyright © 2014 Wikimedia Foundation and contributors
 * @covers \XhprofData
 */
class XhprofDataTest extends TestCase {
	use MediaWikiCoversValidator;

	/**
	 * @dataProvider provideSplitKey
	 */
	public function testSplitKey( $key, $expect ) {
		$this->assertSame( $expect, XhprofData::splitKey( $key ) );
	}

	public static function provideSplitKey() {
		return [
			[ 'main()', [ null, 'main()' ] ],
			[ 'foo==>bar', [ 'foo', 'bar' ] ],
			[ 'bar@1==>bar@2', [ 'bar@1', 'bar@2' ] ],
			[ 'foo==>bar==>baz', [ 'foo', 'bar==>baz' ] ],
			[ '==>bar', [ '', 'bar' ] ],
			[ '', [ null, '' ] ],
		];
	}

	public function testInclude() {
		$xhprofData = $this->getXhprofDataFixture( [
			'include' => [ 'main()' ],
		] );
		$raw = $xhprofData->getRawData();
		$this->assertArrayHasKey( 'main()', $raw );
		$this->assertArrayHasKey( 'main()==>foo', $raw );
		$this->assertArrayHasKey( 'main()==>xhprof_disable', $raw );
		$this->assertCount( 3, $raw );
	}

	/**
	 * Validate the structure of data returned by
	 * Xhprof::getInclusiveMetrics(). This acts as a guard against unexpected
	 * structural changes to the returned data in lieu of using a more heavy
	 * weight typed response object.
	 */
	public function testInclusiveMetricsStructure() {
		$metricStruct = [
			'ct' => 'int',
			'wt' => 'array',
			'cpu' => 'array',
			'mu' => 'array',
			'pmu' => 'array',
		];
		$statStruct = [
			'total' => 'numeric',
			'min' => 'numeric',
			'mean' => 'numeric',
			'max' => 'numeric',
			'variance' => 'numeric',
			'percent' => 'numeric',
		];

		$xhprofData = $this->getXhprofDataFixture();
		$metrics = $xhprofData->getInclusiveMetrics();

		foreach ( $metrics as $name => $metric ) {
			$this->assertArrayStructure( $metricStruct, $metric );

			foreach ( $metricStruct as $key => $type ) {
				if ( $type === 'array' ) {
					$this->assertArrayStructure( $statStruct, $metric[$key] );
					if ( $name === 'main()' ) {
						$this->assertEquals( 100, $metric[$key]['percent'] );
					}
				}
			}
		}
	}

	/**
	 * Validate the structure of data returned by
	 * Xhprof::getCompleteMetrics(). This acts as a guard against unexpected
	 * structural changes to the returned data in lieu of using a more heavy
	 * weight typed response object.
	 */
	public function testCompleteMetricsStructure() {
		$metricStruct = [
			'ct' => 'int',
			'wt' => 'array',
			'cpu' => 'array',
			'mu' => 'array',
			'pmu' => 'array',
			'calls' => 'array',
			'subcalls' => 'array',
		];
		$statsMetrics = [ 'wt', 'cpu', 'mu', 'pmu' ];
		$statStruct = [
			'total' => 'numeric',
			'min' => 'numeric',
			'mean' => 'numeric',
			'max' => 'numeric',
			'variance' => 'numeric',
			'percent' => 'numeric',
			'exclusive' => 'numeric',
		];

		$xhprofData = $this->getXhprofDataFixture();
		$metrics = $xhprofData->getCompleteMetrics();

		foreach ( $metrics as $name => $metric ) {
			$this->assertArrayStructure( $metricStruct, $metric, $name );

			foreach ( $metricStruct as $key => $type ) {
				if ( in_array( $key, $statsMetrics ) ) {
					$this->assertArrayStructure(
						$statStruct, $metric[$key], $key
					);
					$this->assertLessThanOrEqual(
						$metric[$key]['total'], $metric[$key]['exclusive']
					);
				}
			}
		}
	}

	public function testEdges() {
		$xhprofData = $this->getXhprofDataFixture();
		$this->assertSame( [], $xhprofData->getCallers( 'main()' ) );
		$this->assertSame( [ 'foo', 'xhprof_disable' ],
			$xhprofData->getCallees( 'main()' )
		);
		$this->assertSame( [ 'main()' ],
			$xhprofData->getCallers( 'foo' )
		);
		$this->assertSame( [], $xhprofData->getCallees( 'strlen' ) );
	}

	public function testCriticalPath() {
		$xhprofData = $this->getXhprofDataFixture();
		$path = $xhprofData->getCriticalPath();

		$last = null;
		foreach ( $path as $key => $value ) {
			[ $func, $call ] = XhprofData::splitKey( $key );
			$this->assertSame( $last, $func );
			$last = $call;
		}
		$this->assertSame( 'bar@1', $last );
	}

	/**
	 * Get an Xhprof instance that has been primed with a set of known testing
	 * data. Tests for the Xhprof class should laregly be concerned with
	 * evaluating the manipulations of the data collected by xhprof rather
	 * than the data collection process itself.
	 *
	 * The returned Xhprof instance primed will be with a data set created by
	 * running this trivial program using the PECL xhprof implementation:
	 * @code
	 * function bar( $x ) {
	 *   if ( $x > 0 ) {
	 *     bar($x - 1);
	 *   }
	 * }
	 * function foo() {
	 *   for ( $idx = 0; $idx < 2; $idx++ ) {
	 *     bar( $idx );
	 *     $x = strlen( 'abc' );
	 *   }
	 * }
	 * xhprof_enable( XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY );
	 * foo();
	 * $x = xhprof_disable();
	 * var_export( $x );
	 * @endcode
	 *
	 * @param array $opts
	 * @return Xhprof
	 */
	protected function getXhprofDataFixture( array $opts = [] ) {
		return new XhprofData( [
			'foo==>bar' => [
				'ct' => 2,
				'wt' => 57,
				'cpu' => 92,
				'mu' => 1896,
				'pmu' => 0,
			],
			'foo==>strlen' => [
				'ct' => 2,
				'wt' => 21,
				'cpu' => 141,
				'mu' => 752,
				'pmu' => 0,
			],
			'bar==>bar@1' => [
				'ct' => 1,
				'wt' => 18,
				'cpu' => 19,
				'mu' => 752,
				'pmu' => 0,
			],
			'main()==>foo' => [
				'ct' => 1,
				'wt' => 304,
				'cpu' => 307,
				'mu' => 4008,
				'pmu' => 0,
			],
			'main()==>xhprof_disable' => [
				'ct' => 1,
				'wt' => 8,
				'cpu' => 10,
				'mu' => 768,
				'pmu' => 392,
			],
			'main()' => [
				'ct' => 1,
				'wt' => 353,
				'cpu' => 351,
				'mu' => 6112,
				'pmu' => 1424,
			],
		], $opts );
	}

	/**
	 * Assert that the given array has the described structure.
	 *
	 * @param array $struct Array of key => type mappings
	 * @param array $actual Array to check
	 * @param string $label
	 */
	protected function assertArrayStructure( $struct, $actual, $label = '' ) {
		$this->assertIsArray( $actual, $label );
		$this->assertSameSize( $struct, $actual, $label );
		foreach ( $struct as $key => $type ) {
			$this->assertArrayHasKey( $key, $actual );
			switch ( $type ) {
				case 'int':
					$this->assertIsInt( $actual[$key] );
					break;
				case 'array':
					$this->assertIsArray( $actual[$key] );
					break;
				case 'numeric':
					$this->assertIsNumeric( $actual[$key] );
					break;
				default:
					throw new LogicException( "Missing handler for type '$type'" );
			}
		}
	}
}
