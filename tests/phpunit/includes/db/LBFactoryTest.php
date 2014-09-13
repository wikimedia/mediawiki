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
 * @group Database
 * @file
 * @author Antoine Musso
 * @copyright © 2013 Antoine Musso
 * @copyright © 2013 Wikimedia Foundation Inc.
 */
class LBFactoryTest extends MediaWikiTestCase {

	/**
	 * @dataProvider getLBFactoryClassProvider
	 */
	public function testGetLBFactoryClass( $expected, $deprecated ) {
		$mockDB = $this->getMockBuilder( 'DatabaseMysql' )
			->disableOriginalConstructor()
			->getMock();

		$config = array(
			'class'          => $deprecated,
			'connection'     => $mockDB,
			# Various other parameters required:
			'sectionsByDB'   => array(),
			'sectionLoads'   => array(),
			'serverTemplate' => array(),
		);

		$this->hideDeprecated( '$wgLBFactoryConf must be updated. See RELEASE-NOTES for details' );
		$result = LBFactory::getLBFactoryClass( $config );

		$this->assertEquals( $expected, $result );
	}

	public function getLBFactoryClassProvider() {
		return array(
			# Format: new class, old class
			array( 'LBFactorySimple', 'LBFactory_Simple' ),
			array( 'LBFactorySingle', 'LBFactory_Single' ),
			array( 'LBFactoryMulti', 'LBFactory_Multi' ),
			array( 'LBFactoryFake', 'LBFactory_Fake' ),
		);
	}
}
