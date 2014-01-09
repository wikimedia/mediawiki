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
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * @covers TitleValue
 */
class TitleValueTest extends MediaWikiTestCase {

	public function testConstruction() {
		$title = new TitleValue( NS_USER, 'TestThis', '#stuff' );

		$this->assertEquals( NS_USER, $title->getNamespace() );
		$this->assertEquals( 'TestThis', $title->getText() );
		$this->assertEquals( '#stuff', $title->getSection() );
	}

	public function badConstructorProvider() {
		return array(
			array( 'foo', 'title', 'section' ),
			array( null, 'title', 'section' ),
			array( 2.3, 'title', 'section' ),

			array( NS_MAIN, 5, 'section' ),
			array( NS_MAIN, null, 'section' ),
			array( NS_MAIN, '', 'section' ),

			array( NS_MAIN, 'title', 5 ),
			array( NS_MAIN, 'title', null ),
			array( NS_MAIN, 'title', array() ),
		);
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $ns, $text, $section ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		new TitleValue( $ns, $text, $section );
	}

}
