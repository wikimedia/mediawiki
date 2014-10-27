<?php
/**
 * @section LICENSE
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
	 * @covers Xhprof::pruneData
	 * @dataProvider provideRawData
	 */
	public function testRawData( $flags, $keys ) {
		$xhprof = new Xhprof( array( 'flags' => $flags ) );
		$raw = $xhprof->stop();
		$this->assertArrayHasKey( 'main()', $raw );
		foreach ( $keys as $key ) {
			$this->assertArrayHasKey( $key, $raw['main()'] );
		}
	}

	public function provideRawData() {
		return array(
			array( 0, array( 'ct', 'wt' ) ),
			array( XHPROF_FLAGS_MEMORY, array( 'ct', 'wt', 'mu', 'pmu' ) ),
			array( XHPROF_FLAGS_CPU, array( 'ct', 'wt', 'cpu' ) ),
			array( XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU, array(
				'ct', 'wt', 'mu', 'pmu', 'cpu',
			) ),
		);
	}

	/**
	 * @covers Xhprof::__construct
	 * @covers Xhprof::stop
	 * @covers Xhprof::pruneData
	 */
	public function testMinimal() {
		$xhprof = new Xhprof( array() );
		$raw = $xhprof->stop();
		$this->assertArrayHasKey( 'main()', $raw );
		$this->assertArrayHasKey( 'main()==>Xhprof::stop', $raw );
		$this->assertArrayHasKey( 'Xhprof::stop==>xhprof_disable', $raw );
		$this->assertSame( 3, count( $raw ) );
	}

	/**
	 * @covers Xhprof::__construct
	 * @covers Xhprof::stop
	 * @covers Xhprof::pruneData
	 */
	public function testExclude() {
		$xhprof = new Xhprof( array(
			'exclude' => array( 'Xhprof::stop', 'xhprof_disable' ),
		) );
		$raw = $xhprof->stop();
		$this->assertArrayHasKey( 'main()', $raw );
		$this->assertSame( 1, count( $raw ) );
	}

	/**
	 * @covers Xhprof::__construct
	 * @covers Xhprof::stop
	 * @covers Xhprof::pruneData
	 * @covers Xhprof::splitKey
	 */
	public function testInclude() {
		$xhprof = new Xhprof( array(
			'include' => array( 'main()' ),
		) );
		$raw = $xhprof->stop();
		$this->assertArrayHasKey( 'main()', $raw );
		$this->assertArrayHasKey( 'main()==>Xhprof::stop', $raw );
		$this->assertSame( 2, count( $raw ) );
	}

	/**
	 * @covers Xhprof::getCallers
	 * @covers Xhprof::getCallees
	 * @covers Xhprof::getEdges
	 * @uses Xhprof
	 */
	public function testEdges() {
		$xhprof = new Xhprof();
		$this->assertSame( array( 'Xhprof::getEdges' ),
			$xhprof->getCallers( 'Xhprof::stop' )
		);
		$this->assertSame( array( 'xhprof_disable' ),
			$xhprof->getCallees( 'Xhprof::stop' )
		);
	}

	/**
	 * @covers Xhprof::getCriticalPath
	 * @uses Xhprof
	 */
	public function testCriticalPath() {
		$xhprof = new Xhprof();
		$path = $xhprof->getCriticalPath();

		$last = 'main()';
		foreach ( $path as $key => $value ) {
			list( $func, $call ) = Xhprof::splitKey( $key );
			$this->assertSame( $last, $func );
			$last = $call;
		}
		$this->assertSame( $last, 'xhprof_disable' );
	}

}
