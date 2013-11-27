<?php
/**
 * Holds tests for LBFactory abstract MediaWiki class.
 *
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
 * @author Antoine Musso
 * @copyright © 2013 Antoine Musso
 * @copyright © 2013 Wikimedia Foundation Inc.
 */

class FakeLBFactory extends LBFactory {
	function __construct( $conf ) {}
	function newMainLB( $wiki = false ) {}
	function getMainLB( $wiki = false ) {}
	function newExternalLB( $cluster, $wiki = false ) {}
	function &getExternalLB( $cluster, $wiki = false ) {}
	function forEachLB( $callback, $params = array() ) {}
}

class LBFactoryTest extends MediaWikiTestCase {

	function setup() {
		parent::setup();
		FakeLBFactory::destroyInstance();
	}

	/**
	 * @dataProvider provideDeprecatedLbfactoryClasses
	 */
	function testLbfactoryClassBackcompatibility( $expected, $deprecated ) {
		$mockDB = $this->getMockBuilder( 'DatabaseMysql' )
			-> disableOriginalConstructor()
			->getMock();
		$this->setMwGlobals( 'wgLBFactoryConf',
			array(
				'class'          => $deprecated,
				'connection'     => $mockDB,
				# Various other parameters required:
				'sectionsByDB'   => array(),
				'sectionLoads'   => array(),
				'serverTemplate' => array(),
			)
		);

		global $wgLBFactoryConf;
		$this->assertArrayHasKey( 'class', $wgLBFactoryConf );
		$this->assertEquals( $wgLBFactoryConf['class'], $deprecated );

		# The point of this test is to call a deprecated interface and make
		# sure it keeps back compatibility, so skip the deprecation warning.
		$this->hideDeprecated( '$wgLBFactoryConf must be updated. See RELEASE-NOTES for details' );
		$lbfactory = FakeLBFactory::singleton();
		$this->assertInstanceOf( $expected, $lbfactory,
	   		"LBFactory passed $deprecated should yield the new class $expected" );
	}

	function provideDeprecatedLbfactoryClasses() {
		return array(
			# Format: new class, old class
			array( 'LBFactorySimple', 'LBFactory_Simple' ),
			array( 'LBFactorySingle', 'LBFactory_Single' ),
			array( 'LBFactoryMulti', 'LBFactory_Multi' ),
			array( 'LBFactoryFake', 'LBFactory_Fake' ),
		);
	}
}
