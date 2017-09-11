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

	public function goodConstructorProvider() {
		return [
			[ NS_USER, 'TestThis', 'stuff', '', false, true, false ],
			[ NS_USER, 'TestThis', '', 'baz', false, false, true ],
			[ NS_MAIN, '', 'Kittens', '', true, true, false ],
			[ NS_MAIN, '/Kittens', '', '', true, false, false ],
		];
	}

	/**
	 * @dataProvider goodConstructorProvider
	 */
	public function testConstruction( $ns, $text, $fragment, $interwiki, $relative, $hasFragment,
		$hasInterwiki
	) {
		$title = new TitleValue( $ns, $text, $fragment, $interwiki, $relative );

		if ( !$relative && !$hasInterwiki ) {
			$this->assertSame( $ns, $title->getNamespace() );
			$this->assertTrue( $title->inNamespace( $ns ) );
		}

		$this->assertSame( $text, $title->getText() );
		$this->assertSame( $fragment, $title->getFragment() );
		$this->assertSame( $hasFragment, $title->hasFragment() );
		$this->assertSame( $interwiki, $title->getInterwiki() );
		$this->assertSame( $hasInterwiki, $title->isExternal() );
		$this->assertSame( $relative, $title->isRelative() );
	}

	public function badConstructorProvider() {
		return [
			[ 'foo', 'title', 'fragment', '', false ],
			[ null, 'title', 'fragment', '', false ],
			[ 2.3, 'title', 'fragment', '', false ],

			[ NS_MAIN, 5, 'fragment', '', false ],
			[ NS_MAIN, null, 'fragment', '', false ],
			[ NS_MAIN, '', 'fragment', '', false ],
			[ NS_MAIN, 'foo bar', '', '', false ],
			[ NS_MAIN, 'bar_', '', '', false ],
			[ NS_MAIN, '_foo', '', '', false ],
			[ NS_MAIN, ' eek ', '', '', false ],

			[ NS_MAIN, 'title', 5, '', false ],
			[ NS_MAIN, 'title', null, '', false ],
			[ NS_MAIN, 'title', [], '', false ],

			[ NS_MAIN, 'title', '', 5, false ],
			[ NS_MAIN, 'title', null, 5, false ],
			[ NS_MAIN, 'title', [], 5, false ],

			[ NS_MAIN, 'title', 'fragment', 'xx', true ],
			[ NS_MAIN, '', '', '', true ],
			[ NS_USER, '', 'fragment', '', true ],
		];
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $ns, $text, $fragment, $interwiki, $relative ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		new TitleValue( $ns, $text, $fragment, $interwiki, $relative );
	}

	public function fragmentTitleProvider() {
		return [
			[ new TitleValue( NS_MAIN, 'Test' ), 'foo' ],
			[ new TitleValue( NS_TALK, 'Test', 'foo' ), '' ],
			[ new TitleValue( NS_CATEGORY, 'Test', 'foo' ), 'bar' ],
			[ new TitleValue( NS_MAIN, '', 'foo', '', true ), 'bar' ],
		];
	}

	/**
	 * @dataProvider fragmentTitleProvider
	 */
	public function testCreateFragmentTitle( TitleValue $title, $fragment ) {
		$fragmentTitle = $title->createFragmentTarget( $fragment );

		if ( !$title->isRelative() ) {
			$this->assertEquals( $title->getNamespace(), $fragmentTitle->getNamespace() );
			$this->assertEquals( $title->getInterwiki(), $fragmentTitle->getInterwiki() );
		}

		$this->assertEquals( $title->getText(), $fragmentTitle->getText() );
		$this->assertEquals( $title->isRelative(), $fragmentTitle->isRelative() );#

		$this->assertEquals( $fragment, $fragmentTitle->getFragment() );
	}

	public function resolveRelativeLinkProvider() {
		return [
			[
				new TitleValue( NS_MAIN, '/Kittens', '', '', true ),
				new TitleValue( NS_USER, 'Test', '', 'xx', false ),
				'xx:4:Test/Kittens'
			],
			[
				new TitleValue( NS_MAIN, '/Kittens', '', '', true ),
				new TitleValue( NS_USER, 'Test', '', 'xx', true ),
				'Test/Kittens'
			],
			[
				new TitleValue( NS_MAIN, '', 'Kittens', '', true ),
				new TitleValue( NS_USER, 'Test', '', '', false ),
				'4:Test#Kittens'
			],
			[
				new TitleValue( NS_MAIN, 'Kittens', '', '', false ),
				new TitleValue( NS_USER, 'Test', '', 'xx', false ),
				'0:Kittens'
			],
		];
	}

	/**
	 * @dataProvider resolveRelativeLinkProvider
	 */
	public function resolveRelativeLinkTest(
		TitleValue $relative,
		TitleValue $base,
		$expected
	) {
		$result = $relative->resolveRelativeLink( $base );

		$this->assertSame( $expected, "$result" );
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
