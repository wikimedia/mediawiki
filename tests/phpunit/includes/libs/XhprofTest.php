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

/**
 * @uses Xhprof
 * @uses AutoLoader
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 * @since 1.25
 */
class XhprofTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		if ( !function_exists( 'xhprof_enable' ) ) {
			$this->markTestSkipped( 'No xhprof support detected.' );
		}
	}

	/**
	 * @covers Xhprof::splitKey
	 * @dataProvider provideSplitKey
	 */
	public function testSplitKey( $key, $expect ) {
		$this->assertSame( $expect, Xhprof::splitKey( $key ) );
	}

	public function provideSplitKey() {
		return array(
			array( 'main()', array( null, 'main()' ) ),
			array( 'foo==>bar', array( 'foo', 'bar' ) ),
			array( 'bar@1==>bar@2', array( 'bar@1', 'bar@2' ) ),
			array( 'foo==>bar==>baz', array( 'foo', 'bar==>baz' ) ),
			array( '==>bar', array( '', 'bar' ) ),
			array( '', array( null, '' ) ),
		);
	}

	/**
	 * @covers Xhprof::__construct
	 * @covers Xhprof::stop
	 * @covers Xhprof::getRawData
	 * @dataProvider provideRawData
	 */
	public function testRawData( $flags, $keys ) {
		$xhprof = new Xhprof( array( 'flags' => $flags ) );
		$raw = $xhprof->getRawData();
		$this->assertArrayHasKey( 'main()', $raw );
		foreach ( $keys as $key ) {
			$this->assertArrayHasKey( $key, $raw['main()'] );
		}
	}

	public function provideRawData() {
		$tests = array(
			array( 0, array( 'ct', 'wt' ) ),
		);

		if ( defined( 'XHPROF_FLAGS_CPU' ) && defined( 'XHPROF_FLAGS_CPU' ) ) {
			$tests[] = array( XHPROF_FLAGS_MEMORY, array(
				'ct', 'wt', 'mu', 'pmu',
			) );
			$tests[] = array( XHPROF_FLAGS_CPU, array(
				'ct', 'wt', 'cpu',
			) );
			$tests[] = array( XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU, array(
					'ct', 'wt', 'mu', 'pmu', 'cpu',
				) );
		}

		return $tests;
	}

	/**
	 * @covers Xhprof::pruneData
	 */
	public function testInclude() {
		$xhprof = $this->getXhprofFixture( array(
			'include' => array( 'main()' ),
		) );
		$raw = $xhprof->getRawData();
		$this->assertArrayHasKey( 'main()', $raw );
		$this->assertArrayHasKey( 'main()==>foo', $raw );
		$this->assertArrayHasKey( 'main()==>xhprof_disable', $raw );
		$this->assertSame( 3, count( $raw ) );
	}

	/**
	 * Validate the structure of data returned by
	 * Xhprof::getInclusiveMetrics(). This acts as a guard against unexpected
	 * structural changes to the returned data in lieu of using a more heavy
	 * weight typed response object.
	 *
	 * @covers Xhprof::getInclusiveMetrics
	 */
	public function testInclusiveMetricsStructure() {
		$metricStruct = array(
			'ct' => 'int',
			'wt' => 'array',
			'cpu' => 'array',
			'mu' => 'array',
			'pmu' => 'array',
		);
		$statStruct = array(
			'total' => 'numeric',
			'min' => 'numeric',
			'mean' => 'numeric',
			'max' => 'numeric',
			'variance' => 'numeric',
			'percent' => 'numeric',
		);

		$xhprof = $this->getXhprofFixture();
		$metrics = $xhprof->getInclusiveMetrics();

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
	 *
	 * @covers Xhprof::getCompleteMetrics
	 */
	public function testCompleteMetricsStructure() {
		$metricStruct = array(
			'ct' => 'int',
			'wt' => 'array',
			'cpu' => 'array',
			'mu' => 'array',
			'pmu' => 'array',
			'calls' => 'array',
			'subcalls' => 'array',
		);
		$statsMetrics = array( 'wt', 'cpu', 'mu', 'pmu' );
		$statStruct = array(
			'total' => 'numeric',
			'min' => 'numeric',
			'mean' => 'numeric',
			'max' => 'numeric',
			'variance' => 'numeric',
			'percent' => 'numeric',
			'exclusive' => 'numeric',
		);

		$xhprof = $this->getXhprofFixture();
		$metrics = $xhprof->getCompleteMetrics();

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

	/**
	 * @covers Xhprof::getCallers
	 * @covers Xhprof::getCallees
	 * @uses Xhprof
	 */
	public function testEdges() {
		$xhprof = $this->getXhprofFixture();
		$this->assertSame( array(), $xhprof->getCallers( 'main()' ) );
		$this->assertSame( array( 'foo', 'xhprof_disable' ),
			$xhprof->getCallees( 'main()' )
		);
		$this->assertSame( array( 'main()' ),
			$xhprof->getCallers( 'foo' )
		);
		$this->assertSame( array(), $xhprof->getCallees( 'strlen' ) );
	}

	/**
	 * @covers Xhprof::getCriticalPath
	 * @uses Xhprof
	 */
	public function testCriticalPath() {
		$xhprof = $this->getXhprofFixture();
		$path = $xhprof->getCriticalPath();

		$last = null;
		foreach ( $path as $key => $value ) {
			list( $func, $call ) = Xhprof::splitKey( $key );
			$this->assertSame( $last, $func );
			$last = $call;
		}
		$this->assertSame( $last, 'bar@1' );
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
	 * @return Xhprof
	 */
	protected function getXhprofFixture( array $opts = array() ) {
		$xhprof = new Xhprof( $opts );
		$xhprof->loadRawData( array(
			'foo==>bar' => array(
				'ct' => 2,
				'wt' => 57,
				'cpu' => 92,
				'mu' => 1896,
				'pmu' => 0,
			),
			'foo==>strlen' => array(
				'ct' => 2,
				'wt' => 21,
				'cpu' => 141,
				'mu' => 752,
				'pmu' => 0,
			),
			'bar==>bar@1' => array(
				'ct' => 1,
				'wt' => 18,
				'cpu' => 19,
				'mu' => 752,
				'pmu' => 0,
			),
			'main()==>foo' => array(
				'ct' => 1,
				'wt' => 304,
				'cpu' => 307,
				'mu' => 4008,
				'pmu' => 0,
			),
			'main()==>xhprof_disable' => array(
				'ct' => 1,
				'wt' => 8,
				'cpu' => 10,
				'mu' => 768,
				'pmu' => 392,
			),
			'main()' => array(
				'ct' => 1,
				'wt' => 353,
				'cpu' => 351,
				'mu' => 6112,
				'pmu' => 1424,
			),
		) );
		return $xhprof;
	}

	/**
	 * Assert that the given array has the described structure.
	 *
	 * @param array $struct Array of key => type mappings
	 * @param array $actual Array to check
	 * @param string $label
	 */
	protected function assertArrayStructure( $struct, $actual, $label = null ) {
		$this->assertInternalType( 'array', $actual, $label );
		$this->assertCount( count( $struct ), $actual, $label );
		foreach ( $struct as $key => $type ) {
			$this->assertArrayHasKey( $key, $actual );
			$this->assertInternalType( $type, $actual[$key] );
		}
	}
}
