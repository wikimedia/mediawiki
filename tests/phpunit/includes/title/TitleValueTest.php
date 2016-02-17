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
 * @author Daniel Kinzler
 */

/**
 * @covers TitleValue
 *
 * @group Title
 */
class TitleValueTest extends MediaWikiTestCase {

	public function testConstruction() {
		$title = new TitleValue( NS_USER, 'TestThis', 'stuff' );

		$this->assertEquals( NS_USER, $title->getNamespace() );
		$this->assertEquals( 'TestThis', $title->getText() );
		$this->assertEquals( 'stuff', $title->getFragment() );
	}

	public function badConstructorProvider() {
		return [
			[ 'foo', 'title', 'fragment' ],
			[ null, 'title', 'fragment' ],
			[ 2.3, 'title', 'fragment' ],

			[ NS_MAIN, 5, 'fragment' ],
			[ NS_MAIN, null, 'fragment' ],
			[ NS_MAIN, '', 'fragment' ],
			[ NS_MAIN, 'foo bar', '' ],
			[ NS_MAIN, 'bar_', '' ],
			[ NS_MAIN, '_foo', '' ],
			[ NS_MAIN, ' eek ', '' ],

			[ NS_MAIN, 'title', 5 ],
			[ NS_MAIN, 'title', null ],
			[ NS_MAIN, 'title', [] ],
		];
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $ns, $text, $fragment ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		new TitleValue( $ns, $text, $fragment );
	}

	public function fragmentTitleProvider() {
		return [
			[ new TitleValue( NS_MAIN, 'Test' ), 'foo' ],
			[ new TitleValue( NS_TALK, 'Test', 'foo' ), '' ],
			[ new TitleValue( NS_CATEGORY, 'Test', 'foo' ), 'bar' ],
		];
	}

	/**
	 * @dataProvider fragmentTitleProvider
	 */
	public function testCreateFragmentTitle( TitleValue $title, $fragment ) {
		$fragmentTitle = $title->createFragmentTitle( $fragment );

		$this->assertEquals( $title->getNamespace(), $fragmentTitle->getNamespace() );
		$this->assertEquals( $title->getText(), $fragmentTitle->getText() );
		$this->assertEquals( $fragment, $fragmentTitle->getFragment() );
	}

	public function getTextProvider() {
		return [
			[ 'Foo', 'Foo' ],
			[ 'Foo_Bar', 'Foo Bar' ],
		];
	}

	/**
	 * @dataProvider getTextProvider
	 */
	public function testGetText( $dbkey, $text ) {
		$title = new TitleValue( NS_MAIN, $dbkey );

		$this->assertEquals( $text, $title->getText() );
	}
}
